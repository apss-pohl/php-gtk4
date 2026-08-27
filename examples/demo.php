<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

require_once __DIR__ . '/bootstrap.php';

/*
 * The demo application.
 *
 * Every examples/<Class>.php ends in `return Demo::page(...)` and does nothing
 * else. Demo::pages() requires them all and Demo::showcase() puts them in one
 * window - header, sidebar, content - or Demo::single() shows just one.
 *
 *   bin/php-gtk4 examples/demo.php              # the application
 *   bin/php-gtk4 examples/demo.php GtkButton    # only that class
 *   bin/php-gtk4 examples/demo.php --list       # what is available
 */

$pages = Demo::pages();
$wanted = $argv[1] ?? null;

if ($wanted === null) {
    Demo::showcase($pages);
}

if ($wanted === '--list' || $wanted === '-l') {
    foreach ($pages as $page) {
        printf("%-22s %s\n", $page['class'], $page['summary']);
    }
    exit(0);
}

foreach ($pages as $page) {
    if (strcasecmp($page['class'], $wanted) === 0) {
        Demo::single($page);
    }
}

fwrite(STDERR, sprintf("no example for '%s' - try: %s --list\n", $wanted, $argv[0]));
exit(1);
