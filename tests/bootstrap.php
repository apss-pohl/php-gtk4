<?php

declare(strict_types=1);

// PHPUnit bootstrap. Tests must run inside a process that has the gtk4
// extension (and not gtk3) loaded and a display: use tests/run.sh, which
// wraps `bin/php-gtk4 vendor/bin/phpunit` in xvfb-run.
require __DIR__ . '/../vendor/autoload.php';

if (!extension_loaded('gtk4')) {
    fwrite(STDERR, "gtk4 extension not loaded - run tests via ./tests/run.sh (or bin/php-gtk4 vendor/bin/phpunit)\n");
    exit(1);
}
if (extension_loaded('gtk3')) {
    fwrite(STDERR, "gtk3 is loaded in this process; libgtk-3/libgtk-4 cannot coexist - use ./tests/run.sh\n");
    exit(1);
}
if (!Gtk4\Gtk::init()) {
    fwrite(STDERR, "Gtk::init() failed - no display. Use ./tests/run.sh (xvfb-run)\n");
    exit(1);
}
