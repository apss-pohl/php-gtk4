<?php

/**
 * The loader: the installed .gir files parsed into the model.
 *
 * Part of gen/gir.php, the GObject-Introspection generator (docs/PLAN.md milestone 3);
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
