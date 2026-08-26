# Examples

One file per PHP-visible class, named after it, runnable on its own:

```sh
bin/php-gtk4 examples/GtkButton.php
```

Each one opens a window and *shows* what the class does rather than printing about it. GTK 4 has no
container widget bound yet, so a window holds exactly one child — the two surfaces the examples draw on
are a Pango-markup `GtkLabel` and a cairo `GtkDrawingArea`, sometimes wrapped in a `GtkButton` so that
clicking anywhere advances the demo.

`bootstrap.php` is the shared harness (`Demo::run()`, `Demo::label()`, `Demo::canvas()`, `Demo::bars()`
and the sample dataset). It only declares — GTK is initialised lazily — so requiring it has no side
effects. `tests/ExampleTest.php` fails if a registered class has no file here.

## Widgets

| File | Shows |
| ---- | ----- |
| [GtkWidget.php](GtkWidget.php) | the inherited API; the button walks the alignment cases, so it moves |
| [GtkWindow.php](GtkWindow.php) | title, default size, and a `close-request` veto you can toggle |
| [GtkButton.php](GtkButton.php) | `clicked`, a widget child, `activate()` |
| [GtkLabel.php](GtkLabel.php) | Pango markup, wrapping, and live `get_selection_bounds()` |
| [GtkDrawingArea.php](GtkDrawingArea.php) | a draw func plus `queue_draw()` — a sweeping clock hand |
| [CairoContext.php](CairoContext.php) | a sampler of every cairo call: paths, sources, matrix, text |

## Application and actions

| File | Shows |
| ---- | ----- |
| [Gtk.php](Gtk.php) | `init()` and the callback exception handler catching a live throw |
| [GtkApplication.php](GtkApplication.php) | the main loop and the window list, growing as you click |
| [GSimpleAction.php](GSimpleAction.php) | stateless, parameterised and stateful actions |
| [GAction.php](GAction.php) | the interface; a disabled action visibly stops responding |
| [GActionMap.php](GActionMap.php) | `add`/`lookup`/`remove_action` step by step |
| [GActionGroup.php](GActionGroup.php) | `has_action`, `list_actions`, `activate_action` by name |
| [GApplicationFlags.php](GApplicationFlags.php) | why flags are a constant class, drawn as a bitmask |

## Objects, values and errors

| File | Shows |
| ---- | ----- |
| [GObject.php](GObject.php) | one property written three ways, each emitting `notify` |
| [GParamSpec.php](GParamSpec.php) | the metadata a `notify` handler is handed |
| [PhpValue.php](PhpValue.php) | PHP data inside a GObject, held by reference |
| [GdkRGBA.php](GdkRGBA.php) | parsed colour swatches and boxed value semantics |
| [GdkRectangle.php](GdkRectangle.php) | `intersect`, `union` and a moving `contains_point` probe |
| [GdkTexture.php](GdkTexture.php) | a PNG built by hand, decoded, saved and re-read |
| [GError.php](GError.php) | what a failing C call throws, domain and code included |
| [ExceptionMode.php](ExceptionMode.php) | `Log` survives; `Rethrow` comes back out of `run()` |

## Lists

| File | Shows |
| ---- | ----- |
| [GListModel.php](GListModel.php) | the read interface and `items-changed` |
| [GListStore.php](GListStore.php) | the whole editing API, one operation per click |
| [GtkFilter.php](GtkFilter.php) | why `changed()` exists — state the model cannot see |
| [GtkCustomFilter.php](GtkCustomFilter.php) | a PHP predicate called from C |
| [GtkFilterListModel.php](GtkFilterListModel.php) | swapping the filter and the source model |
| [GtkFilterChange.php](GtkFilterChange.php) | what the three hints mean |
| [GtkSorter.php](GtkSorter.php) | the sorter side of the same story |
| [GtkCustomSorter.php](GtkCustomSorter.php) | a PHP comparator called from C |
| [GtkSortListModel.php](GtkSortListModel.php) | stacking store → filter → sort |
| [GtkSorterChange.php](GtkSorterChange.php) | `Inverted` versus `Different` |

## Loop and enums

| File | Shows |
| ---- | ----- |
| [GLib.php](GLib.php) | idle and timeout sources, and removing one |
| [GMainLoop.php](GMainLoop.php) | a window with no `GtkApplication` at all |
| [GtkAlign.php](GtkAlign.php) | every alignment case, applied on a timer |
| [GtkOrientation.php](GtkOrientation.php) | the two cases, drawn |
