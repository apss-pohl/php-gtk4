<?php

declare(strict_types=1);

namespace PhpGtk4\Examples\Notes;

use Gtk4\ExceptionMode;
use Gtk4\GApplicationFlags;
use Gtk4\GSimpleAction;
use Gtk4\Gtk;
use Gtk4\GtkApplication;

/*
 * Notes - a small but complete GTK 4 application in PHP.
 *
 * Where examples/demo.php shows every class one page at a time, this is the
 * same widgets used the way an application uses them: a GtkApplication with
 * actions and keyboard shortcuts, a GtkApplicationWindow subclass with a
 * GtkHeaderBar, a GtkPaned between a searchable GtkListView and a GtkTextView,
 * a GListStore of PHP objects behind filter, sort and selection models, async
 * file dialogs, a toast with Undo, and a JSON file that is saved automatically.
 *
 *   bin/php-gtk4 examples/notes/notes.php              # your notes (see NoteStore::defaultPath())
 *   bin/php-gtk4 examples/notes/notes.php some.json    # a different notes file
 */

require_once __DIR__ . '/Note.php';
require_once __DIR__ . '/NoteRow.php';
require_once __DIR__ . '/NoteStore.php';
require_once __DIR__ . '/NotesWindow.php';

if (!Gtk::init()) {
    fwrite(STDERR, "no display - run inside a desktop session or under xvfb-run\n");
    exit(1);
}

// A Throwable must never unwind through GTK's C frames: one thrown inside a signal
// handler is reported here instead and the application keeps running.
Gtk::set_exception_mode(ExceptionMode::Log);
Gtk::set_exception_handler(static function (\Throwable $e, string $origin): void {
    fwrite(STDERR, sprintf("notes: %s in %s: %s\n%s\n", $e::class, $origin, $e->getMessage(), $e->getTraceAsString()));
});

$store = new NoteStore($argv[1] ?? NoteStore::defaultPath());
$store->load();

$app = new GtkApplication('org.phpgtk4.Notes', GApplicationFlags::NON_UNIQUE);

// Application-wide actions: what the primary menu and the shortcuts refer to as "app.<name>".
$app->connect('startup', static function (GtkApplication $app): void {
    $quit = new GSimpleAction('quit');
    $quit->connect('activate', static fn() => $app->quit());
    $app->add_action($quit);
    $app->set_accels_for_action('app.quit', ['<Control>q']);
    $app->set_accels_for_action('app.new', ['<Control>n']);
});

$app->connect('activate', static function (GtkApplication $app) use ($store): void {
    $window = new NotesWindow($app, $store);

    $new = new GSimpleAction('new');
    $new->connect('activate', static fn() => $window->newNote());
    $app->add_action($new);
    $about = new GSimpleAction('about');
    $about->connect('activate', static fn() => $window->about());
    $app->add_action($about);

    $window->present();
});

exit($app->run());
