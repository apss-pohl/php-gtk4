<?php

/**
 * Regenerates the status column of docs/GTK3-MAP.md from the stub files.
 *
 * Run by gen/gir.php --install (and therefore checked by ./ci.sh --only=gen). A row whose
 * "GTK 4 replacement" cell names one or more classes in backticks gets ✅ when every one of them
 * is declared in a stub, 🟡 when some are, ❌ when none is. Rows marked ⛔ / 🧩 and rows whose
 * replacement is not a class name (a method, a mechanism) stay as written. Notes are never touched.
 *
 *   php gen/map-status.php            # rewrite docs/GTK3-MAP.md in place
 *   php gen/map-status.php --check    # exit 1 when the file would change
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$mapPath = "$root/docs/GTK3-MAP.md";

$declared = [];
foreach (array_merge(["$root/src/gtk4.stub.php"], glob("$root/src/*/*.stub.php") ?: []) as $stub) {
    $src = file_get_contents($stub);
    if ($src === false) {
        throw new RuntimeException("cannot read $stub");
    }
    if (preg_match_all('/^\s*(?:final\s+)?(?:class|interface|enum)\s+([A-Za-z0-9_]+)/m', $src, $m) > 0) {
        foreach ($m[1] as $name) {
            $declared[] = $name;
        }
    }
}
sort($declared);
$declared = array_values(array_unique($declared));
$isDeclared = array_fill_keys($declared, true);

$text = file_get_contents($mapPath);
if ($text === false) {
    throw new RuntimeException("cannot read $mapPath");
}

$marks = ['✅', '🟡', '❌'];
$counts = ['✅' => 0, '🟡' => 0, '❌' => 0, '⛔' => 0, '🧩' => 0];
$lines = explode("\n", $text);
foreach ($lines as $i => $line) {
    if (!str_starts_with($line, '| ')) {
        continue;
    }
    $cells = explode('|', $line);
    $status = null;
    foreach ($cells as $k => $cell) {
        if (in_array(trim($cell), ['✅', '🟡', '❌', '⛔', '🧩'], true)) {
            $status = $k;
            break;
        }
    }
    if ($status === null || $status < 2) {
        continue;
    }
    $mark = trim($cells[$status]);
    $newInGtk4 = trim($cells[1]) === '—'; // no php-gtk3 counterpart: hand-maintained row
    if (in_array($mark, $marks, true) && !$newInGtk4) {
        $replacement = trim($cells[$status - 1]);
        preg_match_all('/`([A-Z][A-Za-z0-9]+)`/', $replacement, $m);
        $classes = $m[1];
        if ($classes !== [] && preg_match('/^`[A-Z][A-Za-z0-9]+`/', $replacement) === 1) {
            $have = count(array_filter($classes, static fn(string $c): bool => isset($isDeclared[$c])));
            $mark = $have === count($classes) ? '✅' : ($have > 0 ? '🟡' : '❌');
            $cells[$status] = " $mark ";
            $lines[$i] = implode('|', $cells);
        }
    }
    if (isset($counts[$mark]) && $i > 30) {
        $counts[$mark]++;
    }
}
$text = implode("\n", $lines);

// The date is part of the file and `./ci.sh --only=gen` compares a fresh run with what is
// committed, so a stamp that follows the calendar fails CI on the first day after the file was
// last written - it did, on 2026-09-07. Keep the date the file carries while building, and
// advance it below only when something else in the file really changed.
$today = date('Y-m-d');
$stamp = preg_match('/^Status column regenerated (\d{4}-\d{2}-\d{2})/m', $text, $m) === 1 ? $m[1] : $today;
$listStamp = preg_match('/^php-gtk4 currently declares \((\d{4}-\d{2}-\d{2}),/m', $text, $m) === 1 ? $m[1] : $today;
$text = preg_replace(
    '/^(?:Generated \d{4}-\d{2}-\d{2}\.|Status column regenerated \d{4}-\d{2}-\d{2}).*(?:\n.*may lag\.)?$/m',
    "Status column regenerated $stamp by `gen/map-status.php` (run by `gen/gir.php --install`);\n" .
    'the notes are hand-written and may lag.',
    $text,
    1,
) ?? $text;
$text = preg_replace(
    '/^\| ✅ implemented \| \d+ \|/m',
    "| ✅ implemented | {$counts['✅']} |",
    $text,
    1,
) ?? $text;
$text = preg_replace('/^\| 🟡 partial \| \d+ \|/m', "| 🟡 partial | {$counts['🟡']} |", $text, 1) ?? $text;
$text = preg_replace(
    '/^\| ❌ to port \(GTK 4 equivalent exists\) \| ~?\d+ \|/m',
    "| ❌ to port (GTK 4 equivalent exists) | {$counts['❌']} |",
    $text,
    1,
) ?? $text;
$text = preg_replace(
    '/^\| ⛔ removed in GTK 4 \| ~?\d+ \|/m',
    "| ⛔ removed in GTK 4 | {$counts['⛔']} |",
    $text,
    1,
) ?? $text;
$text = preg_replace(
    '/^\| 🧩 out of scope \/ later milestone \| ~?\d+ \|/m',
    "| 🧩 out of scope / later milestone | {$counts['🧩']} |",
    $text,
    1,
) ?? $text;

$list = 'php-gtk4 currently declares (' . $listStamp . ', ' . count($declared) . ' names): ' .
    implode(', ', array_map(static fn(string $n): string => "`$n`", $declared)) .
    '. Everything else in this document is open work.';
$wrapped = wordwrap($list, 110, "\n", false);
$text = preg_replace(
    '/^php-gtk4 currently (?:registers|declares) \(.*?\. Everything else in this document is open work\./ms',
    $wrapped,
    $text,
    1,
) ?? $text;

$check = in_array('--check', $argv, true);
$old = file_get_contents($mapPath);
if ($old === $text) {
    echo "docs/GTK3-MAP.md up to date\n";
    exit(0);
}
// Something else changed, so the file really is regenerated today: now the dates advance.
$text = preg_replace(
    '/^Status column regenerated \d{4}-\d{2}-\d{2}/m',
    "Status column regenerated $today",
    $text,
    1,
) ?? $text;
$text = preg_replace(
    '/^php-gtk4 currently declares \(\d{4}-\d{2}-\d{2},/m',
    "php-gtk4 currently declares ($today,",
    $text,
    1,
) ?? $text;
if ($old === $text) {
    echo "docs/GTK3-MAP.md up to date\n";
    exit(0);
}
if ($check) {
    fwrite(STDERR, "docs/GTK3-MAP.md status column is stale (run php gen/map-status.php)\n");
    exit(1);
}
file_put_contents($mapPath, $text);
echo "docs/GTK3-MAP.md rewritten\n";
