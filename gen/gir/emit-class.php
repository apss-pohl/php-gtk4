<?php

// The class emitter: one generated `src/<Ns>/<Class>.cpp` and stub class per GObject class, method by
// method (`method()` is shared with the record and vfunc emitters).
// Split out of gen/gir.php (2026-09-05): a trait of Generator, moved verbatim, so `$this` and the
// class' helpers are what they were; gen/README.md has the map.

declare(strict_types=1);

namespace PhpGtk4\Gen;

trait EmitsClasses
{
    /** Nearest known ancestor's qualified name, or null when the chain has none. */
    private function resolveParent(Node $n): ?string
    {
        for ($p = $n->parent; $p !== null; $p = ($this->gir->types[$p] ?? null)?->parent) {
            if ($this->types->known($p)) {
                return $p;
            }
        }
        return null;
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

    /**
     * @param list<string> $minit
     * @param list<string> $protos
     * @return array{string, string} stub section, cpp file
     */
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
            if ($this->types->known($i) && $this->gir->types[$i]->kind === 'interface') {
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
        // Interface methods: one implementation per interface, aliased into the class - the
        // interfaces it implements and everything those require (a GtkSelectionModel is a
        // GListModel). An interface itself declares nothing of its prerequisites: PHP inherits
        // those through `extends`, and a body in an interface is not even valid PHP.
        $aliasIfaces = $n->kind === 'interface' ? [] : $ifaces;
        for ($ai = 0; $ai < count($aliasIfaces); $ai++) {
            $prereqs = $this->gir->types[$this->types->qOfPhp($aliasIfaces[$ai])]->prerequisites;
            foreach ($prereqs as $q) {
                if (!$this->types->known($q) || $this->gir->types[$q]->kind !== 'interface') {
                    continue;
                }
                $prereqPhp = phpClass($this->gir->types[$q]);
                if (!in_array($prereqPhp, $aliasIfaces, true)) {
                    $aliasIfaces[] = $prereqPhp;
                }
            }
        }
        foreach ($aliasIfaces as $iface) {
            $in = $this->gir->types[$this->types->qOfPhp($iface)];
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
                    $sig = $this->typeMap->stubSignature($in, $f, $phpName);
                }
                if ($sig === null) {
                    continue;
                }
                $stubMethods[] = "    /** @implementation-alias Gtk4\\$iface::$phpName */\n"
                    . "    public function $sig {}\n";
            }
        }

        // ---- vfuncs: thunks + installers + native vfunc_<name>() methods (PHP subclasses, PLAN §2.6)
        // skip.txt `<Ns>.<Type>.vfuncs`: no thunks at all (GdkPixbufAnimation's class struct is
        // behind GDK_PIXBUF_ENABLE_BACKEND, so nothing could install one)
        $noVfuncs = isset($this->skipList[$n->qname() . '.vfuncs']);
        if ($noVfuncs && $n->vfuncs !== []) {
            $this->skip($n, 'vfuncs', 'skip.txt: ' . $this->skipList[$n->qname() . '.vfuncs']);
        }
        if (($n->kind === 'class' && !$n->final || $n->kind === 'interface') && $n->vfuncs !== [] && !$noVfuncs) {
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
            // modern API only, like the methods: a deprecated property gets no tag (its getter and
            // setter are skipped too), so the stub does not promise what GTK asks nobody to use
            if ($p['deprecated'] !== null) {
                $this->skip($n, 'property ' . $p['name'], "deprecated ({$p['deprecated']})");
                continue;
            }
            // skip.txt `<Ns>.<Type>.property:<name>`: no @property tag (the GObject property is
            // still there, but the binding does not promise it - GTK 4.14's
            // GtkFixedLayoutChild:transform getter is broken)
            $propKey = $n->qname() . '.property:' . $p['name'];
            if (isset($this->skipList[$propKey])) {
                $this->skip($n, 'property ' . $p['name'], 'skip.txt: ' . $this->skipList[$propKey]);
                continue;
            }
            // nullable where to_gvalue() accepts null and a getter can answer it (an object, a
            // string, a boxed record, a variant); a scalar or enum property is never null, and
            // declaring it so made `$label->xalign = null` a runtime TypeError PHPStan could not see
            $scalar = $this->typeMap->phpType($p['type'], false);
            $kind = ($this->gir->types[$p['type']->name] ?? null)?->kind;
            $nullable = !in_array($scalar, ['int', 'float', 'bool'], true) && $kind !== 'enum' && $kind !== 'bitfield';
            $pt = $this->typeMap->phpType($p['type'], $nullable);
            if ($pt === null) {
                $this->skip($n, 'property ' . $p['name'], 'property type ' . $p['type']->name . ' not mappable');
                continue;
            }
            // the access PHPDoc can state: a read is what the getter answers, a write converts like
            // the setter's parameter; a write-only property (GtkTextTag's colour names) refuses a read
            $tag = match (true) {
                $p['readable'] && $p['writable'] => '@property',
                $p['readable'] => '@property-read',
                default => '@property-write',
            };
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
        $joined = implode('', $cppMethods);
        $prelude = $this->preludes[$n->qname()] ?? '';
        $includes = $this->coreIncludes($n, ['"php_gtk4.h"', '"core/object.h"'], $joined, $prelude);
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
            // Every method the class has to have a body for: this interface's, plus the ones it
            // inherits from its prerequisites (GtkSelectionModel extends GListModel). A missing
            // one would leave the class abstract, and wrap() cannot instantiate that.
            $fbSeen = [];
            $fbSources = [$n];
            for ($i = 0; $i < count($fbSources); $i++) {
                foreach ($fbSources[$i]->prerequisites as $q) {
                    if ($this->types->known($q) && $this->gir->types[$q]->kind === 'interface') {
                        $fbSources[] = $this->gir->types[$q];
                    }
                }
            }
            foreach ($fbSources as $fbSource) {
                $fbPhp = phpClass($fbSource);
                foreach ($fbSource->funcs as $f) {
                    if (
                        $f->kind !== 'method' || $f->shadowedBy !== null
                        || !$this->methodEmittable($fbSource, $f)
                    ) {
                        continue;
                    }
                    $phpName = $f->shadows ?? $f->name;
                    $sig = $this->typeMap->stubSignature($fbSource, $f, $phpName);
                    if ($sig === null || isset($fbSeen[$phpName])) {
                        continue;
                    }
                    $fbSeen[$phpName] = true;
                    $fbMethods[] = "    /** @implementation-alias Gtk4\\$fbPhp::$phpName */\n"
                        . "    public function $sig {}\n";
                }
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

    /**
     * Which core headers a class needs is decided by the identifiers its emitted code uses - code
     * lines only (comments and docblocks stripped, so a docblock mentioning "variant" pulls nothing
     * in), and nothing the class prelude already includes.
     *
     * @param list<string> $includes
     * @return list<string>
     */
    private function coreIncludes(Node $n, array $includes, string $joined, string $prelude): array
    {
        $code = preg_replace('#^\s*(//|/\*|\*).*$#m', '', $joined) ?? '';
        // A conditional namespace's C API is not part of gtk.h (CONDITIONAL_NAMESPACES): its
        // header follows php_gtk4.h, before the core headers that may use its types.
        $own = CONDITIONAL_NAMESPACES[$n->ns]['include'] ?? null;
        if ($own !== null && !in_array($own, $includes, true)) {
            array_splice($includes, 1, 0, [$own]);
        }
        foreach (
            ['enum_' => '"core/enums.h"', 'strv_' => '"core/collections.h"', 'glist_to_php' => '"core/collections.h"',
                'gslist_to_php' => '"core/collections.h"', 'gptrarray_to_php' => '"core/collections.h"',
                'throw_gerror' => '"core/gerror.h"', 'gerror_from_php' => '"core/gerror.h"',
                'variant' => '"core/variant.h"', 'wrap_boxed' => '"core/boxed.h"',
                'unwrap_boxed' => '"core/boxed.h"', 'boxed_class_for_type' => '"core/boxed.h"',
                'wrap_fundamental' => '"core/fundamental.h"', 'unwrap_fundamental' => '"core/fundamental.h"',
                'fundamental_class_for_type' => '"core/fundamental.h"', 'fundamental_self' => '"core/fundamental.h"',
                'fundamental_adopt' => '"core/fundamental.h"',
                'wrap_cairo' => '"Cairo/CairoContext.h"', 'wrap_param_spec' => '"core/paramspec.h"',
                'subtype_' => '"core/subtype.h"', 'report_pending_exception' => '"core/error.h"',
                'assert_gui_thread' => '"core/mainloop.h"', 'RunningLoop' => '"core/mainloop.h"',
                'callback_new' => '"core/callback.h"', 'CAIRO_GOBJECT_TYPE' => '<cairo-gobject.h>',
                'PHPGTK_TYPE_GSK_ROUNDED_RECT' => '"Gsk/GskRoundedRectType.h"',
                'PHPGTK_TYPE_PANGO_RECTANGLE' => '"Pango/PangoRectangleType.h"',
                'std::array' => '<array>'] as $needle => $inc
        ) {
            if (
                str_contains($code, $needle) && !in_array($inc, $includes, true)
                && !str_contains($prelude, "#include $inc")
            ) {
                $includes[] = $inc;
            }
        }
        return $includes;
    }

    /**
     * A GIR class marked glib:fundamental (GskRenderNode and its subclasses): neither a GObject
     * nor a boxed record but a refcounted instance type with ref/unref functions of its own. The
     * PHP class is a handle on src/core/fundamental like the hand-written GdkEvent: methods and
     * factories as for any class, `new` adopts what the C constructor allocated (the type map's
     * fundamental_adopt() arm), no properties, no signals, no PHP subclassing (the class is final
     * unless an emitted class derives from it, and then its constructor is private).
     *
     * @param array<mixed> $minit
     * @param array<mixed> $protos
     * @return array{string, string} stub section, cpp text - both empty when not generated
     * @param list<string> $minit
     * @param list<string> $protos
     */
    private function emitFundamental(Node $n, array &$minit, array &$protos): array
    {
        $php = phpClass($n);
        [$typeMacro, $castMacro] = macroParts($this->gir, $n);
        $ctype = $n->ctype ?? $n->gtypeName ?? $n->name;
        $pair = $this->typeMap->fundamentalRefPair($n);
        if ($pair === null) {
            $this->skip($n, '*', 'fundamental type without ref/unref functions: not generated');
            return ['', ''];
        }
        [$refFn, $unrefFn, $rootCtype] = $pair;
        $parentQ = $this->resolveParent($n);
        $parentPhp = $parentQ !== null ? phpClass($this->gir->types[$parentQ]) : null;

        $stubMethods = [];
        $cppMethods = [];
        $seenNames = [];
        $overrides = $this->overrides[$n->qname()] ?? [];
        // fundamental_self() throws on a handle that never got an instance (a constructor that
        // failed), the counterpart of PHPGTK_BOXED_SELF.
        foreach ($n->funcs as $f) {
            // A method's instance parameter is typed as the root of the hierarchy (every render
            // node method takes a GskRenderNode *), so `self` is cast to what the call wants.
            $selfType = $f->selfCtype ?? $ctype;
            $selfLine = "  auto *self = static_cast<$selfType *>(fundamental_self(execute_data));\n"
                . '  if (self == nullptr) RETURN_THROWS();';
            $phpNameOf = $f->kind === 'constructor' && $f->name === 'new' ? '__construct' : ($f->shadows ?? $f->name);
            if (isset($overrides[$phpNameOf])) {
                continue;
            }
            if (in_array($f->name, ['ref', 'unref'], true) && $f->kind === 'method') {
                $this->skip($n, $f->name, 'memory management belongs to the handle (destructor)');
                continue;
            }
            $m = $this->method($n, $f, $typeMacro, $castMacro, false, [], [], null, $selfLine);
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
            $why = $n->abstract ? 'is abstract in GTK' : 'has no constructor in GTK';
            array_unshift($stubMethods, "    /** $php $why: instances come from GTK, never from `new`. */\n"
                . "    private function __construct() {}\n");
            array_unshift($cppMethods, "/**\n * Gtk4\\$php::__construct()\n *\n"
                . " * $php $why: instances come from GTK, never from `new`.\n */\n"
                . "ZEND_METHOD($ce, __construct) {\n"
                . "  // Private: never called (object_init_ex() in wrap_fundamental() skips constructors).\n"
                . "  ZEND_PARSE_PARAMETERS_NONE();\n}\n\n");
        }

        // ---- stub: final unless an emitted class derives from it (GskRenderNode)
        $isBase = false;
        foreach (array_keys($this->types->emit) as $q) {
            if (($this->gir->types[$q]->parent ?? null) === $n->qname()) {
                $isBase = true;
            }
        }
        $docLines = docLines(docSummary($n->doc), '');
        $head = "/**\n" . implode("\n", $docLines) . ($docLines !== [] ? "\n *\n" : '')
            . " * A handle on a refcounted GTK instance (neither a GObject nor a value type): the same\n"
            . " * instance wraps to the same handle while PHP holds it, and there is no PHP subclassing.\n"
            . " *\n * @not-serializable\n */\n";
        $decl = ($isBase ? '' : 'final ') . "class $php" . ($parentPhp !== null ? " extends $parentPhp" : '');
        $stub = $head . "$decl\n{\n" . implode("\n", $stubMethods) . "}\n";
        $this->classInfo[$php] = ['node' => $n, 'stub' => $stub];

        // ---- cpp: the ref/unref pair as the registry's untyped RefFn/UnrefFn, and the registration
        $registration = "namespace {\n\n// $refFn() as the fundamental registry's RefFn.\n"
            . "gpointer fundamental_ref(gpointer p) {\n  return $refFn(static_cast<$rootCtype *>(p));\n}\n\n"
            . "// $unrefFn() as the fundamental registry's UnrefFn.\n"
            . "void fundamental_unref(gpointer p) {\n  $unrefFn(static_cast<$rootCtype *>(p));\n}\n\n"
            . "}  // namespace\n\n"
            . "namespace phpgtk {\n// MINIT: bind the PHP class to $typeMacro on the fundamental registry.\n"
            . "void register_{$php}(zend_class_entry *ce) {\n"
            . "  register_fundamental(FundamentalClass{.type = $typeMacro, .ce = ce, .ref = fundamental_ref,"
            . " .unref = fundamental_unref});\n}\n}  // namespace phpgtk\n";
        $joined = implode('', $cppMethods);
        $prelude = $this->preludes[$n->qname()] ?? '';
        $includes = $this->coreIncludes(
            $n,
            ['"php_gtk4.h"', '"core/fundamental.h"', '"core/object.h"'],
            $joined,
            $prelude,
        );
        $cpp = "// GENERATED by gen/gir.php from {$n->qname()} - do not edit (gen/overrides, gen/skip.txt).\n"
            . "// Gtk4\\$php (fundamental handle)\n"
            . implode("\n", array_map(fn($i) => "#include $i", $includes)) . "\n\nusing namespace phpgtk;\n\n"
            . $prelude . $joined . $registration;

        // ---- MINIT: declaration/registration pair, the parent's ce_ first (minitText orders)
        $parentArg = $parentPhp !== null ? "ce_$parentPhp" : '';
        $minit[] = "  zend_class_entry *ce_$php = register_class_{$ce}($parentArg);";
        $minit[] = "  phpgtk::register_{$php}(ce_$php);";
        $protos[] = "namespace phpgtk {\nvoid register_{$php}(zend_class_entry *ce);\n}  // namespace phpgtk";
        return [$stub, $cpp];
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

    /** Cheap pre-check shared by class and interface emission (no report entry). */
    private function methodEmittable(Node $n, Func $f): bool
    {
        return $this->methodSkipReason($n, $f) === null
            && $this->typeMap->stubSignature($n, $f, $f->shadows ?? $f->name) !== null;
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
                    || $this->typeMap->callbackSignature($p->type) === null
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
     * @param list<string> $vfuncChecked its empty-slot answer, emitted after the argument checks
     * @param list<array>|null $presetOuts out mappings replacing the mapped ones
     * @return array{string, string, string}|null php name, stub method, cpp method
     */
    private function method(
        Node $n,
        Func $f,
        string $typeMacro,
        string $castMacro,
        bool $isRoot,
        array $vfuncPre = [],
        array $vfuncChecked = [],
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
        $mapped = $this->typeMap->mapParams($n, $f);
        if (is_string($mapped)) {
            $this->skip($n, $f->name, $mapped);
            return null;
        }
        [$ins, $outs] = $mapped;
        if ($presetOuts !== null) {
            $outs = $presetOuts;
        }
        $retMap = $this->typeMap->retMapping($n, $f, $outs);
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
        }
        foreach (ARG_PRECONDITIONS[$f->cid] ?? [] as $i => [$argNum, $predicate, $wording]) {
            // a named bool rather than `if (!(a || b))`: clang-tidy would rewrite the latter
            $lines[] = "  const bool precondition_$i = $predicate;";
            $lines[] = "  if (!precondition_$i) {";
            $lines[] = "    zend_argument_value_error($argNum, \"$wording\");";
            $lines[] = '    RETURN_THROWS();';
            $lines[] = '  }';
        }
        if ($f->kind === 'method' && isset(SELF_PRECONDITIONS[$f->cid])) {
            [$predicate, $wording] = SELF_PRECONDITIONS[$f->cid];
            $lines[] = "  const bool state_holds = $predicate;";
            $lines[] = '  if (!state_holds) {';
            $lines[] = '    zend_throw_exception_ex(spl_ce_LogicException, 0, "%s(): ' . $wording . '",';
            $lines[] = '                            ZSTR_VAL(EX(func)->common.function_name));';
            $lines[] = '    RETURN_THROWS();';
            $lines[] = '  }';
        }
        if ($f->kind === 'method' && isset(SELF_UNPARENTED_OR[$f->cid])) {
            $parent = SELF_UNPARENTED_OR[$f->cid] . '_o';
            $lines[] = '  if (gtk_widget_get_parent(GTK_WIDGET(self)) != nullptr &&';
            $lines[] = "      gtk_widget_get_parent(GTK_WIDGET(self)) != GTK_WIDGET($parent)) {";
            $lines[] = '    zend_throw_exception_ex(spl_ce_LogicException, 0,';
            $lines[] = '                            "%s(): this %s already has a parent, and not the one given",';
            $lines[] = '                            ZSTR_VAL(EX(func)->common.function_name),';
            $lines[] = '                            G_OBJECT_TYPE_NAME(self));';
            $lines[] = '    RETURN_THROWS();';
            $lines[] = '  }';
        }
        // A native vfunc's empty-slot answer goes here, after every argument check: a slot GTK
        // left NULL is a no-op, but the arguments still have to be what the slot would take.
        array_push($lines, ...array_map(fn($l) => "  $l", $vfuncChecked));
        foreach ($outs as $o) {
            $lines[] = $o['kind'] === 'boxed'
                ? "  {$o['ctype']} {$o['name']}{};"
                : "  {$o['ctype']} {$o['name']} = {$o['init']};";
        }
        // The C call keeps GIR's parameter order: out parameters are not necessarily
        // trailing (gtk_text_view_get_iter_at_position(self, &iter, &trailing, x, y)).
        // Entries without a position (a vfunc's presetOuts) stay behind the ins.
        $ordered = array_merge(
            array_map(fn($p) => ['pos' => $p['pos'] ?? PHP_INT_MAX, 'carg' => $p['carg']], $ins),
            array_map(fn($o) => ['pos' => $o['pos'] ?? PHP_INT_MAX, 'carg' => $o['arg']], $outs),
        );
        usort($ordered, fn($a, $b) => $a['pos'] <=> $b['pos']);
        foreach ($ordered as $e) {
            $callArgs[] = $e['carg'];
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
        $subtype = $phpName === '__construct' && $posts === [] && !$n->fundamental
            ? $this->subtypeCall($n, $f, $ins) : null;
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
}
