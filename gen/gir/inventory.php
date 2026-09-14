<?php

/**
 * docs/INVENTORY.md: every class, interface and enum the extension registers, by GIR namespace,
 * each linked to its page in the upstream documentation.
 *
 * Part of gen/gir.php (written on --install, next to gen/report.md). The generated classes come
 * from what run() emitted; the hand-written ones (src/gtk4.stub.php) are matched to their GIR
 * type by name, and the handful php-gtk4 adds of its own are in OWN below. A class that matches
 * nothing is an error, not a row without a link: the generator stops, so the table is complete
 * or the `gen` stage fails.
 */

declare(strict_types=1);

namespace PhpGtk4\Gen;

/** GIR namespace -> where its documentation lives (gi-docgen sites share one page scheme). */
const INVENTORY_DOCS = [
    'GLib' => ['GLib', 'https://docs.gtk.org/glib/'],
    'GObject' => ['GObject', 'https://docs.gtk.org/gobject/'],
    'Gio' => ['GIO', 'https://docs.gtk.org/gio/'],
    'Gdk' => ['GDK 4', 'https://docs.gtk.org/gdk4/'],
    'Gsk' => ['GSK 4', 'https://docs.gtk.org/gsk4/'],
    'Gtk' => ['GTK 4', 'https://docs.gtk.org/gtk4/'],
    'Pango' => ['Pango', 'https://docs.gtk.org/Pango/'],
    'PangoCairo' => ['PangoCairo', 'https://docs.gtk.org/PangoCairo/'],
    'GdkPixbuf' => ['GdkPixbuf', 'https://docs.gtk.org/gdk-pixbuf/'],
    'cairo' => ['cairo', 'https://www.cairographics.org/manual/'],
    'Graphene' => ['Graphene', 'https://ebassi.github.io/graphene/docs/'],
    'Soup' => ['libsoup 3', 'https://libsoup.gnome.org/libsoup-3.0/'],
    'JavaScriptCore' => ['JavaScriptCore GLib', 'https://webkitgtk.org/reference/jsc-glib/stable/'],
    'WebKit' => ['WebKitGTK', 'https://webkitgtk.org/reference/webkitgtk/stable/'],
];

/** GIR kind -> the page prefix gi-docgen sites use, and the word the table shows. */
const INVENTORY_KINDS = [
    'class' => ['class', 'class'],
    'interface' => ['iface', 'interface'],
    'enum' => ['enum', 'enum'],
    'bitfield' => ['flags', 'flags'],
    'record' => ['struct', 'struct'],
];

/** cairo's manual is gtk-doc: one page per section, named by hand. */
const INVENTORY_CAIRO_PAGES = ['Context' => 'cairo-cairo-t.html', 'Surface' => 'cairo-cairo-surface-t.html'];

/** Graphene's manual is gtk-doc too: the vectors share a page, the rest are named after the type. */
const INVENTORY_GRAPHENE_PAGES = ['Rect' => 'Rectangle', 'Vec2' => 'Vectors', 'Vec3' => 'Vectors', 'Vec4' => 'Vectors'];

/**
 * What php-gtk4 adds of its own, or reshapes: PHP class -> [kind, link label, URL, note].
 * Documentation URLs point at the closest upstream page; a relative link is into this repository.
 */
const INVENTORY_OWN = [
    'Gtk' => [
        'functions',
        'GTK 4 functions',
        'https://docs.gtk.org/gtk4/index.html#functions',
        'the `gtk_*` functions (`Gtk::init()`, `Gtk::set_exception_handler()`, the `testing_*` hooks)',
    ],
    'GLib' => [
        'functions',
        'GLib functions',
        'https://docs.gtk.org/glib/index.html#functions',
        'the `g_*` functions (`GLib::timeout_add()`, `GLib::io_add_watch()`, `GLib::set_prgname()`)',
    ],
    'ExceptionMode' => [
        'enum',
        'docs/DESIGN.md',
        'DESIGN.md',
        'what an uncaught Throwable inside a GTK callback does: `Log` or `Rethrow`',
    ],
    'PhpValue' => [
        'class',
        'docs/DESIGN.md',
        'DESIGN.md',
        'a GObject carrying a PHP value, so PHP data can sit in a `GListStore`',
    ],
    'GtkStyleProviderPriority' => [
        'constants',
        'GTK 4 constants',
        'https://docs.gtk.org/gtk4/index.html#constants',
        'the `GTK_STYLE_PROVIDER_PRIORITY_*` constants as a class',
    ],
];

/**
 * One row of the inventory.
 *
 * @return array<string, string> class, ns, kind, origin, label, url, note
 */
function inventoryRow(
    string $class,
    string $ns,
    string $kind,
    string $origin,
    string $label,
    string $url,
    string $note = '',
): array {
    return [
        'class' => $class,
        'ns' => $ns,
        'kind' => $kind,
        'origin' => $origin,
        'label' => $label,
        'url' => $url,
        'note' => $note,
    ];
}

/**
 * The documentation link of a GIR type: [label, URL].
 *
 * @return array{string, string}
 */
function inventoryLink(Node $n): array
{
    [, $base] = INVENTORY_DOCS[$n->ns]
        ?? throw new \RuntimeException("inventory: no documentation site for namespace {$n->ns}");
    $label = "{$n->ns}.{$n->name}";
    if ($n->kind === 'constants') {
        return ["{$n->ns} constants", $base . 'index.html#constants'];
    }
    if ($n->ns === 'cairo') {
        return [$label, $base . (INVENTORY_CAIRO_PAGES[$n->name] ?? 'index.html')];
    }
    if ($n->ns === 'Graphene') {
        return [$label, $base . 'graphene-' . (INVENTORY_GRAPHENE_PAGES[$n->name] ?? $n->name) . '.html'];
    }
    [$page] = INVENTORY_KINDS[$n->kind]
        ?? throw new \RuntimeException("inventory: {$n->qname()} is a {$n->kind}, which has no page");
    if ($n->errorDomain !== null) {
        $page = 'error';   // an enum of GError codes has its own page kind
    }

    return [$label, "$base$page.{$n->name}.html"];
}

/**
 * The word the table shows for a GIR type's kind.
 */
function inventoryKind(Node $n): string
{
    if ($n->kind === 'constants') {
        return 'constants';
    }
    $kind = INVENTORY_KINDS[$n->kind][1] ?? $n->kind;
    if ($n->errorDomain !== null) {
        return 'enum (error domain)';
    }

    return $n->fundamental ? "$kind (fundamental)" : $kind;
}

/**
 * The rows for what the generator emitted: the type itself and, for an interface, the
 * `<Interface>Object` fallback wrap() hands out for a GTK-private implementation.
 *
 * @param list<Node> $emitted
 * @return list<array<string, string>> rows, see inventoryRow()
 */
function inventoryGeneratedRows(array $emitted): array
{
    $rows = [];
    foreach ($emitted as $n) {
        [$label, $url] = inventoryLink($n);
        $php = phpClass($n);
        $rows[] = inventoryRow($php, $n->ns, inventoryKind($n), 'generated', $label, $url);
        if ($n->kind === 'interface') {
            $base = INTERFACE_FALLBACK_BASE[$n->qname()] ?? null;
            $note = 'the interface with a body, what `wrap()` hands out for a GTK-private implementation'
                . ($base === null ? '' : " (extends `$base`)");
            $rows[] = inventoryRow($php . 'Object', $n->ns, 'class', 'generated', $label, $url, $note);
        }
    }

    return $rows;
}

/**
 * The rows for the classes src/gtk4.stub.php declares by hand: each matched to its GIR type by
 * PHP name (the GType name, or namespace + name for a record without one), else to OWN.
 *
 * @param array<string, Node> $types every GIR type, qualified name -> node
 * @param list<string> $emittedClasses the PHP names the generator emitted (never hand-written too)
 * @return list<array<string, string>> rows, see inventoryRow()
 */
function inventoryHandwrittenRows(array $types, array $emittedClasses, string $stubFile): array
{
    $stub = file_get_contents($stubFile);
    if ($stub === false) {
        throw new \RuntimeException("inventory: cannot read $stubFile");
    }
    preg_match_all('/^\s*(?:final |abstract )?(?:class|interface|enum) ([A-Za-z0-9_]+)/m', $stub, $m);
    $byPhp = [];
    foreach ($types as $n) {
        $byPhp[phpClass($n)] ??= $n;
        // The C spelling: GdkRGBA, PangoRectangle - and GParamSpec, whose GType is named GParam
        $prefix = in_array($n->ns, ['GLib', 'GObject', 'Gio'], true) ? 'G' : $n->ns;
        $byPhp[$prefix . $n->name] ??= $n;
    }
    $rows = [];
    foreach ($m[1] as $class) {
        if (in_array($class, $emittedClasses, true)) {
            throw new \RuntimeException("inventory: $class is both generated and declared in " . basename($stubFile));
        }
        if (isset(INVENTORY_OWN[$class])) {
            [$kind, $label, $url, $note] = INVENTORY_OWN[$class];
            $rows[] = inventoryRow($class, 'php-gtk4', $kind, 'php-gtk4', $label, $url, $note);
            continue;
        }
        $n = $byPhp[$class] ?? throw new \RuntimeException(
            "inventory: $class (" . basename($stubFile)
            . ') matches no GIR type and is not in INVENTORY_OWN (gen/gir/inventory.php)',
        );
        [$label, $url] = inventoryLink($n);
        $rows[] = inventoryRow($class, $n->ns, inventoryKind($n), 'hand-written', $label, $url);
    }

    return $rows;
}

/**
 * The whole document.
 *
 * @param array<string, Node> $types every GIR type, qualified name -> node
 * @param list<Node> $emitted what run() emitted, in emission order
 */
function inventoryText(array $types, array $emitted, string $stubFile): string
{
    $generated = inventoryGeneratedRows($emitted);
    $handwritten = inventoryHandwrittenRows($types, array_column($generated, 'class'), $stubFile);
    $rows = [...$generated, ...$handwritten];
    $byNs = [];
    foreach ($rows as $row) {
        $byNs[$row['ns']][$row['class']] = $row;
    }
    $order = [...array_keys(INVENTORY_DOCS), 'php-gtk4'];
    uksort($byNs, static fn(string $a, string $b): int => array_search($a, $order, true)
        <=> array_search($b, $order, true));

    $out = ['# Inventory', '',
        'Every class, interface and enum the extension registers, by GIR namespace, each linked to its page',
        'in the upstream documentation. Generated by `gen/gir.php --install` from the GIR files and',
        '`src/gtk4.stub.php` - never edited by hand, the `gen` stage of `./ci.sh` fails on a diff - and held',
        'to what the built module registers by `InventoryTest`. What php-gtk3 had and where it went is',
        '[GTK3-MAP.md](GTK3-MAP.md); what the generator left out of these classes, and why, is `gen/report.md`.',
        '',
        '"Origin" says where the binding comes from: `generated` by `gen/gir.php` from GIR, `hand-written`',
        'in `src/` for a GIR type the generator cannot shape, `php-gtk4` for what the extension adds of its',
        'own. A namespace that needs a configure feature says so in its heading.',
        '', '## Summary', '', '| Namespace | Types | Documentation |', '| --- | ---: | --- |'];
    foreach ($byNs as $ns => $classes) {
        [$title, $base] = INVENTORY_DOCS[$ns] ?? ['php-gtk4', 'DESIGN.md'];
        $site = $ns === 'php-gtk4' ? 'https://github.com/apss-pohl/php-gtk4' : $base;
        $out[] = sprintf('| [%s](#%s) | %d | <%s> |', $ns, strtolower($ns), count($classes), $site);
    }
    $out[] = sprintf('| **all** | **%d** | |', count($rows));
    $out[] = '';
    foreach ($byNs as $ns => $classes) {
        ksort($classes, SORT_STRING);
        // The heading is the namespace alone, so the summary's #fragment links hold; the site's
        // name and the feature gate go in the line below it.
        $feature = CONDITIONAL_NAMESPACES[$ns]['feature'] ?? null;
        [$title, $base] = INVENTORY_DOCS[$ns]
            ?? ['what php-gtk4 adds of its own', 'https://github.com/apss-pohl/php-gtk4'];
        $out[] = "## $ns";
        $out[] = '';
        $out[] = ($ns === 'php-gtk4' ? ucfirst($title) : "$title, <$base>")
            . ($feature === null ? '.' : ". Built with `--enable-gtk4-$feature` only.");
        $out[] = '';
        $out[] = '| Class | Kind | Origin | Documentation |';
        $out[] = '| --- | --- | --- | --- |';
        foreach ($classes as $row) {
            $note = $row['note'] === '' ? '' : ' - ' . $row['note'];
            $out[] = sprintf(
                '| `%s` | %s | %s | [%s](%s)%s |',
                $row['class'],
                $row['kind'],
                $row['origin'],
                $row['label'],
                $row['url'],
                $note,
            );
        }
        $out[] = '';
    }

    return implode("\n", $out);
}
