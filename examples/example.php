<?php

declare(strict_types=1);

/*
 * Canonical showcase: every PHP-visible class of the extension appears here at
 * least once with a realistic use. tests/ExampleTest.php enforces that, so
 * adding a class means extending this file (and writing tests for it).
 *
 *   bin/php-gtk4 examples/example.php
 */

use Gtk4\{GObject, Gtk, GtkWindow};

if (!Gtk::init()) {
    fwrite(STDERR, "no display\n");
    exit(1);
}

// Exceptions thrown inside signal handlers cannot propagate through GTK's
// main loop; route them somewhere visible instead of relying on stderr.
Gtk::set_exception_handler(function (\Throwable $e, string $origin): void {
    error_log(sprintf(
        "[example] handler for '%s' failed: %s: %s at %s:%d",
        $origin,
        $e::class,
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
    ));
});

// GtkWindow -----------------------------------------------------------------
$win = new GtkWindow();
$win->set_title('php-gtk4 example');
$win->set_default_size(400, 300);

// GObject: properties are reachable three ways - typed methods, get/set_property(),
// and as PHP properties (underscores map to dashes: $win->default_width).
$win->set_property('resizable', true);
$win->default_width = 480;
printf("title=%s resizable=%s width=%d\n", $win->get_title(), var_export($win->resizable, true), $win->default_width);

$id = $win->connect('notify::title', function (GObject $obj, string $property, string $tag): void {
    printf("[%s] %s changed on %s\n", $tag, $property, $obj::class);
}, 'notify');
$win->set_title('renamed');
$win->handler_disconnect($id);

// close-request returns bool: true vetoes the close.
$win->connect('close-request', function (GtkWindow $w): bool {
    echo "closing\n";
    Gtk::main_quit();
    return false;
});

$win->present();
Gtk::main();
