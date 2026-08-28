# Examples

One demo application, one source file per class:

```sh
bin/php-gtk4 examples/demo.php              # the application
bin/php-gtk4 examples/demo.php GtkButton    # only that class, in a window of its own
bin/php-gtk4 examples/demo.php --list       # what is available
```

Every `<Class>.php` ends in `return Demo::page(...)` and does nothing else — it *describes* a demo
rather than running one. `demo.php` requires them all, so a file that ran itself would fire on
import. `ExampleTest` enforces that.

The application is a header row, a sidebar and a content area, all `GtkBox`. That container is what
made it an application at all: before it, a `GtkWindow` held exactly one child and a `GtkButton` one
more, so navigation could not sit next to the thing it navigates and the demo had to be a timed
slideshow. The sidebar shows the current section only, because `GtkScrolledWindow` is not bound yet
and that many buttons do not fit; `Demo::SECTIONS` is the grouping and every registered class must appear
in it exactly once.

Each page opens on what its class does rather than printing about it. A window still holds one
child, so a page is usually a Pango-markup `GtkLabel` or a cairo `GtkDrawingArea`, sometimes inside
a `GtkButton` so clicking anywhere advances it.

The `$win` a page receives is the *application's* window. Reading it is fine; taking it over is not
— a page that vetoed its `close-request` left the application with no working close button. A page
that wants a window of its own creates one, as `GtkWindow.php` does. The main window is primary:
closing it quits, however many toplevels a page has opened.

`bootstrap.php` is the shared harness — `Demo::page()`, `pages()`, `showcase()`, `single()`,
`run()`, `status()`, plus `label()`, `canvas()`, `bars()` and the sample dataset. It only declares
(GTK is initialised lazily), so requiring it has no side effects.

Two pages own a main loop and so do more when run on their own than the application can show:
`ExceptionMode` needs a `try`/`catch` around `run()` to demonstrate `Rethrow`, and `GMainLoop`
drives a window with no `GtkApplication`. Both pass a standalone override to `Demo::page()`, which
`demo.php <Class>` uses.

## Widgets

| File | Shows |
| ---- | ----- |
| [GtkBox.php](GtkBox.php) | the layout container — what holds more than one widget |
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

## Styling

| File | Shows |
| ---- | ----- |
| [GtkCssProvider.php](GtkCssProvider.php) | three stylesheets swapped under a live label |
| [GtkStyleProvider.php](GtkStyleProvider.php) | attaching and detaching a provider on the fly |
| [GtkStyleProviderPriority.php](GtkStyleProviderPriority.php) | two providers, one class, who wins |
| [GtkCssSection.php](GtkCssSection.php) | broken CSS reporting itself through `parsing-error` |
| [GdkDisplay.php](GdkDisplay.php) | the display everything above is attached to |

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
