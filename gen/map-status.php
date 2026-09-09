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
/** @var array<string, int> $perNamespace names declared per generated namespace stub */
$perNamespace = [];
$handWritten = 0;
foreach (array_merge(["$root/src/gtk4.stub.php"], glob("$root/src/*/*.stub.php") ?: []) as $stub) {
    $src = file_get_contents($stub);
    if ($src === false) {
        throw new RuntimeException("cannot read $stub");
    }
    $found = preg_match_all('/^\s*(?:final\s+)?(?:class|interface|enum)\s+([A-Za-z0-9_]+)/m', $src, $m) > 0
        ? $m[1]
        : [];
    foreach ($found as $name) {
        $declared[] = $name;
    }
    if (basename($stub) === 'gtk4.stub.php') {
        $handWritten += count($found);
    } else {
        $perNamespace[basename(dirname($stub))] = count($found);
    }
}
arsort($perNamespace);
sort($declared);
$declared = array_values(array_unique($declared));
$isDeclared = array_fill_keys($declared, true);

$text = file_get_contents($mapPath);
if ($text === false) {
    throw new RuntimeException("cannot read $mapPath");
}

/**
 * Applies one replacement that must happen. A pattern that stops matching - because the text it
 * anchors on was rewrapped or reworded - used to leave the document silently unchanged, which is
 * how the class list below stayed frozen while the stubs grew past it; the --check gate saw
 * nothing, because the "new" text was the old text.
 *
 * A closure, not a function: this file is a script, and phpcs' PSR1 rule refuses a file that both
 * declares symbols and does work.
 */
$replaceOnce = static function (string $pattern, string $replacement, string $subject, string $what): string {
    $out = preg_replace_callback($pattern, static fn(): string => $replacement, $subject, 1, $done);
    if ($out === null) {
        throw new RuntimeException("$what: " . preg_last_error_msg());
    }
    if ($done !== 1) {
        throw new RuntimeException("$what: nothing in docs/GTK3-MAP.md matches $pattern");
    }
    return $out;
};

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
$listStamp = preg_match('/^php-gtk4 currently declares \((\d{4}-\d{2}-\d{2})[,)]/m', $text, $m) === 1
    ? $m[1]
    : $today;
$text = $replaceOnce(
    '/^(?:Generated \d{4}-\d{2}-\d{2}\.|Status column regenerated \d{4}-\d{2}-\d{2}).*(?:\n.*may lag\.)?$/m',
    "Status column regenerated $stamp by `gen/map-status.php` (run by `gen/gir.php --install`);\n" .
    'the notes are hand-written and may lag.',
    $text,
    'header stamp',
);
$text = $replaceOnce(
    '/^\| ✅ implemented \| \d+ \|/m',
    "| ✅ implemented | {$counts['✅']} |",
    $text,
    'implemented count',
);
$text = $replaceOnce('/^\| 🟡 partial \| \d+ \|/m', "| 🟡 partial | {$counts['🟡']} |", $text, 'partial count');
$text = $replaceOnce(
    '/^\| ❌ to port \(GTK 4 equivalent exists\) \| ~?\d+ \|/m',
    "| ❌ to port (GTK 4 equivalent exists) | {$counts['❌']} |",
    $text,
    'to-port count',
);
$text = $replaceOnce(
    '/^\| ⛔ removed in GTK 4 \| ~?\d+ \|/m',
    "| ⛔ removed in GTK 4 | {$counts['⛔']} |",
    $text,
    'removed count',
);
$text = $replaceOnce(
    '/^\| 🧩 out of scope \/ later milestone \| ~?\d+ \|/m',
    "| 🧩 out of scope / later milestone | {$counts['🧩']} |",
    $text,
    'out-of-scope count',
);

// Naming every declared class here was readable at 65 of them and is noise at ten times that, so
// the paragraph carries the shape of the surface - hand-written against generated, per namespace -
// and points at the stub for the names themselves.
$namespaces = [];
foreach ($perNamespace as $ns => $n) {
    $namespaces[] = "$ns ($n)";
}
$list = 'php-gtk4 currently declares (' . $listStamp . ') ' . count($declared) .
    " classes, interfaces and enums: $handWritten hand-written (`src/gtk4.stub.php`) and " .
    (count($declared) - $handWritten) . ' generated - ' . implode(', ', $namespaces) .
    '. `stubs/gtk4.php` has the names; everything else in this document is open work.';
$text = $replaceOnce(
    '/^php-gtk4 currently (?:registers|declares) \(.*?open work\./ms',
    wordwrap($list, 110, "\n", false),
    $text,
    'declared-class summary',
);

$check = in_array('--check', $argv, true);
$old = file_get_contents($mapPath);
if ($old === $text) {
    echo "docs/GTK3-MAP.md up to date\n";
    exit(0);
}
// Something else changed, so the file really is regenerated today: now the dates advance.
$text = $replaceOnce(
    '/^Status column regenerated \d{4}-\d{2}-\d{2}/m',
    "Status column regenerated $today",
    $text,
    'header date',
);
$text = $replaceOnce(
    '/^php-gtk4 currently declares \(\d{4}-\d{2}-\d{2}\)/m',
    "php-gtk4 currently declares ($today)",
    $text,
    'summary date',
);
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
