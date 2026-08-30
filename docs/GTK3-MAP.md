# php-gtk3 → php-gtk4 API map

Inventory of every class the old **php-gtk3** extension exposes
(`/mnt/share/dev/code/php-gtk3/src/`), its GTK 4 replacement, and whether php-gtk4 already
implements it.

Source of truth: php-gtk3 class headers (158 headers, ~2400 exported methods) vs.
`src/gtk4.stub.php` + the MINIT registration block in `src/gtk4.cpp`.

Status column regenerated 2026-08-30 by `gen/map-status.php` (run by `gen/gir.php --install`);
the notes are hand-written and may lag.

## Legend

| Mark | Meaning |
| --- | --- |
| ✅ | implemented in php-gtk4 (registered in MINIT, declared in the stub, tested) |
| 🟡 | partially implemented — class exists, only a subset of the gtk3 methods is there |
| ❌ | not implemented yet — the GTK 4 API exists and is a planned port target |
| ⛔ | **gone in GTK 4** — no direct replacement; the row names what to use instead |
| 🧩 | out of scope for now (separate library / later milestone: WebKit, GtkSourceView, Glade, …) |

## Status at a glance

| | classes | gtk3 methods behind them |
| --- | ---: | ---: |
| ✅ implemented | 49 | — |
| 🟡 partial | 1 | — |
| ❌ to port (GTK 4 equivalent exists) | 44 | ~1750 |
| ⛔ removed in GTK 4 | 41 | ~520 |
| 🧩 out of scope / later milestone | 5 | ~130 |

php-gtk4 currently declares (2026-08-28, 65 names): `CairoContext`, `ExceptionMode`, `GAction`,
`GActionGroup`, `GActionMap`, `GApplication`, `GApplicationFlags`, `GAsyncResult`, `GCancellable`, `GError`,
`GLib`, `GListModel`, `GListStore`, `GMainLoop`, `GObject`, `GParamSpec`, `GSimpleAction`, `GdkDisplay`,
`GdkMemoryFormat`, `GdkModifierType`, `GdkRGBA`, `GdkRectangle`, `GdkTexture`, `Gtk`, `GtkAlertDialog`,
`GtkAlign`, `GtkApplication`, `GtkApplicationInhibitFlags`, `GtkBaselinePosition`, `GtkBox`, `GtkButton`,
`GtkCssProvider`, `GtkCssSection`, `GtkCustomFilter`, `GtkCustomSorter`, `GtkDirectionType`, `GtkDrawingArea`,
`GtkFilter`, `GtkFilterChange`, `GtkFilterListModel`, `GtkFilterMatch`, `GtkJustification`, `GtkLabel`,
`GtkNaturalWrapMode`, `GtkOrdering`, `GtkOrientable`, `GtkOrientation`, `GtkOverflow`, `GtkPickFlags`,
`GtkRequisition`, `GtkRoot`, `GtkSizeRequestMode`, `GtkSortListModel`, `GtkSorter`, `GtkSorterChange`,
`GtkSorterOrder`, `GtkStateFlags`, `GtkStyleProvider`, `GtkStyleProviderPriority`, `GtkTextDirection`,
`GtkWidget`, `GtkWindow`, `PangoEllipsizeMode`, `PangoWrapMode`, `PhpValue`. Everything else in this document
is open work.

---

## Core / GObject layer (`php-gtk3/src/G/`)

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `GObject` | 13 | `GObject` (unchanged) | ✅ | `connect`, `connect_after`, `emit`, `handler_disconnect`, `get_property`, `set_property` + `$obj->prop` access (hand-written, `src/core/object`). |
| `GApplication` | 37 | `GApplication` | ✅ | Generated (wave 0): the whole GTK 4 method set (hold/release, `run` with argv, registration, actions via `GActionMap`/`GActionGroup`). `open`, `send_notification`, option groups wait for `GFile`/`GNotification`/`GOptionGroup` (gen/report.md). |
| `GIcon` | 4 | `GIcon` / `GThemedIcon` / `GFileIcon` | ❌ | Needed once `GtkImage`/`GtkButton::set_icon_name` land. |
| — | — | `GParamSpec` | ✅ | New in php-gtk4, no php-gtk3 counterpart. |
| — | — | `GVariant` (as plain PHP values) | ✅ | New: `src/core/variant.cpp`, used by `GSimpleAction`. |
| — | — | `GAction` / `GActionMap` / `GActionGroup` / `GSimpleAction` | ✅ | New: GTK 4 replaces `GtkAction`/`GtkUIManager` with the `GAction` stack. |

## Gtk top-level (`php-gtk3/src/Gtk/Gtk.h`)

| php-gtk3 method | GTK 4 replacement | php-gtk4 | Notes |
| --- | --- | :---: | --- |
| `Gtk::main_iteration`, `Gtk::events_pending`, `Gtk::main_do_event` | `GMainContext` iteration | ⛔ | GTK 4 has no `gtk_main()`. php-gtk4 policy: `GtkApplication::run()` or `GMainLoop::run()`. |
| `Gtk::timeout_add`, `Gtk::source_remove` | `g_timeout_add` / `g_source_remove` | ✅ | `GLib::timeout_add()`, `GLib::idle_add()`, `GLib::source_remove()`. |
| `Gtk::get_major_version` / `minor` / `micro` | `gtk_get_major_version` … | ❌ | php-gtk4 exposes `Gtk4\VERSION`, `BUILD_INFO`, `FEATURES` but not the runtime GTK version triple. |
| `Gtk::show_uri_on_window` | `gtk_show_uri` / `GtkUriLauncher` | ❌ | `gtk_show_uri` is deprecated in 4.10; port as `GtkUriLauncher`. |
| `Gtk::is_destroyed` | — | ⛔ | php-gtk3 handle bookkeeping; php-gtk4 uses a toggle-ref hold + qdata identity instead. |
| — | `Gtk::init`, `set_exception_handler`, `set_exception_mode` | ✅ | New in php-gtk4 (`Gtk4\ExceptionMode`). |
| — | `GMainLoop` (`run`/`quit`/`is_running`) | ✅ | New class. |

## Widgets — base and containers

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `GtkWidget` | 257 | `GtkWidget` | ✅ | Generated (wave 0): the whole GTK 4 method set (125 methods — layout, margins, align/expand, focus, tree navigation, state flags, tooltips, CSS classes, actions). The ~230 remaining php-gtk3 methods are GTK 3-only (`GdkEvent` unions, `size_allocate` w/ `GtkAllocation`, style properties, `gtk_widget_destroy`, DnD, accel closures) or need types outside the closure (`GtkLayoutManager`, `GdkDisplay`, controllers — later waves). |
| `GtkContainer` | 38 | — | ⛔ | **Removed in GTK 4.** Children are set per-widget (`set_child`) or via container-specific API (`GtkBox::append`, `GtkGrid::attach`). `set_child()` is already on `GtkWindow`/`GtkButton`. |
| `GtkBin` | 2 | — | ⛔ | Removed; folded into `set_child`/`get_child`. |
| `GtkBox` | 14 | `GtkBox` | ✅ | `append`, `prepend`, `insert_child_after`, `remove`, `set_spacing`, `set_homogeneous`, `set_orientation`, `get_children`. `pack_start`/`pack_end` are gone, and GTK inserts rather than reparents — move = `remove()` then insert. |
| `GtkHBox` / `GtkVBox` | 1 + 1 | `GtkBox` w/ orientation | ⛔ | Removed in GTK 3.2 already; use `new GtkBox(GtkOrientation::Horizontal, 0)`. |
| `GtkGrid` | 21 | `GtkGrid` | ✅ | Largely unchanged (`attach`, `attach_next_to`, `insert_row/column`). |
| `GtkTable` | 15 | `GtkGrid` | ⛔ | Removed; port callers to `GtkGrid`. |
| `GtkFixed` | 3 | `GtkFixed` | ✅ | `put`/`move` survive; `gtk_fixed_get_children` does not. |
| `GtkLayout` | 10 | `GtkFixed` + `GtkScrolledWindow` | ⛔ | Removed in GTK 4. |
| `GtkAlignment` | 4 | `halign`/`valign`/`margin-*` on `GtkWidget` | ⛔ | Removed in GTK 3.14. |
| `GtkMisc` | 0 | `halign`/`valign` | ⛔ | Removed. |
| `GtkAspectFrame` | 2 | `GtkAspectFrame` | ❌ | Still exists, no longer a `GtkBin`. |
| `GtkFrame` | 9 | `GtkFrame` | ✅ | `set_shadow_type` removed (CSS instead). |
| `GtkPaned` | 9 | `GtkPaned` | ✅ | `add1`/`add2` → `set_start_child`/`set_end_child`. |
| `GtkOverlay` | 5 | `GtkOverlay` | ✅ | `add_overlay`, `set_child`. |
| `GtkRevealer` | 8 | `GtkRevealer` | ✅ | Unchanged apart from `set_child`. |
| `GtkStack` | 22 | `GtkStack` | ✅ | Unchanged apart from `GtkStackPage` accessors. |
| `GtkStackSwitcher` | 5 | `GtkStackSwitcher` | ✅ | |
| `GtkStackSidebar` | 3 | `GtkStackSidebar` | ✅ | |
| `GtkScrolledWindow` | 12 | `GtkScrolledWindow` | ✅ | `add_with_viewport` gone → `set_child`. |
| `GtkViewport` | 9 | `GtkViewport` | ✅ | `set_shadow_type` gone. |
| `GtkFlowBox` | 30 | `GtkFlowBox` | ❌ | Mostly unchanged. |
| `GtkFlowBoxChild` | 4 | `GtkFlowBoxChild` | ❌ | |
| `GtkListBox` | 28 | `GtkListBox` | ❌ | Still current; the newer `GtkListView` is the recommended alternative for large models. |
| `GtkListBoxRow` | 2 | `GtkListBoxRow` | ❌ | |
| `GtkNotebook` | 40 | `GtkNotebook` | ✅ | `set_tab_label`, `append_page` etc. survive. |
| `GtkExpander` | 18 | `GtkExpander` | ❌ | |
| `GtkActionBar` | 5 | `GtkActionBar` | ❌ | |
| `GtkHeaderBar` | 15 | `GtkHeaderBar` | ✅ | `pack_start`/`pack_end`/`set_title_widget`; `set_title`/`set_subtitle` removed. |
| `GtkEventBox` | 5 | — | ⛔ | Removed; every `GtkWidget` takes event controllers now (`GtkGestureClick`, `GtkEventControllerMotion`). |
| `GtkSizeGroup` | 6 | `GtkSizeGroup` | ✅ | |
| `GtkSeparator` | 1 | `GtkSeparator` | ✅ | |
| `GtkDrawingArea` | 1 | `GtkDrawingArea` | ✅ | `set_draw_func` replaces the `draw` signal; needs a cairo binding. |
| `GtkRequisition` | 1 | `GtkRequisition` | ⛔ | Superseded by `measure()` / `GtkRequisition` is boxed-only. |

## Widgets — controls

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `GtkWindow` | 85 | `GtkWindow` | ✅ | Generated (wave 0): 58 methods (title, sizes, modal/resizable/decorated/deletable, fullscreen/maximize, transient-for, default widget, focus, hide-on-close, …). **Removed in GTK 4:** `move`/`resize`/`get_position`/`get_size`, `set_position`, `set_type_hint`, `iconify`/`stick`/`set_keep_above`, `set_icon*`, `get_screen`, `add_accel_group`. |
| `GtkApplicationWindow` | 2 | `GtkApplicationWindow` | ✅ | Not registered yet; `GtkWindow` takes the application in its constructor instead. |
| `GtkButton` | 29 | `GtkButton` | ✅ | Generated (wave 0): 16 methods (label, child, icon name, has-frame, use-underline, static `new_with_label`/`new_with_mnemonic`/`new_from_icon_name`). **Removed:** `set_image`/`set_always_show_image`/`set_relief`/`set_alignment`/`set_use_stock`/`get_event_window`, `enter`/`leave`/`pressed`/`released` signals. |
| `GtkToggleButton` | 10 | `GtkToggleButton` | ✅ | `set_inconsistent` kept; `set_mode` removed → use `GtkCheckButton`. |
| `GtkCheckButton` | 6 | `GtkCheckButton` | ✅ | GTK 4 merges `GtkRadioButton` in via `set_group`. |
| `GtkRadioButton` | 9 | `GtkCheckButton::set_group` | ⛔ | Class removed in GTK 4. |
| `GtkColorButton` | 13 | `GtkColorButton` (dep. 4.10 → `GtkColorDialogButton`) | ❌ | Prefer `GtkColorDialogButton` on 4.10+, guarded by `GTK_CHECK_VERSION`. |
| `GtkFontButton` | 16 | `GtkFontButton` (dep. 4.10 → `GtkFontDialogButton`) | ❌ | Same caveat. |
| `GtkAppChooserButton` | 11 | `GtkAppChooserButton` (dep. 4.10) | ❌ | |
| `GtkMenuButton` | 2 | `GtkMenuButton` | ✅ | Takes a `GMenuModel` popover, not a `GtkMenu`. |
| `GtkLabel` | 43 | `GtkLabel` | ✅ | Generated (wave 0): 44 methods (text/markup/mnemonic, `set_use_markup`, ellipsize, justify, wrap (`set_line_wrap` renamed), lines, width chars, xalign/yalign, mnemonic widget, selection, `get_current_uri`). `set_angle`/`set_pattern`/`get_layout` are GTK 3-only or Pango-dependent. |
| `GtkEntry` | 41 | `GtkEntry` (+ `GtkText`) | ✅ | Text handling moved into `GtkEditable`/`GtkText`; `set_icon_from_pixbuf` etc. use `GdkPaintable` now. |
| `GtkEntryBuffer` | 9 | `GtkEntryBuffer` | ✅ | Unchanged. |
| `GtkEntryCompletion` | 27 | `GtkEntryCompletion` (dep. 4.10) | ❌ | Deprecated in GTK 4.10; consider not porting. |
| `GtkComboBox` | 40 | `GtkComboBox` (dep. 4.10 → `GtkDropDown`) | 🟡 | Port to `GtkDropDown`. |
| `GtkComboBoxText` | 11 | `GtkDropDown` + `GtkStringList` | ⛔ | Deprecated 4.10. |
| `GtkSpinner` | 3 | `GtkSpinner` | ✅ | |
| `GtkProgressBar` | 14 | `GtkProgressBar` | ✅ | |
| `GtkAdjustment` | 3 | `GtkAdjustment` | ✅ | |
| `GtkCalendar` | 15 | `GtkCalendar` | ✅ | `get_date`/`select_day` reworked around `GDateTime`. |
| `GtkImage` | 29 | `GtkImage` | ✅ | Pixbuf setters deprecated → `GdkPaintable`/`GdkTexture`, `set_from_icon_name`, `set_from_file`. |
| `GtkStatusbar` | 7 | `GtkStatusbar` (dep. 4.10) | ❌ | |
| `GtkInfoBar` | 14 | `GtkInfoBar` (dep. 4.10) | ❌ | |
| `GtkStatusIcon` | 32 | — | ⛔ | Removed in GTK 4; no tray API. |

## Menus, toolbars, accelerators

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `GtkMenu` | 25 | `GtkPopoverMenu` + `GMenuModel` | ⛔ | `GtkMenu` is gone in GTK 4. |
| `GtkMenuBar` | 5 | `GtkPopoverMenuBar` + `GMenuModel` | ⛔ | |
| `GtkMenuItem` | 19 | `GMenuItem` (model) | ⛔ | |
| `GtkMenuShell` | 14 | — | ⛔ | |
| `GtkCheckMenuItem` | 10 | stateful `GSimpleAction` + menu item | ⛔ | Already expressible: `GSimpleAction` with boolean state ✅. |
| `GtkSeparatorMenuItem` | 1 | menu-model section | ⛔ | |
| `GtkToolbar` | 16 | `GtkBox` w/ `.toolbar` CSS class | ⛔ | Removed in GTK 4. |
| `GtkToolButton` | 11 | `GtkButton` | ⛔ | |
| `GtkToolItem` | 27 | — | ⛔ | |
| `GtkSeparatorToolItem` | 3 | `GtkSeparator` | ⛔ | |
| `GtkButtonBox` | 7 | `GtkBox` | ⛔ | Removed in GTK 4. |
| `GtkAccelGroup` | 1 | `GtkApplication::set_accels_for_action` / `GtkShortcutController` | ⛔ | |

## Dialogs

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `GtkDialog` | 13 | `GtkDialog` (dep. 4.10) | ❌ | `run()` is gone in GTK 4 — dialogs are async (`response` signal / `GtkAlertDialog`). |
| `GtkMessageDialog` | 6 | `GtkAlertDialog` (4.10+) | ✅ | |
| `GtkAboutDialog` | 33 | `GtkAboutDialog` | ❌ | Still current. |
| `GtkFileChooser` / `GtkFileChooserDialog` | 1 + 45 | `GtkFileDialog` (4.10+) | ❌ | Async API; `GtkFileChooserNative` for portals. |
| `GtkFileFilter` | 8 | `GtkFileFilter` | ❌ | |
| `GtkColorChooserDialog` | 6 | `GtkColorDialog` (4.10+) | ❌ | |
| `GtkFontChooserDialog` | 7 | `GtkFontDialog` (4.10+) | ❌ | |
| `GtkAppChooserDialog` | 6 | `GtkAppChooserDialog` (dep. 4.10) | ❌ | |
| `GtkRecentChooserDialog` | 33 | — | ⛔ | Recent-files API removed in GTK 4. |

## Text view

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `GtkTextView` | 63 | `GtkTextView` | ❌ | Largely unchanged; `GdkEvent`-based methods and `add_child_in_window` differ. |
| `GtkTextBuffer` | 69 | `GtkTextBuffer` | ❌ | Unchanged, except pixbuf inserts → `GdkPaintable`. |
| `GtkTextIter` | 83 | `GtkTextIter` | ❌ | Boxed type — port via `src/core/boxed.cpp`. |
| `GtkTextMark` | 7 | `GtkTextMark` | ❌ | |
| `GtkTextTag` | 6 | `GtkTextTag` | ❌ | |
| `GtkTextTagTable` | 6 | `GtkTextTagTable` | ❌ | |
| `GtkWrapMode` | 0 | `GtkWrapMode` enum | ❌ | Becomes a PHP enum in the stub. |

## Tree / list model stack

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `GtkTreeView` | 38 | `GtkColumnView` / `GtkListView` | ❌ | `GtkTreeView` still exists in 4.x but is **deprecated since 4.10**. Recommendation: bind the list-model stack instead. |
| `GtkTreeViewColumn` | 28 | `GtkColumnViewColumn` | ❌ | |
| `GtkTreeModel` | 6 | `GListModel` | ✅ | |
| `GtkListStore` | 25 | `GListStore` / `GtkStringList` | ✅ | |
| `GtkTreeStore` | 17 | `GtkTreeListModel` | ❌ | |
| `GtkTreeIter` | 1 | — | ⛔ | No iterator concept in `GListModel`. |
| `GtkTreeSelection` | 20 | `GtkSelectionModel` (`GtkSingleSelection`, `GtkMultiSelection`) | ❌ | |
| `GtkTreeSortable` | 4 | `GtkSortListModel` + `GtkSorter` | ✅ | |
| `GtkCellRenderer` (+ `Text`, `Toggle`, `Pixbuf`, `Combo`) | 3 + 2 + 7 + 1 + 1 | `GtkListItemFactory` (`GtkSignalListItemFactory`, `GtkBuilderListItemFactory`) | ⛔ | Cell renderers are deprecated in 4.10; the factory model replaces them. |

## Styling / builder / clipboard

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `GtkCssProvider` | 16 | `GtkCssProvider` | ✅ | `load_from_data` signature changed (no length/GError out-param in 4.12+). |
| `GtkStyleContext` | 79 | — | ⛔ | **Deprecated/gutted in GTK 4**; use `GtkWidget::add_css_class` / `remove_css_class` — already ✅ on `GtkWidget`. |
| `GtkWidgetPath` | 39 | — | ⛔ | Removed in GTK 4. |
| `GtkBuilder` | 26 | `GtkBuilder` | ❌ | GTK 4 `.ui` syntax; `connect_signals` replaced by `GtkBuilderScope`. |
| `GtkClipboard` | 26 | `GdkClipboard` | ❌ | Async, `GdkContentProvider`-based. |
| `GtkIconTheme` | 8 | `GtkIconTheme` | ❌ | Returns `GtkIconPaintable` now. |
| `GtkLogSuppression` | 0 | — | ⛔ | php-gtk3-specific helper. |

## Printing

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `GtkPrintSettings` | 74 | `GtkPrintSettings` | ❌ | Unchanged in GTK 4. |
| `GtkPageSetup` | 25 | `GtkPageSetup` | ❌ | Unchanged. |
| `GtkPaperSize` | 24 | `GtkPaperSize` | ❌ | Boxed type. |

## Gdk (`src/Gdk/`)

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `GdkRGBA` | 4 | `GdkRGBA` | ✅ | Boxed type with `parse`, `to_string`, `equal`, `is_opaque` + `r/g/b/a` fields. Richer than php-gtk3's. |
| — | — | `GdkRectangle` | ✅ | New in php-gtk4 (`intersect`, `union`, `contains_point`, `equal`). |
| `GdkDisplay` | 5 | `GdkDisplay` | ✅ | `get_monitors()` returns a `GListModel` in GTK 4. |
| `GdkMonitor` | 5 | `GdkMonitor` | ❌ | `get_geometry`, `get_width_mm`; `get_workarea` removed in GTK 4. |
| `GdkScreen` | 4 | — | ⛔ | Removed in GTK 4; use `GdkDisplay`. |
| `GdkVisual` | 6 | — | ⛔ | Removed in GTK 4. |
| `GdkWindow` | 10 | `GdkSurface` | ❌ | Much smaller API; most methods (`maximize`, `get_children`, `get_default_root_window`) are gone. |
| `GdkDrawable` | 0 | — | ⛔ | Removed long ago. |
| `GdkCursor` | 7 | `GdkCursor` | ❌ | `new_from_name`, `new_from_texture`; `get_cursor_type` removed. |
| `GdkPixbuf` | 21 | `GdkTexture` / `GdkPaintable` | ✅ | GdkPixbuf still exists as a library but GTK 4 widgets want `GdkPaintable`. |
| `GdkPixbufFormat` | 1 | `GdkPixbufFormat` | ❌ | |
| `GdkEvent` + `GdkEventButton/Key/Motion/Scroll/Crossing/Focus/Configure/Touch/Any` | 3 + 8×2 | `GdkEvent` (opaque) + event controllers | ⛔ | **The event unions are gone.** GTK 4 uses `GtkEventControllerKey`, `GtkGestureClick`, `GtkEventControllerMotion`, `GtkEventControllerScroll`, `GtkEventControllerFocus`. Binding these controllers is the port target. |
| `GdkThreads` | 1 | `g_idle_add` | ⛔ | `GLib::idle_add()` ✅ covers it. |
| `Gdk::test_simulate_button` | 1 | — | ⛔ | GTK 3 test API. |

## Out of scope / later milestones

| php-gtk3 class | gtk3 methods | GTK 4 replacement | php-gtk4 | Notes |
| --- | ---: | --- | :---: | --- |
| `WebKitWebView` (+ `_Unix`, `_Windows`) | 17 | `WebKitWebView` (WebKitGTK 6.0) | 🧩 | `--enable-gtk4-webkit` build flag exists; binding is a later milestone (docs/PLAN.md). |
| `GtkWebView` | 0 | — | 🧩 | php-gtk3 shim. |
| `GtkSourceView`, `GtkSourceBuffer`, `GtkSourceLanguage`, `GtkSourceLanguageManager` | 48 | GtkSourceView 5 | 🧩 | Separate library; not planned for the first milestones. |
| `GladeApp`, `GladeDesignView`, `GladeEditor`, `GladePalette`, `GladeProject`, `GladeWidget` | 33 | — | 🧩 | libgladeui has no GTK 4 release; drop. |
| `WnckScreen`, `WnckWindow`, `WnckClassGroup` | 20 | — | ⛔ | libwnck is X11-only and not part of the GTK 4 story. |
| `PangoLayout`, `PangoContext`, `PangoAttrList`, `PangoLayoutLine`, `PangoWrapMode` | 18 | Pango (unchanged) | 🧩 | Only needed once `GtkDrawingArea`/custom drawing lands. |
| `GtkosxApplication` | 19 | — | ⛔ | macOS integration; Linux is the primary target (docs/PLAN.md). |
| `Cef` | 0 | — | ⛔ | Empty in php-gtk3. |

---

## Recommended port order

This order is the wave plan of docs/PLAN.md §3 "Rollout": each wave is an allow-list for the
generator, reviewed as a draft, hand-finished through overrides/promotion, merged with CI green.

1. **Layout containers** — `GtkBox` ✅ and `GtkWidget::set_hexpand`/`set_vexpand` ✅ are done (they
   are what turned `examples/demo.php` into an application); `GtkScrolledWindow` is next and is what
   the demo's sidebar needs, then `GtkGrid`, `GtkPaned`, `GtkFrame` and the remaining `GtkWidget`
   layout methods (`set_margin_*`).
2. **Controls** — `GtkEntry`/`GtkEditable`, `GtkCheckButton`, `GtkToggleButton`, `GtkSpinButton`,
   `GtkScale`, `GtkProgressBar`, `GtkImage`, `GtkSpinner`.
3. **Event controllers** — `GtkEventControllerKey`, `GtkGestureClick`,
   `GtkEventControllerMotion`. This is the replacement for the whole `GdkEvent*` family and is the
   single biggest behavioural gap versus php-gtk3.
4. **Menus/actions** — `GMenu`/`GMenuItem`, `GtkPopoverMenu`, `GtkPopoverMenuBar`, `GtkMenuButton`,
   `GtkHeaderBar`, `GtkApplication::set_accels_for_action`.
5. **Dialogs** — `GtkAlertDialog`, `GtkFileDialog`, `GtkAboutDialog` (async API, no `run()`).
6. **Text** — `GtkTextView` + `GtkTextBuffer` + `GtkTextIter` (boxed).
7. **List models** — `GListModel`, `GListStore`, `GtkStringList`, `GtkSingleSelection`,
   `GtkListView`, `GtkColumnView`, `GtkSignalListItemFactory`. Do **not** port `GtkTreeView`.
8. **Styling / builder** — `GtkCssProvider`, `GtkBuilder`.
9. Optional/later: printing, `GdkClipboard`, WebKitGTK 6, GtkSourceView 5.

Each entry follows the four-part definition of done in CLAUDE.md: implementation + registration,
tests, stub + regenerated arginfo/IDE stub, and its own `examples/<Class>.php`.
