# TODO

Findings of the 2026-08-25 review ("best practice or php-gtk3 ballast?"). Ordered by impact.
Tick items off here; design rationale lives in docs/PLAN.md. §1–§5 are history, §6–§7 the
generator backlog, §9 the open design questions.

## 1. Runtime foundation — replace PHP-CPP with the native Zend API  ✅ done 2026-08-25

Inherited from php-gtk3 without re-examination. PHP-CPP's 2018 rationale (Zend API churn) no
longer holds for a PHP 8.4+ target, and it blocks idiomatic PHP:

- [x] `struct { GObject *obj; zend_object std; }` objects with custom handlers
      (`free_obj`, no clone, `get_debug_info`, `read_property`/`write_property` → GObject props)
- [x] `src/gtk4.stub.php` as the **single source**: `gen_stub.php` → `gtk4_arginfo.h`
      (typed arginfo, return types, nullable class types, enums, `#[\Deprecated]`); IDE stub is
      derived, not hand-synced → delete the sync half of `StubsTest`
- [x] `phpize` / `config.m4` build (PIE/PECL-installable, Windows via php-sdk); drop the
      PHP-CPP template Makefile, `PHPCPP_STATIC`/`PHPCPP_BASE` pairing logic, the PHP-CPP fork
- [x] signals: `zend_fcall_info` + cache resolved once at `connect()`, `zend_call_function`
      directly (no `call_user_func_array` round trip)
- [x] arity/type violations = `ArgumentCountError`/`TypeError` (PHP 8 semantics), not `E_WARNING`
- [x] exception boundary: capture the real `Throwable` (`EG(exception)`), hand the object to
      `Gtk::set_exception_handler` (done); rethrow mode (done, §2)
- [x] `var_dump($obj)` shows GObject properties (`get_debug_info`)

## 2. GTK3 mental-model ballast in the PHP API  ✅ done 2026-08-25

- [x] `Gtk::main()` / `Gtk::main_quit()` (+ the `quit_pending` hack) → `GtkApplication::run()`
      as the documented path, `Gtk4\GMainLoop` (+ `GLib::idle_add/timeout_add/source_remove`) for
      scripts that need a bare loop; `Gtk::init()` kept
- [x] `connect($signal, $handler, ...$userData)` → `connect(string $signal, callable $handler): int`;
      PHP closures capture context with `use`
- [x] `Gtk4\PHPGTK_VERSION` → `Gtk4\VERSION` etc. (double prefix)
- [x] exception model: `Gtk4\ExceptionMode::Log` (default) / `::Rethrow` via
      `Gtk::set_exception_mode()`; Rethrow stops running loops and propagates the Throwable

## 3. Decisions to write down  ✅ done 2026-08-25

- [x] **snake_case methods** (`set_title`) — kept, final. 1:1 mapping to docs.gtk.org and every
      other binding (PyGObject, gjs, gtk-rs, Vala); recorded in docs/PLAN.md; single spelling, no
      camelCase aliases (exceptions: `GError::getDomain()`, CamelCase enum cases); phpcs' camelCaps
      rule is off for tests/examples because of `vfunc_<name>()` overrides
- [x] property access: `get_property()/set_property()` *and* `$obj->prop` via handlers

- [ ] **Branch protection for `main`** — blocked (private repo, Free plan); details in §9.
- [x] **License file** — MIT, `LICENSE` added 2026-08-25 (matches composer.json);
      keep php-src header on `gen/gen_stub.php` (PHP License 3.01, MIT-compatible)

## 4. Milestone 2 leftovers  ✅ done 2026-08-25

- [x] boxed core (`src/core/boxed.*`: value handles, clone/compare by value, field properties) +
      `GdkRGBA`, `GdkRectangle`, `GStrv` ↔ `list<string>`; graphene/GBytes/GError when an API needs them
- [x] `G_TYPE_VARIANT` ↔ PHP values (`src/core/variant.*`), real `GParamSpec` class;
      `G_TYPE_POINTER` deliberately unsupported (no meaningful PHP value)
- [x] interface mechanism (`zend_class_implements` from the stub's `implements`): `GAction`,
      `GActionMap`, `GActionGroup`; the rest come with the generator
- [x] one callback abstraction for non-signal callbacks (`src/core/callback.*`, used by GLib
      idle/timeout; reuse for sorters, draw funcs, factories)
- [x] `GtkWidget` layer (+ `GtkButton`, `GtkLabel`), `GObject::emit()`
- [x] closure/source teardown in RSHUTDOWN (`src/core/teardown.*`, `tests/scripts/shutdown.php`)

## 5. Milestone 2b — foundation the generator will emit against (decided 2026-08-26)

Do before milestone 3; each changes what generated code looks like.

- [x] **Enums and flags as PHP types** (2026-08-26) — `src/core/enums.*`; `GtkAlign`, `GtkOrientation`
      as PHP enums (cases literal in the stub, verified against the C enum at RINIT), `GApplicationFlags`
      as a constant class (values literal, verified at RINIT); properties return cases and accept
      cases or ints, typed methods
      take the enum only; unregistered enum types stay ints.
- [x] **Collection helpers** (2026-08-26) — `src/core/collections.*`: `GList`/`GSList`/`GPtrArray`/`char**`
      → PHP list by element GType with `Transfer::{None,Container,Full}`; `strv_from_php`. Users:
      `GtkApplication::get_windows()` (none), `GtkWidget::list_mnemonic_labels()` (container),
      CSS classes (`GStrv`). `GPtrArray` helper exists but has no bound user yet.
- [x] **Out parameters** (2026-08-26) — convention recorded in PLAN.md: outs become the return value
      (one → value, several → list in declaration order); a `bool` C return with outs → the outs or
      `null`. Users: `get_size_request()`, `GtkWindow::get_default_size()`, `GtkLabel::get_selection_bounds()`.
- [x] **Fundamental handle mechanism** (2026-08-26) — `src/core/fundamental.*`: registry of
      GType → {class, ref, unref}; `GParamSpec` migrated onto it. `GdkEvent`/`GskRenderNode`/
      `GtkExpression` register the same way when their APIs arrive (GdkEvent with controllers).
- [x] **Typed C callbacks from GIR** (2026-08-26) — hand-written trampolines as the generator
      template (PLAN.md "Typed C callbacks"): `GtkDrawingArea::set_draw_func()`
      (`GtkDrawingAreaDrawFunc`, with a minimal `CairoContext` handle on the fundamental registry),
      `GtkCustomFilter` (`GtkCustomFilterFunc`) + `GtkFilterListModel`, `GtkCustomSorter`
      (`GCompareDataFunc`) + `GtkSortListModel`; notified scope tracked by `teardown.*`, callable
      release deferred to `callback_drain()` (destroy notifies run inside GTK frames).
- [x] **`GError` → exception, `GBytes` ↔ string** (2026-08-26) — `Gtk4\GError extends RuntimeException`
      (`getDomain()`, GLib code in `getCode()`), `throw_gerror()` for `GError **` APIs, `G_TYPE_ERROR`
      values become exception objects; `GBytes` ↔ string in marshal and method signatures.
      First user: `GdkTexture` (`new_from_filename/new_from_bytes/save_to_png_bytes/save_to_png`).

## 6. Milestone 3 — generator rollout (decided 2026-08-26, see PLAN.md §3 "Rollout")

Map-driven: generate only the classes in `docs/GTK3-MAP.md`, wave by wave, review each wave as a
draft, hand-write via overrides / promotion where the project needs more.

- [x] **Prep** (2026-08-27) — interface methods emitted once per interface (`@implementation-alias`),
      per-namespace stubs, constructor ownership rule, `gen/` inputs.
- [x] **`gir.php` in `gen/`** (2026-08-27) — GIR parser (Gtk/Gdk/Gio/GObject/GLib/Pango/Gsk/cairo),
      allow-list + transitive closure, emitters (per-namespace stub, `.cpp` per class, MINIT block,
      prototypes, smoke tests, example skeletons, `gen/report.md`, the status column of
      docs/GTK3-MAP.md via `gen/map-status.php`), `gen/overrides/`, `skip.txt`, `handwritten.txt`,
      `GENERATED` header + `./ci.sh --only=gen` staleness check, version policy (≤ 4.14 emitted,
      newer skipped and reported; the `GTK_CHECK_VERSION` + `@since` branch is unexercised until a
      wave needs it). `config.m4`/`config.w32` glob `src/**/*.cpp`, so no source list is emitted.
- [x] **Wave 0** (2026-08-27) — the existing GObject classes are generated (`gen/report.md` lists
      the skips); API follows GTK's shape (static `new_*` factories, no PHP-side defaults, no
      deprecated 4.10 API), the suite was adapted accordingly.
- [x] **Wave 0 follow-up** (2026-08-27) — generated smoke tests (`tests/Generated/`, PLAN §3.5),
      visual pages for every generated class/enum, hand-written tests for the new API
      (`LabelTest`, `ButtonTest`, additions to the widget/box/application/list/texture/action
      tests). Deviations from PLAN §3 decided 2026-08-27: deprecated members are *skipped* (no
      `#[\Deprecated]` emission — modern API only), GObject properties are `@property` tags mapped
      by the runtime handlers (not PHP 8.4 property hooks), docblocks carry GIR's first paragraph
      (no docs.gtk.org links yet). Not done: a coverage doc beyond `gen/report.md` + the map.
- [x] **Wave 1** (2026-08-29) — layout: `GtkScrolledWindow`/`GtkViewport`/`GtkScrollable`/
      `GtkAdjustment`, `GtkGrid`, `GtkPaned`, `GtkFrame`, `GtkStack` (+`Page`/`Switcher`/`Sidebar`),
      `GtkNotebook` (+`Page`), `GtkOverlay`, `GtkRevealer`, `GtkFixed`, `GtkSeparator`,
      `GtkSizeGroup` and their enums; `examples/demo.php`'s sidebar scrolls. Skipped for later
      closures (gen/report.md): `Gsk.Transform`, `Gtk.Border`, `Gtk.SelectionModel` (`GtkStack::get_pages`),
      `Gtk.ScrollInfo`. `GtkStackPage`/`GtkNotebookPage` refuse `new` (GTK creates them).
- [x] **Wave 2** (2026-08-29) — controls: `GtkEntry`/`GtkEditable`/`GtkEntryBuffer`/
      `GtkPasswordEntry`, `GtkCheckButton`, `GtkToggleButton`, `GtkSpinButton`, `GtkRange`/`GtkScale`
      (`set_format_value_func` is an override), `GtkProgressBar`, `GtkImage`, `GtkPicture` +
      `GdkPaintable`, `GtkSpinner`, `GtkCalendar`, `GtkDropDown` + `GtkStringList`/`GtkStringObject`
      and their enums/flags. Skipped for later closures (gen/report.md): `GLib.DateTime`
      (`GtkCalendar::get_date`), `Gio.File` (`GtkPicture::set_file`), `Gio.Icon`, `Gio.MenuModel`
      (extra menus), `Gtk.Expression`/`Gtk.ListItemFactory` (`GtkDropDown` factories, wave 7),
      `Pango.AttrList`/`TabArray`, the 4.10-deprecated `GtkEntryCompletion`.
- [x] **Wave 3** (2026-08-29) — event controllers: `GtkEventController` + `Key`/`Motion`/`Scroll`/
      `Focus`/`Legacy`, `GtkGesture`/`GtkGestureSingle` + `Click`/`Drag`/`LongPress`/`Swipe`/`Pan`/
      `Zoom`/`Rotate`, their enums, and the hand-written `GdkEvent` family on the fundamental
      registry (`src/Gdk/GdkEvent.cpp`: `GdkEvent` + 9 typed subclasses; the generator emits
      `get_current_event()` etc. through a `FUNDAMENTALS` arm; marshal handles GdkEvent GValues).
      Tests fake real X input through ext-ffi + libXtst (`tests/XInput.php`). Skipped for later
      closures (gen/report.md; `Gdk.EventSequence` followed on 2026-08-30): `Gdk.Device`,
      `Gtk.IMContext`, `Gtk.ShortcutController` (accels already go through
      `GtkApplication::set_accels_for_action()`).
- [x] **Wave 4** (2026-08-30) — menus/actions: `GMenuModel`/`GMenu`/`GMenuItem`, `GtkPopover`,
      `GtkPopoverMenu`(+`Bar`), `GtkMenuButton` (`set_create_popup_func` override), `GtkHeaderBar`,
      `GtkApplicationWindow`, `GtkApplication::set_menubar`. Two PHP-level clashes resolved in
      gen/skip.txt: `GtkApplicationWindow` does not declare `GActionGroup` (its `activate_action`
      would be incompatible with `GtkWidget`'s — the skip key `<Class>.implements:<Iface>` is new)
      and `GtkMenuButton::get/set_direction` are the widget's (the arrow is the `direction`
      property). `new GMenuModel()` is refused (NULL class slots). Skipped: `Gio.Icon` (`set_icon`),
      `Gtk.ShortcutsWindow` (help overlay), the varargs `set_attribute` family (`*_value` exists).
- [x] **Socket on the main loop** (2026-08-30): `GLib::io_add_watch(resource, GIOCondition, callable)`
      — a `GIOChannel` watch over PHP's descriptor (`php_stream_cast(PHP_STREAM_AS_SOCKETD)`),
      callable + stream parked through the callback graveyard, RSHUTDOWN-tracked like timeouts
      (`IoWatchTest`). ext-sockets users go through `socket_export_stream()`. The runtime item a
      ported application needs to retire GTK3's nested `events_pending()/main_iteration()` pumps.
- [x] **Wave 5 — dialogs** (2026-08-30): `GtkFileDialog`, `GtkFileFilter`, `GtkColorDialog`,
      `GtkFontDialog`, `GtkAboutDialog` (+ `PangoFontDescription`, `GtkLicense` and the Pango
      enums the descriptions use). The async machinery was already there from the review wave, so
      the wave is allow-list plus two decisions: **`Gio.File` maps to a path string** (a GFile
      carries nothing else a PHP program can use, and the GIO file stack is unbound — it also
      unlocked `GtkPicture::set_file`), and `GtkFileFilter::new_from_gvariant()` names the
      `(sa(us))` type in an override because inference cannot build a tuple from a PHP list.
      Generator fixes it forced: an enum case may not start with a digit (`GTK_LICENSE_0BSD` →
      `Bsd0`), the emitted return local is `phpgtk_ret` (a parameter called `$result` collided),
      and `ci.sh` reconfigures when the source list changes.
- [x] **Wave 6 — text** (2026-08-31): `GtkTextView`, `GtkTextBuffer`, `GtkTextIter` (the first
      boxed record with a real method surface), `GtkTextMark`, `GtkTextTag`, `GtkTextTagTable`,
      `GtkWrapMode` (+ the search-flags/window-type enums the signatures keep). Three decisions:
      **a boxed handle can hold its owner** — `BoxedClass::owner` (gen/gir/config.php
      `BOXED_OWNERS`) refs the iter's buffer for the handle's lifetime, because a kept iter
      dangled into freed memory once the script dropped the buffer (a *stale* iter over a live
      buffer is only GTK's g_warning and survives); the insert family's **byte counts are
      guarded** (`gen/overrides/Gtk.TextBuffer.*`: `$len = -1` default where trailing, and a
      given count must lie inside the string on a UTF-8 character boundary — GTK reads exactly
      `len` bytes and trusts them); and `GtkTextView::get_extra_menu()` is declared
      `?GMenuModel` in an override (the 4.14 GIR misses the nullable its own doc states).
      Generator fixes it forced: the emitted C call keeps **GIR's parameter order** (out
      parameters are not necessarily trailing — `gtk_text_view_get_iter_at_position(self, &iter,
      &trailing, x, y)` was called with the ins first), and the boxed emitter includes the
      collection converters (`GtkTextIter::get_marks()` is a `GSList`).
- [x] **Wave 7 — list models/views** (2026-09-01): `GtkListView`, `GtkGridView`, `GtkColumnView`
      (+ `GtkColumnViewColumn`), `GtkListItemFactory`/`GtkSignalListItemFactory`/`GtkListItem`,
      the selection models (`GtkSelectionModel`, `GtkSingleSelection`, `GtkMultiSelection`,
      `GtkNoSelection`) with `GtkBitset`, and `GtkTreeListModel`/`GtkTreeListRow`/
      `GtkTreeExpander`. `GtkScrollInfo` came along (it is what `scroll_to()` takes, on
      `GtkViewport` too). Decisions: the tree's **create function is an override**
      (`gen/overrides/Gtk.TreeListModel.*`) — `function (GObject $item): ?GListModel`, a wrong
      return type is a TypeError through the boundary and the row stays a leaf; because the model
      has no setter to take the callable back, the Callback is also qdata on the model and the
      teardown hook releases the callable there. `GtkListItem`/`GtkTreeListRow` constructors are
      in gen/skip.txt (GTK creates them). Three generator/runtime fixes it forced: a **boxed
      parameter marked `transfer full` is `g_boxed_copy()`d** (`scroll_to()` handed GTK the
      handle's own `GtkScrollInfo`, which unreffed it — a double free that aborted the process),
      **`clone`/`==` on an opaque record** go through the type's own `copy()`/`equal()`
      (`BoxedClass::copy`/`::equal`; cloning a refcounted record used to alias it, and two
      `GtkTextIter`s always compared equal), and an **interface fallback class implements the
      methods it inherits** (`GtkSelectionModelObject` was abstract, so `GtkNotebook::get_pages()`
      could not be wrapped).
- [x] **Layout managers + the cairo image source** (2026-09-01, out of band) — wave 1 bound the
      containers but not the object GTK 4 delegates their allocation to, so a PHP subclass of
      `GtkBox`/`GtkOverlay` could not lay itself out at all: GTK hands a widget that *has* a layout
      manager to the manager and never reaches `WidgetClass.size_allocate`/`.measure`, which made
      `vfunc_size_allocate()` look broken downstream. `GtkLayoutManager` (+ `GtkLayoutChild`,
      `GtkBinLayout`/`BoxLayout`/`CenterLayout`/`FixedLayout`/`GridLayout`/`OverlayLayout`) with
      `vfunc_allocate()`/`vfunc_measure()`/`vfunc_get_request_mode()`, `GtkWidget::allocate()`
      (GIR's `GskTransform` becomes `$x`/`$y`) and `set/get_layout_manager()`. Alongside it the
      **cairo image source** — `CairoSurface` on the fundamental registry,
      `GdkTexture::download()` (GDK writes CAIRO_FORMAT_ARGB32, so the buffer *is* an image
      surface) and `CairoContext::set_source_surface()`: there was no way to paint an image in a
      draw func, GTK 3's `gdk_cairo_set_source_pixbuf()` having no GTK 4 counterpart. Decisions:
      **a layout child is the manager's to create** (`new` on the four `*LayoutChild` classes is
      refused — GTK holds their manager and widget *unowned*, so one built from PHP CRITICALs and
      then dangles into freed memory), and a **`vfunc_*()` that no bound slot answers is reported**
      (`class_init` warns once per declaring class; a typo or a slot the generator skipped, like
      `vfunc_snapshot`, used to be ignored in silence). `VFUNC_NOTES` in `gen/gir/config.php`
      appends the layout-manager caveat to the two `GtkWidget` docblocks.
- [x] **Wave 8 — styling/builder/Gdk** (2026-09-02): `GtkBuilder` (+ `GtkBuilderScope`),
      `GtkIconTheme`/`GtkIconPaintable`, `GdkMonitor`, `GdkSurface` (+ `GtkNative`, without which
      a widget cannot reach its surface), `GdkCursor`, `GdkClipboard`; the CSS and `GdkDisplay`
      half landed 2026-08-28. The wave is mostly the scope and four refusals:
      **`GtkBuilder::set_handlers()`** installs a `GtkBuilderScope` that resolves
      `<signal handler="...">` to a PHP callable (GTK 4 dropped `connect_signals()`, and the
      default C scope looks for a symbol, so every document with a signal failed); it delegates
      type resolution to a `GtkBuilderCScope` it owns, because that class is final. `swapped="yes"`
      and `object="..."` are refused — a PHP closure already carries what it captured.
      **`new_from_string`/`new_from_file`/`new_from_resource` are skipped**: they `g_error()` (abort
      the process) on any document GTK cannot parse, and a `.ui` document is data; `add_from_*()`
      throws a `GError` for the same input. **The string parsers take no length** (GTK's separate
      `gssize` let a script read past the buffer). **`new` is refused on `GdkClipboard`,
      `GdkSurface`, `GdkMonitor` and `GtkIconPaintable`** — the first two abort inside GDK
      (`assertion failed: (priv->display != NULL)`), the others answer NULL from getters whose
      declared types do not allow it. One generator bug fell out: the closure pulled enums from
      members it then skipped, so the 4.12-deprecated `gdk_surface_create_similar_surface()`
      dragged `cairo_content_t` in - and with it a second, lower-case cairo namespace directory
      that *is* `src/Cairo/` on a case-insensitive filesystem (`HeaderNamesTest` grew a check for
      two of our own paths differing only in case).
- [x] **Wave 3b - drag and drop** (2026-09-03), the last wave of the map's port order:
      `GtkDragSource`, `GtkDropTarget` (+ `Async`), `GtkDragIcon`, `GdkContentProvider`,
      `GdkContentFormats`, `GdkDrag`/`GdkDrop`. A drag carries a *value*, and GIR spells both ends
      with a GValue and a GType - neither of which PHP has a word for - so the payload is an
      ordinary PHP value through `core/marshal` and the type is named the way a list store names
      its item type: **`gtype_from_php_name()`** ("string"/"int"/"float"/"bool" or a registered
      class) with **`php_name_for_gtype()`** as its inverse, so a getter answers in the spelling
      its setter takes. That is what `GdkContentProvider::new_for_value()`/`get_value()`,
      `GtkDropTarget::__construct()`/`set_gtypes()`/`get_gtypes()`/`get_value()`,
      `GdkContentFormats`'s array members and `GdkClipboard::set_value()` are overrides for -
      without the constructor override a drop target accepted nothing at all, GIR's own taking a
      GType. `GdkClipboard::set_content()`/`get_content()` came back on their own once
      `GdkContentProvider` was in the closure, which is the wave-8 gap this closes.
      `gdk_content_provider_get_value()` is caller-*typed*, not merely caller-allocated: it
      asserts on an uninitialised GValue, so the type is asked for or taken from the provider's
      own formats. `new` is refused on `GdkDrag`/`GdkDrop` (GDK makes them when a drag starts; a
      PHP subtype has no device and aborts in `gdk_drag_set_property`).
      One generator bug, the third of its family and the first fixed categorically: GIR marks a
      method's **receiver** with `<instance-parameter transfer-ownership="full">` when the call
      *consumes* the object it is called on (`gdk_content_formats_union()` and the four
      `union_*serialize_*`). Only parameter and return transfer were read, so GTK freed the
      handle's own value and the handle's later unref hit a dead refcount - a `ref_count > 0`
      CRITICAL nothing surfaced, then `malloc(): unaligned fastbin chunk` some three thousand
      tests later. ASan completed the suite silently; valgrind named it. `Func::consumesSelf` now
      carries the annotation and the boxed emitter hands the callee a `g_boxed_copy()`.
      Two more overrides came out of writing the tests, both of the same shape - GTK does not do
      what its GIR says. `gtk_drop_target_async_get_formats()` is annotated `transfer full` but
      returns `self->formats` borrowed (its sibling `gtk_drop_target_get_formats()` is annotated
      correctly), so the generated free unref'd GTK's own formats and the *second* read was a
      use-after-free; the override wraps without freeing. And `new GtkDragIcon()` builds a
      GtkRoot with no GdkSurface, which dies unrealized in `gtk_drag_icon_realize()` - refused in
      `gen/skip.txt` like `GdkDrag`/`GdkDrop`, GTK makes the icon for a drag it started.

**The rollout is complete**: every step of docs/GTK3-MAP.md's "Recommended port order" has landed,
so there is no next wave. What the map still marks ❌ is the residue the order deliberately left
for last, and each group needs a decision rather than a wave:

- *deprecated in 4.10* — `GtkDialog`, `GtkInfoBar`, `GtkStatusbar`, `GtkEntryCompletion`,
  `GtkColorButton`/`GtkFontButton`, `GtkAppChooser*`. The wave rules skip deprecated API in favour
  of its replacement, and every replacement is bound; port none of them unless a real port asks.
- *current widgets no wave needed* — `GtkListBox`(+`Row`), `GtkFlowBox`(+`Child`), `GtkExpander`,
  `GtkActionBar`, `GtkAspectFrame`. Mechanical: add to `gen/allowlist.txt` and generate.
- *small gaps in bound classes* — `GIcon`/`GThemedIcon` (what `GtkImage::set_from_gicon()` wants),
  the runtime GTK version triple (`gtk_get_major_version` …, next to `Gtk4\VERSION`), and
  `GtkUriLauncher` as the replacement for the deprecated `gtk_show_uri`.
- *whole subsystems* — printing (`GtkPrintSettings`/`GtkPageSetup`/`GtkPaperSize`), `GdkPixbuf*`
  (prefer `GdkTexture`), GSK's own types (§7), WebKitGTK 6 (PLAN.md milestone 6).

## 7. GTK4 feature surface (what the binding still has to expose to deliver GTK4's benefits)

Inherited for free (nothing to do): GSK/GPU rendering, the flat widget hierarchy (no
GtkContainer), Wayland/HiDPI/platform backends, the cleaned-up API (the stub is generated from
GTK4 GIR only). Every open item here is generator output and is ticked when its wave (§6, PLAN.md
§3) lands; design questions live in §9.

- [x] **Concrete layouts** → wave 1 (2026-08-29): `GtkGrid`, `GtkStack`, `GtkPaned`,
      `GtkScrolledWindow`, … (`GtkBox` and `GtkOrientable` done 2026-08-26/27). `GtkCenterBox`
      is not in the map's wave; add it to the allow-list when a port needs it.
- [x] **Event controllers** → wave 3 (2026-08-29): `GtkGestureClick`, `GtkEventControllerKey/Motion/Scroll/Focus`,
      `GtkWidget::add_controller()/remove_controller()`; signals already marshal (ints/doubles/flags);
      `GdkEvent` goes on the fundamental registry, `GdkModifierType` is a flags class.
- [x] **Drag and drop** → wave 3b (2026-09-03): `GtkDragSource`, `GtkDropTarget`,
      `GdkContentProvider` and the typed clipboard payloads that share its machinery. Still out:
      `GdkDevice`/`GdkSeat` (which is why `GdkDrag::get_device()` and the drop's device-aware
      members stay skipped) and the `Gio.InputStream` half of `read_finish`.
- [x] **Interface-only handles** (2026-08-30): `wrap()` falls back to a generated
      `Gtk4\<Interface>Object` class (private constructor, every interface method aliased) when an
      object's own classes are unregistered but a registered interface matches - most derived first
      (`GtkSelectionModel` over `GListModel`). `GtkNotebook::get_pages()` is a usable list now
      (`LayoutTest`); `ExampleTest` treats those classes as covered by their interface's page.
- [x] **Fundamental handle identity** (2026-08-30): `wrap_fundamental()` returns the live handle of
      an instance (`===` holds while PHP keeps it, `FoundationTest`, `EventControllerTest`); the
      entry is dropped with the handle. `GdkEventSequence` rides on it as an opaque identity
      (no ref/unref: GTK owns the sequence), so every per-sequence `GtkGesture` method is bound.
- [x] **List views** → wave 7 (2026-09-01): selection models, `GtkListView`/`GtkColumnView`/
      `GtkGridView`, `GtkSignalListItemFactory` (`setup`/`bind`/`unbind`/`teardown`),
      `GtkListItem`, `GtkTreeListModel` + `GtkTreeExpander`. Done 2026-08-26:
      `Gtk4\PhpValue` (GType `PhpValue`, a GObject carrying a zval), `GListModel`, `GListStore`,
      `GtkFilterListModel`/`GtkCustomFilter`, `GtkSortListModel`/`GtkCustomSorter`;
      `GtkStringList` came with wave 2.
- [x] **CSS** (2026-08-28) — `GtkCssProvider`, `GtkStyleProvider`, `GtkCssSection` (the
      `parsing-error` argument, a refcounted boxed type on the fundamental registry),
      `GtkStyleProviderPriority` and `Gtk::add_provider_for_display()` /
      `remove_provider_for_display()`; `GdkDisplay` came with it. Custom CSS *properties*
      (`gtk_widget_class_install_style_property`-style) are not a GTK 4 concept and are not planned.
- [x] **Widgets from a `.ui` document** → wave 8 (2026-09-02): `GtkBuilder` with a
      `GtkBuilderScope` that resolves `<signal handler="...">` to a PHP callable
      (`set_handlers()`), `GtkIconTheme`/`GtkIconPaintable`, and the display-owned Gdk objects a
      window asks about (`GdkMonitor`, `GdkSurface` through `GtkNative`, `GdkCursor`,
      `GdkClipboard`). Not bound: `GdkContentProvider`/`GdkContentFormats` (the clipboard's typed
      payloads — they arrive with drag and drop, wave 3b), `GdkDevice`/`GdkSeat`, `GtkBuilder`'s
      `<template>` support (`extend_with_template` needs a GType parameter).
- [x] **Rendering from PHP** → milestone 4 (2026-09-02): `GtkSnapshot` + `GdkSnapshot` and the
      geometry they take (`GrapheneRect`/`Point`/`Size` as boxed records, the first types from the
      Graphene GIR). `append_cairo()` is what makes it usable from PHP: it answers with the
      `CairoContext` the binding already speaks, so a snapshot mixes GSK nodes and cairo in one
      pass. Binding the type is also what let `GtkWidget::vfunc_snapshot()` out of the closure -
      the last widget slot a PHP subclass could not override, so a widget that paints itself no
      longer has to be a `GtkDrawingArea` (`tests/Subclass/PaintedWidget.php`).
      Earlier: `GdkTexture`, `GtkDrawingArea::set_draw_func` + `CairoContext` (2026-08-26),
      `CairoSurface` + `GdkTexture::download()` + `set_source_surface()` (2026-09-01).
      Still out: GSK proper (`GskRenderNode`, `GskPath`, `GskTransform`, `GskRoundedRect`), so
      `append_node`, the gradient and shadow families and `push_rounded_clip` stay skipped, and
      the five 4.10-deprecated `render_*` helpers are skipped as deprecated.
      Two binding decisions of its own: **`free_to_paintable()` is not bound** - it frees the
      GtkSnapshot itself, so the handle is left on freed memory and its next qdata/toggle-ref
      touch is a SEGV (ASan caught it through RobustnessTest); `to_paintable()` answers with the
      same paintable and leaves the object alive. And **graphene's plain `inset()`/`offset()`/
      `normalize()` rewrite the rectangle they are given**, which a boxed *value* handle must not
      do (`$rect->inset(1, 1)` silently shrank `$rect`), so the plain names are bound to
      graphene's `_r` const forms and the `_r` names are not bound at all.
      Three generator bugs it forced: a class need not carry a **`c:type`** (`GtkSnapshot` is a
      typedef of `GdkSnapshot`, so every emitted `<ctype> *self` came out empty - the loader falls
      back to the identifier prefix plus the name); the **GType macro** is derived from the
      glib:type-name rather than the c:type (Graphene's c:type is the struct's own snake_case
      name, giving `GRAPHENE_TYPE__RECT_T` instead of `GRAPHENE_TYPE_RECT`; GTK's own types spell
      both the same, so nothing else moved); and a **`transfer none` record getter may be const**
      (`graphene_point_zero()`), which the emitted local now keeps.
- [x] **GL renderer smoke test** — the test infrastructure forces `GSK_RENDERER=cairo` +
      `GDK_DEBUG=gl-disable` (Xvfb). Verified manually on a real Wayland session with an AMD GPU on
      2026-08-26 (see PLAN.md §6 "GL"); repeat before a release, no automation possible on CI runners.

## 8. Threads (decided 2026-08-27)

- [x] ZTS build: per-request state in module globals (`src/core/globals.h`, `GTK4_G()`), GINIT/
      GSHUTDOWN construct/destroy the C++ members per thread, `config.m4`/`php_gtk4.h` no longer
      refuse ZTS, one `phpts: ts` job in `tests.yml`. Registries filled in MINIT stay static.
- [x] GUI-thread guard: `record_gui_thread()` in `Gtk::init()`, `assert_gui_thread()` in the
      loop-driving methods → `Error` from any other thread.
- [x] Not doing: multiple GUI threads / one GTK per request thread. GTK is single-threaded;
      documented in README "Threads" and docs/BUILD.md.
- Cross-thread hand-off (`GLib::invoke_on_main`) is design work → §9.

## 9. Design work (open questions, not generator output)

Each needs a written design in PLAN.md before code; none blocks the waves.

- [x] **Identity across GTK ownership** (2026-08-28) — the handle's reference is a toggle ref;
      while GTK holds the object the GObject holds the `zend_object`, so a PHP subclass (state,
      overridden methods) survives `$box->append(new MyButton())` without a PHP reference and
      comes back from `get_first_child()` as itself. Released in the toggle notify and at
      RSHUTDOWN (`WrapTest`, stress script, ASan/valgrind clean). What subclassing still needs:
- [x] **GObject subclassing from PHP** (2026-08-28, PLAN §2.6) — `class MyWidget extends GtkWidget`
      is a real GType (registered at first `new`, constructor arguments as construct properties,
      `gen/ctor-props.txt` for renames), `vfunc_<name>()` overrides class-struct slots through
      generated thunks with `parent::` chaining down to GTK, abstract classes constructible
      through a subclass only. `SubclassTest`, `EveryClassTest` (every abstract class through an
      eval'd subclass), smoke tests of abstract classes use an anonymous subclass, stress
      script. Open follow-ups: GObject properties/signals declared in PHP, `snapshot()` once
      `GtkSnapshot` lands, widget templates. Closed since: GTK *interfaces* implemented in PHP
      (`GListModel`), and custom **layout** from PHP — a `GtkLayoutManager` subclass, because a
      widget that has one never reaches `vfunc_size_allocate()` (2026-09-01).
- [x] **Relative paths were resolved against the wrong directory under ZTS** (2026-09-02, found
      with the local ZTS build the same day - docs/BUILD.md "Testing a ZTS build locally"). PHP's
      `chdir()` moves a *per-thread virtual* cwd; GTK, GLib and cairo are C libraries reading the
      *process* cwd, so `$texture->save_to_png('out.png')` landed where the process started rather
      than where PHP was. NTS never sees it - there the two are the same directory - and it is
      also why `RobustnessTest`'s scratch-directory containment silently did nothing there (the
      sweep wrote four TIFFs into the repository root). Every `filename` parameter now goes
      through `phpgtk::absolute_filename()` (`expand_filepath()` against PHP's own cwd) before
      GTK sees it, emitted by the generator, so reading and writing are both covered.
- [x] **A GTK CRITICAL fails the test that caused it** (2026-09-02, reworked 2026-09-03) — GLib's
      warnings used to scroll past in the PHPUnit output, so a missing guard at the boundary looked
      like noise. 26 ordinary-test criticals were real missing guards and are fixed. The first cut
      captured them behind `--enable-gtk4-testing`, which meant the shipped `.so` had no
      diagnostics at all and one ordinary `./ci.sh --only=build` silently un-gated the suite;
      it is now a runtime feature instead (`gtk4.diagnostics`, `src/core/diagnostics.cpp`), so
      `GtkTestCase` gates with a plain `set_error_handler` and every build behaves the same.
      A suite whose job is to hand GTK bad values answers `toleratesGtkCriticals()`, a single test
      declares the message it provokes with `expectsGtkCritical($substring)` - asserted both ways,
      so neither a *new* complaint nor a stale expectation hides. `RobustnessTest` no longer
      tolerates its sweep wholesale either: `tests/robustness-criticals.txt` pins the 140
      `<class>::<method>#<sweep>` keys GTK is known to complain about, and the sweep fails on an
      unlisted complaint or a listed method that has gone quiet
      (`tests/README-robustness-pin.md`).
- [x] **The coverage floor has almost no margin** (2026-09-03; margin restored 2026-09-04: the
      vfunc sweeps, the variant thunks and the review guards took it to 85.7%). It was 80.1% against
      `COVERAGE_MIN_LINES=80`, i.e. 23 lines. Wave 3b is what thinned it - `GdkDrag` and `GdkDrop`
      are 128 lines that no test can reach, because only a compositor-driven drag creates one, and
      they count in the denominator like any other file. The next wave will trip the floor. The
      choice then is to raise real coverage or to decide what the denominator should contain;
      lowering the floor is not it.
- [ ] **Cross-thread hand-off** for the "one GUI thread + workers" shape: a thread-safe
      `GLib::invoke_on_main(callable)` (serialise the callable or require a `parallel`-style
      channel; `g_main_context_invoke` on the GUI context, callable released on that thread).
      Needs a concrete consumer (`ext-parallel` or PHP-native threads) before designing the API.
- [ ] **Branch protection for `main`** (not design, just blocked) — private repo on a Free plan
      (GitHub API returns 403 "Upgrade to GitHub Pro or make this repository public"). Ruleset is
      ready in `.github/ruleset-main.json`; once public/Pro:
      `gh api -X POST repos/apss-pohl/php-gtk4/rulesets --input .github/ruleset-main.json`
      (drop `required_approving_review_count` to 0 while there is a single maintainer).

## 9b. Review 2026-08-28 — leftovers

Closed 2026-08-28 (second pass): lock-free PHP-GType snapshot on the `wrap()` path,
`find_property()` without a heap allocation, GFlags input validation, the reusable Windows recipe
(`windows-build.yml`: one gvsbuild pin, `/W3` + a warning gate on `src\`, GTK floor on the
non-pkgconf path, newest-dll launchers), incremental `ci.sh` builds + `GTK4_CONFIGURE_ARGS`
(a WebKit build leg), `phpunit.xml.dist`,
EXIT-trap cleanup / named asan env / phpt against the default build, `Cairo/CairoContext.h`,
`register_boxed()` without the dead parameter, anonymous namespaces in `src/core`, dead handle
arguments as `Error`, the comment gate over generated files, generated-sections only when needed,
identifier-precise include selection. PHP-Parser: vendor-first by design (gen/README.md).

- [x] `gen/gir.php` split (2026-08-30) into `gen/gir/{config,model,loader,type-map,writer}.php`
      plus the emitters and the CLI that stay in `gir.php`: 3 259 lines in one file became 1 890 +
      1 076 + 218 + 160 + 114 + 23. Verified by regenerating after every step - all 178 generated
      files stayed byte-identical - and `GeneratorTypeMapTest` now asks the type map directly,
      which needed no running generator. `phpstan-baseline-gir.neon` holds the same findings,
      redistributed over the new paths. One bug fell out: `foreach ($this->handwritten as $q)`
      iterated the values (`true`), so hand-written parents were never pre-marked as defined in
      the MINIT ordering; it iterates the keys now.
- [ ] Per-namespace stub naming (`src/Gtk/Gtk.stub.php` next to the hand-written `Gtk.cpp`) stays
      as documented in gen/README.md; renaming would touch every tool's path list for no behaviour.
- [x] `upload-artifact@v7` / `download-artifact@v8`: both on the v4+ artifact backend, verified
      compatible. Since 2026-09-04 every action is pinned to a commit SHA with the tag as a
      trailing comment (the form `update-deps.sh` and Dependabot both keep moving).
- [ ] Branch protection for `main` (§9): blocked by the private/Free-plan repo.

## 9c. Sweep widening 2026-09-03 — leftovers

Closed 2026-09-03: `tests/GtkInstances.php`, one instance factory shared by `RobustnessTest` and
`TypeDeclarationTest`, so a class is either live in both sweeps or skipped by both (suite skips
706 → 284, and every remaining one carries a reason). Four process-enders it found:
`GMenuModel::get_item_link()`/`get_item_attribute_value()` outside the model (SIGSEGV / `g_error`),
the use-after-free behind `GtkStack::get_pages()` and a composite widget's `get_first_child()`
(`object_hold_owner()` + `RETURNS_HOLD_SELF`, the object counterpart of `BOXED_OWNERS`), and
`CairoContext`'s N-double parser, which ran Zend's ZPP macros inside a `for` loop and so carried a
failed parse into a `__builtin_unreachable`. Three generator tables came with them —
`NULLABLE_RETURNS`, `NON_NULLABLE_PARAMS`, `PARAM_DOMAINS` — plus `check_enum_member()` for the six
unbound enums that were being read as `GFlagsClass`. `tests/robustness-criticals.txt` went 158 → 77.

- [x] **The method pin lines** (77 on 2026-09-04, 17 on 2026-09-05; the file also carries six
      lines from the property and emission sweeps). Closed by mirroring GTK's own assertion in a
      generator table - `ARG_PRECONDITIONS` (a position past the end, a minimum above the
      maximum, a name nothing answers to, an unconnected handler id), `PARAM_VALIDATORS` (an
      application id, a resource path, a menu attribute name, an accelerator string),
      `SELF_PRECONDITIONS` (registration), `PARAM_DOMAINS` (alignments, climb rate, font size) -
      or by hand where the method is an override (`GtkWidget::allocate()`, the `GtkTextIter` line
      setters, a `GTask` returning twice). The 17 that stay are GTK reporting about the *data* or
      about a state it keeps private, listed with their reasons in
      tests/README-robustness-pin.md; `GTK4_PIN_REPORT=<file>` prints what GTK says per line.
      Done 2026-09-04: the *widget-in-the-wrong-place* family -
      `list != NULL` on the seven `GtkNotebook` child methods, `gtk_widget_get_parent(child) ==
      box` on reorder/move/attach/overlay, the sibling checks of `insert_after/before` - is
      `CHILD_PARAMS` + `SELF_UNPARENTED_OR` in `gen/gir/config.php`, emitted as a `LogicException`
      naming the argument (`ArgumentGuardTest`). What is left, in the order worth attacking:
      *state preconditions* — `parent != NULL` on `gtk_layout_manager_get_layout_child()`,
      `!task->ever_returned`, `is_registered` on the `GApplication` methods,
      `center_child != NULL` on `GtkTextView::move_overlay()` — `LogicException` candidates by the
      CLAUDE.md vocabulary;
      *lookup misses* — "Child name not found in GtkStack", "no mark named" — a question of whether
      a miss should raise at all; and *parse diagnostics* — theme parser errors, "invalid
      accelerator string", the `goption.c` warnings — which are GTK reporting about data the script
      supplied and are correctly pinned. Regenerate the file the way
      `tests/README-robustness-pin.md` describes, and only after closing a boundary.
- [ ] `gdk_drop_read_async()` very likely asserts `callback != NULL` like its four `GdkClipboard`
      siblings, but a `GdkDrop` needs a real drag from another client and no test can reach one
      (`DragDropTest::testTheseAreGdksToCreateNotPhps`), so `NON_NULLABLE_PARAMS` deliberately has
      no line for it: every entry there is a precondition that was *observed* firing. Revisit when
      a drag test exists.
- [ ] `RETURNS_HOLD_SELF` can only name `$this`, so the sibling walk is outside it —
      `get_next_sibling()`'s result belongs to the shared parent. Measured, not assumed: reaching a
      sibling through the eight composite widgets that have one, dropping everything that holds the
      parent and calling every arg-less getter on the orphan is clean under ASan+UBSan. Re-measure
      before adding the owner-expression form `BOXED_OWNERS` has.
- [ ] `GtkInstances::UNREACHABLE` is guarded dynamically (`TypeDeclarationTest` fails if any getter
      hands out a class it calls unbuildable, which is how `GtkBuilderScopeObject` turned out to be
      reachable), but only through arg-less getters on classes the factory can build. A class
      reachable *only* through a method with arguments would keep a stale excuse.
- [x] Windows and PHP 8.5 unverified locally for this work - answered by CI on 2026-09-04: PHP 8.5
      NTS/ZTS green on Linux; the four Windows jobs build and load the DLL and then die in
      PHPUnit at `PhpSelectionModelTest` with `gtk_list_item_manager_clear_model: assertion
      failed: (gtk_rb_tree_get_root (self->items) == NULL)`, exit 0xC0000409, the same way
      before and after every push that day.
- [ ] **The Windows list-view abort** (from the line above). The assertion exists in the newer
      GTK gvsbuild ships, not in the 4.14 Linux CI runs, so Linux may be leaking silently where
      Windows aborts. Hypothesis: a PHP-implemented `GtkSelectionModel` slot answers its default
      (zero items) once the handle is unavailable, leaving GTK's item manager with items it
      believes gone. Needs a run against that GTK; a workflow run with the test class excluded
      would also show the two earlier failures the abort hides.

## 9d. Review 2026-09-04 — leftovers

Six-area review (conventions, tools, architecture, type safety, security, performance). Closed the
same day: GtkBuilder resolves `<signal handler>` from `set_handlers()` only (GTK's C-symbol lookup
let a `.ui` document call `abort`), builder closures are torn down at RSHUTDOWN, `emit()` bounds a
length by the string before it, typed `GVariant` scalars / GStrv elements / vfunc returns /
`GdkRectangle` / `GBytes` convert like parameters (`caller_is_strict()` now looks through the
internal frame to the PHP caller, so `set_property()` is strict where its caller is), property
access on a disposed handle is an `Error`, out-of-range property writes are a `ValueError`,
`Gtk::testing_run_dispose()` takes widgets only, `@property` tags are nullable only where null is
possible, `GType` properties are type names, `get_page()` ×2 nullable, the `gen` stage skips off the
reference toolchain, actions and the PHP SDK are SHA/tag pinned, plus the doc drift and script
fixes. The interface-property routing and the `GVariant` thunks landed the same day (PLAN §2.6).

- [x] **GC-invisible cycles through `object_hold_owner()` and `BoxedClass::owner`** (closed the same
      day: the owner's `get_gc` reports the held dependents whose GTK reference is its own - the
      child of a parent widget, `holds_dependent()` - and a boxed value holds its owner's handle;
      `OwnerCycleTest`, ASan/LSan clean): parent handle →
      (toggle) parent GObject → child GObject → (`held`) child handle → `owner` → parent handle.
      Zend sees only the `owner` edge, so `get_first_child()` on a detached tree, or a PHP buffer
      subclass storing one of its own iters, leaks both handles until RSHUTDOWN. Reporting the held
      child handles from the parent's `get_gc` would let the collector see it, but the freeing order
      then meets the toggle release inside the parent's finalize; dropping `owner` while held brings
      back the `GtkScale` gizmo use-after-free. Needs its own design and an ASan run; pin the
      current behaviour with a test first.
- [x] **Sweeps for what the converters now guard** (property side closed the same day: every
      `@property` tag is now swept both ways - `RobustnessTest` writes hostile values to each
      (`<class>::$<prop>#properties` in the pin file) and `TypeDeclarationTest` reads each against
      its declared type, with `@property-read`/`@property-write` held to their word. The sweep found
      the generated boxed field writers coercing under strict_types, read-only writes growing a
      dynamic property, construct-only writes reaching GLib, write-only reads answering "undefined
      property", and the `is-remote` read asserting before register() - all closed. The next day
      `emit()` arguments (`GObject::list_signals()` is what made the signals enumerable; 1,052
      rows, 5 pins: a drop target's `enter`/`motion` without a drop, `delete-text` with a negative
      start) and scalar vfunc returns (`#return`) joined.) Still unswept: typed `GVariant`
      conversions, which `ConverterGuardTest` pins case by case.
- [x] `gen/gir.php` emitters split out (2026-09-05): four traits of `Generator`, moved verbatim -
      `gen/gir/emit-class.php`, `emit-record.php`, `emit-vfunc.php`, `emit-tests.php` (2 028 lines
      became 534 + 692 + 272 + 284 + 312); every generated file stayed byte-identical and the
      PHPStan baseline holds the same 115 findings over the new paths.
- [ ] Header declarations in `src/core/*.h` carry no comment (the gate covers definitions only);
      `.clang-tidy` explains its macro exclusions but not the style choices.
- [ ] Performance, all single-digit percent: the signal marshaller re-resolves the callable and
      allocates the argument array per emission, `subtype_vfunc()` hashes the method name per call,
      the PHP-GType snapshots are O(N²) in PHP subclasses and never freed.
- [x] The whole-tree clang-tidy pass could not be run on the development machine for this batch
      (killed for memory three times after the header changes); the changed TUs were linted
      directly, and CI's `cpp-lint` job passed the whole tree on 2026-09-04.

## 10. Keep (verified good, do not "clean up")

Namespace `Gtk4\`; PHP class == GType name + registry; toggle-ref hold + qdata identity;
GValue-array GClosure marshaller; single GValue bridge; catch → leave scope → report; `ci.sh`
stages; ASan/UBSan/LSan + valgrind + gcov; `EveryClassTest`/`ExampleTest`; PHP 8.4/8.5 × NTS/ZTS
on GTK 4.14 (Linux) and the pinned gvsbuild GTK (Windows); C++20.
