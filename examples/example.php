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
Gtk::set_exception_handler(function (string $message, string $origin, int $code): void {
    error_log("[example] handler for '$origin' failed (code $code): $message");
});

// GtkWindow -----------------------------------------------------------------
$win = new GtkWindow();
$win->set_title('php-gtk4 example');
$win->set_default_size(400, 300);

// GObject: generic property access and signals work on every object.
$win->set_property('resizable', true);
printf("title=%s resizable=%s\n", $win->get_title(), var_export($win->get_property('resizable'), true));

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
