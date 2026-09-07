<?php

// The vfunc emitter: per class-struct slot a thunk into the PHP override, its installer, and the native
// `vfunc_<name>()` method `parent::` chains to.
// Split out of gen/gir.php (2026-09-05): a trait of Generator, moved verbatim, so `$this` and the
// class' helpers are what they were; gen/README.md has the map.

declare(strict_types=1);

namespace PhpGtk4\Gen;

trait EmitsVfuncs
{
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
            $mapped = $this->typeMap->mapParams($n, $v);
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
                . "subclass, for `parent::$phpName()` from an override. " . docSummary($v->doc)
                . (isset(VFUNC_NOTES[$n->qname() . '.' . $v->name])
                    ? ' ' . VFUNC_NOTES[$n->qname() . '.' . $v->name] : ''),
            );
            // An empty slot (a signal's class handler GTK left NULL, `clicked`) is a no-op that
            // yields the type's zero value, so parent::vfunc_x() from an override always works.
            $retMap = $this->typeMap->retMapping($n, $native, $outs);
            $phpRet = is_array($retMap) ? $retMap['phpType'] : 'void';
            $empty = match (true) {
                $phpRet === 'void' => ['return;'],
                $phpRet === 'bool' => ['RETURN_FALSE;'],
                $phpRet === 'int' => ['RETURN_LONG(0);'],
                $phpRet === 'float' => ['RETURN_DOUBLE(0);'],
                $phpRet === 'string' => ['RETURN_EMPTY_STRING();'],
                $phpRet === 'mixed' => ['RETURN_NULL();'],
                $phpRet === 'array' => ['array_init_size(return_value, ' . count($outs) . ');',
                    ...array_map(
                        fn($o) => ($o['kind'] === 'bool' ? 'add_next_index_bool' : ($o['kind'] === 'double'
                            ? 'add_next_index_double' : 'add_next_index_long')) . '(return_value, '
                            . (str_contains($o['name'], 'baseline') ? '-1' : '0') . ');',
                        $outs,
                    ),
                    'return;'],
                str_starts_with($phpRet, '?') => ['RETURN_NULL();'],
                default => ['enum_to_php(' . ($this->typeMap->enumMacroOf($v->ret) ?? 'G_TYPE_NONE')
                    . ', 0, return_value);',
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
            $lines = $this->typeMap->cToZval($p->type, $p->name, "argv[$argIndex]");
            if ($lines === null) {
                return null;
            }
            array_push($conv, ...$lines);
            $argIndex++;
        }
        $argc = $argIndex;
        [$resultDecl, $resultConv, $resultRet] = $this->typeMap->zvalToC(
            $v,
            $outs,
            $n->qname() . '.' . $v->name,
            "$php::$phpName",
        );
        if ($resultDecl === null) {
            return null;
        }
        $l = [];
        $l[] = "// vfunc thunk: {$classMacro}->{$v->name} -> \$this->$phpName() on a PHP subclass";
        $l[] = "$retCt vfunc_thunk_{$v->name}(" . implode(', ', $cParams) . ') {';
        $l[] = '  zval zself;';
        $l[] = "  zend_function *fn = subtype_vfunc(G_OBJECT(self), \"$phpName\", &zself);";
        $l[] = '  if (fn == nullptr) {  // no handle (mid-construction, after shutdown)';
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
        // Zend refuses to run PHP while an exception is pending, and answering GTK with the
        // slot's default instead is a lie about the object: a GListModel that says "0 items"
        // because PHP happens to be unwinding leaves GtkListView's item manager holding rows GTK
        // believes gone (an assertion in a GTK built with them, a silent leak in one without).
        // So park it - an earlier callback of this emission in Rethrow mode, or a throw
        // unwinding through the dispose of a widget - exactly as Zend parks one around
        // a __destruct().
        $l[] = '  // PHP cannot run with an exception pending, and the default is no answer to';
        $l[] = '  // give GTK: park it for the call, as Zend does around a __destruct().';
        $l[] = '  zend_exception_save();';
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
        $l[] = '  zend_exception_restore();  // the parked one, previous of whatever this threw';
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
}
