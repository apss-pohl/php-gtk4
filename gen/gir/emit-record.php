<?php

// The record and enum emitters: boxed records with their field accessors and registration, GEnum/GFlags as
// PHP enums and constant classes.
// Split out of gen/gir.php (2026-09-05): a trait of Generator, moved verbatim, so `$this` and the
// class' helpers are what they were; gen/README.md has the map.

declare(strict_types=1);

namespace PhpGtk4\Gen;

trait EmitsRecords
{
    /** @param list<string> $minit */
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

    /**
     * The namespace's `<constant>`s as one final class of `public const`s named after the
     * namespace (`Gdk::KEY_Return`, `Gdk::BUTTON_PRIMARY`): the C names without their prefix,
     * which is how GTK's own documentation refers to them. No GType behind it, so MINIT only
     * declares the class (a "// constants" line, kept with the enums in gen_minit.inc).
     *
     * @param list<string> $minit
     */
    private function emitConstants(Node $n, array &$minit): string
    {
        $php = phpClass($n);
        $lines = [];
        foreach ($n->members as $m) {
            $key = $n->qname() . '.' . $m['name'];
            if (isset($this->skipList[$key])) {
                $this->skip($n, $m['name'], 'skip.txt: ' . $this->skipList[$key]);
                continue;
            }
            if ($m['deprecated'] || ($m['version'] !== null && version_compare($m['version'], GTK_FLOOR, '>'))) {
                $why = $m['deprecated'] ? 'deprecated constant' : "constant since {$m['version']}";
                $this->skip($n, $m['name'], $why);
                continue;
            }
            $lines[] = is_bool($m['value'])
                ? "    public const bool {$m['name']} = " . ($m['value'] ? 'true' : 'false') . ';'
                : "    public const int {$m['name']} = {$m['value']};";
        }
        $minit[] = "  register_class_{$this->ceName($php)}();  // constants";
        return "/**\n * " . $n->doc . "\n *\n * " . count($lines)
            . " constants. Never instantiated: a namespace's constants only.\n */\nfinal class $php\n{\n"
            . implode("\n", $lines) . "\n}\n";
    }

    /**
     * A boxed record (glib:get-type) as a value-type handle on core/boxed: public scalar fields
     * become PHP properties, GIR methods/constructors are emitted like a class's with the handle's
     * data as `self`; copy/free/ref/unref are the handle's business and skipped.
     *
     * @return array{string, string} stub section, cpp text
     * @param list<string> $minit
     * @param list<string> $protos
     */
    private function emitRecord(Node $n, array &$minit, array &$protos): array
    {
        $php = phpClass($n);
        [$typeMacro, $castMacro] = macroParts($this->gir, $n);
        $ctype = $n->ctype ?? $n->gtypeName;
        $fields = [];
        $opaque = in_array($n->qname(), OPAQUE_RECORDS, true);
        foreach ($n->recordFields as $fld) {
            if ($opaque) {
                break;  // the C struct is opaque: GIR's fields are not reachable
            }
            $t = $fld['type'];
            $elem = $t->isArray && $t->zeroTerminated && $t->element !== null
                ? ($this->gir->types[$t->element->name] ?? null) : null;
            $kind = match (true) {
                $t->name === 'gboolean' => 'bool',
                in_array($t->name, ['gfloat', 'gdouble'], true) => 'float',
                preg_match(INT_TYPES, $t->name) === 1 => 'int',
                // a flags-typed field reads as its int (as a flags parameter does)
                ($this->gir->types[$t->name] ?? null)?->kind === 'bitfield' && !$t->isArray => 'int',
                // read-only: a C string the struct owns (GDBusArgInfo.name), or a NULL-terminated
                // array of pointers to a boxed record in the closure (GDBusMethodInfo.in_args) as a
                // list of handles, each a boxed copy (a ref, for these refcounted infos)
                $t->name === 'utf8' && !$t->isArray => 'string',
                $elem !== null && $elem->kind === 'record' && $elem->gtypeName !== null
                    && $this->types->known($elem->qname()) && str_ends_with($t->ctype ?? '', '**') => 'list',
                default => null,
            };
            $scalar = in_array($kind, ['bool', 'float', 'int'], true);
            // the flags field reads as an int but is not written back (its C type is the enum)
            $flags = $kind === 'int' && ($this->gir->types[$t->name] ?? null)?->kind === 'bitfield';
            // A refcounted record's `ref_count` field is the handle's business (as its ref/unref
            // are), never a property - GDBusNodeInfo.ref_count is public in GIR all the same.
            if ($fld['name'] === 'ref_count' && $kind === 'int') {
                continue;
            }
            $pointer = $t->isArray || str_ends_with($t->ctype ?? '', '*');
            if ($fld['private'] || $kind === null || ($scalar && $pointer)) {
                if (!$fld['private']) {
                    $this->skip($n, 'field ' . $fld['name'], "field type {$t->name} is not a scalar");
                }
                continue;
            }
            $fields[] = ['name' => $fld['name'], 'kind' => $kind, 'ctype' => $t->ctype ?? 'int',
                'writable' => $fld['writable'] && $scalar && !$flags, 'elem' => $elem];
        }

        $stubMethods = [];
        $cppMethods = [];
        $seenNames = [];
        $overrides = $this->overrides[$n->qname()] ?? [];
        $selfLine = "  $ctype *self = PHPGTK_BOXED_SELF($ctype);";
        foreach ($n->funcs as $f) {
            // A method GIR marks <instance-parameter transfer-ownership="full"> *consumes* the
            // value it is called on (gdk_content_formats_union()). The handle owns that value and
            // releases it later, so the callee gets a reference of its own - otherwise GTK frees
            // it under the handle and the next unref corrupts the heap.
            // PHPGTK_BOXED_SELF is a statement pair (it returns on a dead handle), so the copy
            // is a second statement rather than a nested call.
            $self = $f->consumesSelf
                ? "  $ctype *owned = PHPGTK_BOXED_SELF($ctype);\n"
                    . "  auto *self = static_cast<$ctype *>(g_boxed_copy($typeMacro, owned));"
                    . '  // the call takes ownership of it'
                : $selfLine;
            $phpNameOf = $f->kind === 'constructor' && $f->name === 'new' ? '__construct' : ($f->shadows ?? $f->name);
            if (isset($overrides[$phpNameOf])) {
                continue;
            }
            if (in_array($f->name, ['copy', 'free', 'ref', 'unref'], true) && $f->kind === 'method') {
                $this->skip($n, $f->name, 'memory management belongs to the handle (clone / destructor)');
                continue;
            }
            $m = $this->method($n, $f, $typeMacro, $castMacro, false, [], [], null, $self);
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
            $scalars = array_filter($fields, fn($fd) => in_array($fd['kind'], ['bool', 'float', 'int'], true));
            if ($fields !== [] && count($scalars) === count($fields)) {
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
                'string' => "if (value->{$fd['name']} == nullptr) {\n      ZVAL_NULL(rv);\n    } else {\n"
                    . "      ZVAL_STRING(rv, value->{$fd['name']});\n    }",
                'list' => "array_init(rv);\n    for (auto **item = value->{$fd['name']}; "
                    . "item != nullptr && *item != nullptr; item++) {\n      zval h;\n"
                    . '      wrap_boxed(' . macroParts($this->gir, $fd['elem'] ?? $n)[0] . ", *item, &h);\n"
                    . "      add_next_index_zval(rv, &h);\n    }",
                default => "ZVAL_LONG(rv, static_cast<zend_long>(value->{$fd['name']}));",
            };
            $reads[] = "  if (strcmp(field, \"{$fd['name']}\") == 0) {\n    $zv\n    return true;\n  }";
            if ($fd['writable']) {
                // converted like a parameter (core/boxed: strict where the assignment is written)
                // and, for an integer field, checked against the C type's range; a refused value
                // leaves the field alone with the TypeError/ValueError pending
                $who = "\"Gtk4\\\\$php\", \"{$fd['name']}\"";
                $set = match ($fd['kind']) {
                    'bool' => "bool b = false;\n    if (boxed_field_bool(v, $who, &b)) "
                        . "value->{$fd['name']} = b ? TRUE : FALSE;",
                    'float' => "double d = 0;\n    if (boxed_field_double(v, $who, &d)) "
                        . "value->{$fd['name']} = static_cast<{$fd['ctype']}>(d);",
                    default => "zend_long l = 0;\n    if (boxed_field_long(v, $who, &l) && "
                        . "phpgtk::check_range<{$fd['ctype']}>(l, 0)) "
                        . "value->{$fd['name']} = static_cast<{$fd['ctype']}>(l);",
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
            . "// Boxed field writer: each field converts like a parameter of its type (core/boxed).\n"
            . "bool write(gpointer data, const char *field, zval *v) {\n"
            . ($writes !== []
                ? "  auto *value = static_cast<$ctype *>(data);\n" . implode("\n", $writes) . "\n"
                : "  (void)data;\n  (void)field;\n  (void)v;\n")
            . "  return false;\n}\n\n}  // namespace\n\n";
        // A record whose values point into a GObject (BOXED_OWNERS): the handle refs that
        // owner so the value cannot dangle when the script drops it (core/boxed).
        $ownerFn = \PhpGtk4\Gen\BOXED_OWNERS[$n->qname()] ?? null;
        $owner = $ownerFn === null ? '' : ",\n      .owner = [](gpointer d) {"
            . " return reinterpret_cast<GObject *>($ownerFn(static_cast<$ctype *>(d))); }";
        // A refcounted record registers ref() as its boxed copy, so g_boxed_copy() would make
        // `clone` an alias: such a type clones through its own copy() instead (GtkBitset).
        $byName = [];
        foreach ($n->funcs as $f) {
            $byName[$f->name] = $f;
        }
        // pango_attr_list_copy() takes its operand non-const where most _copy() functions take
        // it const; the registry hands it over as gconstpointer either way.
        $copyFn = isset($byName['copy'], $byName['ref']) ? $byName['copy'] : null;
        $copyConst = $copyFn === null || $copyFn->selfConst;
        $copy = $copyFn === null ? '' : ",\n"
            . ($copyConst ? ''
                : "      // NOLINTBEGIN(cppcoreguidelines-pro-type-const-cast) {$copyFn->cid}()"
                    . " takes its operand non-const\n")
            . '      .copy = [](gconstpointer d) {'
            . " return static_cast<gpointer>({$copyFn->cid}(" . ($copyConst
                ? "static_cast<const $ctype *>(d)"
                : "static_cast<$ctype *>(const_cast<gpointer>(d))") . ')); }'
            . ($copyConst ? '' : "\n      // NOLINTEND(cppcoreguidelines-pro-type-const-cast)\n     ");
        // `==` on an opaque record goes through the type's own equality (GtkTextIter::equal,
        // GtkBitset::equals); without one it can only compare the public fields.
        $equalFn = $byName['equal'] ?? $byName['equals'] ?? null;
        // gsk_transform_equal() takes its operands non-const; the registry hands them over const
        $equalOperand = (string) ($equalFn?->params[0]->type->ctype ?? 'const');
        $equalConst = $equalFn === null || str_starts_with($equalOperand, 'const');
        $operand = fn(string $v) => $equalConst
            ? "static_cast<const $ctype *>($v)"
            : "static_cast<$ctype *>(const_cast<gpointer>($v))";
        $equal = $equalFn === null ? '' : ",\n"
            . ($equalConst ? ''
                : "      // NOLINTBEGIN(cppcoreguidelines-pro-type-const-cast) {$equalFn->cid}()"
                    . " takes non-const operands\n")
            . '      .equal = [](gconstpointer a, gconstpointer b) {'
            . " return {$equalFn->cid}({$operand('a')}, {$operand('b')}) != FALSE; }"
            . ($equalConst ? '' : "\n      // NOLINTEND(cppcoreguidelines-pro-type-const-cast)\n     ");
        $registration = "namespace phpgtk {\n// MINIT: bind the PHP class to $typeMacro with its field table.\n"
            . "void register_{$php}(zend_class_entry *ce) {\n"
            . "  register_boxed(BoxedClass{.type = $typeMacro, .ce = ce, .fields = fields, .read = read,"
            . " .write = write$owner$copy$equal});\n"
            . "}\n}  // namespace phpgtk\n";

        // ---- stub
        $props = [];
        foreach ($fields as $fd) {
            $type = match ($fd['kind']) {
                'string' => '?string',
                'list' => 'list<' . phpClass($fd['elem'] ?? $n) . '>',
                default => $fd['kind'],
            };
            $props[] = ' * @' . ($fd['writable'] ? 'property' : 'property-read') . " $type \${$fd['name']}";
        }
        $docLines = docLines(docSummary($n->doc), '');
        $head = "/**\n" . implode("\n", $docLines) . ($docLines !== [] && $props !== [] ? "\n *\n" : '')
            . implode("\n", $props) . "\n * @not-serializable\n */\n";
        $stub = $head . "final class $php\n{\n" . implode("\n", $stubMethods) . "}\n";
        $this->classInfo[$php] = ['node' => $n, 'stub' => $stub];

        // ---- cpp
        $joined = implode('', $cppMethods);
        $includes = $this->coreIncludes(
            $n,
            ['"php_gtk4.h"', '"core/boxed.h"', '"core/object.h"', '<cstring>'],
            $joined,
            $this->preludes[$n->qname()] ?? '',
        );
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
}
