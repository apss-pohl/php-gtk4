<?php

/**
 * gir.php - the GObject-Introspection generator (docs/PLAN.md milestone 3).
 *
 * Reads the installed GIR files, takes the classes named in gen/allowlist.txt
 * (plus their parents, interfaces and the enums their kept signatures use),
 * and emits, per GIR namespace, the stub section and one .cpp per class, the
 * MINIT block, and gen/report.md listing everything it skipped and why.
 *
 *   php gen/gir.php --out=gen/out          # wave preview: everything under gen/out/
 *   php gen/gir.php --install              # the real thing: src/<Ns>/, src/gen_*.{inc,h},
 *                                          # examples/ skeletons, gen/report.md, the status column
 *                                          # of docs/GTK3-MAP.md via gen/map-status.php (ci.sh --only=gen)
 *
 * Inputs (all in gen/): allowlist.txt (Ns.Name per line), handwritten.txt (classes
 * the generator must know about - parents, parameter types - but never emits),
 * skip.txt (Ns.Type.method  reason). Emitted files carry a GENERATED header.
 *
 * Hand-written files are the templates: src/Gtk/GtkLabel.cpp (getters/setters),
 * GtkWindow.cpp (object params, out params, attach() for roots), GdkTexture.cpp
 * (GError, GBytes), GtkWidget.cpp (enums, GStrv, GList). What the generator emits
 * must read like those.
 */

declare(strict_types=1);

namespace PhpGtk4\Gen;

const GIR_DIRS = ['/usr/share/gir-1.0', '/usr/lib/x86_64-linux-gnu/gir-1.0', '/usr/lib64/gir-1.0'];
const GIR_FILES = ['GLib-2.0', 'GObject-2.0', 'Gio-2.0', 'cairo-1.0', 'Pango-1.0', 'Gdk-4.0', 'Gsk-4.0', 'Gtk-4.0'];
const GTK_FLOOR = '4.14';           // API newer than this is skipped in this wave
const PHP_NAMESPACE = 'Gtk4';
const INT_TYPES = '/^g(u?int(8|16|32|64)?|size|ssize|u?long|u?short|unichar|u?char)$/';

const NS_GIR = 'http://www.gtk.org/introspection/core/1.0';
const NS_C = 'http://www.gtk.org/introspection/c/1.0';
const NS_GLIB = 'http://www.gtk.org/introspection/glib/1.0';

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

// ---------------------------------------------------------------- GIR loading

final class Gir
{
    /** @var array<string, Node> qualified name -> node */
    public array $types = [];
    /** @var array<string, string> namespace -> c:identifier-prefixes (Gtk, Gdk, G, ...) */
    public array $prefixes = [];

    public function load(string $file): void
    {
        $doc = new \DOMDocument();
        if (!$doc->load($file, LIBXML_PARSEHUGE | LIBXML_NOBLANKS)) {
            throw new \RuntimeException("cannot load $file");
        }
        $x = new \DOMXPath($doc);
        $x->registerNamespace('g', NS_GIR);
        $x->registerNamespace('c', NS_C);
        $x->registerNamespace('glib', NS_GLIB);
        $nsNode = $x->query('/g:repository/g:namespace')->item(0);
        assert($nsNode instanceof \DOMElement);
        $ns = $nsNode->getAttribute('name');
        $this->prefixes[$ns] = explode(',', $nsNode->getAttributeNS(NS_C, 'identifier-prefixes'))[0];

        $kinds = 'g:class|g:interface|g:enumeration|g:bitfield|g:record|g:callback|g:alias';
        foreach ($x->query($kinds, $nsNode) as $el) {
            assert($el instanceof \DOMElement);
            $kind = match ($el->localName) {
                'class' => 'class', 'interface' => 'interface', 'enumeration' => 'enum',
                'bitfield' => 'bitfield', 'record' => 'record', 'callback' => 'callback', 'alias' => 'alias',
            };
            $node = new Node(
                $ns,
                $el->getAttribute('name'),
                $kind,
                $el->getAttributeNS(NS_C, 'type') ?: null,
                $el->getAttributeNS(NS_GLIB, 'type-name') ?: null,
                $el->getAttributeNS(NS_GLIB, 'get-type') ?: null,
                $el->getAttribute('version') ?: null,
            );
            $node->abstract = $el->getAttribute('abstract') === '1';
            $node->final = $el->getAttribute('final') === '1';
            $node->doc = self::doc($x, $el);
            if ($el->hasAttribute('parent')) {
                $node->parent = self::qualify($ns, $el->getAttribute('parent'));
            }
            foreach ($x->query('g:implements|g:prerequisite', $el) as $impl) {
                assert($impl instanceof \DOMElement);
                if ($impl->localName === 'implements') {
                    $node->implements[] = self::qualify($ns, $impl->getAttribute('name'));
                } else {
                    $node->prerequisites[] = self::qualify($ns, $impl->getAttribute('name'));
                }
            }
            foreach ($x->query('g:method|g:constructor|g:function', $el) as $f) {
                assert($f instanceof \DOMElement);
                $node->funcs[] = self::func($x, $ns, $f);
            }
            foreach ($x->query('g:virtual-method', $el) as $f) {
                assert($f instanceof \DOMElement);
                $node->vfuncs[] = self::func($x, $ns, $f);
            }
            if ($kind === 'callback') {
                $node->funcs[] = self::func($x, $ns, $el);  // the callback's own signature
            }
            if ($el->getAttributeNS(NS_GLIB, 'is-gtype-struct-for') !== '') {
                $node->structFor = self::qualify($ns, $el->getAttributeNS(NS_GLIB, 'is-gtype-struct-for'));
                foreach ($x->query('g:field', $el) as $fld) {
                    assert($fld instanceof \DOMElement);
                    $node->fields[] = $fld->getAttribute('name');
                }
            } elseif ($kind === 'record') {
                foreach ($x->query('g:field', $el) as $fld) {
                    assert($fld instanceof \DOMElement);
                    $ft = self::type($x, $ns, $fld);
                    if ($ft === null) {
                        continue;  // a callback field
                    }
                    $node->recordFields[] = [
                        'name' => $fld->getAttribute('name'),
                        'type' => $ft,
                        'writable' => $fld->getAttribute('writable') === '1',
                        'private' => $fld->getAttribute('private') === '1',
                    ];
                }
            }
            foreach ($x->query('g:property', $el) as $p) {
                assert($p instanceof \DOMElement);
                $t = self::type($x, $ns, $p);
                if ($t === null) {
                    continue;
                }
                $node->props[] = [
                    'name' => $p->getAttribute('name'),
                    'type' => $t,
                    'readable' => $p->getAttribute('readable') !== '0',
                    'writable' => $p->getAttribute('writable') === '1',
                    'constructOnly' => $p->getAttribute('construct-only') === '1',
                    'doc' => self::doc($x, $p),
                ];
            }
            foreach ($x->query('g:member', $el) as $m) {
                assert($m instanceof \DOMElement);
                $node->members[] = [
                    'name' => $m->getAttribute('name'),
                    'value' => (int) $m->getAttribute('value'),
                    'deprecated' => $m->hasAttribute('deprecated') || $m->hasAttribute('deprecated-version'),
                    'version' => $m->getAttribute('version') ?: null,
                ];
            }
            $this->types[$node->qname()] = $node;
        }
    }

    public static function qualify(string $ns, string $name): string
    {
        return str_contains($name, '.') ? $name : "$ns.$name";
    }

    private static function doc(\DOMXPath $x, \DOMElement $el): string
    {
        $d = $x->query('g:doc', $el)->item(0);
        return $d === null ? '' : trim($d->textContent);
    }

    private static function type(\DOMXPath $x, string $ns, \DOMElement $parent): ?Type
    {
        foreach ($x->query('g:type|g:array|g:varargs', $parent) as $t) {
            assert($t instanceof \DOMElement);
            if ($t->localName === 'varargs') {
                return new Type('varargs', null);
            }
            if ($t->localName === 'array') {
                $el = self::type($x, $ns, $t);
                return new Type(
                    $t->getAttribute('name') !== '' ? self::qualify($ns, $t->getAttribute('name')) : 'array',
                    $t->getAttributeNS(NS_C, 'type') ?: null,
                    true,
                    $el,
                    $t->getAttribute('zero-terminated') !== '0',
                    $t->hasAttribute('length') ? (int) $t->getAttribute('length') : null,
                );
            }
            $name = $t->getAttribute('name');
            $el = null;
            if (in_array($name, ['GLib.List', 'GLib.SList', 'GLib.PtrArray', 'GLib.HashTable'], true)) {
                $el = self::type($x, $ns, $t);
            }
            $builtin = in_array($name, ['none', 'utf8', 'filename', 'gpointer', 'gconstpointer'], true)
                || preg_match('/^g[a-z0-9]+$/', $name);
            $qualified = $builtin
                ? $name
                : self::qualify($ns, $name);
            return new Type($qualified, $t->getAttributeNS(NS_C, 'type') ?: null, false, $el);
        }
        return null;
    }

    private static function func(\DOMXPath $x, string $ns, \DOMElement $f): Func
    {
        $rv = $x->query('g:return-value', $f)->item(0);
        assert($rv instanceof \DOMElement);
        $ret = self::type($x, $ns, $rv) ?? new Type('none', 'void');
        $params = [];
        $varargs = false;
        foreach ($x->query('g:parameters/g:parameter', $f) as $p) {
            assert($p instanceof \DOMElement);
            $t = self::type($x, $ns, $p);
            if ($t === null || $t->name === 'varargs') {
                $varargs = true;
                continue;
            }
            $params[] = new Param(
                $p->getAttribute('name'),
                $t,
                $p->getAttribute('direction') ?: 'in',
                $p->getAttribute('nullable') === '1' || $p->getAttribute('allow-none') === '1',
                $p->getAttribute('optional') === '1',
                $p->getAttribute('transfer-ownership') ?: 'none',
                $p->getAttribute('scope') ?: null,
                $p->getAttribute('caller-allocates') === '1',
                $p->hasAttribute('closure') ? (int) $p->getAttribute('closure') : null,
            );
        }
        return new Func(
            $f->getAttribute('name'),
            $f->getAttributeNS(NS_C, 'identifier'),
            match ($f->localName) {
                'constructor' => 'constructor', 'function' => 'function', 'virtual-method' => 'vfunc',
                default => 'method'
            },
            $ret,
            $rv->getAttribute('transfer-ownership') ?: 'none',
            $rv->getAttribute('nullable') === '1',
            $params,
            $f->getAttribute('throws') === '1',
            $f->getAttribute('version') ?: null,
            $f->hasAttribute('deprecated') ? ($f->getAttribute('deprecated-version') ?: 'yes') : null,
            $f->getAttribute('shadows') ?: null,
            $f->getAttribute('shadowed-by') ?: null,
            $varargs,
            self::doc($x, $f),
        );
    }
}

// ---------------------------------------------------------------- naming helpers

/** GtkLabel -> GTK_TYPE_LABEL / GTK_LABEL, GListStore -> G_TYPE_LIST_STORE / G_LIST_STORE. */
/** @return array{string, string} The `GDK_TYPE_EVENT` / `GDK_EVENT` macro pair of a node. */
function macroParts(Gir $gir, Node $n): array
{
    $prefix = $gir->prefixes[$n->ns];
    $ctype = $n->ctype ?? $n->gtypeName ?? ($prefix . $n->name);
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
    return str_replace('_', '', ucwords($snake, '_'));
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

// ---------------------------------------------------------------- generator

final class Generator
{
    /** @var array<string, true> qualified names emitted as PHP classes/enums */
    private array $emit = [];
    /** @var array<string, true> known but hand-written (usable as types, never emitted) */
    private array $handwritten = [];

    /**
     * Hand-written handles on the fundamental registry (src/core/fundamental) that generated
     * signatures may take or return: GIR name => the unref function a transfer-full result needs.
     */
    private const array FUNDAMENTALS = [
        'Gdk.Event' => 'gdk_event_unref', 'Gtk.CssSection' => 'gtk_css_section_unref',
        'Gdk.EventSequence' => null,  // GTK owns sequences; the handle is an identity, never a reference
    ];

    /** The `GDK_TYPE_EVENT`-style macro of a node, for the emitted C++. */
    private function typeMacroOf(Node $node): string
    {
        return macroParts($this->gir, $node)[0];
    }
    /** @var array<string, array<string, string>> "Ns.Type" -> constructor param -> property (gen/ctor-props.txt) */
    private array $ctorProps = [];
    /** @var list<string> register_vfuncs_<Class>() calls for MINIT */
    private array $vfuncMinit = [];
    /** @var array<string, string> 'Ns.Type.method' -> reason */
    private array $skipList = [];
    /** @var list<array{type: string, member: string, reason: string}> */
    private array $report = [];
    /** @var array<string, string> php class -> namespace dir */
    private array $files = [];

    /** @var array<string, array<string, array{stub: string, cpp: string}>> 'Ns.Type' -> method -> parts */
    private array $overrides = [];

    public function __construct(
        private Gir $gir,
        private string $out,
        private ?string $examplesDir = null,
        private ?string $sectionsFile = null,
        private ?string $bootstrap = null,
        private ?string $testsDir = null,
    ) {}

    /** @var array<string, array{node: Node, stub: string}> php class -> what emitClass produced */
    private array $classInfo = [];
    /** @var array<string, string> 'Ns.Type.member' (member may be *) -> reason: no smoke test */
    private array $smokeSkip = [];

    /** @param array<string, string> $map "Ns.Type.param" -> property name */
    public function setCtorProps(array $map): void
    {
        foreach ($map as $key => $prop) {
            $dot = strrpos($key, '.');
            if ($dot === false) {
                throw new \RuntimeException("ctor-props.txt: bad key $key (Ns.Type.param property)");
            }
            $this->ctorProps[substr($key, 0, $dot)][substr($key, $dot + 1)] = $prop;
        }
    }

    public function setSmokeSkip(array $skip): void
    {
        $this->smokeSkip = $skip;
    }

    /**
     * gen/overrides/<Ns>.<Type>.<method>.cpp: the first docblock holds the stub declaration
     * (`public function x(...): T` on its first line, docs below); the rest is the complete
     * ZEND_METHOD as it should appear in the generated file. Overrides replace a generated
     * method or add one GIR does not have (GtkBox::get_children).
     */
    /** @var array<string, string> 'Ns.Type' -> verbatim C++ emitted before the methods */
    private array $preludes = [];

    public function loadOverrides(string $dir): void
    {
        foreach (glob("$dir/*.cpp") ?: [] as $file) {
            if (preg_match('/^(\w+)\.(\w+)\.cpp$/', basename($file), $m)) {
                // <Ns>.<Type>.cpp: shared helpers (trampolines, statics, extra includes) for
                // that class's overrides, emitted verbatim after the generated include block.
                $this->preludes["$m[1].$m[2]"] = rtrim(file_get_contents($file) ?: '') . "\n\n";
                continue;
            }
            if (!preg_match('/^(\w+)\.(\w+)\.(\w+)\.cpp$/', basename($file), $m)) {
                throw new \RuntimeException("override file name must be <Ns>.<Type>[.<method>].cpp: $file");
            }
            $src = file_get_contents($file) ?: '';
            if (!preg_match('~^/\*\*\n \* (.+?)\n(.*?) \*/\n~s', $src, $d)) {
                throw new \RuntimeException("override needs a leading docblock with the stub signature: $file");
            }
            $docBody = trim(preg_replace('/^ \*( |$)/m', '', $d[2]) ?? '');
            $stub = '';
            if ($docBody !== '') {
                $docRows = array_map(fn($l) => rtrim("     * $l"), explode("\n", $docBody));
                $stub .= "    /**\n" . implode("\n", $docRows) . "\n     */\n";
            }
            $stub .= '    ' . $d[1] . " {}\n";
            $this->overrides["$m[1].$m[2]"][$m[3]] = ['stub' => $stub, 'cpp' => rtrim($src) . "\n\n"];
        }
    }

    public function configure(array $allow, array $handwritten, array $skip): void
    {
        foreach ($handwritten as $q) {
            $this->handwritten[$q] = true;
        }
        foreach ($allow as $q) {
            $this->add($q);
        }
        $this->skipList = $skip;
    }

    private function add(string $q): void
    {
        if (isset($this->emit[$q]) || isset($this->handwritten[$q])) {
            return;
        }
        $n = $this->gir->types[$q] ?? null;
        if ($n === null) {
            throw new \RuntimeException("allowlist: unknown type $q");
        }
        $this->emit[$q] = true;
        if ($n->parent !== null && $this->resolveParent($n) === null) {
            $this->add($n->parent);
        }
        // Interfaces are declared on the PHP class only when allow-listed themselves
        // (GtkAccessible/GtkBuildable on every widget would triple the wave).
        // Enums/flags used by kept signatures are cheap: always emit them.
        foreach ($n->funcs as $f) {
            foreach ([$f->ret, ...array_map(fn($p) => $p->type, $f->params)] as $t) {
                $tn = $this->gir->types[$t->name] ?? null;
                if ($tn !== null && in_array($tn->kind, ['enum', 'bitfield'], true) && $tn->gtypeName !== null) {
                    $this->add($t->name);
                }
            }
        }
    }

    private function known(string $q): bool
    {
        return isset($this->emit[$q]) || isset($this->handwritten[$q]);
    }

    /** Nearest known ancestor's qualified name, or null when the chain has none. */
    private function resolveParent(Node $n): ?string
    {
        for ($p = $n->parent; $p !== null; $p = ($this->gir->types[$p] ?? null)?->parent) {
            if ($this->known($p)) {
                return $p;
            }
        }
        return null;
    }

    private function skip(Node $n, string $member, string $reason): void
    {
        $this->report[] = ['type' => phpClass($n), 'member' => $member, 'reason' => $reason];
    }

    // ------------------------------------------------------------ run

    public function run(): void
    {
        $byNs = [];
        foreach (array_keys($this->emit) as $q) {
            $n = $this->gir->types[$q];
            $byNs[$n->ns][] = $n;
        }
        $minit = [];
        $protos = [];
        foreach ($byNs as $ns => $nodes) {
            usort($nodes, fn($a, $b) => strcmp($a->name, $b->name));
            $stub = [];
            foreach ($nodes as $n) {
                switch ($n->kind) {
                    case 'enum':
                    case 'bitfield':
                        $stub[] = $this->emitEnum($n, $minit);
                        break;
                    case 'interface':
                    case 'class':
                        [$stubText, $cpp] = $this->emitClass($n, $minit, $protos);
                        $stub[] = $stubText;
                        $this->write("$ns/" . phpClass($n) . '.cpp', $cpp);
                        break;
                    case 'record':
                        if ($n->gtypeName === null || $n->getType === null) {
                            $this->skip($n, '*', 'record without a GType (not boxed): not generated');
                            break;
                        }
                        [$stubText, $cpp] = $this->emitRecord($n, $minit, $protos);
                        $stub[] = $stubText;
                        $this->write("$ns/" . phpClass($n) . '.cpp', $cpp);
                        break;
                    default:
                        $this->skip($n, '*', "{$n->kind}: not generated");
                }
            }
            $this->write("$ns/$ns.stub.php", $this->stubHeader($ns) . implode("\n", $stub));
        }
        $this->write('gen_minit.inc', $this->minitText($minit));
        $this->write('gen_prototypes.h', $this->prototypesText($protos));
        $nss = array_keys($byNs);
        sort($nss);
        $this->write('gen_arginfo.h', "// GENERATED by gen/gir.php - the per-namespace arginfo (gen_stub.php output).\n"
            . "// Included once by src/gtk4.cpp after gen_prototypes.h.\n#pragma once\n"
            . implode("\n", array_map(fn($ns) => "#include \"$ns/{$ns}_arginfo.h\"", $nss)) . "\n");

        $this->writeExamples();
        $this->writeSmokeTests();
        $report = $this->reportText();
        $reportPath = dirname(__DIR__) . '/gen/report.md';
        self::putIfChanged($reportPath, rtrim($report) . "\n");
    }

    /**
     * tests/Generated/<Class>SmokeTest.php per generated class (PLAN.md §3.5): construction, every
     * setter/getter pair and every writable property round-trip with a sample value of its type.
     * Behavioural coverage still comes from hand-written tests; this catches marshalling slips.
     */
    private function writeSmokeTests(): void
    {
        $dir = $this->testsDir ?? $this->out . '/tests';
        if (!is_dir($dir)) {
            mkdir($dir, 0o777, true);
        }
        // The set is owned by the generator, but deleting it up front and writing it back
        // would touch every file on every run (putIfChanged: mtimes are what `make` reads).
        // Write what belongs here, then remove whatever is left over from an earlier wave.
        $stale = [];
        foreach (glob("$dir/*SmokeTest.php") ?: [] as $file) {
            $stale[$file] = true;
        }
        foreach ($this->classInfo as $php => ['node' => $n, 'stub' => $stub]) {
            if ($n->kind !== 'class') {
                continue;
            }
            $reason = $this->smokeSkip[$n->qname() . '.*'] ?? null;
            if ($reason !== null) {
                $this->skip($n, 'smoke test', "smoke-skip.txt: $reason");
                continue;
            }
            $test = $this->smokeTest($n, $php, $stub);
            if ($test === null) {
                continue;
            }
            self::putIfChanged("$dir/{$php}SmokeTest.php", $test);
            unset($stale["$dir/{$php}SmokeTest.php"]);
            $this->files["tests/Generated/{$php}SmokeTest.php"] = "$dir/{$php}SmokeTest.php";
        }
        foreach (array_keys($stale) as $file) {
            unlink($file);
        }
    }

    /**
     * @return list<array{name: string, static: bool, params: list<array{type: string, optional: bool}>, ret: string}>
     */
    private static function stubMethods(string $stub): array
    {
        preg_match_all('/public (static )?function (\w+)\(([^)]*)\)(?:: ([^\s;{]+))?/', $stub, $ms, PREG_SET_ORDER);
        $out = [];
        foreach ($ms as $m) {
            $params = [];
            if (trim($m[3]) !== '') {
                foreach (explode(',', $m[3]) as $p) {
                    preg_match('/^\s*(\S+) \$\w+( = .*)?$/', trim($p), $pm);
                    $params[] = ['type' => $pm[1] ?? 'mixed', 'optional' => isset($pm[2])];
                }
            }
            $out[] = ['name' => $m[2], 'static' => $m[1] !== '', 'params' => $params, 'ret' => $m[4] ?? 'void'];
        }
        return $out;
    }

    /** PHP literal for a sample value of a stub type, or null if the type cannot be sampled. */
    private function sample(string $type): ?string
    {
        $type = ltrim($type, '?');
        return match ($type) {
            'string' => "'smoke'",
            'int' => '1',
            'float' => '1.0',
            'bool' => 'true',
            default => isset($this->classInfo[$type]) && $this->classInfo[$type]['node']->kind === 'enum'
                ? "$type::cases()[0]"
                : null,
        };
    }

    /** The assertion comparing a getter result with the sample set before, per type. */
    private static function assertion(string $type, string $expr): string
    {
        $type = ltrim($type, '?');
        return match ($type) {
            'bool' => "self::assertTrue($expr);",
            'float' => "self::assertEqualsWithDelta(1.0, $expr, 1e-6);",
            'int' => "self::assertSame(1, $expr);",
            'string' => "self::assertSame('smoke', $expr);",
            default => "self::assertSame($type::cases()[0], $expr);",
        };
    }

    private function smokeTest(Node $n, string $php, string $stub): ?string
    {
        $methods = self::stubMethods($stub);
        $byName = [];
        foreach ($methods as $m) {
            $byName[$m['name']] = $m;
        }
        // --- how to get an instance
        $subject = null;
        if ($php === 'GtkWindow') {
            $subject = '$this->window()';
        } elseif (str_contains($stub, 'only works on a PHP subclass')) {
            $subject = "new class () extends $php {}";  // abstract in GTK: a PHP subtype (core/subtype.h)
        } elseif (isset($byName['__construct']) && str_contains($stub, 'public function __construct')) {
            $args = $this->sampleArgs($byName['__construct']['params']);
            if ($args !== null) {
                $subject = "new $php($args)";
            }
        }
        if ($subject === null) {
            foreach ($methods as $m) {
                if ($m['static'] && str_starts_with($m['name'], 'new_') && $m['ret'] === $php) {
                    $args = $this->sampleArgs($m['params']);
                    if ($args !== null) {
                        $subject = "$php::{$m['name']}($args)";
                        break;
                    }
                }
            }
        }
        if ($subject === null) {
            $this->skip($n, 'smoke test', 'no constructor or factory whose parameters can be sampled');
            return null;
        }
        // --- setter/getter pairs
        $lines = [];
        $uses = [$php];
        foreach ($methods as $m) {
            if ($m['static'] || !str_starts_with($m['name'], 'set_') || count($m['params']) !== 1) {
                continue;
            }
            $prop = substr($m['name'], 4);
            $getter = $byName["get_$prop"] ?? null;
            $sameType = $getter !== null && ltrim($getter['ret'], '?') === ltrim($m['params'][0]['type'], '?');
            if ($getter === null || $getter['params'] !== [] || !$sameType) {
                continue;
            }
            if (isset($this->smokeSkip[$n->qname() . '.' . $m['name']])) {
                continue;
            }
            $type = $m['params'][0]['type'];
            $value = $this->sample($type);
            if ($value === null) {
                continue;
            }
            if (!in_array(ltrim($type, '?'), ['string', 'int', 'float', 'bool'], true)) {
                $uses[] = ltrim($type, '?');
            }
            $lines[] = "        \$o->{$m['name']}($value);";
            $lines[] = '        ' . self::assertion($type, "\$o->get_$prop()");
        }
        // --- writable properties (@property tags), construct-only ones excluded
        $props = [];
        foreach ($n->props as $p) {
            if (!$p['writable'] || !$p['readable'] || $p['constructOnly']) {
                continue;
            }
            $type = $this->phpType($p['type'], true);
            $value = $type !== null ? $this->sample($type) : null;
            $name = str_replace('-', '_', $p['name']);
            if ($value === null || isset($this->smokeSkip[$n->qname() . '.' . $name])) {
                continue;
            }
            if (!in_array(ltrim($type, '?'), ['string', 'int', 'float', 'bool'], true)) {
                $uses[] = ltrim($type, '?');
            }
            $props[] = "        \$o->$name = $value;";
            $props[] = '        ' . self::assertion($type, "\$o->$name");
        }
        $uses = array_unique($uses);
        sort($uses);
        $useLines = implode("\n", array_map(fn($u) => "use Gtk4\\$u;", $uses));
        $body = "    public function testConstructs(): void\n    {\n"
            . "        self::assertInstanceOf($php::class, \$this->subject());\n    }\n";
        if ($lines !== []) {
            $body .= "\n    public function testSetterGetterRoundTrips(): void\n    {\n"
                . "        \$o = \$this->subject();\n"
                . implode("\n", $lines) . "\n    }\n";
        }
        if ($props !== []) {
            $body .= "\n    public function testPropertyRoundTrips(): void\n    {\n        \$o = \$this->subject();\n"
                . implode("\n", $props) . "\n    }\n";
        }
        return "<?php\n\n// GENERATED by gen/gir.php - smoke test for Gtk4\\$php (construction, setter/getter and\n"
            . "// property round trips with sample values). Do not edit; gen/smoke-skip.txt excludes members.\n\n"
            . "declare(strict_types=1);\n\nnamespace PhpGtk4\\Tests\\Generated;\n\n"
            . "$useLines\nuse PhpGtk4\\Tests\\GtkTestCase;\n\n"
            . "final class {$php}SmokeTest extends GtkTestCase\n{\n"
            . "    private function subject(): $php\n    {\n        return $subject;\n    }\n\n"
            . $body . "}\n";
    }

    /** @param list<array{type: string, optional: bool}> $params */
    private function sampleArgs(array $params): ?string
    {
        $args = [];
        foreach ($params as $p) {
            if ($p['optional']) {
                break;
            }
            // nullable means "may be absent" in GTK (an application id, a model): pass null
            $v = str_starts_with($p['type'], '?') ? 'null' : $this->sample($p['type']);
            if ($v === null) {
                return null;
            }
            $args[] = $v;
        }
        return implode(', ', $args);
    }

    /** examples/<Class>.php skeletons for classes that have none yet, and the sidebar section. */
    private function writeExamples(): void
    {
        $dir = $this->examplesDir ?? $this->out . '/examples';
        if (!is_dir($dir)) {
            mkdir($dir, 0o777, true);
        }
        $known = [];
        if ($this->bootstrap !== null && is_file($this->bootstrap)) {
            $src = file_get_contents($this->bootstrap) ?: '';
            if (preg_match('/SECTIONS = \[(.*?)\n    \];/s', $src, $m)) {
                preg_match_all("/'(\\w+)'/", $m[1], $names);
                $known = array_flip($names[1]);
            }
        }
        $generated = [];
        foreach (array_keys($this->emit) as $q) {
            $n = $this->gir->types[$q];
            if (!in_array($n->kind, ['class', 'interface', 'enum', 'bitfield', 'record'], true)) {
                continue;
            }
            if ($n->kind === 'record' && ($n->gtypeName === null || $n->getType === null)) {
                continue;
            }
            $php = phpClass($n);
            if (!isset($known[$php])) {
                $generated[] = $php;
            }
            $file = "$dir/$php.php";
            if (!is_file($file)) {
                file_put_contents($file, $this->examplePage($n));
                $this->files["examples/$php.php"] = $file;
            }
        }
        sort($generated);
        $sections = $this->sectionsFile ?? "$dir/generated-sections.inc";
        if ($generated === []) {
            // nothing unplaced: no file (Demo::sections() copes with a missing one)
            if (is_file($sections)) {
                unlink($sections);
            }
            return;
        }
        $list = implode("\n", array_map(fn($c) => "        '$c',", $generated));
        self::putIfChanged($sections, "<?php\n\n"
            . "// GENERATED by gen/gir.php - classes the generator added that Demo::SECTIONS does not\n"
            . "// place yet. Move a class into a real section in bootstrap.php once its page is written.\n\n"
            . "declare(strict_types=1);\n\nreturn [\n    'Generated' => [\n$list\n    ],\n];\n");
        $this->files['examples/generated-sections.inc'] = $sections;
    }

    /** A page that shows what the class/enum is: cases for enums, the API surface for classes. */
    private function examplePage(Node $n): string
    {
        $php = phpClass($n);
        $summary = docSummary($n->doc) ?: "the $php " . ($n->kind === 'class' ? 'class' : $n->kind);
        $summary = str_replace(["'", '`', '\\'], ['\\\'', '', ''], $summary);
        if (strlen($summary) > 80) {  // one line in Demo::page(): cut at a word, mark the cut
            $summary = rtrim(substr($summary, 0, strrpos(substr($summary, 0, 78), ' ') ?: 78), ' ,;:') . '…';
        }
        $head = "<?php\n\ndeclare(strict_types=1);\n\nnamespace PhpGtk4\\Examples;\n\n";
        if ($n->kind === 'enum' || $n->kind === 'bitfield') {
            $what = $n->kind === 'enum' ? 'cases' : 'constants';
            $rows = $n->kind === 'enum'
                ? "array_map(\n            static fn(\\BackedEnum \$c): string"
                    . " => sprintf('%s = %d', \$c->name, \$c->value),\n            $php::cases(),\n        )"
                : "array_map(\n            static fn(string \$k, int \$v): string => sprintf('%s = %d', \$k, \$v),\n"
                    . "            array_keys(\$constants),\n            \$constants,\n        )";
            $constants = $n->kind === 'bitfield'
                ? "        \$constants = array_filter(new \\ReflectionClass($php::class)->getConstants(), 'is_int');\n"
                : '';
            return $head . "use Gtk4\\$php;\nuse Gtk4\\GtkLabel;\nuse Gtk4\\GtkWidget;\nuse Gtk4\\GtkWindow;\n\n"
                . "/*\n * Gtk4\\$php - $summary\n *\n"
                . " * GENERATED skeleton (gen/gir.php): lists every one of the {$n->kind}'s $what. Replace it with a\n"
                . " * page that shows the {$n->kind} in action and move the class out of the 'Generated' section.\n *\n"
                . " *   bin/php-gtk4 examples/demo.php $php\n */\n\n"
                . "require_once __DIR__ . '/bootstrap.php';\n\n"
                . "return Demo::page(\n    '$php',\n    '$summary',\n    function (GtkWindow \$win): GtkWidget {\n"
                . $constants
                . "        \$label = new GtkLabel();\n"
                . "        \$rows = $rows;\n"
                . "        \$label->set_markup(\"<b>$php</b>\\n\" . implode(\"\\n\", \$rows));\n"
                . "        return \$label;\n    },\n);\n";
        }
        $methods = [];
        foreach ($n->funcs as $f) {
            if ($this->methodSkipReason($n, $f) === null) {
                $methods[] = $f->kind === 'constructor' && $f->name === 'new'
                    ? '__construct'
                    : ($f->shadows ?? $f->name);
            }
        }
        $methods = array_values(array_unique($methods));
        sort($methods);
        $count = count($methods);
        $shown = "implode(', ', [\n" . implode('', array_map(
            fn($m) => "            '$m',\n",
            array_slice($methods, 0, 12),
        )) . ($count > 12 ? "            '…',\n" : '') . '        ])';
        return $head . "use Gtk4\\GtkLabel;\nuse Gtk4\\GtkWidget;\nuse Gtk4\\GtkWindow;\n\n"
            . "/*\n * Gtk4\\$php - $summary\n *\n"
            . " * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page\n"
            . " * that shows $php doing something and move the class out of the 'Generated' section.\n *\n"
            . " *   bin/php-gtk4 examples/demo.php $php\n */\n\n"
            . "require_once __DIR__ . '/bootstrap.php';\n\n"
            . "return Demo::page(\n    '$php',\n    '$summary',\n    function (GtkWindow \$win): GtkWidget {\n"
            . "        \$label = new GtkLabel();\n"
            . "        \$names = $shown;\n"
            . "        \$label->set_markup(\"<b>$php</b>\\n$count generated methods\\n<small>\$names</small>\");\n"
            . "        return \$label;\n    },\n);\n";
    }

    private function write(string $rel, string $text): void
    {
        $path = $this->out . '/' . $rel;
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0o777, true);
        }
        $content = rtrim($text) . "\n";
        if (str_ends_with($rel, '.cpp')) {
            // Same style the cpp-lint stage enforces (.clang-format); the generator is not a
            // formatter. Through a pipe, not `-i`, so an unchanged file is never touched.
            $content = self::clangFormatted($content, $path);
        }
        self::putIfChanged($path, $content);
        $this->files[$rel] = $path;
    }

    /**
     * Write only when the content really changed.
     *
     * The generator runs on every `./ci.sh` (the `gen` stage regenerates in place and fails on
     * a diff), and rewriting an identical file still bumps its mtime - which had `make`
     * recompiling `src/gtk4.cpp` and every generated class on each run, ~50 s of nothing.
     * Content is the only thing that decides here; timestamps stay put.
     */
    private static function putIfChanged(string $path, string $content): void
    {
        if (is_file($path) && file_get_contents($path) === $content) {
            return;
        }
        file_put_contents($path, $content);
    }

    /** $source through clang-format, with $path only deciding which .clang-format applies. */
    private static function clangFormatted(string $source, string $path): string
    {
        $binary = self::clangFormat();
        if ($binary === null) {
            return $source;
        }
        $cmd = escapeshellarg($binary) . ' --style=file --assume-filename=' . escapeshellarg($path);
        $pipes = [];
        $process = proc_open($cmd, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
        if (!is_resource($process)) {
            return $source;
        }
        fwrite($pipes[0], $source);
        fclose($pipes[0]);
        $formatted = (string) stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $errors = (string) stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        if (proc_close($process) !== 0 || $formatted === '') {
            throw new \RuntimeException("clang-format failed for $path: $errors");
        }
        return $formatted;
    }

    private static function clangFormat(): ?string
    {
        static $bin = false;
        if ($bin === false) {
            $bin = getenv('CLANG_FORMAT') ?: null;
            if ($bin === null) {
                $newest = 'ls /usr/bin/clang-format-[0-9]* 2>/dev/null | sort -t- -k2 -V | tail -1';
                $found = trim((string) shell_exec($newest));
                $bin = $found !== '' ? $found : (trim((string) shell_exec('command -v clang-format')) ?: null);
            }
        }
        return $bin;
    }

    private function stubHeader(string $ns): string
    {
        return <<<PHP
            <?php

            /**
             * GENERATED by gen/gir.php from $ns GIR - do not edit; edit gen/overrides, gen/skip.txt
             * or gen/allowlist.txt and regenerate (./ci.sh --only=gen --fix).
             *
             * @generate-class-entries
             * @generate-legacy-arginfo 80400
             */

            namespace Gtk4;


            PHP;
    }

    // ------------------------------------------------------------ enums

    private function emitEnum(Node $n, array &$minit): string
    {
        [$typeMacro] = macroParts($this->gir, $n);
        $php = phpClass($n);
        $seen = [];
        $lines = [];
        foreach ($n->members as $m) {
            $key = $n->qname() . '.' . $m['name'];
            if (isset($this->skipList[$key])) {
                $this->skip($n, $m['name'], 'skip.txt: ' . $this->skipList[$key]);
                continue;
            }
            if ($m['deprecated'] || ($m['version'] !== null && version_compare($m['version'], GTK_FLOOR, '>'))) {
                $this->skip($n, $m['name'], $m['deprecated'] ? 'deprecated member' : "member since {$m['version']}");
                continue;
            }
            if (isset($seen[$m['value']])) {
                $this->skip($n, $m['name'], "duplicate value {$m['value']} (alias of {$seen[$m['value']]})");
                continue;
            }
            $seen[$m['value']] = $m['name'];
            $lines[] = $n->kind === 'enum'
                ? '    case ' . camel($m['name']) . " = {$m['value']};"
                : '    public const int ' . strtoupper($m['name']) . " = {$m['value']};";
        }
        $doc = docLines(docSummary($n->doc), '');
        $head = $doc === [] ? '' : "/**\n" . implode("\n", $doc) . "\n */\n";
        if ($n->kind === 'enum') {
            $minit[] = "  phpgtk::register_enum($typeMacro, register_class_{$this->ceName($php)}());";
            return $head . "enum $php: int\n{\n" . implode("\n", $lines) . "\n}\n";
        }
        $minit[] = "  phpgtk::register_flags($typeMacro, register_class_{$this->ceName($php)}());";
        return $head . "final class $php\n{\n" . implode("\n", $lines) . "\n}\n";
    }

    private function ceName(string $php): string
    {
        return PHP_NAMESPACE . '_' . $php;
    }

    // ------------------------------------------------------------ classes

    /** @return array{string, string} stub section, cpp file */
    private function emitClass(Node $n, array &$minit, array &$protos): array
    {
        $php = phpClass($n);
        [$typeMacro, $castMacro] = macroParts($this->gir, $n);
        $parentQ = $this->resolveParent($n);
        $parentPhp = $parentQ !== null ? phpClass($this->gir->types[$parentQ]) : null;
        if ($n->kind === 'class' && $parentPhp === null) {
            $parentPhp = 'GObject';
        }
        $ifaces = [];
        // an interface's prerequisites that are interfaces become `extends` (GtkSelectionModel: GListModel)
        foreach ($n->kind === 'interface' ? $n->prerequisites : $n->implements as $i) {
            if ($this->known($i) && $this->gir->types[$i]->kind === 'interface') {
                // skip.txt `<Class>.implements:<Iface>`: the C object still is one, the PHP class does
                // not say so (an inherited method's signature is incompatible with the interface's).
                $key = $n->qname() . '.implements:' . $i;
                if (isset($this->skipList[$key])) {
                    $this->skip($n, "implements $i", 'skip.txt: ' . $this->skipList[$key]);
                    continue;
                }
                $ifaces[] = phpClass($this->gir->types[$i]);
            }
        }
        $isRoot = $this->implementsQ($n, 'Gtk.Root');

        $stubMethods = [];
        $cppMethods = [];
        $seenNames = [];
        $overrides = $this->overrides[$n->qname()] ?? [];
        // An interface with vfuncs can be implemented from PHP (core/subtype): its PHP interface
        // then declares only the vfunc-backed methods (what an implementor must provide); the
        // utility methods (items_changed, get_object) exist on the generated implementors through
        // @implementation-alias, their C bodies still live in the interface's .cpp.
        $ifaceVfuncs = $n->kind === 'interface' ? array_map(fn($v) => $v->name, $n->vfuncs) : null;
        $declares = fn(string $name) => $ifaceVfuncs === null || $ifaceVfuncs === []
            || in_array($name, $ifaceVfuncs, true);
        foreach ($n->funcs as $f) {
            $phpNameOf = $f->kind === 'constructor' && $f->name === 'new' ? '__construct' : ($f->shadows ?? $f->name);
            if (isset($overrides[$phpNameOf])) {
                continue;  // emitted from the override below
            }
            if ($n->kind === 'interface' && $f->kind !== 'method') {
                $this->skip($n, $f->name, 'static function on an interface (PHP interfaces have no bodies)');
                continue;
            }
            $m = $this->method($n, $f, $typeMacro, $castMacro, $isRoot);
            if ($m === null) {
                continue;
            }
            [$phpName, $stubM, $cppM] = $m;
            if (isset($seenNames[$phpName])) {
                $this->skip($n, $f->name, "PHP name $phpName already taken");
                continue;
            }
            $seenNames[$phpName] = true;
            if ($declares($phpName)) {
                $stubMethods[] = $stubM;
            }
            $cppMethods[] = $cppM;
        }
        foreach ($overrides as $phpName => $o) {
            $seenNames[$phpName] = true;
            // an interface declares, it does not implement: `;` instead of `{}` in the stub
            if ($declares($phpName)) {
                $stubMethods[] = $n->kind === 'interface' ? preg_replace('/ \{\}\n$/', ";\n", $o['stub']) : $o['stub'];
            }
            $cppMethods[] = $o['cpp'];
        }
        if ($n->kind === 'class' && !isset($seenNames['__construct'])) {
            $seenNames['__construct'] = true;
            $why = $n->abstract ? 'is abstract in GTK' : 'has no constructor in GTK';
            // skip.txt "Ns.Type.__construct": abstract for GTK's own subclasses only (GdkTexture
            // needs internal state a factory sets; g_object_new() of a subtype aborts on 4.16+).
            $notSubclassable = isset($this->skipList[$n->qname() . '.__construct']);
            if ($notSubclassable) {
                $this->skip($n, '__construct', 'skip.txt: ' . $this->skipList[$n->qname() . '.__construct']);
            }
            if (!$n->abstract && !$notSubclassable) {
                // Concrete, but GIR's `new` was unusable (varargs: gtk_alert_dialog_new(format, ...)):
                // g_object_new() with default properties, PHP subclasses through subtype_new().
                array_unshift($stubMethods, "    /** A $php with default properties (GTK's own constructor is "
                    . "varargs-only; set the properties afterwards). */\n    public function __construct() {}\n");
                array_unshift($cppMethods, "/**\n * Gtk4\\$php::__construct()\n *\n"
                    . " * A $php with default properties (GTK's own constructor is varargs-only; set the\n"
                    . " * properties afterwards).\n */\n"
                    . "ZEND_METHOD({$this->ceName($php)}, __construct) {\n"
                    . "  ZEND_PARSE_PARAMETERS_NONE();\n"
                    . "  GObject *obj = subtype_new(ZEND_THIS, nullptr);\n"
                    . "  if (obj == nullptr) {\n"
                    . "    if (EG(exception) != nullptr) RETURN_THROWS();\n"
                    . "    obj = static_cast<GObject *>(g_object_new($typeMacro, nullptr));\n  }\n"
                    . '  ' . ($isRoot ? 'attach' : 'attach_new') . "(object_from_zval(ZEND_THIS), obj);\n}\n\n");
            } elseif ($n->final || $notSubclassable) {
                array_unshift($stubMethods, "    /** $php $why: instances come from GTK, never from `new`. */\n"
                    . "    private function __construct() {}\n");
                array_unshift($cppMethods, "/**\n * Gtk4\\$php::__construct()\n *\n"
                    . " * $php $why: instances come from GTK, never from `new`.\n */\n"
                    . "ZEND_METHOD({$this->ceName($php)}, __construct) {\n"
                    . "  // Private: never called (object_init_ex() in wrap() skips constructors).\n"
                    . "  ZEND_PARSE_PARAMETERS_NONE();\n}\n\n");
            } else {
                // public (a PHP subclass without its own constructor inherits it): `new GtkWidget()`
                // throws, `new MyWidget()` (a PHP subclass, its own GType through core/subtype.h)
                // works - that is how a widget is written in PHP.
                array_unshift($stubMethods, "    /** $php $why: `new` only works on a PHP subclass "
                    . "(which gets its own GType). */\n    public function __construct() {}\n");
                array_unshift($cppMethods, "/**\n * Gtk4\\$php::__construct()\n *\n"
                    . " * $php $why: `new` only works on a PHP subclass (which gets its own GType).\n */\n"
                    . "ZEND_METHOD({$this->ceName($php)}, __construct) {\n"
                    . "  ZEND_PARSE_PARAMETERS_NONE();\n"
                    . "  GObject *obj = subtype_new(ZEND_THIS, nullptr);\n"
                    . "  if (obj == nullptr) {\n"
                    . "    if (EG(exception) == nullptr) {\n"
                    . "      zend_throw_error(nullptr, \"$php $why: subclass it in PHP (new MyClass())\");\n"
                    . "    }\n"
                    . "    RETURN_THROWS();\n  }\n"
                    . '  ' . ($isRoot ? 'attach' : 'attach_new') . "(object_from_zval(ZEND_THIS), obj);\n}\n\n");
            }
        }
        // Interface methods: one implementation per interface, aliased into the class.
        foreach ($ifaces as $iface) {
            $in = $this->gir->types[$this->qOfPhp($iface)];
            foreach ($in->funcs as $f) {
                if ($f->kind !== 'method') {
                    continue;
                }
                $phpName = $f->shadows ?? $f->name;
                if ($f->shadowedBy !== null || isset($seenNames[$phpName]) || !$this->methodEmittable($in, $f)) {
                    continue;
                }
                $aliasKey = $n->qname() . '.' . $phpName;
                if (isset($this->skipList[$aliasKey])) {  // an alias skipped on this class only
                    $this->skip($n, $phpName, 'skip.txt: ' . $this->skipList[$aliasKey]);
                    continue;
                }
                $seenNames[$phpName] = true;
                $ifaceOverride = $this->overrides[$in->qname()][$phpName] ?? null;
                if ($ifaceOverride !== null) {
                    preg_match('/function (\w+\(.*\)(?:: [^\s{]+)?)/', $ifaceOverride['stub'], $sm);
                    $sig = $sm[1] ?? null;
                } else {
                    $sig = $this->stubSignature($in, $f, $phpName);
                }
                if ($sig === null) {
                    continue;
                }
                $stubMethods[] = "    /** @implementation-alias Gtk4\\$iface::$phpName */\n"
                    . "    public function $sig {}\n";
            }
        }

        // ---- vfuncs: thunks + installers + native vfunc_<name>() methods (PHP subclasses, PLAN §2.6)
        if (($n->kind === 'class' && !$n->final || $n->kind === 'interface') && $n->vfuncs !== []) {
            [$vStub, $vCpp, $vReg] = $this->emitVfuncs($n, $typeMacro, $castMacro, $seenNames);
            array_push($stubMethods, ...$vStub);
            array_push($cppMethods, ...$vCpp);
            if ($vReg !== '') {
                $cppMethods[] = $vReg;
                $protos[] = "void register_vfuncs_{$php}();";
                $this->vfuncMinit[] = "  register_vfuncs_{$php}();";
            }
        }

        // ---- stub
        $props = [];
        foreach ($n->props as $p) {
            $pt = $this->phpType($p['type'], true);
            if ($pt === null) {
                $this->skip($n, 'property ' . $p['name'], 'property type ' . $p['type']->name . ' not mappable');
                continue;
            }
            $tag = $p['writable'] ? '@property' : '@property-read';
            $props[] = " * $tag $pt \$" . str_replace('-', '_', $p['name']);
        }
        $docLines = docLines(docSummary($n->doc), '');
        $head = '';
        if ($docLines !== [] || $props !== []) {
            $separator = $docLines !== [] && $props !== [] ? "\n *\n" : '';
            $head = "/**\n" . implode("\n", $docLines) . $separator . implode("\n", $props) . "\n */\n";
        }
        // Never `abstract`: wrap() instantiates the nearest registered PHP class for whatever
        // GTK hands back (a GdkMemoryTexture becomes a GdkTexture handle), and PHP refuses to
        // instantiate abstract classes. `new` on a GIR-abstract class (or one without a `new`
        // constructor) is refused by a private constructor instead - object_init_ex() skips it.
        $decl = ($n->kind === 'interface' ? 'interface ' : ($n->final ? 'final ' : '') . 'class ') . $php;
        if ($n->kind === 'class') {
            $decl .= " extends $parentPhp";
        }
        if ($ifaces !== []) {
            $decl .= ($n->kind === 'interface' ? ' extends ' : ' implements ') . implode(', ', $ifaces);
        }
        $stub = $head . "$decl\n{\n" . implode("\n", $stubMethods) . "}\n";
        $this->classInfo[$php] = ['node' => $n, 'stub' => $stub];

        // ---- cpp
        $includes = ['"php_gtk4.h"', '"core/object.h"'];
        // Which core headers a class needs is decided by the identifiers its emitted code
        // uses - code lines only (comments and docblocks stripped, so a docblock mentioning
        // "variant" pulls nothing in).
        $joined = implode('', $cppMethods);
        $code = preg_replace('#^\s*(//|/\*|\*).*$#m', '', $joined) ?? '';
        $prelude = $this->preludes[$n->qname()] ?? '';
        foreach (
            ['enum_' => '"core/enums.h"', 'strv_' => '"core/collections.h"', 'glist_to_php' => '"core/collections.h"',
                'gslist_to_php' => '"core/collections.h"', 'gptrarray_to_php' => '"core/collections.h"',
                'throw_gerror' => '"core/gerror.h"', 'gerror_from_php' => '"core/gerror.h"',
                'variant' => '"core/variant.h"', 'wrap_boxed' => '"core/boxed.h"',
                'unwrap_boxed' => '"core/boxed.h"', 'boxed_class_for_type' => '"core/boxed.h"',
                'wrap_fundamental' => '"core/fundamental.h"', 'unwrap_fundamental' => '"core/fundamental.h"',
                'fundamental_class_for_type' => '"core/fundamental.h"',
                'wrap_cairo' => '"Cairo/CairoContext.h"', 'wrap_param_spec' => '"core/paramspec.h"',
                'subtype_' => '"core/subtype.h"', 'report_pending_exception' => '"core/error.h"',
                'assert_gui_thread' => '"core/mainloop.h"', 'RunningLoop' => '"core/mainloop.h"',
                'callback_new' => '"core/callback.h"',
                'std::array' => '<array>'] as $needle => $inc
        ) {
            if (
                str_contains($code, $needle) && !in_array($inc, $includes, true)
                && !str_contains($prelude, "#include $inc")
            ) {
                $includes[] = $inc;
            }
        }
        $cpp = "// GENERATED by gen/gir.php from {$n->qname()} - do not edit (gen/overrides, gen/skip.txt).\n"
            . "// Gtk4\\$php\n"
            . implode("\n", array_map(fn($i) => "#include $i", $includes)) . "\n\nusing namespace phpgtk;\n\n"
            . ($this->preludes[$n->qname()] ?? '') . $joined;

        // ---- MINIT
        $ce = 'ce_' . $php;
        $args = [];
        if ($n->kind === 'class') {
            $args[] = 'ce_' . $parentPhp;
        }
        foreach ($ifaces as $i) {
            $args[] = 'ce_' . $i;
        }
        if ($n->kind === 'interface') {
            $minit[] = "  zend_class_entry *$ce = register_class_{$this->ceName($php)}(" . implode(', ', $args) . ');';
            $minit[] = "  phpgtk::register_interface(\"$php\", $ce, $typeMacro);";
            foreach ($n->funcs as $f) {
                if ($f->kind === 'method' && $f->shadowedBy === null && $this->methodEmittable($n, $f)) {
                    $protos[] = "ZEND_METHOD({$this->ceName($php)}, " . ($f->shadows ?? $f->name) . ');';
                }
            }
            // The fallback class (core/object.cpp fallback_for): what wrap() hands out for a
            // GTK-private class whose only registered face is this interface (GtkNotebookPages
            // -> GListModelObject). Every interface method, aliased; never constructed.
            $fb = $php . 'Object';
            $fbCe = 'ce_' . $fb;
            $minit[] = "  zend_class_entry *$fbCe = register_class_{$this->ceName($fb)}(ce_GObject, $ce);";
            $minit[] = "  phpgtk::register_interface_fallback($typeMacro, $fbCe);";
            $fbMethods = ["    /** Never called: these handles only come from wrap(). */\n"
                . "    private function __construct() {}\n"];
            foreach ($n->funcs as $f) {
                if ($f->kind !== 'method' || $f->shadowedBy !== null || !$this->methodEmittable($n, $f)) {
                    continue;
                }
                $phpName = $f->shadows ?? $f->name;
                $sig = $this->stubSignature($n, $f, $phpName);
                if ($sig === null) {
                    continue;
                }
                $fbMethods[] = "    /** @implementation-alias Gtk4\\$php::$phpName */\n    public function $sig {}\n";
            }
            $stub .= "\n/**\n * The handle {@see GObject} wrapping falls back to for a GTK-internal class whose only\n"
                . " * registered interface is {@see $php} - a private list model behind a `get_pages()`, for\n"
                . " * instance. Not a GType of its own and never constructed; it is {@see $php} with a body.\n"
                . " *\n * @not-serializable\n */\n"
                . "final class $fb extends GObject implements $php\n{\n" . implode("\n", $fbMethods) . "}\n";
            $cpp .= "\n/**\n * Gtk4\\$fb::__construct()\n *\n"
                . " * Never called: these handles only come from wrap().\n */\n"
                . "ZEND_METHOD({$this->ceName($fb)}, __construct) {\n  ZEND_PARSE_PARAMETERS_NONE();\n}\n";
        } else {
            $minit[] = "  zend_class_entry *$ce = register_class_{$this->ceName($php)}(" . implode(', ', $args) . ');';
            $minit[] = "  phpgtk::register_class(\"$php\", $ce, $typeMacro);";
        }
        return [$stub, $cpp];
    }

    private function qOfPhp(string $php): string
    {
        foreach ($this->gir->types as $q => $n) {
            if (phpClass($n) === $php && $this->known($q)) {
                return $q;
            }
        }
        throw new \RuntimeException("no known type for $php");
    }

    private function implementsQ(Node $n, string $q): bool
    {
        for ($c = $n; $c !== null; $c = $c->parent !== null ? ($this->gir->types[$c->parent] ?? null) : null) {
            if (in_array($q, $c->implements, true)) {
                return true;
            }
        }
        return false;
    }

    // ------------------------------------------------------------ methods

    /** Cheap pre-check shared by class and interface emission (no report entry). */
    private function methodEmittable(Node $n, Func $f): bool
    {
        return $this->methodSkipReason($n, $f) === null
            && $this->stubSignature($n, $f, $f->shadows ?? $f->name) !== null;
    }

    private function methodSkipReason(Node $n, Func $f): ?string
    {
        $key = $n->qname() . '.' . $f->name;
        if (isset($this->skipList[$key])) {
            return 'skip.txt: ' . $this->skipList[$key];
        }
        if ($f->shadowedBy !== null) {
            return "shadowed by {$f->shadowedBy}";
        }
        if ($f->deprecated !== null) {
            return "deprecated ({$f->deprecated})";
        }
        if ($f->version !== null && version_compare($f->version, GTK_FLOOR, '>')) {
            return "since {$f->version} (> " . GTK_FLOOR . ')';
        }
        if ($f->varargs) {
            return 'varargs';
        }
        $closures = [];
        foreach ($f->params as $p) {
            $isCallback = ($this->gir->types[$p->type->name] ?? null)?->kind === 'callback';
            if ($p->scope !== null || str_ends_with($p->type->name, 'DestroyNotify') || $isCallback) {
                // async (one invocation, then released) and call (the duration of the call)
                // scopes are generated with a trampoline when the callback's arguments convert;
                // notified needs the owner's clear function - an override.
                if (
                    !in_array($p->scope, ['async', 'call'], true) || $p->closure === null
                    || $this->callbackSignature($p->type) === null
                ) {
                    return 'callback parameter (needs an override)';
                }
                $closures[$p->closure] = true;
                continue;
            }
            if (in_array($p->type->name, ['gpointer', 'gconstpointer'], true)) {
                if (isset($closures[array_search($p, $f->params, true)])) {
                    continue;  // the callback's user_data slot
                }
                if ($p->name !== 'user_data') {
                    return 'gpointer parameter';
                }
            }
        }
        return null;
    }

    /**
     * @param list<string> $vfuncPre lines for a native vfunc_<name>() method (after `self`)
     * @return array{string, string, string}|null php name, stub method, cpp method
     */
    private function method(
        Node $n,
        Func $f,
        string $typeMacro,
        string $castMacro,
        bool $isRoot,
        array $vfuncPre = [],
        ?array $presetOuts = null,
        ?string $selfLine = null,
    ): ?array {
        $reason = $this->methodSkipReason($n, $f);
        if ($reason !== null) {
            $this->skip($n, $f->name, $reason);
            return null;
        }
        if ($f->kind === 'constructor' && $f->name === 'new') {
            $phpName = '__construct';
        } else {
            $phpName = $f->shadows ?? $f->name;
        }
        $mapped = $this->mapParams($n, $f);
        if (is_string($mapped)) {
            $this->skip($n, $f->name, $mapped);
            return null;
        }
        [$ins, $outs] = $mapped;
        if ($presetOuts !== null) {
            $outs = $presetOuts;
        }
        $retMap = $this->retMapping($n, $f, $outs);
        if (is_string($retMap)) {
            $this->skip($n, $f->name, $retMap);
            return null;
        }

        // ---- stub signature (hidden entries - a callback's user_data - are not PHP parameters)
        $visible = array_values(array_filter($ins, fn($p) => !($p['hidden'] ?? false)));
        $sigParams = [];
        foreach ($visible as $p) {
            $sigParams[] = $p['phpType'] . ' $' . $p['phpName'] . ($p['default'] !== null ? ' = ' . $p['default'] : '');
        }
        $static = $f->kind !== 'method' && $phpName !== '__construct';
        $retType = $phpName === '__construct' ? '' : ': ' . $retMap['phpType'];
        $doc = docSummary($f->doc);
        $docTags = [];
        if (isset($retMap['docType'])) {
            $docTags[] = '@return ' . $retMap['docType'];
        }
        $stubDoc = '';
        $dl = docLines($doc, '    ');
        if ($docTags === [] && count($dl) === 1 && strlen($doc) <= 90) {
            $stubDoc = "    /** $doc */\n";
        } elseif ($dl !== [] || $docTags !== []) {
            $stubDoc = "    /**\n" . implode("\n", $dl) . ($dl !== [] && $docTags !== [] ? "\n     *\n" : '')
                . implode("\n", array_map(fn($t) => "     * $t", $docTags)) . "\n     */\n";
        }
        $sig = "$phpName(" . implode(', ', $sigParams) . ")$retType";
        $body = $n->kind === 'interface' ? ";\n" : " {}\n";
        $stub = $stubDoc . '    public ' . ($static ? 'static ' : '') . "function $sig" . $body;

        // ---- cpp
        $php = phpClass($n);
        $lines = [];
        $lines[] = '/**';
        $lines[] = ' * ' . ($static ? 'static ' : '') . "Gtk4\\$php::$sig";
        if ($doc !== '') {
            $lines[] = ' *';
            foreach (docLines($doc, '') as $l) {
                $lines[] = $l;
            }
        }
        $lines[] = ' */';
        $lines[] = "ZEND_METHOD({$this->ceName($php)}, $phpName) {";
        $required = count(array_filter($visible, fn($p) => $p['default'] === null));
        foreach ($visible as $p) {
            $lines[] = '  ' . $p['decl'];
        }
        if ($visible === []) {
            $lines[] = '  ZEND_PARSE_PARAMETERS_NONE();';
        } else {
            $lines[] = "  ZEND_PARSE_PARAMETERS_START($required, " . count($visible) . ')';
            $optionalOpened = false;
            foreach ($visible as $p) {
                if ($p['default'] !== null && !$optionalOpened) {
                    $lines[] = '  Z_PARAM_OPTIONAL';
                    $optionalOpened = true;
                }
                $lines[] = '  ' . $p['zpp'];
            }
            $lines[] = '  ZEND_PARSE_PARAMETERS_END();';
        }
        $callArgs = [];
        if ($f->kind === 'method') {
            $lines[] = $selfLine ?? "  {$n->ctype} *self = PHPGTK_SELF({$n->ctype}, $typeMacro);";
            array_push($lines, ...array_map(fn($l) => "  $l", $vfuncPre));
            $callArgs[] = 'self';
        }
        foreach ($ins as $p) {
            foreach ($p['pre'] as $l) {
                $lines[] = '  ' . $l;
            }
            $callArgs[] = $p['carg'];
        }
        foreach ($outs as $o) {
            $lines[] = $o['kind'] === 'boxed'
                ? "  {$o['ctype']} {$o['name']}{};"
                : "  {$o['ctype']} {$o['name']} = {$o['init']};";
            $callArgs[] = $o['arg'];
        }
        if ($f->throws) {
            $lines[] = '  GError *error = nullptr;';
            $callArgs[] = '&error';
        }
        $call = $f->cid . '(' . implode(', ', $callArgs) . ')';
        $posts = [];
        foreach ($ins as $p) {
            array_push($posts, ...$p['post']);
        }
        $subtype = $phpName === '__construct' && $posts === [] ? $this->subtypeCall($n, $f, $ins) : null;
        $body = $retMap['lines']($call, $isRoot, $subtype);
        // A pending Throwable (Rethrow mode, a callback threw inside the call) propagates by
        // itself once this method returns - Zend checks EG(exception) after every internal
        // call - so no explicit check; only parameter clean-up has to run right after the call.
        if ($posts !== []) {
            $body = self::spliceCall($body, $call, $posts, $f->ret->ctype);
        }
        foreach ($body as $l) {
            $lines[] = '  ' . $l;
        }
        $lines[] = '}';
        $trampolines = implode('', array_map(fn($p) => $p['trampoline'] ?? '', $ins));
        return [$phpName, $stub, $trampolines . implode("\n", $lines) . "\n\n"];
    }

    /**
     * The C signature of a GIR <callback> as [return Type, list<Param> without the user_data
     * slot, user_data index], or null when an argument does not convert to a zval.
     *
     * @return array{Type, list<Param>, int}|null
     */
    private function callbackSignature(Type $t): ?array
    {
        $node = $this->gir->types[$t->name] ?? null;
        if ($node === null || $node->kind !== 'callback' || $node->funcs === []) {
            return null;
        }
        $sig = $node->funcs[0];
        if ($sig->ret->name !== 'none') {
            return null;  // returning callbacks (compare funcs) stay overrides
        }
        $args = [];
        $userData = null;
        foreach ($sig->params as $i => $p) {
            if (in_array($p->type->name, ['gpointer', 'gconstpointer'], true)) {
                $userData = $i;
                continue;
            }
            if ($p->direction !== 'in' || $p->type->ctype === null || $this->cToZval($p->type, 'x', 'z') === null) {
                return null;
            }
            $args[] = $p;
        }
        if ($userData === null) {
            return null;
        }
        return [$sig->ret, $args, $userData];
    }

    /**
     * A callable parameter with scope async/call: parsed with Z_PARAM_FUNC, carried by a
     * core/callback Callback, invoked by a generated trampoline. The trampoline text rides along
     * in 'trampoline' and is emitted before the method.
     *
     * @return array<string, mixed>|null
     */
    private function callbackParam(Node $n, Func $f, Param $p, int $argNum, bool $trailingNullable = false): ?array
    {
        $sig = $this->callbackSignature($p->type);
        if ($sig === null) {
            return null;
        }
        [, $args] = $sig;
        $cbNode = $this->gir->types[$p->type->name];
        $name = $p->name;
        $php = phpClass($n);
        $phpName = $f->kind === 'constructor' && $f->name === 'new' ? '__construct' : ($f->shadows ?? $f->name);
        // `__construct` would make a reserved identifier (double underscore): use the GIR name
        $tramp = 'cb_' . ($phpName === '__construct' ? $f->name : $phpName) . "_{$name}";
        $cbVar = "cb_$name";
        $origin = "$php::$phpName";
        // --- the trampoline
        $cParams = [];
        $conv = [];
        $i = 0;
        foreach ($cbNode->funcs[0]->params as $cp) {
            $ct = $cp->type->ctype ?? 'gpointer';
            $cParams[] = preg_replace('/\*$/', ' *', $ct) . (str_ends_with($ct, '*') ? '' : ' ') . $cp->name;
        }
        foreach ($args as $a) {
            array_push($conv, ...$this->cToZval($a->type, $a->name, "argv[$i]") ?? []);
            $i++;
        }
        $argc = count($args);
        $userDataName = $cbNode->funcs[0]->params[$sig[2]]->name;
        $l = [];
        $l[] = 'namespace {';
        $l[] = "// {$cbNode->name} trampoline for $origin(): wraps the C arguments, invokes the PHP callable"
            . ($p->scope === 'async'
                ? ' once and releases it (async scope).'
                : ' (call scope: released after the call).');
        $l[] = "void $tramp(" . implode(', ', $cParams) . ') {';
        $l[] = "  auto *cb = static_cast<Callback *>($userDataName);";
        if ($argc > 0) {
            $l[] = "  std::array<zval, $argc> args{};";
            $l[] = '  zval *argv = args.data();';
            foreach ($conv as $c) {
                $l[] = "  $c";
            }
        }
        $l[] = '  zval ret;';
        $l[] = "  callback_invoke(cb, $argc, " . ($argc > 0 ? 'argv' : 'nullptr') . ', &ret);';
        $l[] = '  if (!Z_ISUNDEF(ret)) zval_ptr_dtor(&ret);';
        if ($argc > 0) {
            $l[] = '  for (zval &arg : args) zval_ptr_dtor(&arg);';
        }
        if ($p->scope === 'async') {
            $l[] = '  callback_free(cb);';
            $l[] = '  callback_drain();';
        }
        $l[] = '}';
        $l[] = '}  // namespace';
        $trampoline = implode("\n", $l) . "\n\n";
        // --- the parameter
        $nullable = $p->nullable;
        $phpArgs = implode(', ', array_map(
            fn($a) => ($a->nullable ? '?' : '') . ($this->phpType($a->type, false) ?? 'mixed') . ' $' . $a->name,
            $args,
        ));
        return [
            'phpName' => $name, 'default' => $nullable && $trailingNullable ? 'null' : null,
            'phpType' => ($nullable ? '?' : '') . 'callable',
            'docType' => "callable($phpArgs): void",
            'decl' => "zend_fcall_info fci_$name = empty_fcall_info;\n"
                . "  zend_fcall_info_cache fcc_$name = empty_fcall_info_cache;",
            'zpp' => 'Z_PARAM_FUNC' . ($nullable ? '_OR_NULL' : '') . "(fci_$name, fcc_$name)",
            'pre' => $nullable
                ? ["Callback *$cbVar = ZEND_FCI_INITIALIZED(fci_$name)"
                    . " ? callback_new(&fci_$name.function_name, \"$origin\") : nullptr;"]
                : ["Callback *$cbVar = callback_new(&fci_$name.function_name, \"$origin\");"],
            'carg' => $nullable ? "$cbVar != nullptr ? $tramp : nullptr" : $tramp,
            'post' => $p->scope === 'call'
                ? ["if ($cbVar != nullptr) {", "  callback_free($cbVar);", '  callback_drain();', '}']
                : [],
            'cbVar' => $cbVar,
            'trampoline' => $trampoline,
        ];
    }


    /**
     * The subtype_new() call for a constructor: every parameter must be a construct property of
     * the class (by name, `_` -> `-`) or mapped in gen/ctor-props.txt; otherwise a PHP subclass
     * of this class cannot be built through `new` (reported, plain wrapper subclass as before).
     *
     * @param list<array<string, mixed>> $ins
     * @return array{call: string, post: list<string>}|null
     */
    private function subtypeCall(Node $n, Func $f, array $ins): ?array
    {
        $props = [];
        for ($q = $n->qname(); $q !== null; $q = $this->gir->types[$q]->parent ?? null) {
            $node = $this->gir->types[$q] ?? null;
            if ($node === null) {
                break;
            }
            foreach ($node->props as $p) {
                $props[$p['name']] = true;
            }
            foreach ($node->implements as $i) {  // interface properties (GtkOrientable::orientation)
                foreach (($this->gir->types[$i] ?? null)?->props ?? [] as $p) {
                    $props[$p['name']] = true;
                }
            }
        }
        $args = [];
        $post = [];
        foreach ($ins as $p) {
            $name = $p['phpName'];
            $prop = $this->ctorProps[$n->qname()][$name] ?? str_replace('_', '-', $name);
            if (!isset($props[$prop])) {
                $this->skip($n, 'PHP subclasses', "constructor argument $name is not a construct property "
                    . '(map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain ' . phpClass($n));
                return null;
            }
            $args[] = "\"$prop\"";
            $args[] = $p['carg'];
            if (($p['objectVar'] ?? null) !== null) {
                // g_object_new() takes its own reference; the one taken for the C constructor goes
                $post[] = "if ({$p['objectVar']} != nullptr) g_object_unref({$p['objectVar']});";
            }
        }
        $args[] = 'nullptr';
        return ['call' => 'subtype_new(ZEND_THIS, ' . implode(', ', $args) . ')', 'post' => $post];
    }

    /**
     * A boxed record (glib:get-type) as a value-type handle on core/boxed: public scalar fields
     * become PHP properties, GIR methods/constructors are emitted like a class's with the handle's
     * data as `self`; copy/free/ref/unref are the handle's business and skipped.
     *
     * @return array{string, string} stub section, cpp text
     */
    private function emitRecord(Node $n, array &$minit, array &$protos): array
    {
        $php = phpClass($n);
        [$typeMacro, $castMacro] = macroParts($this->gir, $n);
        $ctype = $n->ctype ?? $n->gtypeName;
        $fields = [];
        foreach ($n->recordFields as $fld) {
            $t = $fld['type'];
            $kind = match (true) {
                $t->name === 'gboolean' => 'bool',
                in_array($t->name, ['gfloat', 'gdouble'], true) => 'float',
                preg_match(INT_TYPES, $t->name) === 1 => 'int',
                default => null,
            };
            if ($fld['private'] || $kind === null || $t->isArray || str_ends_with($t->ctype ?? '', '*')) {
                if (!$fld['private']) {
                    $this->skip($n, 'field ' . $fld['name'], "field type {$t->name} is not a scalar");
                }
                continue;
            }
            $fields[] = ['name' => $fld['name'], 'kind' => $kind, 'ctype' => $t->ctype ?? 'int',
                'writable' => $fld['writable']];
        }

        $stubMethods = [];
        $cppMethods = [];
        $seenNames = [];
        $overrides = $this->overrides[$n->qname()] ?? [];
        $selfLine = "  $ctype *self = PHPGTK_BOXED_SELF($ctype);";
        foreach ($n->funcs as $f) {
            $phpNameOf = $f->kind === 'constructor' && $f->name === 'new' ? '__construct' : ($f->shadows ?? $f->name);
            if (isset($overrides[$phpNameOf])) {
                continue;
            }
            if (in_array($f->name, ['copy', 'free', 'ref', 'unref'], true) && $f->kind === 'method') {
                $this->skip($n, $f->name, 'memory management belongs to the handle (clone / destructor)');
                continue;
            }
            $m = $this->method($n, $f, $typeMacro, $castMacro, false, [], null, $selfLine);
            if ($m === null) {
                continue;
            }
            [$phpName, $stubM, $cppM] = $m;
            if (isset($seenNames[$phpName])) {
                $this->skip($n, $f->name, "PHP name $phpName already taken");
                continue;
            }
            $seenNames[$phpName] = true;
            $stubMethods[] = $stubM;
            $cppMethods[] = $cppM;
        }
        foreach ($overrides as $phpName => $o) {
            $seenNames[$phpName] = true;
            $stubMethods[] = $o['stub'];
            $cppMethods[] = $o['cpp'];
        }
        $ce = $this->ceName($php);
        if (!isset($seenNames['__construct'])) {
            $seenNames['__construct'] = true;
            if ($fields !== []) {
                // a plain struct: construct from its fields (a stack value copied by the type's
                // own copy function, so the handle frees it with the matching free function)
                $params = [];
                $decls = [];
                $zpp = [];
                $sets = [];
                foreach ($fields as $fd) {
                    $default = match ($fd['kind']) {
                        'bool' => 'false', 'float' => '0.0', default => '0'
                    };
                    $params[] = "{$fd['kind']} \${$fd['name']} = $default";
                    [$cdecl, $zp, $cast] = match ($fd['kind']) {
                        'bool' => ["bool {$fd['name']} = false;", "Z_PARAM_BOOL({$fd['name']})", "{$fd['name']}"],
                        'float' => ["double {$fd['name']} = 0;", "Z_PARAM_DOUBLE({$fd['name']})",
                            "static_cast<{$fd['ctype']}>({$fd['name']})"],
                        default => ["zend_long {$fd['name']} = 0;", "Z_PARAM_LONG({$fd['name']})",
                            "static_cast<{$fd['ctype']}>({$fd['name']})"],
                    };
                    $decls[] = "  $cdecl";
                    $zpp[] = "  $zp";
                    $sets[] = "  value.{$fd['name']} = $cast;";
                }
                $sig = '__construct(' . implode(', ', $params) . ')';
                array_unshift($stubMethods, "    /** A value from its fields (all optional, zero by default). */\n"
                    . "    public function $sig {}\n");
                array_unshift($cppMethods, "/**\n * Gtk4\\$php::$sig\n *\n"
                    . " * A value from its fields (all optional, zero by default).\n */\n"
                    . "ZEND_METHOD($ce, __construct) {\n" . implode("\n", $decls) . "\n"
                    . '  ZEND_PARSE_PARAMETERS_START(0, ' . count($fields) . ")\n  Z_PARAM_OPTIONAL\n"
                    . implode("\n", $zpp) . "\n  ZEND_PARSE_PARAMETERS_END();\n"
                    . "  $ctype value{};\n" . implode("\n", $sets) . "\n"
                    . "  boxed_adopt(boxed_from_zval(ZEND_THIS), $typeMacro, "
                    . "g_boxed_copy($typeMacro, &value));\n}\n\n");
            } else {
                array_unshift($stubMethods, "    /** $php values come from GTK, never from `new`. */\n"
                    . "    private function __construct() {}\n");
                array_unshift($cppMethods, "/**\n * Gtk4\\$php::__construct()\n *\n"
                    . " * $php values come from GTK, never from `new`.\n */\n"
                    . "ZEND_METHOD($ce, __construct) {\n  ZEND_PARSE_PARAMETERS_NONE();\n}\n\n");
            }
        }

        // ---- field access (core/boxed reader/writer) + registration
        $names = implode(', ', array_map(fn($fd) => "\"{$fd['name']}\"", $fields));
        $reads = [];
        $writes = [];
        foreach ($fields as $fd) {
            $zv = match ($fd['kind']) {
                'bool' => "ZVAL_BOOL(rv, value->{$fd['name']} != FALSE);",
                'float' => "ZVAL_DOUBLE(rv, value->{$fd['name']});",
                default => "ZVAL_LONG(rv, static_cast<zend_long>(value->{$fd['name']}));",
            };
            $reads[] = "  if (strcmp(field, \"{$fd['name']}\") == 0) {\n    $zv\n    return true;\n  }";
            if ($fd['writable']) {
                $set = match ($fd['kind']) {
                    'bool' => "value->{$fd['name']} = zend_is_true(v) ? TRUE : FALSE;",
                    'float' => "value->{$fd['name']} = static_cast<{$fd['ctype']}>(zval_get_double(v));",
                    default => "value->{$fd['name']} = static_cast<{$fd['ctype']}>(zval_get_long(v));",
                };
                $writes[] = "  if (strcmp(field, \"{$fd['name']}\") == 0) {\n    $set\n    return true;\n  }";
            }
        }
        $access = "namespace {\n\nconst char *const fields[] = {" . ($names !== '' ? "$names, " : '') . "nullptr};\n\n"
            . "// Boxed field reader: the public scalar fields as PHP properties.\n"
            . "bool read(gpointer data, const char *field, zval *rv) {\n"
            . ($reads !== []
                ? "  auto *value = static_cast<$ctype *>(data);\n" . implode("\n", $reads) . "\n"
                : "  (void)data;\n  (void)field;\n  (void)rv;\n")
            . "  return false;\n}\n\n"
            . "// Boxed field writer: coerces to the field's C type.\n"
            . "bool write(gpointer data, const char *field, zval *v) {\n"
            . ($writes !== []
                ? "  auto *value = static_cast<$ctype *>(data);\n" . implode("\n", $writes) . "\n"
                : "  (void)data;\n  (void)field;\n  (void)v;\n")
            . "  return false;\n}\n\n}  // namespace\n\n";
        $registration = "namespace phpgtk {\n// MINIT: bind the PHP class to $typeMacro with its field table.\n"
            . "void register_{$php}(zend_class_entry *ce) {\n"
            . "  register_boxed(BoxedClass{.type = $typeMacro, .ce = ce, .fields = fields, .read = read,"
            . " .write = write});\n"
            . "}\n}  // namespace phpgtk\n";

        // ---- stub
        $props = [];
        foreach ($fields as $fd) {
            $props[] = ' * @' . ($fd['writable'] ? 'property' : 'property-read') . " {$fd['kind']} \${$fd['name']}";
        }
        $docLines = docLines(docSummary($n->doc), '');
        $head = "/**\n" . implode("\n", $docLines) . ($docLines !== [] && $props !== [] ? "\n *\n" : '')
            . implode("\n", $props) . "\n * @not-serializable\n */\n";
        $stub = $head . "final class $php\n{\n" . implode("\n", $stubMethods) . "}\n";
        $this->classInfo[$php] = ['node' => $n, 'stub' => $stub];

        // ---- cpp
        $joined = implode('', $cppMethods);
        $includes = ['"php_gtk4.h"', '"core/boxed.h"', '"core/object.h"', '<cstring>'];
        $code = preg_replace('#^\s*(//|/\*|\*).*$#m', '', $joined) ?? '';
        foreach (
            ['enum_' => '"core/enums.h"', 'throw_gerror' => '"core/gerror.h"',
                'gerror_from_php' => '"core/gerror.h"', 'strv_' => '"core/collections.h"',
                'variant' => '"core/variant.h"', 'std::array' => '<array>'] as $needle => $inc
        ) {
            if (str_contains($code, $needle) && !in_array($inc, $includes, true)) {
                $includes[] = $inc;
            }
        }
        $cpp = "// GENERATED by gen/gir.php from {$n->qname()} - do not edit (gen/overrides, gen/skip.txt).\n"
            . "// Gtk4\\$php (boxed value type)\n"
            . implode("\n", array_map(fn($i) => "#include $i", $includes)) . "\n\nusing namespace phpgtk;\n\n"
            . ($this->preludes[$n->qname()] ?? '') . $access . $joined . $registration;

        // ---- MINIT (declaration/registration pair like a class; no parent dependency)
        $minit[] = "  zend_class_entry *ce_$php = register_class_{$ce}();";
        $minit[] = "  phpgtk::register_{$php}(ce_$php);";
        $protos[] = "namespace phpgtk {\nvoid register_{$php}(zend_class_entry *ce);\n}  // namespace phpgtk";
        return [$stub, $cpp];
    }

    /**
     * Per virtual method of a class: a C thunk calling `$this->vfunc_<name>()`, an installer
     * writing it into the class struct, and the native `vfunc_<name>()` method (the GTK
     * implementation below every PHP level, for `parent::vfunc_<name>()`). See core/subtype.h.
     *
     * @param array<string, bool> $seenNames
     * @return array{list<string>, list<string>, string} stub methods, cpp methods, registration fn
     */
    private function emitVfuncs(Node $n, string $typeMacro, string $castMacro, array &$seenNames): array
    {
        $php = phpClass($n);
        $struct = null;
        foreach ($this->gir->types as $t) {
            if ($t->structFor === $n->qname()) {
                $struct = $t;
                break;
            }
        }
        if ($struct === null) {
            $this->skip($n, 'vfuncs', 'no class struct in GIR');
            return [[], [], ''];
        }
        // An interface's slots live in its iface struct (GListModelInterface): the thunk calls the
        // PHP *interface method* itself (get_n_items(), not vfunc_get_n_items()), there is no native
        // implementation to chain to, and the installer runs from iface_init of a PHP GType.
        $isIface = $n->kind === 'interface';
        $classMacro = $isIface ? "static_cast<{$struct->ctype} *>" : "{$castMacro}_CLASS";
        $stub = [];
        $thunks = [];
        $natives = [];
        $reg = [];
        foreach ($n->vfuncs as $v) {
            $label = "vfunc {$v->name}";
            if (!in_array($v->name, $struct->fields, true)) {
                $this->skip($n, $label, "no field {$v->name} in {$struct->name}");
                continue;
            }
            if ($v->throws) {
                $this->skip($n, $label, 'GError out parameter');
                continue;
            }
            $reason = $this->methodSkipReason($n, $v);
            if ($reason !== null) {
                $this->skip($n, $label, $reason);
                continue;
            }
            $pointerScalar = null;
            foreach ($v->params as $p) {
                $scalar = $p->type->name === 'gboolean' || in_array($p->type->name, ['gfloat', 'gdouble'], true)
                    || preg_match(INT_TYPES, $p->type->name) === 1;
                if ($scalar && $p->direction === 'in' && str_ends_with($p->type->ctype ?? '', '*')) {
                    $pointerScalar = $p->name;  // gboolean* without direction (compute_expand)
                }
            }
            if ($pointerScalar !== null) {
                $this->skip($n, $label, "parameter $pointerScalar is a pointer to a scalar without direction");
                continue;
            }
            $mapped = $this->mapParams($n, $v);
            if (is_string($mapped)) {
                $this->skip($n, $label, $mapped);
                continue;
            }
            [$ins, $outs] = $mapped;
            if ($isIface) {
                $thunk = $this->vfuncThunk($n, $v, $ins, $outs, $classMacro, $v->name);
                if ($thunk === null) {
                    $this->skip($n, $label, 'return or argument type not convertible in a thunk');
                    continue;
                }
                $thunks[] = $thunk;
                $reg[] = "  register_iface_vfunc($typeMacro, \"{$v->name}\", vfunc_install_{$v->name});";
                continue;
            }
            $phpName = "vfunc_{$v->name}";
            if (isset($seenNames[$phpName])) {
                $this->skip($n, $label, "PHP name $phpName already taken");
                continue;
            }
            // --- the native method (parent::vfunc_x() from an override)
            $native = new Func(
                $phpName,
                "klass->{$v->name}",
                'method',
                $v->ret,
                $v->retTransfer,
                $v->retNullable,
                $v->params,
                false,
                $v->version,
                null,
                null,
                null,
                false,
                "Native `{$v->name}` ({$struct->name}.{$v->name}): the GTK implementation below any PHP "
                . "subclass, for `parent::$phpName()` from an override. " . docSummary($v->doc),
            );
            // An empty slot (a signal's class handler GTK left NULL, `clicked`) is a no-op that
            // yields the type's zero value, so parent::vfunc_x() from an override always works.
            $retMap = $this->retMapping($n, $native, $outs);
            $phpRet = is_array($retMap) ? $retMap['phpType'] : 'void';
            $empty = match (true) {
                $phpRet === 'void' => ['return;'],
                $phpRet === 'bool' => ['RETURN_FALSE;'],
                $phpRet === 'int' => ['RETURN_LONG(0);'],
                $phpRet === 'float' => ['RETURN_DOUBLE(0);'],
                $phpRet === 'string' => ['RETURN_EMPTY_STRING();'],
                $phpRet === 'array' => ['array_init_size(return_value, ' . count($outs) . ');',
                    ...array_map(
                        fn($o) => ($o['kind'] === 'bool' ? 'add_next_index_bool' : ($o['kind'] === 'double'
                            ? 'add_next_index_double' : 'add_next_index_long')) . '(return_value, '
                            . (str_contains($o['name'], 'baseline') ? '-1' : '0') . ');',
                        $outs,
                    ),
                    'return;'],
                str_starts_with($phpRet, '?') => ['RETURN_NULL();'],
                default => ['enum_to_php(' . ($this->enumMacroOf($v->ret) ?? 'G_TYPE_NONE') . ', 0, return_value);',
                    'return;'],
            };
            // Only a PHP subtype may reach the slot directly (parent:: from its override): on a
            // native instance this bypasses the public API's preconditions (map() unrealized ...).
            $pre = ['if (!is_php_type(G_OBJECT_TYPE(self))) {',
                '  zend_throw_exception_ex(spl_ce_LogicException, 0,',
                "                          \"$php::$phpName(): for parent:: chaining from a PHP subclass \"",
                '                          "only; call the public method instead");',
                '  RETURN_THROWS();', '}',
                "auto *klass = $classMacro(subtype_native_class(G_OBJECT(self)));",
                "if (klass->{$v->name} == nullptr) {", ...array_map(fn($l) => "  $l", $empty), '}'];
            // gtk_widget_measure() hands the vfunc baselines preset to -1 ("no baseline"); the
            // out-parameter convention would report 0, which GTK then warns about.
            foreach ($outs as &$o) {
                if (str_contains($o['name'], 'baseline')) {
                    $o['init'] = '-1';
                }
            }
            unset($o);
            $m = $this->method($n, $native, $typeMacro, $castMacro, false, $pre, $outs);
            if ($m === null) {
                continue;  // reported by method()
            }
            // --- the thunk
            $thunk = $this->vfuncThunk($n, $v, $ins, $outs, $classMacro, $phpName);
            if ($thunk === null) {
                $this->skip($n, $label, 'return or argument type not convertible in a thunk');
                continue;
            }
            $seenNames[$phpName] = true;
            $stub[] = $m[1];
            $thunks[] = $thunk;
            $natives[] = $m[2];
            $reg[] = "  register_vfunc($typeMacro, \"{$v->name}\", vfunc_install_{$v->name});";
        }
        if ($reg === []) {
            return [[], [], ''];
        }
        // file-local thunks and installers in one anonymous namespace (the convention for
        // hand-written helpers in gen/overrides), the native vfunc_*() methods after it
        $cpp = ["// vfunc thunks and installers: file-local, installed by class_init of a PHP subtype\n"
            . "namespace {\n\n" . implode('', $thunks) . "}  // namespace\n\n", ...$natives];
        $regFn = "// MINIT: the vfunc thunks of $php (core/subtype.h).\nvoid register_vfuncs_{$php}() {\n"
            . implode("\n", $reg) . "\n}\n\n";
        return [$stub, $cpp, $regFn];
    }

    /**
     * @param list<array<string, mixed>> $ins
     * @param list<array<string, mixed>> $outs
     */
    private function vfuncThunk(Node $n, Func $v, array $ins, array $outs, string $classMacro, string $phpName): ?string
    {
        $php = phpClass($n);
        $retCt = $v->ret->name === 'none' ? 'void' : ($v->ret->ctype ?? null);
        if ($retCt === null) {
            return null;
        }
        $cParams = ["{$n->ctype} *self"];
        $passArgs = ['self'];
        $conv = [];
        $argIndex = 0;
        foreach ($v->params as $p) {
            $ct = $p->type->ctype;
            if ($ct === null) {
                return null;
            }
            $cParams[] = preg_replace('/\*$/', ' *', $ct) . (str_ends_with($ct, '*') ? '' : ' ') . $p->name;
            $passArgs[] = $p->name;
            if ($p->direction === 'out') {
                continue;
            }
            $lines = $this->cToZval($p->type, $p->name, "argv[$argIndex]");
            if ($lines === null) {
                return null;
            }
            array_push($conv, ...$lines);
            $argIndex++;
        }
        $argc = $argIndex;
        [$resultDecl, $resultConv, $resultRet] = $this->zvalToC($v, $outs);
        if ($resultDecl === null) {
            return null;
        }
        $l = [];
        $l[] = "// vfunc thunk: {$classMacro}->{$v->name} -> \$this->$phpName() on a PHP subclass";
        $l[] = "$retCt vfunc_thunk_{$v->name}(" . implode(', ', $cParams) . ') {';
        $l[] = '  zval zself;';
        // An exception already pending (an earlier callback of this emission threw, Rethrow mode)
        // must not be reported again by every later thunk: GTK's own implementation runs instead.
        $l[] = "  zend_function *fn = EG(exception) == nullptr ? subtype_vfunc(G_OBJECT(self), \"$phpName\", &zself)";
        $l[] = '                                                 : nullptr;';
        $l[] = '  if (fn == nullptr) {  // no handle (mid-construction, after shutdown) or exception pending';
        if ($n->kind === 'interface') {
            $l[] = '    // an interface implemented in PHP has no native implementation below it';
            $l[] = $retCt === 'void' ? '    return;' : "    return {$resultDecl['default']};";
        } else {
            $l[] = "    auto *native = $classMacro(subtype_native_class(G_OBJECT(self)));";
            $nativeCall = "native->{$v->name}(" . implode(', ', $passArgs) . ')';
            if ($retCt === 'void') {
                $l[] = "    if (native->{$v->name} != nullptr) $nativeCall;";
                $l[] = '    return;';
            } else {
                $l[] = "    return native->{$v->name} != nullptr ? $nativeCall : {$resultDecl['default']};";
            }
        }
        $l[] = '  }';
        if ($argc > 0) {
            $l[] = "  std::array<zval, $argc> args{};";
            $l[] = '  zval *argv = args.data();';
            foreach ($conv as $c) {
                $l[] = "  $c";
            }
        }
        $l[] = '  zval ret;';
        $l[] = '  ZVAL_UNDEF(&ret);';
        if ($retCt !== 'void') {
            $l[] = "  {$resultDecl['decl']}";
        }
        $argvExpr = $argc > 0 ? 'args.data()' : 'nullptr';
        $l[] = "  zend_call_known_instance_method(fn, Z_OBJ(zself), &ret, $argc, $argvExpr);";
        if ($resultConv !== []) {
            $l[] = '  if (EG(exception) == nullptr && !Z_ISUNDEF(ret)) {';
            foreach ($resultConv as $c) {
                $l[] = "    $c";
            }
            $l[] = '  }';
        }
        if ($argc > 0) {
            $l[] = '  for (zval &arg : args) zval_ptr_dtor(&arg);';
        }
        $l[] = '  zval_ptr_dtor(&ret);';
        $l[] = '  zval_ptr_dtor(&zself);';
        $l[] = "  report_pending_exception(\"$php::$phpName\");";
        if ($retCt !== 'void') {
            $l[] = "  return $resultRet;";
        }
        $l[] = '}';
        $l[] = '';
        $l[] = "// vfunc installer: {$classMacro}->{$v->name} (called from class_init / iface_init of a PHP subtype)";
        $l[] = "void vfunc_install_{$v->name}(gpointer klass) {";
        $l[] = "  $classMacro(klass)->{$v->name} = vfunc_thunk_{$v->name};";
        $l[] = '}';
        return implode("\n", $l) . "\n\n";
    }

    /** The *_TYPE_* macro of a known GEnum return type, null otherwise. */
    private function enumMacroOf(Type $t): ?string
    {
        $node = $this->gir->types[$t->name] ?? null;
        if ($node === null || $node->kind !== 'enum' || $node->gtypeName === null || !$this->known($t->name)) {
            return null;
        }
        return macroParts($this->gir, $node)[0];
    }

    /**
     * C value -> zval lines for a thunk argument (nullptr: not convertible).
     *
     * @return list<string>|null
     */
    private function cToZval(Type $t, string $c, string $zv): ?array
    {
        if (in_array($t->name, ['utf8', 'filename'], true) && !$t->isArray) {
            return ["if ($c == nullptr) {", "  ZVAL_NULL(&$zv);", '} else {', "  ZVAL_STRING(&$zv, $c);", '}'];
        }
        if ($t->name === 'gboolean') {
            return ["ZVAL_BOOL(&$zv, $c != FALSE);"];
        }
        if (in_array($t->name, ['gfloat', 'gdouble'], true)) {
            return ["ZVAL_DOUBLE(&$zv, $c);"];
        }
        if (preg_match(INT_TYPES, $t->name)) {
            return ["ZVAL_LONG(&$zv, static_cast<zend_long>($c));"];
        }
        if (in_array($t->name, ['GObject.GType', 'Gio.GType', 'GLib.GType', 'GType'], true)) {
            return ["ZVAL_STRING(&$zv, g_type_name($c));"];
        }
        $node = $this->gir->types[$t->name] ?? null;
        if ($node === null || $t->isArray) {
            return null;
        }
        [$typeMacro] = macroParts($this->gir, $node);
        if ($node->kind === 'enum' && $node->gtypeName !== null && $this->known($t->name)) {
            return ["enum_to_php($typeMacro, static_cast<gint>($c), &$zv);"];
        }
        if ($node->kind === 'enum' || $node->kind === 'bitfield') {
            return ["ZVAL_LONG(&$zv, static_cast<zend_long>($c));"];
        }
        if (in_array($node->kind, ['class', 'interface'], true) && $this->phpTypeOfNode($t->name) !== null) {
            return ["wrap($c != nullptr ? G_OBJECT($c) : nullptr, &$zv);"];
        }
        if ($node->kind === 'record' && $node->gtypeName !== null && $this->known($t->name)) {
            return ["wrap_boxed($typeMacro, $c, &$zv);"];
        }
        return null;
    }

    /**
     * Return value + out parameters of a thunk from the PHP result (`ret`).
     *
     * @param list<array<string, mixed>> $outs
     * @return array{array{decl: string, default: string}|null, list<string>, string}
     */
    private function zvalToC(Func $v, array $outs): array
    {
        $t = $v->ret;
        foreach ($outs as $o) {
            if (!in_array($o['kind'], ['long', 'bool', 'double'], true)) {
                return [null, [], ''];  // a thunk can only fill scalar outs
            }
        }
        $outLines = function (string $arrayExpr) use ($outs): array {
            $l = [];
            foreach ($outs as $i => $o) {
                $get = match ($o['kind']) {
                    'long' => "static_cast<{$o['ctype']}>(zval_get_long(e$i))",
                    'bool' => "zend_is_true(e$i) ? TRUE : FALSE",
                    default => "static_cast<{$o['ctype']}>(zval_get_double(e$i))",
                };
                $l[] = "if (zval *e$i = zend_hash_index_find(Z_ARRVAL($arrayExpr), $i);"
                    . " e$i != nullptr && {$o['name']} != nullptr) {";
                $l[] = "  *{$o['name']} = $get;";
                $l[] = '}';
            }
            return $l;
        };
        if ($t->name === 'none') {
            if ($outs === []) {
                return [['decl' => '', 'default' => ''], [], ''];
            }
            $lines = ['if (Z_TYPE(ret) == IS_ARRAY) {', ...array_map(fn($x) => "  $x", $outLines('ret')), '}'];
            return [['decl' => '', 'default' => ''], $lines, ''];
        }
        $ct = $t->ctype ?? '';
        if ($t->name === 'gboolean') {
            if ($outs !== []) {
                return [['decl' => 'gboolean result = FALSE;', 'default' => 'FALSE'],
                    ['if (Z_TYPE(ret) == IS_ARRAY) {', '  result = TRUE;',
                        ...array_map(fn($x) => "  $x", $outLines('ret')), '}'],
                    'result'];
            }
            return [['decl' => 'gboolean result = FALSE;', 'default' => 'FALSE'],
                ['result = zend_is_true(&ret) ? TRUE : FALSE;'], 'result'];
        }
        if ($outs !== []) {
            return [null, [], ''];
        }
        if (in_array($t->name, ['gfloat', 'gdouble'], true)) {
            $conv = $ct === 'double' ? 'zval_get_double(&ret)' : "static_cast<$ct>(zval_get_double(&ret))";
            return [['decl' => "$ct result = 0;", 'default' => '0'], ["result = $conv;"], 'result'];
        }
        if (preg_match(INT_TYPES, $t->name)) {
            return [['decl' => "$ct result = 0;", 'default' => '0'],
                ["result = static_cast<$ct>(zval_get_long(&ret));"], 'result'];
        }
        if (in_array($t->name, ['utf8', 'filename'], true) && !$t->isArray && $v->retTransfer === 'full') {
            return [['decl' => 'char *result = nullptr;', 'default' => 'nullptr'],
                ['if (Z_TYPE(ret) == IS_STRING) result = g_strdup(Z_STRVAL(ret));'], 'result'];
        }
        if (in_array($t->name, ['GObject.GType', 'Gio.GType', 'GLib.GType', 'GType'], true)) {
            // the PHP side names the type ("GObject", a registered class' GType name)
            return [['decl' => 'GType result = G_TYPE_OBJECT;', 'default' => 'G_TYPE_OBJECT'],
                ['if (Z_TYPE(ret) == IS_STRING) {', '  const GType named = g_type_from_name(Z_STRVAL(ret));',
                    '  if (named != 0) result = named;', '}'], 'result'];
        }
        $node = $this->gir->types[$t->name] ?? null;
        if ($node === null || $t->isArray) {
            return [null, [], ''];
        }
        [$typeMacro, $castMacro] = macroParts($this->gir, $node);
        if ($node->kind === 'enum' && $node->gtypeName !== null && $this->known($t->name)) {
            return [['decl' => "$ct result = static_cast<$ct>(0);", 'default' => "static_cast<$ct>(0)"],
                ['gint v = 0;', "if (enum_from_php(&ret, $typeMacro, &v)) result = static_cast<$ct>(v);"], 'result'];
        }
        if ($node->kind === 'enum' || $node->kind === 'bitfield') {
            return [['decl' => "$ct result = static_cast<$ct>(0);", 'default' => "static_cast<$ct>(0)"],
                ["result = static_cast<$ct>(zval_get_long(&ret));"], 'result'];
        }
        if (in_array($node->kind, ['class', 'interface'], true) && $this->phpTypeOfNode($t->name) !== null) {
            $ref = $v->retTransfer === 'full' ? ['if (result != nullptr) g_object_ref(result);  // transfer full'] : [];
            return [['decl' => "$ct result = nullptr;", 'default' => 'nullptr'],
                ['if (Z_TYPE(ret) == IS_OBJECT) {', "  GObject *o = unwrap(&ret, $typeMacro);",
                    "  result = o != nullptr ? $castMacro(o) : nullptr;",
                    ...array_map(fn($x) => "  $x", $ref), '}'], 'result'];
        }
        return [null, [], ''];
    }

    /**
     * Rewrite the result-handling lines so that the C call happens once (hoisted into a
     * local when it sits inside an expression) and parameter clean-up (g_bytes_unref,
     * g_strfreev, ...) runs right after it, before any early return.
     *
     * @param list<string> $body
     * @param list<string> $posts
     * @return list<string>
     */
    private static function spliceCall(array $body, string $call, array $posts, ?string $ctype): array
    {
        // The C return type from GIR ("GtkWidget*", "gboolean"); `auto` only when GIR has none.
        $decl = $ctype !== null && $ctype !== '' ? preg_replace('/\s*\*$/', ' *', $ctype) : 'auto ';
        $decl = str_ends_with($decl, '*') || str_ends_with($decl, ' ') ? $decl : "$decl ";
        $out = [];
        $done = false;
        foreach ($body as $i => $line) {
            if (!$done && str_contains($line, $call)) {
                $done = true;
                if (preg_match('/^[\w\s\*<>:]+ \w+ = ' . preg_quote($call, '/') . ';$/', $line) || $line === "$call;") {
                    // already a statement: keep it, clean up right after
                    $out[] = $line;
                    array_push($out, ...$posts);
                    continue;
                }
                // expression context: hoist
                $out[] = "{$decl}call_result = $call;";
                array_push($out, ...$posts);
                $line = str_replace($call, 'call_result', $line);
            }
            $out[] = $line;
        }
        return $out;
    }

    /** NULL-terminated char** (GStrv) without a length parameter. */
    private static function isStrv(Type $t): bool
    {
        return $t->isArray && $t->element !== null && $t->element->name === 'utf8' && $t->lengthParam === null;
    }

    /** PHP type of an out parameter mapping (docblock / single-out return type). */
    private static function outScalar(array $o): string
    {
        return $o['phpType'];
    }

    private function stubSignature(Node $n, Func $f, string $phpName): ?string
    {
        $mapped = $this->mapParams($n, $f);
        if (is_string($mapped)) {
            return null;
        }
        [$ins, $outs] = $mapped;
        $retMap = $this->retMapping($n, $f, $outs);
        if (is_string($retMap)) {
            return null;
        }
        $sigParams = [];
        foreach ($ins as $p) {
            $sigParams[] = $p['phpType'] . ' $' . $p['phpName'] . ($p['default'] !== null ? ' = ' . $p['default'] : '');
        }
        return "$phpName(" . implode(', ', $sigParams) . '): ' . $retMap['phpType'];
    }

    // ------------------------------------------------------------ type mapping

    /** PHP type of a GIR type for the stub (null = not mappable). */
    private function phpType(Type $t, bool $nullable): ?string
    {
        $base = match (true) {
            $t->name === 'none' => 'void',
            in_array($t->name, ['utf8', 'filename'], true) => 'string',
            $t->name === 'gboolean' => 'bool',
            in_array($t->name, ['gfloat', 'gdouble'], true) => 'float',
            (bool) preg_match(INT_TYPES, $t->name) => 'int',
            $t->name === 'GLib.Variant' => 'mixed',
            $t->name === 'GLib.Bytes' => 'string',
            $t->name === 'GLib.VariantType' => 'string',
            in_array($t->name, ['GObject.GType', 'Gio.GType', 'GLib.GType'], true) => 'string',
            $t->name === 'cairo.Context' => 'CairoContext',
            $t->name === 'GObject.ParamSpec' => 'GParamSpec',
            array_key_exists($t->name, self::FUNDAMENTALS) => phpClass($this->gir->types[$t->name]),
            $t->name === 'GObject.Object' => 'GObject',
            $t->isArray => self::isStrv($t) ? 'array' : null,
            in_array($t->name, ['GLib.List', 'GLib.SList', 'GLib.PtrArray'], true) => 'array',
            default => $this->phpTypeOfNode($t->name),
        };
        if ($base === null) {
            return null;
        }
        if ($base === 'mixed' || $base === 'void') {
            return $base;
        }
        return ($nullable ? '?' : '') . $base;
    }

    private function phpTypeOfNode(string $q): ?string
    {
        $n = $this->gir->types[$q] ?? null;
        if ($n === null) {
            return null;
        }
        if ($n->kind === 'alias') {
            return null;
        }
        if ($n->kind === 'bitfield') {
            return 'int';
        }
        if ($n->kind === 'enum') {
            return $this->known($q) ? phpClass($n) : 'int';
        }
        if (in_array($n->kind, ['class', 'interface', 'record'], true) && $this->known($q)) {
            return phpClass($n);
        }
        return null;  // outside the closure: the member is skipped and reported (never a placeholder)
    }

    /**
     * @return string|array{list<array>, list<array>} reason, or [ins, outs]
     */
    private function mapParams(Node $n, Func $f): string|array
    {
        $ins = [];
        $outs = [];
        $count = count($f->params);
        $closures = [];  // user_data index -> Callback variable
        foreach ($f->params as $i => $p) {
            if (isset($closures[$i])) {  // the user_data slot of a generated callback: hidden
                $ins[] = ['phpName' => $p->name, 'hidden' => true, 'default' => null, 'decl' => '', 'zpp' => '',
                    'pre' => [], 'post' => [], 'carg' => $closures[$i], 'phpType' => ''];
                continue;
            }
            if ($p->name === 'user_data' && in_array($p->type->name, ['gpointer', 'gconstpointer'], true)) {
                return 'gpointer parameter';
            }
            if ($p->direction === 'out') {
                // Outs are returned, never filled through a by-reference parameter (PLAN.md);
                // a caller-allocated out (gtk_widget_get_color(&rgba)) is a stack struct here.
                $o = $this->outMapping($p);
                if ($o === null) {
                    return ($p->callerAllocates ? 'caller-allocates ' : '')
                        . "out parameter `{$p->name}` of type {$p->type->name}";
                }
                $outs[] = $o;
                continue;
            }
            if ($p->direction !== 'in') {
                return "{$p->direction} parameter {$p->name}";
            }
            // trailing nullable parameters may be omitted
            $trailingNullable = $p->nullable;
            for ($j = $i + 1; $j < $count; $j++) {
                if (!$f->params[$j]->nullable && $f->params[$j]->direction === 'in') {
                    $trailingNullable = false;
                }
            }
            if (($this->gir->types[$p->type->name] ?? null)?->kind === 'callback' && $p->closure !== null) {
                $visibleSoFar = count(array_filter($ins, fn($x) => !($x['hidden'] ?? false)));
                $cb = $this->callbackParam($n, $f, $p, $visibleSoFar + 1, $trailingNullable && $f->kind !== 'method');
                if ($cb === null) {
                    return "callback parameter {$p->name}";
                }
                $closures[$p->closure] = $cb['cbVar'];
                $ins[] = $cb;
                continue;
            }
            $m = $this->inParam($p, $trailingNullable && $f->kind !== 'method', count($ins) + 1);
            if ($m === null) {
                return "parameter `{$p->name}` of type {$p->type->name}" . ($p->type->isArray ? ' (C array)' : '');
            }
            $ins[] = $m;
        }
        return [$ins, $outs];
    }

    /**
     * How an out parameter is declared, passed and turned into a PHP value.
     *
     * kind: long|bool|double (scalars, also the only kinds a vfunc thunk can fill), string,
     * object, enum, boxed (caller-allocates struct). `toZval($target)` writes the value into the
     * zval expression $target; `post` frees what the callee handed over (transfer full).
     *
     * @return array{name: string, ctype: string, init: string, kind: string, phpType: string,
     *               arg: string, add: string, toZval: callable(string): list<string>, post: list<string>}|null
     */
    private function outMapping(Param $p): ?array
    {
        $t = $p->type;
        $name = $p->name;
        $base = ['name' => $name, 'arg' => "&$name", 'post' => [], 'add' => ''];
        if ($p->callerAllocates) {
            $node = $this->gir->types[$t->name] ?? null;
            if ($node === null || $node->kind !== 'record' || $node->gtypeName === null || !$this->known($t->name)) {
                return null;
            }
            [$typeMacro] = macroParts($this->gir, $node);
            return $base + ['ctype' => $node->ctype, 'init' => '{}', 'kind' => 'boxed', 'phpType' => phpClass($node),
                'toZval' => fn(string $z) => ["wrap_boxed($typeMacro, &$name, $z);"]];
        }
        if ($t->name === 'gboolean') {
            return $base + ['ctype' => 'gboolean', 'init' => 'FALSE', 'kind' => 'bool', 'phpType' => 'bool',
                'add' => 'add_next_index_bool', 'toZval' => fn(string $z) => ["ZVAL_BOOL($z, $name != FALSE);"]];
        }
        if (in_array($t->name, ['gfloat', 'gdouble'], true)) {
            $ct = $t->name === 'gfloat' ? 'float' : 'double';
            return $base + ['ctype' => $ct, 'init' => '0', 'kind' => 'double', 'phpType' => 'float',
                'add' => 'add_next_index_double', 'toZval' => fn(string $z) => ["ZVAL_DOUBLE($z, $name);"]];
        }
        if (preg_match(INT_TYPES, $t->name)) {
            $ct = rtrim($t->ctype ?? 'int', '*');
            return $base + ['ctype' => $ct, 'init' => '0', 'kind' => 'long', 'phpType' => 'int',
                'add' => 'add_next_index_long',
                'toZval' => fn(string $z) => ["ZVAL_LONG($z, static_cast<zend_long>($name));"]];
        }
        if (in_array($t->name, ['utf8', 'filename'], true) && !$t->isArray) {
            return $base + ['ctype' => 'char *', 'init' => 'nullptr', 'kind' => 'string', 'phpType' => '?string',
                'toZval' => fn(string $z) => ["if ($name == nullptr) {", "  ZVAL_NULL($z);", '} else {',
                    "  ZVAL_STRING($z, $name);", '}'],
                'post' => $p->transfer === 'full' ? ["g_free($name);"] : []];
        }
        $node = $this->gir->types[$t->name] ?? null;
        if ($node === null || $t->isArray) {
            return null;
        }
        [$typeMacro] = macroParts($this->gir, $node);
        if ($node->kind === 'enum' && $node->gtypeName !== null && $this->known($t->name)) {
            return $base + ['ctype' => $node->ctype, 'init' => "static_cast<{$node->ctype}>(0)", 'kind' => 'enum',
                'phpType' => phpClass($node),
                'toZval' => fn(string $z) => ["enum_to_php($typeMacro, static_cast<gint>($name), $z);"]];
        }
        if (in_array($node->kind, ['class', 'interface'], true) && $this->phpTypeOfNode($t->name) !== null) {
            return $base + ['ctype' => "{$node->ctype} *", 'init' => 'nullptr', 'kind' => 'object',
                'phpType' => '?' . $this->phpTypeOfNode($t->name),
                'toZval' => fn(string $z) => ["wrap($name != nullptr ? G_OBJECT($name) : nullptr, $z);"],
                'post' => $p->transfer === 'full' ? ["if ($name != nullptr) g_object_unref($name);"] : []];
        }
        return null;
    }

    /** @return array<string, mixed>|null */
    private function inParam(Param $p, bool $trailingNullable, int $argNum = 0): ?array
    {
        $t = $p->type;
        $name = $p->name;
        $r = ['phpName' => $name, 'default' => $trailingNullable ? 'null' : null, 'pre' => [], 'post' => []];
        $nullable = $p->nullable;
        if (array_key_exists($t->name, self::FUNDAMENTALS)) {
            // A hand-written fundamental handle (GdkEvent) as an argument: borrowed for the call.
            $fnode = $this->gir->types[$t->name];
            $macro = $this->typeMacroOf($fnode);
            return array_merge($r, [
                'phpType' => ($nullable ? '?' : '') . phpClass($fnode),
                'decl' => "zval *$name" . ($nullable ? ' = nullptr' : '') . ';',
                'zpp' => 'Z_PARAM_OBJECT_OF_CLASS' . ($nullable ? '_OR_NULL' : '')
                    . "($name, fundamental_class_for_type($macro)->ce)",
                'pre' => $nullable
                    ? ["gpointer {$name}_f = nullptr;", "if ($name != nullptr) {",
                        "  {$name}_f = unwrap_fundamental($name, $macro);",
                        "  if ({$name}_f == nullptr) RETURN_THROWS();", '}']
                    : ["gpointer {$name}_f = unwrap_fundamental($name, $macro);",
                        "if ({$name}_f == nullptr) RETURN_THROWS();"],
                'carg' => "static_cast<{$fnode->ctype} *>({$name}_f)",
            ]);
        }
        if (in_array($t->name, ['utf8', 'filename'], true) && !$t->isArray) {
            $zpp = $t->name === 'filename' ? 'Z_PARAM_PATH_STR' : 'Z_PARAM_STR';
            // A GLib string ends at the first NUL and has to be UTF-8; a PHP string is neither.
            // Z_PARAM_PATH_STR already refuses the NUL, and a filename is bytes on Linux, so
            // only utf8 parameters are validated (php_gtk4.h, check_utf8).
            $utf8Check = [];
            if ($t->name === 'utf8') {
                $guard = $nullable ? "$name != nullptr && " : '';
                $utf8Check = ["if ({$guard}!phpgtk::check_utf8($name, $argNum)) RETURN_THROWS();"];
            }
            return array_merge($r, [
                'phpType' => ($nullable ? '?' : '') . 'string',
                'decl' => "zend_string *$name" . ($nullable ? ' = nullptr' : '') . ';',
                'zpp' => $zpp . ($nullable ? '_OR_NULL' : '') . "($name)",
                'pre' => $utf8Check,
                // transfer full (gtk_string_list_take): the callee frees the string with g_free(),
                // so it gets a GLib copy, never Zend's own buffer.
                'carg' => $p->transfer === 'full'
                    ? ($nullable
                        ? "$name != nullptr ? g_strdup(ZSTR_VAL($name)) : nullptr"
                        : "g_strdup(ZSTR_VAL($name))")
                    : ($nullable ? "$name != nullptr ? ZSTR_VAL($name) : nullptr" : "ZSTR_VAL($name)"),
            ]);
        }
        if ($t->name === 'gboolean') {
            return array_merge($r, [
                'phpType' => 'bool', 'decl' => "bool $name;", 'zpp' => "Z_PARAM_BOOL($name)", 'carg' => $name,
            ]);
        }
        if (in_array($t->name, ['gfloat', 'gdouble'], true)) {
            return array_merge($r, ['phpType' => 'float', 'decl' => "double $name;", 'zpp' => "Z_PARAM_DOUBLE($name)",
                'carg' => $t->name === 'gfloat' ? "static_cast<float>($name)" : $name]);
        }
        if (preg_match(INT_TYPES, $t->name)) {
            $ct = $t->ctype ?? 'int';
            // Without the check a negative value arrives at GTK as a huge unsigned one
            // ($store->remove(-1) reached g_list_store_remove as 4294967295) and a too-large
            // one is truncated - both silently. gint64/guint64 need no upper bound: PHP
            // cannot express one (php_gtk4.h, check_range).
            // gint64/gssize/goffset are exactly zend_long on both platforms - every value PHP
            // can express fits. glong is *not* in that list: it is 32 bits on Windows.
            $range = in_array($ct, ['gint64', 'gssize', 'goffset'], true)
                ? []
                : ["if (!phpgtk::check_range<$ct>($name, $argNum)) RETURN_THROWS();"];
            return array_merge($r, ['phpType' => 'int', 'decl' => "zend_long $name;", 'zpp' => "Z_PARAM_LONG($name)",
                'pre' => $range, 'carg' => "static_cast<$ct>($name)"]);
        }
        if ($t->name === 'GLib.Variant') {
            return array_merge($r, [
                'phpType' => 'mixed', 'default' => 'null',
                'decl' => "zval *$name = nullptr;", 'zpp' => "Z_PARAM_ZVAL($name)",
                'pre' => ["GVariant *{$name}_v = nullptr;",
                    "if ($name != nullptr && Z_TYPE_P($name) != IS_NULL) {",
                    "  {$name}_v = php_to_variant($name, nullptr);",
                    "  if ({$name}_v == nullptr) RETURN_THROWS();",
                    "  g_variant_ref_sink({$name}_v);",
                    '}'],
                'carg' => "{$name}_v",
                'post' => ["if ({$name}_v != nullptr) g_variant_unref({$name}_v);"],
            ]);
        }
        if ($t->name === 'GLib.VariantType') {
            return array_merge($r, [
                'phpType' => ($nullable ? '?' : '') . 'string',
                'decl' => "zend_string *$name" . ($nullable ? ' = nullptr' : '') . ';',
                'zpp' => 'Z_PARAM_STR' . ($nullable ? '_OR_NULL' : '') . "($name)",
                'pre' => ["GVariantType *{$name}_t = nullptr;",
                    "if ($name != nullptr) {",
                    "  if (!g_variant_type_string_is_valid(ZSTR_VAL($name))) {",
                    "    zend_argument_value_error($argNum, \"must be a valid GVariant type string, "
                    . "\\\"%s\\\" given\", ZSTR_VAL($name));",
                    '    RETURN_THROWS();',
                    '  }',
                    "  {$name}_t = g_variant_type_new(ZSTR_VAL($name));",
                    '}'],
                'carg' => "{$name}_t",
                'post' => ["if ({$name}_t != nullptr) g_variant_type_free({$name}_t);"],
            ]);
        }
        if ($t->name === 'GLib.Bytes') {
            return array_merge($r, [
                'phpType' => 'string', 'decl' => "zend_string *$name;", 'zpp' => "Z_PARAM_STR($name)",
                'pre' => ["GBytes *{$name}_b = g_bytes_new(ZSTR_VAL($name), ZSTR_LEN($name));"],
                'carg' => "{$name}_b", 'post' => ["g_bytes_unref({$name}_b);"],
            ]);
        }
        if (self::isStrv($t)) {
            // A nullable string vector (GtkStringList::new(NULL) = an empty list) is ?array.
            return array_merge($r, [
                'phpType' => ($nullable ? '?' : '') . 'array',
                'decl' => "zval *$name" . ($nullable ? ' = nullptr' : '') . ';',
                'zpp' => 'Z_PARAM_ARRAY' . ($nullable ? '_OR_NULL' : '') . "($name)",
                'pre' => $nullable
                    ? ["char **{$name}_v = nullptr;", "if ($name != nullptr) {",
                        "  {$name}_v = strv_from_php($name);", "  if ({$name}_v == nullptr) RETURN_THROWS();", '}']
                    : ["char **{$name}_v = strv_from_php($name);", "if ({$name}_v == nullptr) RETURN_THROWS();"],
                'carg' => "const_cast<const char **>({$name}_v)",
                'post' => [($nullable ? "if ({$name}_v != nullptr) " : '') . "g_strfreev({$name}_v);"],
            ]);
        }
        $node = $this->gir->types[$t->name] ?? null;
        if ($node === null) {
            return null;
        }
        [$typeMacro, $castMacro] = macroParts($this->gir, $node);
        if ($node->kind === 'enum' && $node->gtypeName !== null && $this->known($t->name)) {
            return array_merge($r, [
                'phpType' => phpClass($node), 'decl' => "zval *$name;",
                'zpp' => "Z_PARAM_OBJECT_OF_CLASS($name, enum_class_for_type($typeMacro))",
                'pre' => ["gint {$name}_v = 0;", "if (!enum_from_php($name, $typeMacro, &{$name}_v)) RETURN_THROWS();"],
                'carg' => "static_cast<{$node->ctype}>({$name}_v)",
            ]);
        }
        if ($node->kind === 'bitfield' || $node->kind === 'enum') {
            // Flags are plain ints (no PHP enum to check them), so the value is checked against
            // the type's own mask: GLib otherwise takes the assignment, prints a CRITICAL and
            // carries on with the default (php_gtk4.h, check_flags).
            $mask = $node->gtypeName !== null
                ? ["if (!phpgtk::check_flags($typeMacro, $name, $argNum)) RETURN_THROWS();"]
                : [];
            return array_merge($r, ['phpType' => 'int', 'decl' => "zend_long $name;", 'zpp' => "Z_PARAM_LONG($name)",
                'pre' => $mask, 'carg' => "static_cast<{$node->ctype}>($name)"]);
        }
        if (in_array($node->kind, ['class', 'interface'], true)) {
            $phpT = $this->phpTypeOfNode($t->name);
            if ($phpT === null) {
                return null;
            }
            // The PHP-visible class may be an ancestor (nearest known), the C check is exact.
            $q = $this->qOfPhp($phpT);
            [$knownMacro] = macroParts($this->gir, $this->gir->types[$q]);
            $classExpr = $q === 'GObject.Object' ? 'class_for_gtype(G_TYPE_OBJECT)' : "class_for_gtype($knownMacro)";
            $pre = $nullable
                ? ["GObject *{$name}_o = nullptr;", "if ($name != nullptr) {",
                    "  {$name}_o = unwrap($name, $typeMacro);",
                    "  if ({$name}_o == nullptr) RETURN_THROWS();", '}']
                : ["GObject *{$name}_o = unwrap($name, $typeMacro);", "if ({$name}_o == nullptr) RETURN_THROWS();"];
            if ($p->transfer === 'full') {
                // The callee takes this reference (GIR transfer full); the handle keeps its own.
                $pre[] = "if ({$name}_o != nullptr) g_object_ref({$name}_o);  // transfer full";
            }
            return array_merge($r, [
                'objectVar' => $p->transfer === 'full' ? "{$name}_o" : null,
                'phpType' => ($nullable ? '?' : '') . $phpT,
                'decl' => "zval *$name" . ($nullable ? ' = nullptr' : '') . ';',
                'zpp' => 'Z_PARAM_OBJECT_OF_CLASS' . ($nullable ? '_OR_NULL' : '') . "($name, $classExpr)",
                'pre' => $pre,
                'carg' => $nullable
                    ? "{$name}_o != nullptr ? $castMacro({$name}_o) : nullptr"
                    : "$castMacro({$name}_o)",
            ]);
        }
        if ($t->name === 'GLib.Error') {
            // Gtk4\GError is an exception class (core/gerror), not a boxed handle: a GError is
            // built from it for the call and freed afterwards unless the callee takes it.
            $pre = $nullable
                ? ["GError *{$name}_e = nullptr;", "if ($name != nullptr) {$name}_e = gerror_from_php($name);"]
                : ["GError *{$name}_e = gerror_from_php($name);"];
            return array_merge($r, [
                'phpType' => ($nullable ? '?' : '') . 'GError',
                'decl' => "zval *$name" . ($nullable ? ' = nullptr' : '') . ';',
                'zpp' => 'Z_PARAM_OBJECT_OF_CLASS' . ($nullable ? '_OR_NULL' : '') . "($name, ce_GError)",
                'pre' => $pre,
                'carg' => "{$name}_e",
                'post' => $p->transfer === 'full' ? [] : ["if ({$name}_e != nullptr) g_error_free({$name}_e);"],
            ]);
        }
        if ($node->kind === 'record' && $node->gtypeName !== null && $this->known($t->name)) {
            return array_merge($r, [
                'phpType' => ($nullable ? '?' : '') . phpClass($node),
                'decl' => "zval *$name" . ($nullable ? ' = nullptr' : '') . ';',
                'zpp' => 'Z_PARAM_OBJECT_OF_CLASS' . ($nullable ? '_OR_NULL' : '')
                    . "($name, boxed_class_for_type($typeMacro)->ce)",
                'pre' => $nullable
                    ? ["gpointer {$name}_b = nullptr;", "if ($name != nullptr) {",
                        "  {$name}_b = unwrap_boxed($name, $typeMacro);",
                        "  if ({$name}_b == nullptr) RETURN_THROWS();", '}']
                    : ["gpointer {$name}_b = unwrap_boxed($name, $typeMacro);",
                        "if ({$name}_b == nullptr) RETURN_THROWS();"],
                'carg' => "static_cast<{$node->ctype} *>({$name}_b)",
            ]);
        }
        return null;
    }

    /**
     * @param list<array> $outs
     * @return string|array{phpType: string, docType?: string, lines: callable, tail?: list<string>}
     */
    private function retMapping(Node $n, Func $f, array $outs): string|array
    {
        $t = $f->ret;
        $full = $f->retTransfer === 'full';
        $throwCheck = fn(string $cond) => $f->throws
            ? ["if ($cond) {", '  throw_gerror(error);', '  RETURN_THROWS();', '}']
            : [];
        $outPosts = array_merge([], ...array_map(fn($o) => $o['post'], $outs));
        $outLines = function () use ($outs, $outPosts) {
            $l = ['array_init_size(return_value, ' . count($outs) . ');'];
            foreach ($outs as $o) {
                $l[] = '{';
                $l[] = '  zval item;';
                array_push($l, ...array_map(fn($x) => "  $x", $o['toZval']('&item')));
                $l[] = '  add_next_index_zval(return_value, &item);';
                $l[] = '}';
            }
            return [...$l, ...$outPosts];
        };
        $singleOut = fn() => [...$outs[0]['toZval']('return_value'), ...$outPosts];
        $outDoc = 'array{' . implode(', ', array_map(fn($o) => self::outScalar($o), $outs)) . '}';

        if ($outs === [] && array_key_exists($t->name, self::FUNDAMENTALS)) {  // before the record arms
            // A hand-written fundamental handle (GdkEvent): wrap through its registry entry, which
            // takes its own reference - a transfer-full result gives up the one it came with.
            $fnode = $this->gir->types[$t->name];
            $macro = $this->typeMacroOf($fnode);
            $unref = self::FUNDAMENTALS[$t->name];
            return ['phpType' => ($f->retNullable ? '?' : '') . phpClass($fnode), 'lines' => fn(string $call) => [
                "gpointer result = $call;", "wrap_fundamental($macro, result, return_value);",
                ...($full && $unref !== null
                    ? ["if (result != nullptr) $unref(static_cast<{$fnode->ctype} *>(result));"] : [])]];
        }

        // constructors of a boxed record: the handle adopts the allocation (the type's own
        // allocator: GIR constructors return transfer full); factories copy into a new handle
        if ($f->kind === 'constructor' && $n->kind === 'record') {
            [$recordMacro] = macroParts($this->gir, $n);
            if ($f->name === 'new') {
                return ['phpType' => '', 'lines' => function (
                    string $call,
                    bool $isRoot,
                    ?array $subtype,
                ) use (
                    $f,
                    $throwCheck,
                    $recordMacro
                ) {
                    $l = ["gpointer obj = $call;"];
                    if ($f->throws) {
                        array_push($l, ...$throwCheck('obj == nullptr'));
                    } else {
                        $l[] = 'if (obj == nullptr) {';
                        $l[] = '  zend_throw_error(nullptr, "%s(): GTK refused to create the value '
                            . '(see the CRITICAL above)",';
                        $l[] = '                   ZSTR_VAL(EX(func)->common.function_name));';
                        $l[] = '  RETURN_THROWS();';
                        $l[] = '}';
                    }
                    $l[] = "boxed_adopt(boxed_from_zval(ZEND_THIS), $recordMacro, obj);";
                    return $l;
                }];
            }
            return ['phpType' => phpClass($n), 'lines' => function (string $call) use (
                $f,
                $throwCheck,
                $full,
                $recordMacro
            ) {
                $l = ["gpointer result = $call;"];
                array_push($l, ...$throwCheck('result == nullptr'));
                $l[] = "wrap_boxed($recordMacro, result, return_value);";
                if ($full) {
                    $l[] = "if (result != nullptr) g_boxed_free($recordMacro, result);";
                }
                return $l;
            }];
        }
        // constructors
        if ($f->kind === 'constructor') {
            if ($f->name === 'new') {
                return ['phpType' => '', 'lines' => function (
                    string $call,
                    bool $isRoot,
                    ?array $subtype,
                ) use (
                    $f,
                    $throwCheck
                ) {
                    if ($subtype === null) {
                        $l = ["GObject *obj = G_OBJECT($call);"];
                    } else {
                        // A PHP subclass gets an instance of its own GType (core/subtype.h); the
                        // constructor arguments become construct properties.
                        $l = ["GObject *obj = {$subtype['call']};"];
                        if ($subtype['post'] !== []) {
                            $l[] = 'if (obj != nullptr) {';
                            array_push($l, ...array_map(fn($x) => "  $x", $subtype['post']));
                            $l[] = '}';
                        }
                        $l[] = 'if (obj == nullptr) {';
                        $l[] = '  if (EG(exception) != nullptr) RETURN_THROWS();';
                        $l[] = "  obj = G_OBJECT($call);";
                        $l[] = '}';
                    }
                    if ($f->throws) {
                        array_push($l, ...$throwCheck('obj == nullptr'));
                    } else {
                        // g_return_val_if_fail() inside GTK (invalid arguments) yields NULL: never
                        // leave a dead handle behind, the Gtk-CRITICAL above says what was wrong.
                        $l[] = 'if (obj == nullptr) {';
                        $l[] = '  zend_throw_error(nullptr, "%s(): GTK refused to create the object (see the "';
                        $l[] = '                   "CRITICAL above)",';
                        $l[] = '                   ZSTR_VAL(EX(func)->common.function_name));';
                        $l[] = '  RETURN_THROWS();';
                        $l[] = '}';
                    }
                    $l[] = $isRoot
                        ? '// GtkRoot: GTK\'s toplevel list owns the initial reference (see docs/PLAN.md "Ownership").'
                        : '';
                    $l[] = ($isRoot ? 'attach' : 'attach_new') . '(object_from_zval(ZEND_THIS), obj);';
                    return array_values(array_filter($l, fn($s) => $s !== ''));
                }];
            }
            // other constructors are static factories returning the class
            $php = phpClass($n);
            return ['phpType' => $php, 'lines' => function (string $call) use ($f, $throwCheck, $full) {
                $l = ["GObject *obj = G_OBJECT($call);"];
                array_push($l, ...$throwCheck('obj == nullptr'));
                $l[] = 'wrap(obj, return_value);';
                if ($full) {
                    $l[] = 'if (obj != nullptr) g_object_unref(obj);  // the handle took its own reference';
                }
                return $l;
            }];
        }

        if ($t->name === 'none') {
            if (count($outs) === 1) {  // one out -> that value (PLAN.md "Out parameters")
                return ['phpType' => $outs[0]['phpType'], 'lines' => fn(string $call) => [
                    "$call;", ...$throwCheck('error != nullptr'), ...$singleOut()]];
            }
            if ($outs !== []) {
                return ['phpType' => 'array', 'docType' => $outDoc, 'lines' => fn(string $call) => [
                    "$call;", ...$throwCheck('error != nullptr'), ...$outLines()]];
            }
            return ['phpType' => 'void', 'lines' => fn(string $call) => ["$call;", ...$throwCheck('error != nullptr')]];
        }
        if ($t->name === 'gboolean') {
            if (count($outs) === 1) {  // success -> the out, failure -> null
                $phpT = '?' . ltrim($outs[0]['phpType'], '?');
                return ['phpType' => $phpT, 'lines' => fn(string $call) => [
                    "if (!$call) RETURN_NULL();", ...$singleOut()]];
            }
            if ($outs !== []) {
                return ['phpType' => '?array', 'docType' => "$outDoc|null", 'lines' => fn(string $call) => [
                    "if (!$call) RETURN_NULL();", ...$outLines()]];
            }
            return ['phpType' => 'bool', 'lines' => fn(string $call) => $f->throws
                ? ["const gboolean ok = $call;", ...$throwCheck('!ok'), 'RETURN_BOOL(ok);']
                : ["RETURN_BOOL($call);"]];
        }
        if ($outs !== []) {
            return "return {$t->name} plus out parameters";
        }
        if (in_array($t->name, ['utf8', 'filename'], true) && !$t->isArray) {
            $nullable = $f->retNullable;
            if ($full) {
                return ['phpType' => ($nullable ? '?' : '') . 'string', 'lines' => fn(string $call) => [
                    "char *result = $call;", ...$throwCheck('result == nullptr'),
                    ...($nullable ? ['if (result == nullptr) RETURN_NULL();'] : []),
                    'RETVAL_STRING(result);', 'g_free(result);']];
            }
            return ['phpType' => ($nullable ? '?' : '') . 'string', 'lines' => fn(string $call) => $nullable
                ? ["PHPGTK_RETURN_STRING_OR_NULL($call);"]
                // GIR says non-nullable, GTK returns NULL anyway (gtk_alert_dialog_get_detail()
                // before a detail is set): an empty string, never a crash; the stub type stays.
                : ["const char *result = $call;", 'if (result == nullptr) RETURN_EMPTY_STRING();',
                    'RETURN_STRING(result);']];
        }
        // Scalar returns of a `throws` function carry no failure marker of their own (a dismissed
        // GtkAlertDialog::choose_finish() is -1 *and* a GError): the GError decides, and dropping
        // it would leak it - so the value is taken first and the error checked before returning.
        if (in_array($t->name, ['gfloat', 'gdouble'], true)) {
            return ['phpType' => 'float', 'lines' => fn(string $call) => $f->throws
                ? ["const double value = $call;", ...$throwCheck('error != nullptr'), 'RETURN_DOUBLE(value);']
                : ["RETURN_DOUBLE($call);"]];
        }
        if (preg_match(INT_TYPES, $t->name)) {
            return ['phpType' => 'int', 'lines' => fn(string $call) => $f->throws
                ? ["const auto value = static_cast<zend_long>($call);", ...$throwCheck('error != nullptr'),
                    'RETURN_LONG(value);']
                : ["RETURN_LONG(static_cast<zend_long>($call));"]];
        }
        if ($t->name === 'GLib.Variant') {
            return ['phpType' => 'mixed', 'lines' => fn(string $call) => [
                "GVariant *result = $call;", 'if (result == nullptr) RETURN_NULL();',
                'variant_to_php(result, return_value);', ...($full ? ['g_variant_unref(result);'] : [])]];
        }
        if ($t->name === 'GLib.VariantType') {
            $nullable = $f->retNullable;
            return ['phpType' => ($nullable ? '?' : '') . 'string', 'lines' => fn(string $call) => [
                "const GVariantType *vt = $call;", ...($nullable ? ['if (vt == nullptr) RETURN_NULL();'] : []),
                'RETVAL_STRINGL(g_variant_type_peek_string(vt), g_variant_type_get_string_length(vt));',
                ...($full ? ['g_variant_type_free(const_cast<GVariantType *>(vt));'] : [])]];
        }
        if (in_array($t->name, ['GObject.GType', 'Gio.GType', 'GLib.GType'], true) || $t->name === 'GType') {
            return ['phpType' => 'string', 'lines' => fn(string $call) => ["RETURN_STRING(g_type_name($call));"]];
        }
        if ($t->name === 'GLib.Bytes') {
            return ['phpType' => ($f->retNullable ? '?' : '') . 'string', 'lines' => fn(string $call) => [
                "GBytes *bytes = $call;", ...$throwCheck('bytes == nullptr'),
                ...($f->retNullable ? ['if (bytes == nullptr) RETURN_NULL();'] : []),
                'gsize size = 0;', 'const auto *data = static_cast<const char *>(g_bytes_get_data(bytes, &size));',
                'RETVAL_STRINGL(data, size);', ...($full ? ['g_bytes_unref(bytes);'] : [])]];
        }
        if (self::isStrv($t)) {
            // GTK hands borrowed string vectors out as `const char * const *`; strv_to_php() takes
            // the mutable form (it frees the transfer-full ones), so cast the borrowed ones.
            return ['phpType' => 'array', 'docType' => 'list<string>', 'lines' => fn(string $call) => [
                str_starts_with($t->ctype ?? '', 'const')
                    ? "strv_to_php($call, return_value);"
                    : "strv_to_php($call, Transfer::" . ($full ? 'Full' : 'None') . ', return_value);']];
        }
        if (in_array($t->name, ['GLib.List', 'GLib.SList', 'GLib.PtrArray'], true)) {
            $el = $t->element;
            $elNode = $el !== null ? ($this->gir->types[$el->name] ?? null) : null;
            $elPhp = $el !== null ? $this->phpType($el, false) : null;
            if ($el === null || $elPhp === null) {
                return 'list of ' . ($el?->name ?? '?');
            }
            $elType = $el->name === 'utf8'
                ? 'G_TYPE_STRING'
                : ($elNode !== null ? macroParts($this->gir, $elNode)[0] : null);
            if ($elType === null) {
                return "list of {$el->name}";
            }
            $fn = match ($t->name) {
                'GLib.List' => 'glist_to_php', 'GLib.SList' => 'gslist_to_php', default => 'gptrarray_to_php'
            };
            $transfer = match ($f->retTransfer) {
                'full' => 'Full', 'container' => 'Container', default => 'None'
            };
            return ['phpType' => 'array', 'docType' => "list<$elPhp>", 'lines' => fn(string $call) => [
                "$fn($call, $elType, Transfer::$transfer, return_value);"]];
        }
        if ($t->name === 'cairo.Context') {
            return ['phpType' => ($f->retNullable ? '?' : '') . 'CairoContext', 'lines' => fn(string $call) => [
                "cairo_t *cr = $call;", 'wrap_cairo(cr, return_value);',
                ...($full ? ['if (cr != nullptr) cairo_destroy(cr);'] : [])]];
        }
        if ($t->name === 'GObject.ParamSpec') {
            return ['phpType' => ($f->retNullable ? '?' : '') . 'GParamSpec', 'lines' => fn(string $call) => [
                "wrap_param_spec($call, return_value);"]];
        }
        $node = $this->gir->types[$t->name] ?? null;
        if ($node === null) {
            return "return type {$t->name}";
        }
        [$typeMacro] = macroParts($this->gir, $node);
        if ($node->kind === 'enum' && $node->gtypeName !== null && $this->known($t->name)) {
            return ['phpType' => phpClass($node), 'lines' => fn(string $call) => [
                "enum_to_php($typeMacro, $call, return_value);"]];
        }
        if ($node->kind === 'enum' || $node->kind === 'bitfield') {
            return ['phpType' => 'int', 'lines' => fn(string $call) => ["RETURN_LONG(static_cast<zend_long>($call));"]];
        }
        if (in_array($node->kind, ['class', 'interface'], true)) {
            $phpT = $this->phpTypeOfNode($t->name);
            if ($phpT === null) {
                return "return type {$t->name} (not in the closure)";
            }
            // g_task_get_source_object() and friends return the object as gpointer
            return ['phpType' => ($f->retNullable ? '?' : '') . $phpT, 'lines' => fn(string $call) => [
                "{$node->ctype} *result = " . (str_starts_with($t->ctype ?? '', 'gpointer')
                    ? "static_cast<{$node->ctype} *>($call)" : $call) . ';', ...$throwCheck('result == nullptr'),
                'wrap(result != nullptr ? G_OBJECT(result) : nullptr, return_value);',
                ...($full ? ['if (result != nullptr) g_object_unref(result);  // the handle took its own ref'] : [])]];
        }
        if ($node->kind === 'record' && $node->gtypeName !== null && $this->known($t->name)) {
            return ['phpType' => ($f->retNullable ? '?' : '') . phpClass($node), 'lines' => fn(string $call) => [
                "{$node->ctype} *result = $call;", "wrap_boxed($typeMacro, result, return_value);",
                ...($full ? ["if (result != nullptr) g_boxed_free($typeMacro, result);"] : [])]];
        }
        return "return type {$t->name}";
    }

    // ------------------------------------------------------------ MINIT + report

    private function prototypesText(array $protos): string
    {
        return "// GENERATED by gen/gir.php - prototypes of the interface method implementations.\n"
            . "// gen_stub declares none for an interface's (abstract) methods, but the ZEND_MALIAS\n"
            . "// entries of the implementing classes point at these. Include before the arginfo.\n"
            . "#pragma once\n#include \"php_gtk4.h\"\n\n" . implode("\n", array_unique($protos)) . "\n";
    }

    private function minitText(array $minit): string
    {
        // enums first, then interfaces, then classes parent-first.
        $enums = array_filter($minit, fn($l) => str_contains($l, 'register_enum')
            || str_contains($l, 'register_flags'));
        $rest = array_values(array_diff($minit, $enums));
        // order class registrations so that a parent's ce_ exists before use
        $ordered = [];
        // hand-written classes exist before gen_minit.inc is included (src/gtk4.cpp MINIT)
        $defined = ['ce_GObject' => true];
        foreach ($this->handwritten as $q) {
            if (isset($this->gir->types[$q])) {
                $defined['ce_' . phpClass($this->gir->types[$q])] = true;
            }
        }
        $pending = [];
        for ($i = 0; $i < count($rest); $i += 2) {
            $pending[] = [$rest[$i], $rest[$i + 1]];
        }
        $guard = 0;
        while ($pending !== [] && $guard++ < 1000) {
            foreach ($pending as $k => [$decl, $reg]) {
                preg_match('/zend_class_entry \*(ce_\w+) = \w+\(([^)]*)\)/', $decl, $m);
                $deps = array_filter(array_map('trim', explode(',', $m[2])));
                if (array_diff($deps, array_keys($defined)) === []) {
                    $ordered[] = $decl;
                    $ordered[] = $reg;
                    $defined[$m[1]] = true;
                    unset($pending[$k]);
                }
            }
        }
        if ($pending !== []) {
            // a silently unregistered class would only show up as "class not found" at runtime
            throw new \RuntimeException('MINIT ordering: unresolved parent/interface for '
                . implode(', ', array_map(fn($p) => $p[0], $pending))
                . ' (allow-list the parent or add it to handwritten.txt)');
        }
        return "// GENERATED by gen/gir.php - the MINIT registration block, included by src/gtk4.cpp\n"
            . "// after the hand-written classes (ce_GObject and friends exist by then).\n"
            . "// --- enums and flags\n" . implode("\n", $enums) . "\n"
            . "// --- interfaces and classes, parents first\n" . implode("\n", $ordered) . "\n"
            . "// --- vfunc thunks for PHP subclasses (core/subtype.h)\n" . implode("\n", $this->vfuncMinit) . "\n";
    }

    private function reportText(): string
    {
        $lines = ['# gen/gir.php report', '',
            'Skipped members, by class. Fix with gen/overrides (a hand-written body), gen/skip.txt',
            '(a reason) or by widening gen/allowlist.txt (types outside the closure).', ''];
        $byType = [];
        foreach ($this->report as $r) {
            $byType[$r['type']][] = $r;
        }
        ksort($byType);
        foreach ($byType as $type => $rows) {
            $lines[] = "## $type";
            $lines[] = '';
            foreach ($rows as $r) {
                $lines[] = "- `{$r['member']}` — {$r['reason']}";
            }
            $lines[] = '';
        }
        $lines[] = '## Overrides in effect';
        $lines[] = '';
        foreach ($this->overrides as $type => $methods) {
            $lines[] = "- `$type`: " . implode(', ', array_keys($methods));
        }
        $lines[] = '';
        $lines[] = '## Emitted files';
        $lines[] = '';
        foreach (array_keys($this->files) as $f) {
            if ($f !== 'report.md') {
                $lines[] = "- `$f`";
            }
        }
        return implode("\n", $lines);
    }
}

// ---------------------------------------------------------------- main

function readList(string $file): array
{
    if (!is_file($file)) {
        return [];
    }
    $out = [];
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim(preg_replace('/#.*/', '', $line) ?? '');
        if ($line !== '') {
            $out[] = $line;
        }
    }
    return $out;
}

$out = 'gen/out';
$install = false;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--out=')) {
        $out = substr($arg, 6);
    }
    if ($arg === '--install') {
        $install = true;
        $out = 'src';
    }
}
$root = dirname(__DIR__);
$gir = new Gir();
foreach (GIR_FILES as $f) {
    foreach (GIR_DIRS as $dir) {
        if (is_file("$dir/$f.gir")) {
            $gir->load("$dir/$f.gir");
            continue 2;
        }
    }
    throw new \RuntimeException("$f.gir not found in " . implode(', ', GIR_DIRS) . ' (install gir1.2-gtk-4.0)');
}
$skip = [];
foreach (readList("$root/gen/skip.txt") as $line) {
    [$key, $reason] = array_pad(preg_split('/\s+/', $line, 2) ?: [], 2, '');
    $skip[$key] = $reason;
}
$outDir = str_starts_with($out, '/') ? $out : "$root/$out";
$g = $install
    ? new Generator(
        $gir,
        $outDir,
        "$root/examples",
        "$root/examples/generated-sections.inc",
        "$root/examples/bootstrap.php",
        "$root/tests/Generated",
    )
    : new Generator($gir, $outDir);
$g->loadOverrides("$root/gen/overrides");
$smoke = [];
foreach (readList("$root/gen/smoke-skip.txt") as $line) {
    [$key, $reason] = array_pad(preg_split('/\s+/', $line, 2) ?: [], 2, '');
    $smoke[$key] = $reason;
}
$g->setSmokeSkip($smoke);
$ctorProps = [];
foreach (readList("$root/gen/ctor-props.txt") as $line) {
    [$key, $prop] = array_pad(preg_split('/\s+/', $line, 2) ?: [], 2, '');
    $ctorProps[$key] = $prop;
}
$g->setCtorProps($ctorProps);
$g->configure(readList("$root/gen/allowlist.txt"), readList("$root/gen/handwritten.txt"), $skip);
$g->run();
if ($install) {
    passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/map-status.php'), $rc);
    if ($rc !== 0) {
        exit($rc);
    }
}
echo "generated into $out\n";
