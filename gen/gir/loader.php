<?php

/**
 * The loader: the installed .gir files parsed into the model.
 *
 * Part of gen/gir.php, the GObject-Introspection generator (docs/DESIGN.md);
 * gen/README.md describes the flow. Split out of the 3 200-line original on 2026-08-30.
 */

declare(strict_types=1);

namespace PhpGtk4\Gen;

// ---------------------------------------------------------------- GIR loading

final class Gir
{
    /**
     * Where a namespace's .gir file is: the packages put GLib's in the arch directory and GTK's
     * in the shared one, so both are searched (GIR_DIRS).
     */
    public static function locate(string $namespace): ?string
    {
        foreach (GIR_DIRS as $dir) {
            if (is_file("$dir/$namespace.gir")) {
                return "$dir/$namespace.gir";
            }
        }
        return null;
    }

    /** @var array<string, Node> qualified name -> node */
    public array $types = [];
    /** @var array<string, string> namespace -> c:identifier-prefixes (Gtk, Gdk, G, ...) */
    public array $prefixes = [];

    /**
     * GIR `<alias>` targets, qualified name -> the builtin it stands for.
     *
     * An alias is a typedef, and GIR names the typedef wherever the C header does: a `GTimeSpan`
     * parameter arrives as `GLib.TimeSpan`, which no arm of the type map matches, so the member
     * was skipped for a type that is an integer with a name. Read through it instead.
     *
     * Static because {@see type()} is, and one process loads one set of GIRs. Only aliases of
     * builtins are collected, and never of `gpointer`: the pointer typedefs (`GMutexLocker`,
     * `GMainContextPusher`) are opaque handles GLib hands out, and resolving them would turn one
     * refusal into another - `G_TYPE_POINTER` is unsupported on purpose.
     *
     * @var array<string, Type>
     */
    private static array $aliases = [];

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

        // Before the kinds below: an alias may be declared after the class whose signature uses
        // it (GIR is alphabetical), and namespaces reference each other's.
        $aliasNodes = $x->query('g:alias', $nsNode);
        foreach ($aliasNodes === false ? [] : $aliasNodes as $al) {
            assert($al instanceof \DOMElement);
            $target = self::type($x, $ns, $al);
            if (
                $target !== null
                && preg_match('/^g[a-z0-9]+$/', $target->name) === 1
                && !in_array($target->name, ['gpointer', 'gconstpointer'], true)
            ) {
                self::$aliases[$ns . '.' . $al->getAttribute('name')] = $target;
            }
        }

        $kinds = 'g:class|g:interface|g:enumeration|g:bitfield|g:record|g:callback|g:alias';
        foreach ($x->query($kinds, $nsNode) as $el) {
            assert($el instanceof \DOMElement);
            $kind = match ($el->localName) {
                'class' => 'class', 'interface' => 'interface', 'enumeration' => 'enum',
                'bitfield' => 'bitfield', 'record' => 'record', 'callback' => 'callback', 'alias' => 'alias',
            };
            // A class need not carry a c:type: GtkSnapshot is a typedef of GdkSnapshot, so GIR
            // gives it none, and every emitted `<ctype> *self` came out empty. The identifier
            // prefix plus the name is the C name GTK declares in either case.
            $ctype = $el->getAttributeNS(NS_C, 'type')
                ?: ($kind === 'class' ? $this->prefixes[$ns] . $el->getAttribute('name') : null);
            // A record without a GType the binding registers one for (SYNTHETIC_GTYPES) is
            // loaded as if GIR had named it, so the boxed arms of the type map apply.
            $synthetic = SYNTHETIC_GTYPES[$ns . '.' . $el->getAttribute('name')] ?? null;
            $node = new Node(
                $ns,
                $el->getAttribute('name'),
                $kind,
                $ctype ?: null,
                ($el->getAttributeNS(NS_GLIB, 'type-name') ?: null) ?? $synthetic[0] ?? null,
                ($el->getAttributeNS(NS_GLIB, 'get-type') ?: null) ?? $synthetic[1] ?? null,
                $el->getAttribute('version') ?: null,
            );
            $node->abstract = $el->getAttribute('abstract') === '1';
            $node->final = $el->getAttribute('final') === '1';
            $node->fundamental = $el->getAttributeNS(NS_GLIB, 'fundamental') === '1';
            $node->refFunc = $el->getAttributeNS(NS_GLIB, 'ref-func') ?: null;
            $node->unrefFunc = $el->getAttributeNS(NS_GLIB, 'unref-func') ?: null;
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
                    'deprecated' => $p->hasAttribute('deprecated') || $p->hasAttribute('deprecated-version')
                        ? ($p->getAttribute('deprecated-version') ?: 'yes')
                        : null,
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

        // The namespace's `<constant>`s (GDK_KEY_*, GDK_BUTTON_PRIMARY, ...) as one node of kind
        // `constants`, allow-listed as `<Ns>.constants` and emitted as the class `<Ns>` with a
        // `public const` per constant. Only integer and boolean values: nothing else occurs.
        $constants = new Node($ns, 'constants', 'constants', null, null, null, null);
        $constantNodes = $x->query('g:constant', $nsNode);
        foreach ($constantNodes === false ? [] : $constantNodes as $k) {
            assert($k instanceof \DOMElement);
            $t = self::type($x, $ns, $k);
            if ($t === null || !in_array($t->name, ['gint', 'guint', 'gboolean'], true)) {
                continue;
            }
            $value = $k->getAttribute('value');
            $constants->members[] = [
                'name' => $k->getAttribute('name'),
                'value' => $t->name === 'gboolean' ? $value === 'true' : (int) $value,
                'deprecated' => $k->hasAttribute('deprecated') || $k->hasAttribute('deprecated-version'),
                'version' => $k->getAttribute('version') ?: null,
            ];
        }
        if ($constants->members !== []) {
            $constants->doc = "The constants of the $ns namespace: the C `" . strtoupper($this->prefixes[$ns])
                . '_*` names without their prefix, as GTK\'s own documentation writes them.';
            $this->types[$constants->qname()] = $constants;
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
            // Through the typedef, keeping the c:type the element itself carries: the generated
            // cast then reads `static_cast<GTimeSpan>`, which is what the C function takes.
            $alias = self::$aliases[$qualified] ?? null;
            if ($alias !== null) {
                return new Type($alias->name, $t->getAttributeNS(NS_C, 'type') ?: $alias->ctype, false, $el);
            }
            return new Type($qualified, $t->getAttributeNS(NS_C, 'type') ?: null, false, $el);
        }
        return null;
    }

    private static function func(\DOMXPath $x, string $ns, \DOMElement $f): Func
    {
        $rv = $x->query('g:return-value', $f)->item(0);
        assert($rv instanceof \DOMElement);
        $ret = self::type($x, $ns, $rv) ?? new Type('none', 'void');
        $instances = $x->query('g:parameters/g:instance-parameter', $f);
        $instance = $instances !== false ? $instances->item(0) : null;
        $consumesSelf = $instance instanceof \DOMElement
            && $instance->getAttribute('transfer-ownership') === 'full';
        $selfCtype = null;
        $selfConst = true;
        if ($instance instanceof \DOMElement) {
            $instanceTypes = $x->query('g:type', $instance);
            $instanceType = $instanceTypes === false ? null : $instanceTypes->item(0);
            if ($instanceType instanceof \DOMElement && $instanceType->getAttributeNS(NS_C, 'type') !== '') {
                $declared = $instanceType->getAttributeNS(NS_C, 'type');
                $selfConst = str_contains($declared, 'const');
                $selfCtype = trim(str_replace(['const', '*'], '', $declared));
            }
        }
        $identifier = $f->getAttributeNS(NS_C, 'identifier');
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
                // GIR escapes a name that collides in some binding language by appending an
                // underscore (gtk_list_box_get_row_at_index's `index_`). The PHP API is
                // snake_case, final (docs/DESIGN.md) and carries no such escape - and the
                // generated C++ variable would fail clang-tidy's naming check besides.
                rtrim($p->getAttribute('name'), '_') ?: $p->getAttribute('name'),
                $t,
                $p->getAttribute('direction') ?: 'in',
                ($p->getAttribute('nullable') === '1' || $p->getAttribute('allow-none') === '1'
                    || isset(NULLABLE_PARAMS[$identifier . '.' . $p->getAttribute('name')]))
                    && !isset(NON_NULLABLE_PARAMS[$identifier . '.' . $p->getAttribute('name')]),
                $p->getAttribute('optional') === '1',
                $p->getAttribute('transfer-ownership') ?: 'none',
                $p->getAttribute('scope') ?: null,
                $p->getAttribute('caller-allocates') === '1',
                $p->hasAttribute('closure') ? (int) $p->getAttribute('closure') : null,
            );
        }
        return new Func(
            $f->getAttribute('name'),
            $identifier,
            match ($f->localName) {
                'constructor' => 'constructor', 'function' => 'function', 'virtual-method' => 'vfunc',
                default => 'method'
            },
            $ret,
            $rv->getAttribute('transfer-ownership') ?: 'none',
            $rv->getAttribute('nullable') === '1'
                || isset(NULLABLE_RETURNS[$identifier]),
            $params,
            $f->getAttribute('throws') === '1',
            $f->getAttribute('version') ?: null,
            $f->hasAttribute('deprecated') ? ($f->getAttribute('deprecated-version') ?: 'yes') : null,
            $f->getAttribute('shadows') ?: null,
            $f->getAttribute('shadowed-by') ?: null,
            $varargs,
            self::doc($x, $f),
            $consumesSelf,
            $selfCtype,
            $selfConst,
        );
    }
}
