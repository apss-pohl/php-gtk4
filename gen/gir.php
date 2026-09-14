<?php

/**
 * gir.php - the GObject-Introspection generator (docs/DESIGN.md).
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

require_once __DIR__ . '/gir/config.php';
require_once __DIR__ . '/gir/model.php';
require_once __DIR__ . '/gir/loader.php';
require_once __DIR__ . '/gir/writer.php';
require_once __DIR__ . '/gir/type-map.php';
require_once __DIR__ . '/gir/emit-class.php';
require_once __DIR__ . '/gir/emit-record.php';
require_once __DIR__ . '/gir/emit-vfunc.php';
require_once __DIR__ . '/gir/emit-tests.php';
require_once __DIR__ . '/gir/inventory.php';

// ---------------------------------------------------------------- generator

final class Generator
{
    use EmitsClasses;
    use EmitsRecords;
    use EmitsVfuncs;
    use EmitsTests;

    /** What is in scope this run; the type map reads the same set (gen/gir/type-map.php). */
    private TypeSet $types;
    /** GIR type -> PHP type, ZPP line, conversions (gen/gir/type-map.php). */
    private TypeMap $typeMap;


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
    ) {
        $this->writer = new Writer($out);
        $this->types = new TypeSet($gir);
        $this->typeMap = new TypeMap($gir, $this->types, $this->skip(...));
    }

    /** @var array<string, array{node: Node, stub: string}> php class -> what emitClass produced */
    private array $classInfo = [];
    /** @var list<Node> every type run() emitted, for docs/INVENTORY.md */
    private array $emitted = [];
    /** docs/INVENTORY.md, written on --install only */
    private ?string $inventoryPath = null;

    public function setInventoryPath(string $path): void
    {
        $this->inventoryPath = $path;
    }
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
            $this->types->handwritten[$q] = true;
        }
        $this->skipList = $skip;   // add() consults it: a skipped member pulls no enums
        foreach ($allow as $q) {
            $this->add($q);
        }
    }

    private function add(string $q): void
    {
        if ($this->types->known($q)) {
            return;
        }
        $n = $this->gir->types[$q] ?? null;
        if ($n === null) {
            throw new \RuntimeException("allowlist: unknown type $q");
        }
        $this->types->emit[$q] = true;
        if ($n->parent !== null && $this->resolveParent($n) === null) {
            $this->add($n->parent);
        }
        // Interfaces are declared on the PHP class only when allow-listed themselves
        // (GtkAccessible/GtkBuildable on every widget would triple the wave).
        // Enums/flags used by kept signatures are cheap: always emit them.
        foreach ($n->funcs as $f) {
            // Only from a member that can still be emitted. The type-dependent reasons cannot be
            // decided here (the closure is what decides them), but these three can, and a member
            // that fails one of them must not drag an enum in: gdk_surface_create_similar_surface(),
            // deprecated in 4.12, pulled cairo_content_t into the closure - and with it a
            // src/cairo/ that shadows src/Cairo/ on a case-insensitive filesystem.
            if (
                $f->deprecated !== null
                || ($f->version !== null && version_compare($f->version, GTK_FLOOR, '>'))
                || isset($this->skipList[$n->qname() . '.' . $f->name])
            ) {
                continue;
            }
            foreach ([$f->ret, ...array_map(fn($p) => $p->type, $f->params)] as $t) {
                $tn = $this->gir->types[$t->name] ?? null;
                if ($tn !== null && in_array($tn->kind, ['enum', 'bitfield'], true) && $tn->gtypeName !== null) {
                    $this->add($t->name);
                }
            }
        }
    }


    private function skip(Node $n, string $member, string $reason): void
    {
        $this->report[] = ['type' => phpClass($n), 'member' => $member, 'reason' => $reason];
    }

    // ------------------------------------------------------------ run

    public function run(): void
    {
        $byNs = [];
        foreach (array_keys($this->types->emit) as $q) {
            $n = $this->gir->types[$q];
            $byNs[$n->ns][] = $n;
        }
        // Registration lines and prototypes are kept per namespace, so that the ones of a
        // conditional namespace (CONDITIONAL_NAMESPACES) can be emitted under its #ifdef.
        /** @var array<string, list<string>> $minitByNs */
        $minitByNs = [];
        /** @var array<string, list<string>> $protosByNs */
        $protosByNs = [];
        /** @var array<string, list<string>> $vfuncByNs */
        $vfuncByNs = [];
        foreach ($byNs as $ns => $nodes) {
            usort($nodes, fn($a, $b) => strcmp($a->name, $b->name));
            $stub = [];
            $minit = [];
            $protos = [];
            $vfuncsBefore = count($this->vfuncMinit);
            foreach ($nodes as $n) {
                switch ($n->kind) {
                    case 'enum':
                    case 'bitfield':
                        $stub[] = $this->emitEnum($n, $minit);
                        $this->emitted[] = $n;
                        break;
                    case 'constants':
                        $stub[] = $this->emitConstants($n, $minit);
                        $this->emitted[] = $n;
                        break;
                    case 'interface':
                    case 'class':
                        [$stubText, $cpp] = $n->fundamental
                            ? $this->emitFundamental($n, $minit, $protos)
                            : $this->emitClass($n, $minit, $protos);
                        if ($cpp === '') {
                            break;  // reported
                        }
                        $stub[] = $stubText;
                        $this->write("$ns/" . phpClass($n) . '.cpp', $cpp);
                        $this->emitted[] = $n;
                        break;
                    case 'record':
                        if ($n->gtypeName === null || $n->getType === null) {
                            $this->skip($n, '*', 'record without a GType (not boxed): not generated');
                            break;
                        }
                        [$stubText, $cpp] = $this->emitRecord($n, $minit, $protos);
                        $stub[] = $stubText;
                        $this->write("$ns/" . phpClass($n) . '.cpp', $cpp);
                        $this->emitted[] = $n;
                        break;
                    default:
                        $this->skip($n, '*', "{$n->kind}: not generated");
                }
            }
            $this->write("$ns/$ns.stub.php", $this->stubHeader($ns) . implode("\n", $stub));
            $minitByNs[$ns] = $minit;
            $protosByNs[$ns] = $protos;
            $vfuncByNs[$ns] = array_slice($this->vfuncMinit, $vfuncsBefore);
        }
        $this->write('gen_minit.inc', $this->minitText($minitByNs, $vfuncByNs));
        $this->write('gen_prototypes.h', $this->prototypesText($protosByNs));
        $nss = array_keys($byNs);
        sort($nss);
        $arginfo = [];
        foreach ($nss as $ns) {
            $arginfo[] = self::guarded($ns, "#include \"$ns/{$ns}_arginfo.h\"");
        }
        $this->write('gen_arginfo.h', "// GENERATED by gen/gir.php - the per-namespace arginfo (gen_stub.php output).\n"
            . "// Included once by src/gtk4.cpp after gen_prototypes.h.\n#pragma once\n"
            . implode("\n", $arginfo) . "\n");

        $this->writeExamples();
        $this->writeSmokeTests();
        $report = $this->reportText();
        $reportPath = dirname(__DIR__) . '/gen/report.md';
        self::putIfChanged($reportPath, rtrim($report) . "\n");
        if ($this->inventoryPath !== null) {
            $inventory = inventoryText($this->gir->types, $this->emitted, dirname(__DIR__) . '/src/gtk4.stub.php');
            self::putIfChanged($this->inventoryPath, rtrim($inventory) . "\n");
        }
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

    private function ceName(string $php): string
    {
        return PHP_NAMESPACE . '_' . $php;
    }

    // ------------------------------------------------------------ classes


    // ------------------------------------------------------------ methods










    // ------------------------------------------------------------ type mapping







    // ------------------------------------------------------------ MINIT + report

    /**
     * $text under the `#ifdef` of a conditional namespace (CONDITIONAL_NAMESPACES), verbatim for
     * any other.
     */
    private static function guarded(string $ns, string $text): string
    {
        $macro = CONDITIONAL_NAMESPACES[$ns]['macro'] ?? null;
        if ($macro === null) {
            return $text;
        }
        return "#ifdef $macro\n$text\n#endif  // $macro";
    }

    /**
     * The namespaces grouped by the macro that gates them: '' (always built) first, then one
     * group per macro, each in namespace order. The order every gated block of output follows.
     *
     * @param array<string, mixed> $byNs namespace -> anything
     * @return array<string, list<string>> macro -> namespaces
     */
    private static function byMacro(array $byNs): array
    {
        $groups = ['' => []];
        foreach (array_keys($byNs) as $ns) {
            $groups[CONDITIONAL_NAMESPACES[$ns]['macro'] ?? ''][] = $ns;
        }
        return $groups;
    }

    /** @param array<string, list<string>> $protosByNs namespace -> prototypes */
    private function prototypesText(array $protosByNs): string
    {
        $blocks = [];
        foreach (self::byMacro($protosByNs) as $macro => $nss) {
            // A gated namespace's C header first: src/gtk4.cpp includes this file at file scope
            // and its MINIT block (gen_minit.inc) names the namespace's GType macros.
            $protos = [];
            foreach ($nss as $ns) {
                $include = CONDITIONAL_NAMESPACES[$ns]['include'] ?? null;
                if ($include !== null) {
                    $protos[] = "#include $include";
                }
            }
            foreach ($nss as $ns) {
                array_push($protos, ...$protosByNs[$ns]);
            }
            if ($protos === []) {
                continue;
            }
            $text = implode("\n", array_unique($protos));
            $blocks[] = $macro === '' ? $text : "#ifdef $macro\n$text\n#endif  // $macro";
        }
        return "// GENERATED by gen/gir.php - prototypes of the interface method implementations.\n"
            . "// gen_stub declares none for an interface's (abstract) methods, but the ZEND_MALIAS\n"
            . "// entries of the implementing classes point at these. Include before the arginfo.\n"
            . "#pragma once\n#include \"php_gtk4.h\"\n\n" . implode("\n\n", $blocks) . "\n";
    }

    /**
     * The MINIT block: the always-built namespaces first, then one `#ifdef` block per feature
     * macro (CONDITIONAL_NAMESPACES) whose classes may extend the ones registered before it.
     *
     * @param array<string, list<string>> $minitByNs namespace -> registration lines (decl/reg pairs)
     * @param array<string, list<string>> $vfuncByNs namespace -> register_vfuncs_*() calls
     */
    private function minitText(array $minitByNs, array $vfuncByNs): string
    {
        // hand-written classes exist before gen_minit.inc is included (src/gtk4.cpp MINIT)
        $defined = ['ce_GObject' => true];
        foreach (array_keys($this->types->handwritten) as $q) {
            if (isset($this->gir->types[$q])) {
                $defined['ce_' . phpClass($this->gir->types[$q])] = true;
            }
        }
        $out = "// GENERATED by gen/gir.php - the MINIT registration block, included by src/gtk4.cpp\n"
            . "// after the hand-written classes (ce_GObject and friends exist by then).\n";
        $defs = "// GENERATED by gen/gir.php - the registration block of every conditional\n"
            . "// namespace (CONDITIONAL_NAMESPACES in gen/gir/config.php), one function each,\n"
            . "// included by src/gtk4.cpp at file scope. They are functions rather than more\n"
            . "// lines inside MINIT because MINIT is one statement per registered class and\n"
            . "// clang-tidy's function-size threshold is not a budget worth spending there.\n";
        foreach (self::byMacro($minitByNs) as $macro => $nss) {
            $minit = [];
            $vfuncs = [];
            foreach ($nss as $ns) {
                array_push($minit, ...$minitByNs[$ns]);
                array_push($vfuncs, ...$vfuncByNs[$ns]);
            }
            if ($macro === '') {
                $out .= $this->minitBlock($minit, $vfuncs, $defined);
                continue;
            }
            // A gated namespace registers from its own function: the classes it declares stay
            // in that scope (a later block may not lean on them), the ones it inherits from the
            // always-built block come in as parameters.
            $outer = $defined;
            $text = $this->minitBlock($minit, $vfuncs, $defined);
            $defined = $outer;
            $inner = [];
            preg_match_all('/zend_class_entry \*(ce_\w+)/', $text, $m);
            foreach ($m[1] as $name) {
                $inner[$name] = true;
            }
            preg_match_all('/\bce_\w+/', $text, $m);
            $params = array_values(array_unique(array_filter($m[0], fn($n) => !isset($inner[$n]))));
            sort($params);
            $fn = 'register_' . (CONDITIONAL_NAMESPACES[$nss[0]]['feature'] ?? 'gated') . '_classes';
            $defs .= "#ifdef $macro  // " . implode(', ', $nss) . "\n"
                . '// MINIT: the classes of ' . implode(', ', $nss) . ", registered from here so that\n"
                . "// a build without the feature carries none of it.\n"
                . "static void $fn(" . implode(', ', array_map(fn($n) => "zend_class_entry *$n", $params))
                . ") {\n$text}\n#endif  // $macro\n";
            $out .= "#ifdef $macro  // " . implode(', ', $nss) . "\n"
                . '  ' . $fn . '(' . implode(', ', $params) . ");\n#endif  // $macro\n";
        }
        $this->write('gen_minit_defs.inc', $defs);
        return $out;
    }

    /**
     * One registration block: enums first, then interfaces and classes parent-first. $defined
     * carries the `ce_*` names in scope across blocks (a gated class may extend an always-built
     * one, never the other way round).
     *
     * @param list<string> $minit
     * @param list<string> $vfuncs
     * @param array<string, true> $defined
     */
    private function minitBlock(array $minit, array $vfuncs, array &$defined): string
    {
        $enums = array_filter($minit, fn($l) => str_contains($l, 'register_enum')
            || str_contains($l, 'register_flags') || str_ends_with($l, '// constants'));
        $rest = array_values(array_diff($minit, $enums));
        // order class registrations so that a parent's ce_ exists before use
        $ordered = [];
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
        return "// --- enums and flags\n" . implode("\n", $enums) . "\n"
            . "// --- interfaces and classes, parents first\n" . implode("\n", $ordered) . "\n"
            . "// --- vfunc thunks for PHP subclasses (core/subtype.h)\n" . implode("\n", $vfuncs) . "\n";
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
    $file = Gir::locate($f);
    if ($file === null) {
        $package = str_starts_with($f, 'WebKit') || str_starts_with($f, 'JavaScriptCore')
            ? 'gir1.2-webkit-6.0, a dependency of libwebkitgtk-6.0-dev'
            : 'gir1.2-gtk-4.0';
        throw new \RuntimeException("$f.gir not found in " . implode(', ', GIR_DIRS) . " (install $package)");
    }
    $gir->load($file);
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
if ($install) {
    $g->setInventoryPath("$root/docs/INVENTORY.md");
}
$g->run();
if ($install) {
    passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/map-status.php'), $rc);
    if ($rc !== 0) {
        exit($rc);
    }
}
echo "generated into $out\n";
