<?php

declare(strict_types=1);

/*
 * Canonical showcase: every PHP-visible class of the extension appears here at
 * least once with a realistic use. tests/ExampleTest.php enforces that, so
 * adding a class means extending this file (and writing tests for it).
 *
 *   bin/php-gtk4 examples/example.php
 */

use Gtk4\ExceptionMode;
use Gtk4\GdkRectangle;
use Gtk4\GdkRGBA;
use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\GObject;
use Gtk4\GParamSpec;
use Gtk4\GSimpleAction;
use Gtk4\Gtk;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

if (!Gtk::init()) {
    fwrite(STDERR, "no display\n");
    exit(1);
}

// Exceptions thrown inside signal handlers cannot propagate through GTK's C
// frames on their own. Default (ExceptionMode::Log): they are reported here and
// the app keeps running. ExceptionMode::Rethrow instead stops the main loop and
// rethrows them from run() - handy for scripts and tests.
Gtk::set_exception_mode(ExceptionMode::Log);
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

// GtkApplication owns the main loop; everything happens in 'activate'.
$app = new GtkApplication('org.phpgtk4.example');

// Actions (GAction / GActionMap / GActionGroup): named operations reachable as
// "app.<name>" from widgets, menus and shortcuts. GVariant parameters and states
// are plain PHP values.
$quit = new GSimpleAction('quit');
$quit->connect('activate', fn() => $app->quit());
$app->add_action($quit);
$greet = new GSimpleAction('greet', 's');
$greet->connect('activate', function (GSimpleAction $a, mixed $who): void {
    printf('greet(%s) via %s
', is_string($who) ? $who : '?', $a->get_name());
});
$app->add_action($greet);
$dark = new GSimpleAction('dark', null, false);
$dark->connect('change-state', function (GSimpleAction $a, mixed $on): void {
    $a->set_state($on);
    printf("dark mode: %s\n", var_export($on, true));
});
$app->add_action($dark);

$app->connect('activate', function (GtkApplication $app): void {
    // GtkWindow -------------------------------------------------------------
    $win = new GtkWindow($app);
    $win->set_title('php-gtk4 example');
    $win->set_default_size(400, 300);

    // GObject: properties are reachable three ways - typed methods,
    // get/set_property(), and as PHP properties (dashes become underscores).
    $win->set_property('resizable', true);
    $win->default_width = 480;
    printf(
        "title=%s resizable=%s width=%d\n",
        $win->get_title(),
        var_export($win->resizable, true),
        $win->default_width,
    );

    // Signals: the handler gets the emitting object and the signal's own
    // parameters; capture anything else with use().
    $tag = 'notify';
    $id = $win->connect('notify::title', function (GObject $obj, GParamSpec $property) use ($tag): void {
        printf("[%s] %s (%s) changed on %s\n", $tag, $property->get_name(), $property->get_value_type(), $obj::class);
    });
    $win->set_title('renamed');
    $win->handler_disconnect($id);

    // close-request returns bool: true vetoes the close. Closing the last
    // window ends $app->run().
    $win->connect('close-request', function (GtkWindow $w): bool {
        echo "closing\n";
        return false;
    });

    // Widgets: GtkButton with a GtkLabel child; every widget is a GtkWidget.
    $label = new GtkLabel('Click the button');
    $label->set_selectable(true);
    $button = new GtkButton('Click me');
    $button->add_css_class('suggested-action');
    $button->set_tooltip_text('Emits the clicked signal');
    $clicks = 0;
    $button->connect('clicked', function (GtkButton $b) use ($label, &$clicks): void {
        $clicks++;
        $label->set_markup(sprintf('Clicked <b>%d</b> time(s)', $clicks));
    });
    $win->set_child($button);
    $button->set_child($label);
    $button->activate_action('app.greet', 'example');   // widgets reach the app's actions
    $app->activate_action('dark');                      // toggles the stateful action
    printf("actions: %s\n", implode(', ', $app->list_actions()));
    $child = $win->get_child();
    $parent = $label->get_parent();
    if ($child instanceof GtkWidget && $parent instanceof GtkWidget) {
        printf("child is a %s, parent of the label is a %s\n", $child::class, $parent::class);
    }

    // Boxed value types: cloneable, compared by value, fields are properties.
    $accent = new GdkRGBA('#3584e4');
    $accent->alpha = 0.8;
    $area = new GdkRectangle(0, 0, 480, 300);
    printf(
        "accent=%s opaque=%s area=%dx%d contains(10,10)=%s\n",
        $accent->to_string(),
        var_export($accent->is_opaque(), true),
        $area->width,
        $area->height,
        var_export($area->contains_point(10, 10), true),
    );
    $button->set_css_classes(['suggested-action', 'pill']);   // GStrv <-> list<string>

    // GLib sources on the application's main context.
    GLib::timeout_add(1000, function () use ($win): bool {
        $win->set_title('one second later');
        return false;  // one-shot
    });

    $win->present();
});

$status = $app->run($argv);

// A bare GMainLoop is for scripts that only need to pump events (no windows).
$loop = new GMainLoop();
GLib::idle_add(function () use ($loop): bool {
    echo "idle callback ran, quitting bare loop\n";
    $loop->quit();
    return false;
});
$loop->run();

exit($status);
