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
slideshow. The sidebar shows the current section only and scrolls it (`GtkScrolledWindow`, bound in wave 1 —
before that, the sections were what kept it on screen); `Demo::SECTIONS` is the grouping and every
registered class must appear in it exactly once.

Each page opens on what its class does rather than printing about it. A window still holds one
child, so a page is usually a Pango-markup `GtkLabel` or a cairo `GtkDrawingArea`, sometimes inside
a `GtkButton` so clicking anywhere advances it.

The `$win` a page receives is the *application's* window. Reading it is fine; taking it over is not
— a page that vetoed its `close-request` left the application with no working close button. A page
that wants a window of its own creates one, as `GtkWindow.php` does. The main window is primary:
closing it quits, however many toplevels a page has opened.

To step through one with a debugger, open its file in VS Code and press F5 — the "Example: the open
file's page" configuration runs `demo.php <that class>` under Xdebug (`docs/CONTRIBUTING.md`
"Debugging"); `bin/php-gtk4-debug examples/demo.php GtkBox` is the same thing from a terminal.

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

## Layout

| File | Shows |
| ---- | ----- |
| [GtkGrid.php](GtkGrid.php) | rows and columns - attach() a child to a cell with a span |
| [GtkPaned.php](GtkPaned.php) | two children and a draggable divider between them |
| [GtkFrame.php](GtkFrame.php) | a border with an optional caption - string or widget |
| [GtkOverlay.php](GtkOverlay.php) | widgets stacked on top of one child - badges, toasts, corner labels |
| [GtkRevealer.php](GtkRevealer.php) | animate a child in and out - reveal_child asks, child_revealed answers |
| [GtkRevealerTransitionType.php](GtkRevealerTransitionType.php) | how a GtkRevealer animates - slide, swing, crossfade or nothing |
| [GtkFixed.php](GtkFixed.php) | children at pixel coordinates - put() and move(), no layout |
| [GtkSeparator.php](GtkSeparator.php) | a line between things - horizontal between rows, vertical between columns |
| [GtkSizeGroup.php](GtkSizeGroup.php) | widgets that agree on a width, a height or both |
| [GtkSizeGroupMode.php](GtkSizeGroupMode.php) | which dimension a size group equalises - none, width, height or both |

## Stacks & tabs

| File | Shows |
| ---- | ----- |
| [GtkStack.php](GtkStack.php) | one child visible at a time, switched with a transition |
| [GtkStackPage.php](GtkStackPage.php) | the per-child record a stack keeps: name, title, icon, attention flag |
| [GtkStackSwitcher.php](GtkStackSwitcher.php) | a row of toggle buttons, one per titled page of a stack |
| [GtkStackSidebar.php](GtkStackSidebar.php) | a vertical list of page titles beside a stack |
| [GtkStackTransitionType.php](GtkStackTransitionType.php) | how a stack animates from one page to the next - every case in turn |
| [GtkNotebook.php](GtkNotebook.php) | tabbed pages - append_page(), next_page(), set_tab_pos() |
| [GtkNotebookPage.php](GtkNotebookPage.php) | the per-child record a notebook keeps, reachable through get_page() |
| [GtkPackType.php](GtkPackType.php) | Start or End - which end of the tab row an action widget sits at |
| [GtkPositionType.php](GtkPositionType.php) | Left, Right, Top, Bottom - the tabs of a notebook walk around every side |

## Scrolling

| File | Shows |
| ---- | ----- |
| [GtkScrolledWindow.php](GtkScrolledWindow.php) | the container that makes its child scrollable |
| [GtkViewport.php](GtkViewport.php) | the adapter that makes any widget scrollable |
| [GtkScrollable.php](GtkScrollable.php) | the interface between a scrolled window and what it scrolls |
| [GtkAdjustment.php](GtkAdjustment.php) | a bounded value with increments - the model behind scrollbars and scales |
| [GtkPolicyType.php](GtkPolicyType.php) | when a scrolled window shows its scrollbars |
| [GtkCornerType.php](GtkCornerType.php) | which corner the scrolled child sits in - the scrollbars take the others |
| [GtkScrollablePolicy.php](GtkScrollablePolicy.php) | minimum or natural size request of a scrollable along an axis |

## Text input

| File | Shows |
| ---- | ----- |
| [GtkEntry.php](GtkEntry.php) | the single-line text entry - text, icons, progress and the GtkEditable API |
| [GtkEditable.php](GtkEditable.php) | the text-editing interface every entry, password entry and spin button implement |
| [GtkEntryBuffer.php](GtkEntryBuffer.php) | the text model behind an entry - shared by two entries, editable from the outside |
| [GtkPasswordEntry.php](GtkPasswordEntry.php) | the entry for secrets - hidden text, a peek icon, GtkEditable underneath |
| [GtkEntryIconPosition.php](GtkEntryIconPosition.php) | which of an entry's two icon slots you mean - Primary or Secondary |
| [GtkInputHints.php](GtkInputHints.php) | the bitfield an entry hands to input methods - spellcheck, casing, emoji, OSK |
| [GtkInputPurpose.php](GtkInputPurpose.php) | what kind of text an entry is for - digits, email, phone, password … |
| [GtkImageType.php](GtkImageType.php) | where an image's pixels come from - empty, icon name, GIcon or paintable |
| [GtkAccessiblePlatformState.php](GtkAccessiblePlatformState.php) | Focusable / Focused / Active - what an entry tells the accessibility backend |

## Buttons & ranges

| File | Shows |
| ---- | ----- |
| [GtkCheckButton.php](GtkCheckButton.php) | check boxes, radio groups via set_group(), and the inconsistent state |
| [GtkToggleButton.php](GtkToggleButton.php) | a button that stays pressed; set_group() makes a radio bar |
| [GtkSpinButton.php](GtkSpinButton.php) | a number entry with arrows - ranges, increments, digits, wrap and spin() |
| [GtkSpinButtonUpdatePolicy.php](GtkSpinButtonUpdatePolicy.php) | Always parses anything typed, IfValid keeps the last valid value |
| [GtkSpinType.php](GtkSpinType.php) | the directions spin() can move a spin button in |
| [GtkRange.php](GtkRange.php) | bounds, increments, inversion and fill level, shown on a GtkScale |
| [GtkScale.php](GtkScale.php) | a slider with marks, a printed value and an origin highlight |
| [GtkProgressBar.php](GtkProgressBar.php) | set_fraction() when you know how far, pulse() when you do not |
| [GtkSpinner.php](GtkSpinner.php) | start() and stop() - the busy indicator, invisible when idle |

## Images & dates

| File | Shows |
| ---- | ----- |
| [GtkImage.php](GtkImage.php) | the widget that shows an icon - by name, size or pixel size |
| [GtkPicture.php](GtkPicture.php) | shows a GdkPaintable at its natural size, or fitted |
| [GdkPaintable.php](GdkPaintable.php) | the interface behind everything GTK can draw as an image |
| [GdkPaintableFlags.php](GdkPaintableFlags.php) | what a paintable promises never to change - a GFlags bitfield |
| [GtkContentFit.php](GtkContentFit.php) | how a GtkPicture fits its paintable into the allocation |
| [GtkIconSize.php](GtkIconSize.php) | built-in icon sizes - Inherit, Normal, Large |
| [GtkCalendar.php](GtkCalendar.php) | one month of a Gregorian calendar, with a selected day |
| [GdkDragAction.php](GdkDragAction.php) | what a drop is allowed to do - a GFlags bitfield |

## Choices

| File | Shows |
| ---- | ----- |
| [GtkDropDown.php](GtkDropDown.php) | pick one item out of a list model |
| [GtkStringList.php](GtkStringList.php) | a GListModel that wraps an array of strings |
| [GtkStringObject.php](GtkStringObject.php) | one string as a GObject - the item type of GtkStringList |
| [GtkStringFilterMatchMode.php](GtkStringFilterMatchMode.php) | where a search string may sit inside the text |

## Input controllers

| File | Shows |
| ---- | ----- |
| [GtkEventController.php](GtkEventController.php) | the abstract base - name, propagation phase/limit, widget and the current event |
| [GtkEventControllerKey.php](GtkEventControllerKey.php) | keyboard input - key-pressed / key-released with keyval, keycode and modifiers |
| [GtkEventControllerMotion.php](GtkEventControllerMotion.php) | pointer enter / motion / leave with widget-relative coordinates |
| [GtkEventControllerScroll.php](GtkEventControllerScroll.php) | wheel and touchpad scrolling as dx/dy deltas, with kinetic deceleration |
| [GtkEventControllerFocus.php](GtkEventControllerFocus.php) | keyboard focus enter / leave, is_focus versus contains_focus |
| [GtkEventControllerLegacy.php](GtkEventControllerLegacy.php) | every raw GdkEvent through one signal - typed subclass, position, time, modifiers |
| [GtkPropagationPhase.php](GtkPropagationPhase.php) | Capture / Target / Bubble / None - when a controller sees an event |
| [GtkPropagationLimit.php](GtkPropagationLimit.php) | SameNative or None - events from other native surfaces |
| [GtkEventControllerScrollFlags.php](GtkEventControllerScrollFlags.php) | VERTICAL \| HORIZONTAL \| DISCRETE \| KINETIC - OR-able int constants |
| [GdkScrollUnit.php](GdkScrollUnit.php) | Wheel notches or Surface pixels - the unit of a scroll delta |

## Gestures

| File | Shows |
| ---- | ----- |
| [GtkGesture.php](GtkGesture.php) | the abstract base of every gesture: state, bounding box, groups |
| [GtkGestureSingle.php](GtkGestureSingle.php) | the base of every one-pointer gesture: button, exclusive, touch-only |
| [GtkGestureClick.php](GtkGestureClick.php) | presses and releases, counted into single/double/triple clicks |
| [GtkGestureDrag.php](GtkGestureDrag.php) | press, move, release - a start point and an offset |
| [GtkGestureLongPress.php](GtkGestureLongPress.php) | press and hold without moving |
| [GtkGestureSwipe.php](GtkGestureSwipe.php) | a drag judged by its speed at release |
| [GtkGesturePan.php](GtkGesturePan.php) | a drag locked to one axis |
| [GtkGestureZoom.php](GtkGestureZoom.php) | two-finger pinch, as a scale factor |
| [GtkGestureRotate.php](GtkGestureRotate.php) | two fingers turning around each other |
| [GtkPanDirection.php](GtkPanDirection.php) | which way a GtkGesturePan went |
| [GtkEventSequenceState.php](GtkEventSequenceState.php) | what a gesture has decided about an event sequence |

## Events

| File | Shows |
| ---- | ----- |
| [GdkEvent.php](GdkEvent.php) | the base class of everything the windowing system reports |
| [GdkKeyEvent.php](GdkKeyEvent.php) | a key press or release - keyval, keycode, layout, level and accelerator matching |
| [GdkButtonEvent.php](GdkButtonEvent.php) | a mouse button press or release - which button, where, with what held |
| [GdkScrollEvent.php](GdkScrollEvent.php) | a wheel click or a touchpad scroll - direction, deltas, unit and stop |
| [GdkCrossingEvent.php](GdkCrossingEvent.php) | the pointer entered or left a widget - mode, detail and focus |
| [GdkFocusEvent.php](GdkFocusEvent.php) | the keyboard focus entered or left the window - get_in() |
| [GdkTouchEvent.php](GdkTouchEvent.php) | a finger on a touchscreen - begin, update, end, and pointer emulation |
| [GdkTouchpadEvent.php](GdkTouchpadEvent.php) | a multi-finger touchpad gesture - phase, fingers, deltas, pinch scale and angle |
| [GdkPadEvent.php](GdkPadEvent.php) | a drawing-tablet pad button, ring or strip - button, axis value, group and mode |
| [GdkGrabBrokenEvent.php](GdkGrabBrokenEvent.php) | a pointer or keyboard grab was taken away - get_implicit() |
| [GdkEventSequence.php](GdkEventSequence.php) | one handle per finger - the key every per-touch gesture method takes |
| [GdkEventType.php](GdkEventType.php) | which kind of event a GdkEvent is - the case of every real event as it arrives |
| [GdkScrollDirection.php](GdkScrollDirection.php) | which way a scroll event goes - wheel clicks versus smooth deltas |
| [GdkCrossingMode.php](GdkCrossingMode.php) | why the pointer entered or left - motion, grabs, state changes, touch, device switch |
| [GdkNotifyType.php](GdkNotifyType.php) | how the widget left and the widget entered are related - ancestor, inferior, nonlinear |
| [GdkTouchpadGesturePhase.php](GdkTouchpadGesturePhase.php) | where in a touchpad gesture an event sits - begin, update, end, cancel |
| [GdkKeyMatch.php](GdkKeyMatch.php) | how well a key event matches an accelerator - exact, partial or not at all |

## Menus

| File | Shows |
| ---- | ----- |
| [GMenuModel.php](GMenuModel.php) | the read side of a menu: items, attributes and links |
| [GMenu.php](GMenu.php) | the writable menu model |
| [GMenuItem.php](GMenuItem.php) | one menu entry built by hand |
| [GtkPopover.php](GtkPopover.php) | a bubble anchored to a widget |
| [GtkPopoverMenu.php](GtkPopoverMenu.php) | a popover that renders a GMenuModel |
| [GtkPopoverMenuBar.php](GtkPopoverMenuBar.php) | a menu bar built from a GMenuModel |
| [GtkMenuButton.php](GtkMenuButton.php) | a button that opens a popover |
| [GtkHeaderBar.php](GtkHeaderBar.php) | the title bar with widgets in it |
| [GtkApplicationWindow.php](GtkApplicationWindow.php) | a GtkWindow that is also an action map |
| [GtkArrowType.php](GtkArrowType.php) | which way a GtkMenuButton arrow points |
| [GtkPopoverMenuFlags.php](GtkPopoverMenuFlags.php) | how a GtkPopoverMenu opens submenus: sliding or nested |
