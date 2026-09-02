<?php

/**
 * The model: what a GIR <type>, <parameter>, <method> and type node become in PHP,
 * and the naming helpers that turn them into PHP and C names.
 *
 * Part of gen/gir.php, the GObject-Introspection generator (docs/PLAN.md milestone 3);
 * gen/README.md describes the flow. Split out of the 3 200-line original on 2026-08-30.
 */

declare(strict_types=1);

namespace PhpGtk4\Gen;

// ---------------------------------------------------------------- model

/** A GIR <type> or <array>. */
final class Type
{
    public function __construct(
        public string $name,            // 'utf8', 'gint', 'Gtk.Label', 'GLib.List', 'none'
        public ?string $ctype,
        public bool $isArray = false,
        public ?Type $element = null,
        public bool $zeroTerminated = true,
        public ?int $lengthParam = null,
    ) {}
}

final class Param
{
    public function __construct(
        public string $name,
        public Type $type,
        public string $direction,       // in|out|inout
        public bool $nullable,
        public bool $optional,
        public string $transfer,
        public ?string $scope,          // call|async|notified -> a callback
        public bool $callerAllocates,
        public ?int $closure = null,    // index of the user_data parameter this callback is paired with
    ) {}
}

final class Func
{
    /** @param list<Param> $params */
    public function __construct(
        public string $name,            // GIR name (set_text)
        public string $cid,             // gtk_label_set_text
        public string $kind,            // method|constructor|function
        public Type $ret,
        public string $retTransfer,
        public bool $retNullable,
        public array $params,
        public bool $throws,
        public ?string $version,
        public ?string $deprecated,
        public ?string $shadows,
        public ?string $shadowedBy,
        public bool $varargs,
        public string $doc,
    ) {}
}

final class Node
{
    /** @var list<Func> */
    public array $funcs = [];
    /** @var list<Func> virtual methods (kind 'vfunc'), classes only */
    public array $vfuncs = [];
    /** @var list<string> field names of a class-struct record (`glib:is-gtype-struct-for`) */
    public array $fields = [];
    /** @var list<array{name: string, type: Type, writable: bool, private: bool}> a boxed record's fields */
    public array $recordFields = [];
    public ?string $structFor = null;   // record: the class this is the class struct of (qualified)
    /** @var list<array{name: string, type: Type, readable: bool, writable: bool, constructOnly: bool, doc: string}> */
    public array $props = [];
    /** @var list<array{name: string, value: int, deprecated: bool, version: ?string}> */
    public array $members = [];
    /** @var list<string> qualified names */
    public array $implements = [];
    /** @var list<string> qualified names an interface requires (`interface X extends Y` when Y is one) */
    public array $prerequisites = [];
    public ?string $parent = null;      // qualified
    public bool $abstract = false;
    public bool $final = false;
    public string $doc = '';

    public function __construct(
        public string $ns,
        public string $name,
        public string $kind,            // class|interface|enum|bitfield|record|callback|alias
        public ?string $ctype,
        public ?string $gtypeName,
        public ?string $getType,
        public ?string $version,
    ) {}

    public function qname(): string
    {
        return $this->ns . '.' . $this->name;
    }
}

// ---------------------------------------------------------------- naming helpers

/** GtkLabel -> GTK_TYPE_LABEL / GTK_LABEL, GListStore -> G_TYPE_LIST_STORE / G_LIST_STORE. */
/** @return array{string, string} The `GDK_TYPE_EVENT` / `GDK_EVENT` macro pair of a node. */
function macroParts(Gir $gir, Node $n): array
{
    $prefix = $gir->prefixes[$n->ns];
    // The GType name first: it is the one spelled like the macro. Graphene's c:type is the C
    // struct's own snake_case name (`graphene_rect_t`), which yields GRAPHENE_TYPE__RECT_T
    // instead of GRAPHENE_TYPE_RECT; its glib:type-name (`GrapheneRect`) is what GTK's own types
    // put in both places, so preferring it changes nothing for them.
    $ctype = $n->gtypeName ?? $n->ctype ?? ($prefix . $n->name);
    $rest = substr($ctype, strlen($prefix));
    $snake = strtoupper(preg_replace('/(?<=[a-z0-9])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/', '_', $rest) ?? $rest);
    return [strtoupper($prefix) . '_TYPE_' . $snake, strtoupper($prefix) . '_' . $snake];
}

function phpClass(Node $n): string
{
    return $n->gtypeName ?? $n->name;
}

function camel(string $snake): string
{
    $name = str_replace('_', '', ucwords($snake, '_'));
    // A PHP identifier cannot start with a digit, and GIR nicks sometimes do
    // (GTK_LICENSE_0BSD -> "0bsd"). Moving the digits to the end reads like the case GTK
    // would have written and like its siblings: Bsd0 next to Bsd3.
    if (preg_match('/^(\d+)(.+)$/', $name, $m) === 1) {
        $name = ucfirst($m[2]) . $m[1];
    }
    return $name;
}

/** First paragraph of a GIR doc with gi-docgen links reduced to plain text. */
function docSummary(string $doc): string
{
    $para = trim(explode("\n\n", str_replace("\r", '', $doc))[0]);
    $link = '/\[(?:method|func|ctor|property|signal|class|iface|enum|flags|struct|const|vfunc|callback|type|id)'
        . '@[\w.]*?([\w-]+)\]/';
    $para = preg_replace($link, '`$1`', $para) ?? $para;
    $para = preg_replace('/`?%(TRUE|FALSE|NULL)`?/', '`\L$1`', $para) ?? $para;
    $para = preg_replace_callback('/`\\\\L(\w+)`/', fn($m) => '`' . strtolower($m[1]) . '`', $para) ?? $para;
    $para = preg_replace('/@([a-z_][a-z0-9_]*)/', '\$$1', $para) ?? $para;
    return preg_replace('/\s+/', ' ', $para) ?? $para;
}

/** Wrap a paragraph into docblock lines at 100 columns. */
function docLines(string $text, string $indent): array
{
    if ($text === '') {
        return [];
    }
    $lines = [];
    foreach (explode("\n", wordwrap($text, 96 - strlen($indent), "\n", true)) as $l) {
        $lines[] = $indent . ' * ' . $l;
    }
    return $lines;
}
