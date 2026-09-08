# Porting a php-gtk3 program

`docs/GTK3-MAP.md` answers "what is this class called now". This page answers the harder half:
the places where GTK 4 does not have a different name for the same idea, but a different idea.
Six of them account for most of the work in a port, and none of them is a rename.

Nothing here is mechanical. Read it once before starting, then use the class map as the
reference while you work.

## 1. There is no `Gtk::main()`

php-gtk3 programs build widgets and then hand control to a global main loop, which the program
leaves by calling a quit function from somewhere inside a handler.

```php
// the shape a php-gtk3 program has
$window = /* ... */;
$window->show_all();
Gtk::main();          // blocks here; a handler calls Gtk::main_quit()
```

GTK 4 has no such function, and php-gtk4 does not invent one. A program is a `GtkApplication`:
it owns the loop, and the window is built in its `activate` handler.

```php
use Gtk4\Gtk;
use Gtk4\GtkApplication;
use Gtk4\GtkApplicationWindow;
use Gtk4\GtkLabel;

Gtk::init();
$app = new GtkApplication('test.php.gtk4.hello', 0);
$app->connect('activate', function (GtkApplication $app): void {
    $window = new GtkApplicationWindow($app);
    $window->set_title('Hello');
    $window->set_child(new GtkLabel('...'));
    $window->present();
});
exit($app->run($argv));
```

The application ends when its last window is closed; `$app->quit()` ends it early. What used to
be "run the loop for a moment" is `GLib::main_context_iteration(false)`, and a program that
genuinely wants a bare loop can have one with `GMainLoop` — see `examples/GMainLoop.php`. The
long-running work an application does between frames belongs in `GLib::timeout_add()` or
`GLib::idle_add()` exactly as before.

There is no `show_all()`: a widget is visible unless it is hidden, so building the tree is
enough. Toplevels still need `present()`.

## 2. There are no containers

`GtkContainer` is gone, with `add()`, `remove()`, `pack_start()` and the child properties. What
replaces it is per-widget: a widget that holds *one* child has `set_child()`, a widget that holds
*many* has methods of its own.

```php
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;

$box = new GtkBox(GtkOrientation::Vertical, 6);
$box->append(new GtkButton());   // and prepend(), insert_child_after(), remove()
$window->set_child($box);        // a window holds exactly one child
```

The child properties that used to be passed to `pack_start()` — expand, fill, padding — are now
properties of the child widget itself: `set_hexpand()`, `set_vexpand()`, `set_halign()`,
`set_valign()`, `set_margin_start()` and friends. A `GtkBox` no longer decides that for its
children; the children decide it for themselves.

Nothing is destroyed by removing it: a widget taken out of its parent stays alive as long as PHP
holds it, and can go somewhere else.

## 3. Signals carry no user data

php-gtk3 passes extra arguments through `connect()`, which arrive after the signal's own
arguments. php-gtk4 does not: `connect()` takes exactly a signal name and a callable, and PHP
closures already carry everything they need.

```php
$count = 0;
$button->connect('clicked', function (GtkButton $button) use (&$count): void {
    $count++;                       // captured, not passed
    $button->set_label("clicked $count");
});
```

The handler is passed the object that emitted the signal, then the signal's own arguments, and
its return value is the signal's return value where the signal has one. `connect()` answers with
a handler id for `disconnect()`.

A Throwable thrown inside a handler never unwinds through GTK: it goes to the handler installed
with `Gtk::set_exception_handler()`, or is reported as a PHP warning. `Gtk::set_exception_mode()`
decides whether the program then carries on (`Log`, the default) or the loop stops and the
Throwable reaches your own `try` (`Rethrow`) — see README.md "Design".

## 4. Dialogs answer in a callback

`GtkDialog::run()` is gone, and with it the nested main loop that made a dialog look synchronous.
GTK 4.10's dialogs are asynchronous: you ask, and the answer arrives later.

```php
use Gtk4\GAsyncResult;
use Gtk4\GtkAlertDialog;

$dialog = new GtkAlertDialog();
$dialog->set_message('Delete this file?');
$dialog->set_buttons(['Cancel', 'Delete']);
$dialog->set_cancel_button(0);
$dialog->choose($window, null, function (GtkAlertDialog $d, GAsyncResult $result) use ($file): void {
    if ($d->choose_finish($result) === 1) {   // the index in set_buttons()
        unlink($file);
    }
});
// execution continues here immediately
```

This is the single change that most affects a port's structure: code that used to read top to
bottom around a `run()` has to be split at the question. The same shape applies to
`GtkFileDialog`, `GtkColorDialog`, `GtkFontDialog` and `GtkPrintDialog`, and to every other
`*_async()` method — the callback receives the source object and a `GAsyncResult`, and the
matching `*_finish()` call turns it into a value or throws a `Gtk4\GError`.

## 5. Lists are models and factories, not rows in a widget

`GtkTreeView`, `GtkTreeStore`, `GtkListStore` and `GtkTreeIter` are deprecated in GTK 4 and not
bound. Data lives in a `GListModel`; a view builds widgets for the items it can see.

```php
use Gtk4\GListStore;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListView;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\PhpValue;

$store = new GListStore(PhpValue::class);
$store->append(new PhpValue(['name' => 'Ada Lovelace']));   // any PHP value

$factory = new GtkSignalListItemFactory();
$factory->connect('setup', function ($f, GtkListItem $item): void {
    $item->set_child(new GtkLabel());          // build a row widget
});
$factory->connect('bind', function ($f, GtkListItem $item): void {
    $label = $item->get_child();
    $row = $item->get_item();
    if ($label instanceof GtkLabel && $row instanceof PhpValue) {
        $label->set_text($row->get_value()['name']);   // fill it in
    }
});

$view = new GtkListView(new GtkSingleSelection($store), $factory);
```

`setup` runs once per *recycled* widget, `bind` every time one is pointed at another item — so a
list of a million rows builds a handful of widgets. Sorting and filtering are models too
(`GtkSortListModel`, `GtkFilterListModel`), stacked in front of the store rather than done inside
the view. For a short list that does not need recycling, `GtkListBox::bind_model()` is the
simpler shape.

`PhpValue` is how a PHP value gets into a `GListModel`; a `GtkStringList` holds plain strings.

## 6. Widgets show paintables, not pixbufs

`GdkPixbuf` is still there for decoding, encoding and pixel-pushing, but the widgets no longer
take one: they take a `GdkPaintable`. Bytes cross through a texture.

```php
use Gtk4\GdkTexture;
use Gtk4\GtkPicture;

$picture = new GtkPicture();
$picture->set_paintable(GdkTexture::new_from_bytes(file_get_contents('photo.png')));
```

`GtkImage` is for icons and small fixed-size images; `GtkPicture` is for pictures that scale with
the widget. An icon is best named rather than loaded — `set_from_gicon(new GThemedIcon('folder'))`
leaves the theme to find it at the right size. A pixbuf you have already built becomes a texture
through its bytes: `new GdkMemoryTexture(w, h, $format, $pixbuf->read_pixel_bytes(), $stride)`,
which `examples/GdkPixbuf.php` does.

## What to do first

1. Get the application running with an empty window (§1) — nothing else can be tested until the
   loop is right.
2. Rebuild the widget tree (§2). Keep it dumb; leave the behaviour for later.
3. Reconnect the signals (§3), which is mostly mechanical once the user data is gone.
4. Split the code around every dialog (§4). This is the part that takes the longest.
5. Convert the tree views last (§5) — they are the biggest single rewrite, and everything else
   has to work first.

## Where to look

- `docs/GTK3-MAP.md` — every php-gtk3 class, its GTK 4 replacement and whether php-gtk4 binds it.
- `examples/` — one runnable page per bound class; `bin/php-gtk4 examples/demo.php` shows them all.
- `examples/notes/` — a small complete application (menus, list models, autosave) written the
  way this page recommends.
- README.md "Design" — why the binding is shaped like this, including what was deliberately not
  carried over.
