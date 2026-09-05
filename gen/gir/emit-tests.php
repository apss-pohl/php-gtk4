<?php

// What the generator writes besides the binding: the smoke test per class and the example page skeleton.
// Split out of gen/gir.php (2026-09-05): a trait of Generator, moved verbatim, so `$this` and the
// class' helpers are what they were; gen/README.md has the map.

declare(strict_types=1);

namespace PhpGtk4\Gen;

trait EmitsTests
{
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
            $type = $this->typeMap->phpType($p['type'], true);
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
        foreach (array_keys($this->types->emit) as $q) {
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
}
