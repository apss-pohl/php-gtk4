#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * Comment blocks for every C++ function.
 *
 *   php gen/method-comments.php            # (re)write the generated blocks above each ZEND_METHOD
 *   php gen/method-comments.php --check    # exit 1 if a block is missing/stale, or if any other
 *                                          # function definition has no comment right above it
 *
 * ZEND_METHOD blocks are derived from src/gtk4.stub.php (PHP signature + description),
 * so the stub stays the single source of truth; they are recognised by their first line
 * "Gtk4\Class::method(" and replaced on regeneration. All other functions (helpers,
 * handlers, trampolines) need a hand-written comment directly above the definition.
 */

require __DIR__ . '/../vendor/autoload.php';

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

$root = dirname(__DIR__);
$check = in_array('--check', $argv, true);

// ---------------------------------------------------------------- stub -> signatures
$stubSrc = file_get_contents($root . '/src/gtk4.stub.php') ?: '';
$ast = new ParserFactory()->createForNewestSupportedVersion()->parse($stubSrc) ?? [];
$printer = new Standard();
/** @var array<string, array{sig: string, doc: string}> */
$methods = [];
foreach (new NodeFinder()->findInstanceOf($ast, Stmt\ClassLike::class) as $class) {
    /** @var Stmt\ClassLike $class */
    $className = $class->name?->toString() ?? '';
    foreach ($class->getMethods() as $m) {
        $params = implode(', ', array_map(fn(Node\Param $p) => $printer->prettyPrint([$p]), $m->params));
        $ret = $m->returnType !== null ? ': ' . $printer->prettyPrint([$m->returnType]) : '';
        $static = $m->isStatic() ? 'static ' : '';
        $sig = sprintf('Gtk4\\%s::%s(%s)%s', $className, $m->name->toString(), $params, $ret);
        $doc = '';
        $comment = $m->getDocComment();
        if ($comment instanceof Doc) {
            $lines = [];
            foreach (preg_split('/\R/', $comment->getText()) ?: [] as $line) {
                $line = preg_replace('#^\s*(/\*\*|\*/|\*)\s?#', '', $line) ?? '';
                $line = trim(preg_replace('#\s*\*/\s*$#', '', $line) ?? '');
                if ($line === '' || str_starts_with($line, '@')) {
                    if ($lines !== [] && $line === '') {
                        break;  // first paragraph only
                    }
                    if (str_starts_with($line, '@')) {
                        break;
                    }
                    continue;
                }
                $lines[] = $line;
            }
            $doc = implode(' ', $lines);
        }
        $methods[$className . '::' . $m->name->toString()] = ['sig' => $static . $sig, 'doc' => $doc];
    }
}

// ---------------------------------------------------------------- C++ files
/** @return list<string> */
$wrapText = static function (string $text, int $width): array {
    if ($text === '') {
        return [];
    }
    $parts = preg_split('/\R/', wordwrap($text, $width, "\n", true));
    return $parts === false ? [] : array_map('strval', $parts);
};

$problems = [];
$files = array_merge(glob($root . '/src/*.cpp') ?: [], glob($root . '/src/*/*.cpp') ?: []);
sort($files);
foreach ($files as $file) {
    $lines = preg_split('/\R/', (string) file_get_contents($file)) ?: [];
    // Repair blocks a previous run closed early (clang-format merges "*/" onto the method line).
    $repaired = [];
    foreach ($lines as $l) {
        if (preg_match('/^\*\/\s*((?:ZEND_METHOD|WIDGET_METHOD)\(.*)$/', $l, $rm)) {
            $repaired[] = '*/';
            $repaired[] = $rm[1];
        } else {
            $repaired[] = $l;
        }
    }
    $lines = $repaired;
    $out = [];
    $changed = false;
    $n = count($lines);
    for ($i = 0; $i < $n; $i++) {
        $line = $lines[$i];
        // --- ZEND_METHOD / WIDGET_METHOD definitions
        if (preg_match('/^(ZEND_METHOD|WIDGET_METHOD)\((?:(\w+), )?(\w+)\)/', $line, $mm)) {
            $class = $mm[1] === 'WIDGET_METHOD' ? 'GtkWidget' : preg_replace('/^Gtk4_/', '', $mm[2]);
            $key = $class . '::' . $mm[3];
            $info = $methods[$key] ?? null;
            $block = [];
            if ($info !== null) {
                $block[] = '/**';
                // Wrapped like the description below: a long signature (an enum
                // parameter default is enough) would otherwise break clang-format.
                foreach ($wrapText($info['sig'], 96) as $l) {
                    $block[] = ' * ' . $l;
                }
                if ($info['doc'] !== '') {
                    $block[] = ' *';
                    foreach ($wrapText($info['doc'], 96) as $l) {
                        $block[] = ' * ' . $l;
                    }
                }
                $block[] = ' */';
            } else {
                $problems[] = sprintf('%s:%d: %s is not declared in src/gtk4.stub.php', $file, $i + 1, $key);
            }
            // Drop an existing generated block directly above (starts with "/**", first line has the signature).
            $j = count($out) - 1;
            if ($j >= 0 && trim($out[$j]) === '*/') {
                $k = $j;
                while ($k >= 0 && trim($out[$k]) !== '/**') {
                    $k--;
                }
                if ($k >= 0 && isset($out[$k + 1]) && str_contains($out[$k + 1], 'Gtk4\\' . $class . '::')) {
                    $existing = array_slice($out, $k);
                    array_splice($out, $k);
                    if ($existing !== $block) {
                        $changed = true;
                    }
                } else {
                    // some other comment: keep it, add ours below it
                    $changed = $block !== [];
                }
            } else {
                $changed = $block !== [];
            }
            foreach ($block as $b) {
                $out[] = $b;
            }
            $out[] = $line;
            continue;
        }
        // --- any other function definition at column 0 needs a comment right above
        $definition = '/^(?:static |inline )?[A-Za-z_][\w:<>\*& ]*\**\s*&?\s*\b(\w+)\s*\([^;]*$/';
        $isDefinition = preg_match($definition, $line, $fm) && !str_contains($line, '=');
        $keywords = '/^(?:namespace|using|struct|class|enum|return|if|for|while|switch|extern|#|PHP_|ZEND_)/';
        if ($isDefinition && !preg_match($keywords, $line)) {
            $prev = count($out) - 1;
            while ($prev >= 0 && trim($out[$prev]) === '') {
                $prev--;
            }
            $hasComment = $prev >= 0 && preg_match('#^\s*(//|\*/|/\*)#', $out[$prev]);
            if (!$hasComment) {
                $problems[] = sprintf('%s:%d: function %s() has no comment above it', $file, $i + 1, $fm[1]);
            }
        }
        $out[] = $line;
    }
    if ($changed && !$check) {
        file_put_contents($file, implode("\n", $out));
        echo 'updated ' . substr($file, strlen($root) + 1) . "\n";
    } elseif ($changed && $check) {
        $problems[] = substr($file, strlen($root) + 1)
            . ': ZEND_METHOD comment blocks are missing or stale (run php gen/method-comments.php)';
    }
}

foreach ($problems as $p) {
    fwrite(STDERR, str_replace($root . '/', '', $p) . "\n");
}
exit($problems === [] ? 0 : 1);
