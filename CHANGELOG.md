# Changelog

All notable changes to php-gtk4. Format: [Keep a Changelog](https://keepachangelog.com/),
versions follow [SemVer](https://semver.org/). The version lives in `VERSION` at the repo root and is
mirrored into `src/php_gtk4.h`, `src/gtk4.stub.php` and the built module by `./ci.sh --only=version`
— see docs/RELEASING.md.

## [Unreleased]

### Added

- **`docs/INVENTORY.md`**: every class, interface and enum the extension registers, by GIR
  namespace, each linked to its page in the upstream documentation and marked generated,
  hand-written or php-gtk4's own. Written by `gen/gir.php --install` (`gen/gir/inventory.php`) and
  held to the registered classes by `InventoryTest`.
- **`GdkToplevel`**, the surface side of a window: `get_state()` (the `GdkToplevelState` flags a
  window manager sets - minimized, maximized, focused, tiled), `minimize()`, `lower()`, `focus()`,
  `begin_move()`/`begin_resize()`, `set_icon_list()` (a list of `GdkTexture`, the GTK 4 way to give
  a window an icon of its own), `present()` with a **`GdkToplevelLayout`**, and the `GdkFullscreenMode`,
  `GdkSurfaceEdge` and `GdkTitlebarGesture` enums (`set_modal()` and the `modal` property are left
  out: GDK's Win32 backend corrupts its modal-window list on a repeated write, `docs/TODO.md`;
  `GtkWindow::set_modal()` is the API). A `GtkWindow`'s surface is implemented by a
  backend-private class GIR does not describe, so `get_surface()` used to answer with a bare
  `GdkSurface`; it now answers with `GdkToplevelObject`, a `GdkSurface` that is the interface with
  a body. The mechanism is general: `INTERFACE_FALLBACK_BASE` (`gen/gir/config.php`) names the
  bound class an interface's fallback extends, and `wrap()` refines to it whenever the instance
  implements the interface.

- **`Gdk`, the namespace constants**: `Gdk::KEY_Return`, `Gdk::KEY_Escape`, ... (every keysym
  `gdkkeysyms.h` names, 2,278 of them), `Gdk::BUTTON_PRIMARY`, `Gdk::EVENT_STOP` /
  `EVENT_PROPAGATE`, `Gdk::CURRENT_TIME`, `Gdk::MODIFIER_MASK` - the C `GDK_*` names without
  their prefix, as a `key-pressed` handler compares its keyval against them. Generated from GIR's
  `<constant>`s (`gen/gir.php` learned a `constants` node, allow-listed as `Gdk.constants`).

- **D-Bus.** `GDBusConnection` (`bus_get_sync()` for the session or system bus, `call()` /
  `call_sync()`, `emit_signal()`, `signal_subscribe()`, `register_object()` with PHP handlers for
  method calls and properties, and `GApplication::get_dbus_connection()` with it), `GDBusProxy`
  (`new_sync()` / `new_for_bus_sync()`, `call()` / `call_sync()`, cached properties, the `g-signal`
  and `g-properties-changed` signals), `GDBusMethodInvocation` (`return_value()`,
  `return_dbus_error()`, `return_gerror()` - one answer per invocation, a second is a
  `LogicException`), the introspection records `GDBusNodeInfo::new_for_xml()`,
  `GDBusInterfaceInfo`, `GDBusMethodInfo`, `GDBusSignalInfo`, `GDBusPropertyInfo`, `GDBusArgInfo`
  with their names, signatures and argument lists as read-only properties, `GTestDBus`, and the
  `GBusType`, `GDBusCallFlags`, `GDBusProxyFlags`, `GDBusSignalFlags`, `GDBusConnectionFlags`,
  `GDBusError` enums. A D-Bus body is a PHP list: converted by inference, by the method's
  introspection where a proxy or an invocation has it (`u`, `o`, `a{sv}` and the like that a plain
  PHP value cannot spell), or by a `$signature` the caller gives the connection-level calls. What a
  StatusNotifierItem tray, a screensaver inhibit or "focus the running instance" need.

- **`GLib::set_prgname()` / `get_prgname()`**: the program name GLib knows the process by. GDK
  makes it the X11 window class (`WM_CLASS`), which is what a taskbar matches a desktop entry's
  `StartupWMClass` against - a PHP application was `php8.4` there; set it to the application id
  before `Gtk::init()`.
- **A PHP string is bytes for a typed `ay`**: a D-Bus body, an action parameter or a `GVariant`
  property declared `ay` takes the string's bytes directly (a StatusNotifierItem icon pixmap, a
  file's contents); a list of byte values still converts, and an `ay` still reads as one.

### Changed

- **Boxed record fields**: a C string field reads as a `?string` property and a NULL-terminated
  array of pointers to a bound boxed record as a `list<...>` property (the introspection records
  above are made of them); a flags-typed field reads as its int. Read-only, all of them; a
  refcounted record's `ref_count` is never a property.
- **`tests/run.sh` runs the suite on a private session bus** (`dbus-run-session` with
  `tests/dbus-session.conf`, no service activation) where the tool exists: GTK connects to the
  session bus at init and keeps that connection as the process singleton, so a `GTestDBus` started
  inside a test is bypassed and stalls on finalize. The D-Bus tests skip themselves without a bus.

### Fixed

- **A `throws` function returning a `GVariant` raised nothing on failure**:
  `GDBusProxy::call_finish()` answered `null` for an error reply and leaked the `GError`; it throws
  it now, as every other `throws` return does.

- **A `pie install` knows its commit.** `Gtk4\BUILD_INFO` said `git unknown` for every build from a
  source archive - the release tarball and the GitHub zipball PIE builds from have no `.git`.
  `.git-commit` is now marked `export-subst`, so `git archive` (which is what GitHub serves) writes
  the hash into it, and `config.m4`/`config.w32` read that before asking git. A checkout extracted
  under some unrelated repository no longer reports that repository's HEAD either.

## [0.3.0] - 2026-09-10

Mostly housekeeping around the machinery that ships the extension, plus the rest of Pango: the
text engine's font side, and the geometry a program needs to draw over its own text. It is a
release rather than another dev build because the stubs package is published from a real release
only: this is the first tag that pushes `php-gtk4/stubs` on its own, and the first that
`pie install php-gtk4/php-gtk4` and `composer require --dev php-gtk4/stubs` resolve from Packagist,
where both packages are now registered.

### Added

- **Where text is.** `PangoRectangle` - Pango's struct has no GType, so the binding registers a
  boxed one for it, as it did for `GskRoundedRect` - and with it `PangoLayout::get_extents()`,
  `get_pixel_extents()`, `get_caret_pos()`, `get_cursor_pos()` and `index_to_pos()`: the ink and
  logical boxes, the caret, and one character's cell, in Pango units (`to_pixels()` rounds
  outwards).

- **`PangoLanguage`**, which had been listed as unbindable next to `PangoFont` by mistake: it is
  boxed with a GType of its own. `PangoContext::get_language()`/`set_language()`,
  `GtkTextIter::get_language()`, and the `language` property of `GtkFontDialog` and
  `GtkFontDialogButton` came with it.

- **The font leaves.** `PangoFont`, `PangoFontFamily`, `PangoFontFace` and `PangoFontset` are
  abstract and belong to the backend, exactly like `PangoFontMap` already did - `new` is refused,
  a widget's context loads them (`load_font()`, `load_fontset()`, `get_metrics()`, and the font map
  as a list model of families, each a list model of its faces). `PangoFontMetrics` and
  `PangoGlyphString` are boxed values. A context built with `new PangoContext()` has no font map,
  and the three calls that need one are a `LogicException` on it instead of Pango's assertions.

- **`GskTextNode` has its constructor**, now that a font and a glyph string can cross: the node
  `GtkSnapshot::append_layout()` makes can be taken apart (`get_font()`, `get_glyphs()`,
  `get_offset()`) and rebuilt from the same glyphs in another colour at another offset.
  `get_glyphs()` answers a glyph string holding the node's glyph infos, which is all a text node
  keeps.

- **`PangoGlyphString::set_size()` grows with empty glyphs.** Pango leaves the new entries as the
  allocator left them, so a fresh string measured whatever was in the heap; grown entries are
  `PANGO_GLYPH_EMPTY` with no geometry here, and a negative length is a `ValueError`.

### Changed

- **The gate runs on every push to `main`, published or not.** `verify` used to hang off whether
  there was something to publish, and `coverage` sat behind it, so a `main` parked on an
  already-released `VERSION` - where every real release leaves it until the follow-up bump - ran no
  tests, no coverage and no badge, under a green tick. Whether there is an artifact to upload is a
  question about the artifact; the gate is about the code. Only `build`, `build-windows`,
  `release-gate` and `publish` ask about publishing now, and `WorkflowsTest` fails if either gate
  job grows the condition back.

- **README badges that can go red.** A `badge.svg?branch=main` shows the newest run of that workflow
  *on main*, so the two badges for pull-request-only workflows had been frozen at "passing" since
  2026-08-27 while the release run was failing. The README now carries the two workflows that do run
  on main - the release gate and the C++ lint - plus a coverage badge published by CI itself on
  every merge and a latest-release badge. `WorkflowsTest` rejects a `?branch=main` badge whose
  workflow has no push trigger for main.

### Fixed

- **`PangoLayout::get_caret_pos(-1)` was a SIGSEGV.** Pango asserts the index in
  `get_cursor_pos()` and returns, but `get_caret_pos()` walks on with it, finds no line for a
  negative one and dereferences NULL. The index-taking layout calls now refuse anything outside
  the text as a `ValueError`. `GInputStream::read_bytes(PHP_INT_MAX)` was a GLib-ERROR abort on
  the allocation for the same reason - GLib allocates the whole count first - and is capped at
  what one read can yield. Both surfaced the moment the classes had an instance to sweep: 24
  registered classes had neither a factory branch nor an excuse, so both sweeps had been skipping
  them silently - `PangoLayout` and `PangoFontMap` among them. Every registered class is now built
  or excused, and `GtkInstancesTest` fails on one that is neither.

- **A native `vfunc_*()` whose slot GTK left NULL skipped its argument checks.** The empty-slot
  return came before them, so `parent::vfunc_committed("a\0b")` accepted what the public method
  refuses; the checks come first now, on every generated native vfunc.

- **A WebKit cookie test that failed about one run in ten** (twice on CI, once locally):
  `add_cookie_finish()` reports that the write was *accepted*, not that the store took it, and the
  store lives in a network process the ephemeral session starts lazily - so a cookie written before
  that process first answers was lost silently. The test wakes the store with a read before writing
  and waits for the count it expects instead of taking the first answer.

- The coverage badge's colour, which came out yellow above the floor because a `&&`/`||` chain does
  not mean what it reads like in a shell.

## [0.1.1] - 2026-09-09

### Security

- **Three preconditions GTK 4.22 added, refused at the boundary instead** — so the binding gives
  the same answer on every GTK it supports, rather than passing the value on and letting a newer
  GTK print a `CRITICAL` the caller never asked for. `GtkAdjustment::configure()` and
  `GtkSpinButton::set_range()` now require the page to fit between the bounds
  (`lower + page_size <= upper`, which 4.22 states as a `g_return_if_fail` and 4.14 took
  silently; the spin button reaches it through its own adjustment's page size), and
  `GtkIconPaintable::new_for_file()` requires a non-negative `size` and `scale` — 4.22 builds the
  paintable with `g_object_new()`, whose properties are `g_param_spec_int(0, G_MAXINT)`, so -1 is
  out of range there. All three are `ValueError`s naming the argument (`ArgumentGuardTest`).

- Four more ways a well-typed PHP value ended the process, found by widening `RobustnessTest`
  a second time (see *Added*) so that the classes it could never build get swept too:
  - **`GMenuModel::get_item_link()` / `get_item_attribute_value()` with an index outside the
    model.** A negative one indexes before the item array (SIGSEGV); one past the end reaches
    `g_assert_not_reached()` in `gmenumodel.c`, a `g_error()`. Both are values a loop bound
    produces, and both are a `ValueError` now (`gen/overrides/Gio.MenuModel.*`).
  - **`GtkStack::get_pages()` and a composite widget's `get_first_child()`** hand out objects
    that keep a bare pointer to the object they came from: `new GtkStack()->get_pages()` drops
    the stack on the same line and left a live handle onto freed memory (`get_n_items()`
    answered 497), and measuring a `GtkScale`'s private child after the scale was gone was a
    SEGV. The returned *handle* now keeps its owner's handle alive - `object_hold_owner()`, the
    object counterpart of `BOXED_OWNERS`, listed per member in `RETURNS_HOLD_SELF`. The
    reference is held between the handles rather than the GObjects on purpose: a parent already
    owns its child, so a GObject-level back-reference would have been an uncollectable cycle.
    Sweeping all 1389 arg-less object getters under ASan finds nothing left.
  - **`CairoContext`'s N-double methods were undefined behaviour on a wrong-typed argument.**
    Their shared parser ran `Z_PARAM_DOUBLE` inside a `for` loop; Zend's ZPP macros are a state
    machine whose failure path is a bare `break`, so the loop swallowed it and carried on into a
    point the engine asserts is unreachable. `$cr->set_source_rgb('x', 1.0, 1.0)` was UB that
    the release build happened to survive.

- Two more ways a well-typed PHP value ended the process, both found by widening
  `RobustnessTest` (see *Added*) to sweep the classes it used to skip:
  - **`GtkEditable::get_chars()` with `end < start`** - and every other method returning an
    owned string GIR marks non-nullable - read GTK's NULL result as a PHP string: SIGSEGV.
    The generator emits the same NULL guard the borrowed-string path already had, so a failed
    GTK precondition yields `''`. `GtkEntry`, `GtkText`, `GtkTextBuffer`, `GtkTextIter`,
    `GtkCssProvider` and `PangoFontDescription` were all reachable this way.
  - **`GtkTextIter::set_line_index(-1)` / `set_line_offset(-1)`** is a `g_error()` inside GTK
    ("Byte index -1 is off the end of the line"), which aborts. Both are guarded by hand
    (`gen/overrides/Gtk.TextIter.set_line_*.cpp`) and refuse a negative index with a
    `ValueError`; one past the end of the line is GTK's own business and still clamps.

- Type review of 2026-08-30, the second half of the argument-boundary work:
  - **Property writes are typed.** `$win->title = 'x'` was the one API surface with no type
    checking at all: `$win->default_width = 'garbage'` stored 0, `PHP_INT_MAX` stored -1, and
    `declare(strict_types=1)` had no effect. `core/marshal` now converts exactly like the
    equivalent setter's parameter - strict where the assignment is written, weak coercion
    otherwise - and applies the C type's range. `$win->set_default_size('garbage', 1)` and
    `$win->default_width = 'garbage'` finally agree.
  - **`GtkPopover::popup()` on an unparented popover** asked GDK for a popup surface with a
    NULL parent and segfaulted; it is a `LogicException` now (`gen/overrides`).
    `GCancellable::release_fd()` aborted the process unless `get_fd()` was called first and the
    refcount is not observable from PHP, so it is in `gen/skip.txt`.
  - **Flags arguments are checked against their own mask** (`check_flags`): `new
    GtkApplication($id, 1 << 30)` was accepted, printed a GLib `CRITICAL` and ran with default
    flags. The property path already did this.
  - **`GtkEntry`/`GtkPasswordEntry`/`GtkSpinButton::get_delegate()` broke their own signature**:
    they declare `?GtkEditable` and returned a plain `GtkWidget`, because `GtkText` was unbound
    and `wrap()` stops at the nearest registered *class*. PHP does not verify the return types
    of internal functions, so nothing caught it. `Gtk.Text` is bound now and
    `TypeDeclarationTest` calls every arg-less getter and compares what came back with what was
    declared.

- The PHP -> C argument boundary is checked instead of trusted (security review, 2026-08-30;
  `check_utf8`/`check_range` in `src/php_gtk4.h`, emitted by the generator into every binding and
  applied in `core/marshal` for property writes and signal arguments):
  - **A self-referential or very deeply nested array no longer crashes the process.**
    `$a = [1]; $a[] = &$a;` passed to an action recursed until the C stack was gone (SIGSEGV), as
    did 20 000 levels of nesting. `core/variant` now refuses both with a `ValueError` - a cycle
    guard like Zend's own array walkers use, and a depth cap of 64 (a GVariant cannot be deeper).
  - **Strings with an embedded NUL or invalid UTF-8 are rejected.** A GLib string ends at the
    first NUL, so `set_title("safe\0evil")` stored `safe` while PHP still held the whole string;
    invalid UTF-8 tripped an assertion inside GLib that dropped the value with only a `CRITICAL`
    on stderr. Both are `ValueError` now. `GBytes` parameters stay binary and are not validated.
  - **Integer arguments are range-checked.** `$store->remove(-1)` reached
    `g_list_store_remove()` as 4294967295 and `set_size_request(PHP_INT_MAX, …)` truncated to -1
    ("natural size"); both are `ValueError`. Signed parameters still take -1.
- The gvsbuild GTK archive every Windows binary links against is pinned by content
  (`GVSBUILD_SHA256` in `windows-build.yml`, verified before unpacking and again when the Actions
  cache serves the tree) - a release asset is mutable by its owner, and the cache made one bad
  download persistent.

### Added

- **...and in the other direction too**: a PHP list becomes a C array plus its length, so the
  length parameter disappears from the signature and is filled from `count()`. That finishes
  `GKeyFile`'s setters — `set_string_list()`, `set_integer_list()`, `set_double_list()`,
  `set_boolean_list()` and `set_locale_string_list()` — and brings `GThemedIcon::new_from_names()`
  with them. An element of the wrong type is a `TypeError` naming the argument, and the two
  members GLib asserts a non-empty list for say so as a `ValueError` first.

- **A C array and its length are one PHP list now.** GLib hands an array back beside a
  `gsize *length` out parameter, and the generator had no shape for that, so it reported the
  member and moved on. It maps it now — the length is the list's `count()` — which finishes
  `GKeyFile`: `get_groups()`, `get_keys()`, `to_data()` and the four `get_*_list()` getters, each
  element type converted (strings, ints, floats, bools). A `guint8` array is a PHP string, as
  bytes are everywhere else in the binding, so `JSCValue::array_buffer_get_data()` answers with
  one rather than with a million-element array. Where GIR forgets to link the two — it does for
  `get_groups()` and `get_keys()` — the pair is recognised by shape: one trailing integer out
  called `length` beside an array or string return.

- **The write side of GIO and the 3D types**, which closes the survey of one-class-away gaps:
  `GOutputStream` with `GMemoryOutputStream` (a pixbuf now encodes straight into a PHP string
  and back, no file anywhere), and `GrapheneMatrix`, `GrapheneVec2/3/4` and `GraphenePoint3D` —
  the matrix a `GskTransform` or a `GtkSnapshot` takes, the vector a 3D rotation turns about and
  the point a 3D translation moves by.

- **Pango, and GLib's key file.** The text engine under every label is bound - `PangoLayout`
  (what a widget measures a paragraph with), `PangoContext` and `PangoFontMap` behind it,
  `PangoTabArray` for tab stops and `PangoAttrList` for styling a run of text without markup in
  it — which is around thirty members across `GtkLabel`, `GtkEntry`, `GtkText`, `GtkTextView`,
  `GtkTextTag`, `GtkScale`, `GtkWidget` and the print context. `GKeyFile` comes with them, since
  a `GtkPageSetup`, `GtkPaperSize` and `GtkPrintSettings` all read and write themselves through
  one.

- **Dates, input devices and widgets inside text** — three types that were each blocking
  several members:
  - `GDateTime` with `GTimeZone`: `GtkCalendar::get_date()` and `select_day()` speak it, and so
    do a cookie's expiry and a certificate's validity. A boxed value, so `add_years()` and the
    rest answer with a new date and leave the original alone.
  - `GdkDevice` and `GdkSeat`: which pointer or keyboard an event came from, reachable through
    `GdkDisplay::get_default_seat()` and an event's `get_device()`. Both are abstract and belong
    to the backend, so PHP never builds one — `EveryClassTest` segfaulted on a PHP subclass of
    `GdkDevice` before the constructors were made private.
  - `GtkTextChildAnchor`: a place in a text buffer that holds a widget rather than characters,
    which makes `GtkTextBuffer::create_child_anchor()`, `insert_child_anchor()`,
    `GtkTextIter::get_child_anchor()` and `GtkTextView::add_child_at_anchor()` reachable.
    `new_with_replacement()` now refuses anything but exactly one character, as GTK asserts.

- **Desktop notifications**: `GNotification` and `GApplication::send_notification()`. GTK 4 has
  no tray icon — `GtkStatusIcon` was removed — and this is what took over that job: a title, a
  body, an icon by name, and buttons wired to the application's own actions, handed to the
  desktop under an id that can replace or withdraw it later. Sending from an application that
  is not registered yet is a `LogicException` rather than the GLib assertion it used to be
  (`withdraw_notification()` was already guarded that way; its sibling had never been bound).

- **Icons by name, and opening a URI**: `GIcon` with `GThemedIcon` behind it, and
  `GtkUriLauncher`. Eleven members that had no icon type to speak came back —
  `GtkImage::set_from_gicon()`, `new_from_gicon()` and `get_gicon()`, both of a `GtkEntry`'s icon
  slots, `GMenuItem::set_icon()` and the icon theme's `has_gicon()` / `lookup_by_gicon()` — so an
  icon can be named rather than drawn and left to the theme to find. `GtkUriLauncher` is GTK
  4.10's replacement for the deprecated `gtk_show_uri()`: asynchronous, with the answer in
  `launch_finish()`.

  `GIcon::new_for_string()` and `deserialize()` stay out: they are static functions on an
  interface, and a PHP interface has no bodies (`gen/report.md`). `new GThemedIcon($name)` is
  the way to build one.

- **The containers a GTK 3 program still reaches for**: `GtkListBox` (+ `GtkListBoxRow`),
  `GtkFlowBox` (+ `GtkFlowBoxChild`), `GtkExpander`, `GtkActionBar`, `GtkAspectFrame` and
  `GtkCenterBox` — the last of the class map's "current widgets, no wave needed".

  The boxes come with their callbacks, which is what makes them worth having:
  `GtkListBox::set_filter_func()`, `set_sort_func()`, `set_header_func()` and `bind_model()`,
  and the same minus the headers on `GtkFlowBox`. A filter takes a row and answers `bool`, a
  sort func two rows and answers the usual negative/zero/positive, the header func is handed
  each row with the one above it, and `bind_model()` builds one row per item of a `GListModel`
  and follows it afterwards. A create function that throws, or answers with something that is
  not a widget, is reported through the exception boundary and the row comes out empty rather
  than GTK dereferencing NULL.

  `bind_model()` refuses a builder without a model: GTK returns before it stores the destroy
  notify, so the callable would be leaked, never called and never released.

- **TLS certificates**: `GTlsCertificate` from GIO, which is what a web view reports about the
  page it loaded. Five members came with it — `WebKitWebView::get_tls_info()` and its
  `load_failed_with_tls_errors` vfunc, `WebKitNetworkSession::allow_tls_certificate_for_host()`,
  and `WebKitCredential::new_for_certificate()` / `get_certificate()`. The class is abstract in
  GIO (the concrete one belongs to the TLS backend), so PHP builds a certificate through
  `new_from_pem()`, `new_from_file()` or `list_new_from_file()` and never with `new`.

  Two things had to be taught along the way: a **flags out parameter** is now a plain int, which
  is what `get_tls_info()` needed and what also brought `GdkDisplay::translate_key()` back; and
  `wrap_boxed()` answers a **`GBytes` with a PHP string** the way every other path already did,
  so `get_dns_names()` is a `list<string>` instead of a TypeError about an unregistered type.

- **A `throws` function returning a list dropped its `GError`.** The generator emitted the
  `GError **` and never looked at it, so a failed `WebKitCookieManager::get_cookies_finish()`,
  `get_all_cookies_finish()`, `WebKitWebsiteDataManager::fetch_finish()` or either
  `get_itp_summary_finish()` answered with an empty array — indistinguishable from a genuinely
  empty result — and leaked the error. The list is taken first and the error checked before it
  is converted, exactly as the scalar returns already did.

- **Cookies and HTTP headers**, through a third conditional namespace: `SoupCookie` and
  `SoupMessageHeaders` from libsoup, WebKitGTK's HTTP library, gated on `--enable-gtk4-webkit`
  like the WebKit classes themselves (it needs nothing of its own in `config.m4` — webkitgtk's
  pkg-config already carries `-lsoup-3.0`). Eight members that had no type to speak came back
  with them: `WebKitCookieManager::add_cookie()`, `delete_cookie()`, `get_all_cookies_finish()`
  and `get_cookies_finish()`, and the `get_http_headers()` of a URI request, a URI response and
  a scheme request plus `WebKitURISchemeResponse::set_http_headers()`. A cookie is a boxed
  value, so `clone` copies it; `SoupMessageHeaders::foreach()` walks the headers with a PHP
  callable. `WebKitURIRequest::get_http_headers()` is declared nullable against GIR, which says
  otherwise: a request PHP built is attached to no message and has no headers at all.

- **A URI scheme answered from PHP**: `WebKitWebContext::register_uri_scheme()` puts a callable
  behind a scheme of your own, and `WebKitURISchemeRequest` / `WebKitURISchemeResponse` are what
  it is handed and what it answers with — a stream, a length and a content type, or a response
  object that carries an HTTP status too, or an error. An application can serve its own pages to
  a web view with no network and nothing on disk. A scheme belongs to its context for good
  (WebKit has no unregister call and refuses a second registration, which is a `ValueError` here
  rather than a warning from WebKit), and a malformed scheme is refused before WebKit sees it.

- **The read side of GIO**: `GInputStream` and `GMemoryInputStream`, which turns a PHP string
  into a stream GTK will read. Seven members that were skipped for want of the type came back
  with it - `GdkPixbuf::new_from_stream()`, `new_from_stream_at_scale()` and their async
  counterparts, `GdkPixbufAnimation::new_from_stream()` and `WebKitWebView::save_finish()` - so
  an image that arrives as bytes no longer has to reach the disk to be decoded. `read_bytes()`
  answers with a PHP string and is binary-safe; the base class is abstract, as it is in GIO.

- **`WebKitWebsiteDataManager::clear()`**, and every other member GIR describes with a type
  alias. An alias is a typedef - `GTimeSpan` is a `gint64` with a name - and GIR uses the alias
  wherever the C header uses the typedef, which no arm of the generator's type map matched, so
  the member was skipped over an integer. The loader now reads through aliases of builtins (never
  of `gpointer`: those typedefs are opaque handles, and `G_TYPE_POINTER` is unsupported on
  purpose). `clear()` is the one member that unlocks today; `GTask::return_new_error_literal()`
  also became reachable and is deliberately skipped, because a `GQuark` domain is an interned
  string GLib hands out and PHP has no way to make a meaningful one.

- **The GTK version in use is readable from PHP**: `Gtk::get_major_version()`,
  `get_minor_version()`, `get_micro_version()` and `Gtk::check_version($major, $minor, $micro)`,
  which answers `null` when the running GTK is at least that new and a sentence saying how it is
  not otherwise. It is the library actually loaded, not the one the extension was compiled
  against, and it is what a script asks before using something a later GTK added — `Gtk4\VERSION`
  is php-gtk4's own version and says nothing about GTK.

- **The extension installs with PIE**, and the stubs stay a Composer package:

  ```sh
  pie install php-gtk4/php-gtk4
  composer require --dev php-gtk4/stubs
  ```

  Both come out of this one repository. The root `composer.json` is now `"type": "php-ext"` with a
  `php-ext` block (`extension-name: gtk4`, spelled out because PIE would otherwise derive
  `php-gtk4` from the package name; both thread models; `--enable-gtk4-webkit` offered as a
  configure option) while remaining the development manifest — a consumer ignores `require-dev`,
  `scripts` and `autoload-dev`. On Linux PIE runs the same `phpize && ./configure && make` you
  would, so the GTK 4 development headers still have to be installed first; on Windows PIE never
  builds and takes a release asset instead, so those are now zips named the way PIE looks for them
  (`php_gtk4-<ver>-<X.Y>-<nts|ts>-vs17-x86_64.zip`, holding the matching `.dll`) and both thread
  models ship rather than NTS alone. `PiePackageTest` pins the manifest, `config.m4`'s switches and
  the workflow's asset name to each other, since nothing else fails when those drift apart.

- **WebKitGTK 6, behind `--enable-gtk4-webkit`.** `WebKitWebView` and what an application reaches
  through it - `WebKitSettings`, `WebKitWebContext`, `WebKitNetworkSession` with its cookie and
  website-data managers, `WebKitUserContentManager` with user scripts, style sheets and script
  message handlers, the find controller, back/forward list, inspector, policy decisions, permission
  requests, downloads, resources, dialogs and the boxed records they carry - 74 classes, plus
  JavaScriptCore's `JSCContext`, `JSCValue`, `JSCException` and `JSCVirtualMachine`:
  `evaluate_javascript()` answers with a `JSCValue`, a script message arrives as one, and a
  `JSCContext` runs JavaScript with no page at all. `function_call()`, `constructor_call()` and
  `object_invoke_method()` take a PHP list of values (hand-written, gen/overrides). Generated
  from `WebKit-6.0.gir`/`JavaScriptCore-6.0.gir` into `src/WebKit/` and `src/JavaScriptCore/`, the
  first *conditional namespaces* (`CONDITIONAL_NAMESPACES`, gen/gir/config.php): left out of the
  source glob without the flag, registered under `#ifdef PHPGTK_WITH_WEBKIT` with it. The suite
  runs against both builds - a test of a feature the build lacks skips itself
  (`tests/Features.php`), and the `webkit` CI job runs everything against the WebKit build.
  Linux only (WebView2 on Windows is the plan); the URI scheme handler, the Soup types and TLS
  certificates are what the wave leaves out (docs/TODO.md). The sweeps found three ways a
  well-typed value ended the process before it shipped - a typed-array length JavaScriptCore
  cannot allocate (the two constructors are skipped), a NULL property value it dereferences
  (non-nullable now) and a script `length` past the string's end (a `ValueError`, on the four
  string-plus-length methods) - plus a WebKit bug the property sweep caught: reading
  `WebKitPrintOperation`'s `web-view` property hands the GValue a pointer WebKit does not own,
  so every read dropped one reference of the web view and the second read disposed it (the
  `@property` tag is skipped, `get_web_view()` borrows correctly). `WebKitPrintOperation::run_dialog()`,
  a modal dialog, is excluded from the argument sweep like GTK's print dialogs.
- **The GSK scene graph.** The 35 render node classes (`GskColorNode`, `GskContainerNode`, the
  gradients, shadows, borders, clips, transforms, textures, fills and strokes), `GskRenderer` with
  the cairo and GL renderers, `GskTransform`, `GskPath` with its builder, measure, point and stroke,
  and a hand-written `GskRoundedRect`. A scene is built with `new`, rendered to a `GdkTexture` with
  `GskRenderer::render_texture()`, taken from or appended to a `GtkSnapshot`, and round-tripped
  through `serialize()`/`deserialize()`. Render nodes are GIR *fundamental* classes (refcounted,
  neither GObject nor boxed) that the generator now emits onto the fundamental registry - the
  same identity rules as GObject handles (the same instance is the same handle, a subclass comes
  back as itself), no PHP subclassing. GSK's C arrays are PHP lists: children, `[offset, GdkRGBA]`
  colour stops, `[GdkRGBA, dx, dy, radius]` shadows, four border widths and colours. A realized
  renderer is unrealized when its handle holds the last reference, because GSK aborts the process
  otherwise. `GtkSnapshot::append_node()`, `push_rounded_clip()`, `append_border()`, the shadow
  and fill/stroke appenders and `GtkFixed`'s child transforms came back with the types
  (`RenderNodeTest`, `GskPathTest`, `GskTransformTest`, `GskRoundedRectTest`, `examples/GskRoundedRect.php`).
- **Printing.** `GtkPrintOperation` with its `begin-print`/`draw-page`/`end-print`/`done` signals
  and `GtkPrintContext` (its `get_cairo_context()` is the bound `CairoContext`, so a page is drawn
  like a `GtkDrawingArea`), `GtkPrintSettings` (page ranges as `[start, end]` pairs), `GtkPageSetup`,
  `GtkPaperSize`, and GTK 4.14's async `GtkPrintDialog` + `GtkPrintSetup`. `ACTION_EXPORT` writes
  a PDF with no dialog (`PrintTest`, `examples/GtkPrintOperation.php`).
- **GdkPixbuf.** `GdkPixbuf` with its loader, formats and animations: decode any format from
  bytes or a file, read the pixels as a string, scale, rotate, flip, crop, composite, encode
  (`save_to_bufferv()`/`savev()` take the encoder options as a map). The deprecated pixbuf-to-
  texture calls stay unbound; `GdkMemoryTexture` and `GdkTextureDownloader` move pixels between a
  pixbuf and a `GdkTexture` as bytes in both directions (`PixbufTest`, `examples/GdkPixbuf.php`).
- **`DeprecationTest`** holds the extension to its modern-API-only rule against the installed GIR
  files: no deprecated class is registered, no deprecated method, virtual method, property or
  enum member is bound, and nothing carries `#[\Deprecated]`. It found two deprecated GObject
  properties still promised by `@property` tags (`GtkDropTarget:drop`, `GtkPicture:keep-aspect-ratio`);
  the generator skips those now like it skips deprecated methods.

- **`stubs/` is a Composer package, `php-gtk4/stubs`.** `composer require --dev php-gtk4/stubs` gives
  an IDE and PHPStan the `Gtk4\` API without a checkout of this repository: `stubs/composer.json`
  (no autoload - the extension defines the classes), `stubs/extension.neon` (`scanFiles`, registered
  by `phpstan/extension-installer`) and a README. `release.yml` pushes the directory to the
  `apss-pohl/php-gtk4-stubs` repository and tags it with the extension's version on every real
  release (`publish-stubs`, secret `STUBS_DEPLOY_KEY`), so the stub version always equals the
  extension version (docs/RELEASING.md "The stubs package").
- **`examples/notes/`, a second application.** Notes is a real program next to the class-by-class
  showcase: a `GtkApplicationWindow` subclass with a header bar and primary menu, a `GtkPaned`
  between a searchable, sortable `GtkListView` and a `GtkTextView`, a `GListStore` of PHP objects
  behind the filter, sort and selection models, a toast with Undo, file dialogs, and a JSON file
  saved automatically. `tests/NotesAppTest.php` drives it through its actions and entries.

- **`./update-deps.sh`** — one pass over everything this repository pins to a third party.
  `composer.lock` and the `composer.json` constraints (`--major`), the `package.json` pins, and
  every `uses: owner/repo@v<N>` in the workflows are updated in place; the pins Dependabot cannot
  see are reported — the Windows GTK pin (`--gvsbuild` writes `GVSBUILD_VERSION` and its digest),
  the vendored `gen/gen_stub.php` against the installed php-src copy, and the runner images.
  `--check` writes nothing and exits non-zero when anything is behind, so it fits a cron.

- Three `tests/phpt` cases for runtime paths the PHPUnit suite structurally cannot reach: the
  origin a throw inside an **async callback** is blamed on (stderr only), and what request
  shutdown does with a **live PHP subclass** in a widget tree and with an **armed I/O watch**
  (including one whose stream PHP closed under it) - both crash shapes that would take the
  PHPUnit runner down with them rather than failing a test.

- **The sweeps share one instance factory, and it reaches the classes `new` cannot.**
  `tests/GtkInstances.php` holds a named branch per class no constructor can produce - an
  abstract base, a handle GTK only ever hands out, a constructor argument the generic path
  cannot invent - and both `RobustnessTest` and `TypeDeclarationTest` build their target
  through it, so a class is either live in both or skipped by both. `GdkSurface`, `GdkClipboard`,
  `GdkMonitor`, `GrapheneRect`, `CairoContext`, `CairoSurface`, `GtkListItem`, `GtkCssSection`,
  `GtkNotebookPage`, `GtkIconPaintable`, the three `GtkLayoutChild` classes and the interface
  fallbacks behind `observe_children()` and `get_pages()` came under the sweeps that way, and
  the suite's skips went from 706 to 284. What is still unbuildable is listed in
  `GtkInstances::UNREACHABLE` with the reason - real input events, a drag, the widget-interface
  fallbacks `wrap()` never reaches - and `TypeDeclarationTest` fails if any getter ever hands
  one of them out, which is how `GtkBuilderScopeObject` turned out to be reachable after all.

- **`RobustnessTest` sweeps the whole surface, not the handful of classes it could name.** It
  used to skip any class its hand-written instance map did not list - 3262 of its 4174 cases,
  including all of `GtkTextIter`, `GtkSpinButton`, `GtkBitset` and `GApplication`. It now builds
  an instance of anything with a no-argument constructor, takes the GTK-owned handles from their
  owner (a `GtkTextIter` from its buffer, a `GtkStackPage` from its stack) and fills the
  arguments it is not attacking with a real value of the declared type, so the method bodies run.
  That is 6.5 points of C++ line coverage and the two crashes above.

- Wave 7 — **list models and views** (docs/PLAN.md §3): `GtkListView`, `GtkGridView`,
  `GtkColumnView` (+ `GtkColumnViewColumn`), the factories that build their rows
  (`GtkListItemFactory`, `GtkSignalListItemFactory`, `GtkListItem`), the selection models
  (`GtkSelectionModel` + `GtkSingleSelection`, `GtkMultiSelection`, `GtkNoSelection`) with
  `GtkBitset` as the set of selected positions, and the tree stack (`GtkTreeListModel`,
  `GtkTreeListRow`, `GtkTreeExpander`) — GTK 4's replacement for `GtkTreeView` and
  `GtkCellRenderer`. `GtkScrollInfo` came with them and unlocked `scroll_to()` on the views and
  on `GtkViewport`.
- **A tree unfolds through a PHP callable.** `new GtkTreeListModel($root, $passthrough,
  $autoexpand, $createFunc)` (`gen/overrides/Gtk.TreeListModel.*`) asks
  `function (GObject $item): ?GListModel` for an item's children, null for a leaf; anything else
  is a `TypeError` through the exception boundary and the row stays a leaf. The model has no API
  to take the callable back, so it also keeps it as qdata and request teardown releases it there.
- **A transfer-full boxed argument is copied, not handed over.** `scroll_to($pos, $flags, $info)`
  passed the handle's own `GtkScrollInfo` to GTK, which unreffed it — the next use of the handle
  read freed memory and the process aborted at shutdown. The generator now emits a
  `g_boxed_copy()` for every boxed parameter GIR marks `transfer full`.
- **`clone` and `==` on an opaque boxed record mean something.** A refcounted record registers
  `ref()` as its boxed copy, so `clone $bitset` used to alias the original; such a type now clones
  through its own `copy()` (`BoxedClass::copy`). Comparison goes through the type's own equality
  where it has one (`BoxedClass::equal`: `GtkBitset::equals`, `GtkTextIter::equal`,
  `PangoFontDescription::equal`) — two iterators at different offsets used to compare equal
  because neither has public fields — and by identity for a record with neither.
- The generated fallback class for an interface (`wrap()`'s answer for a GTK-private
  implementation) now also implements the methods the interface *inherits*: without them
  `GtkSelectionModelObject` was abstract and `GtkNotebook::get_pages()` could not be wrapped at all.

- Wave 6 — **text** (docs/PLAN.md §3): `GtkTextView`, `GtkTextBuffer`, `GtkTextIter`,
  `GtkTextMark`, `GtkTextTag`, `GtkTextTagTable` and `GtkWrapMode` (+ the search-flags and
  window-type enums the signatures name). `GtkTextIter` is the first boxed record with a real
  method surface, and it **holds its buffer**: a boxed handle may now own a reference to the object
  it points into (`BOXED_OWNERS` in `gen/gir/config.php`), because an iter kept after the script
  dropped the buffer read freed memory. The insert family (`insert()`, `insert_at_cursor()`,
  `insert_markup()`, `set_text()` and the interactive pair) takes a **byte count that must lie
  inside the string and end on a UTF-8 character boundary** — GTK reads exactly `len` bytes and
  trusts them — defaulting to -1, the whole string, wherever the count is the trailing parameter.
  `GtkTextView::get_extra_menu()` is declared `?GMenuModel`: the 4.14 GIR omits the nullable its
  own documentation states.
- Wave 5 — **dialogs** (docs/PLAN.md §3): `GtkFileDialog`, `GtkFileFilter`, `GtkColorDialog`,
  `GtkFontDialog` and `GtkAboutDialog`, with `PangoFontDescription` (a boxed value: clone copies,
  `equal()` compares), `GtkLicense` and the Pango enums a description uses. GTK 4.10's dialogs are
  async — `open()`/`choose_rgba()`/`choose_font()` return at once and `*_finish()` turns the
  `GAsyncResult` into the answer or throws the `GError`.
- **A `GFile` is a path string.** `Gio.File` parameters take a path or a URI
  (`g_file_new_for_commandline_arg()`) and returns answer with the local path, falling back to the
  URI — a GFile carries nothing else a PHP program can use while the GIO file stack is unbound.
  It also unlocked `GtkPicture::set_file()`/`get_file()`.

- GitHub issue forms (`.github/ISSUE_TEMPLATE/`): a bug form that requires the versions
  (`Gtk4\VERSION`/`BUILD_INFO`/`FEATURES`, `php -v`, `pkg-config --modversion gtk4`), the OS, the
  display server, how the extension was installed, a single-file reproduction script, the expected
  behaviour and the output; a feature form that requires the GTK symbol, the use case and a
  documentation link. Blank issues are disabled and `IssueTemplateTest` guards the required fields.

- Debugging the examples with Xdebug: `.vscode/launch.json` (F5 on an example file debugs its page
  through `examples/demo.php`, plus configurations for the whole demo, an arbitrary script, one
  filtered PHPUnit test and a listen-only session), `.vscode/tasks.json` (build, run, `ci.sh`) and
  `bin/php-gtk4-debug` for the same from a terminal. Breakpoints inside signal handlers and GLib
  callbacks work; `docs/CONTRIBUTING.md` "Debugging" has the caveats.

- Conventional Commits are mandatory (`docs/CONTRIBUTING.md` "Commit messages") and become the
  release notes. `bin/commit-lint` enforces the format from three places — the `commit-msg` hook,
  `./ci.sh --only=commits` (the branch's unpushed commits, or `COMMIT_LINT_RANGE`) and a `commits`
  job in `php-qa.yml` that also checks the PR title, because a squash merge turns the title into the
  commit. `bin/release-notes` groups the commits since the previous release by type and
  `release.yml` appends that to every release body, after the `CHANGELOG.md` section.

- `GLib::io_add_watch($stream, $condition, $callback)` + `GIOCondition`: a socket stream on the
  default main context - the callback runs with the stream and the condition bits when it is
  readable/writable/hung up, returning true keeps the watch; `source_remove()` ends it. PHP keeps
  the descriptor. ext-sockets `\Socket`s join through `socket_export_stream()`.
- Wave 4 (menus/actions, docs/PLAN.md §3): `GMenuModel`, `GMenu`, `GMenuItem`, `GtkPopover`,
  `GtkPopoverMenu`, `GtkPopoverMenuBar`, `GtkMenuButton` (`set_create_popup_func(callable)`),
  `GtkHeaderBar`, `GtkApplicationWindow`, `GtkApplication::set_menubar()`, `GtkArrowType`,
  `GtkPopoverMenuFlags` — generated, `MenuTest`. A menu is a `GMenu` of detailed action names
  (`app.quit`, `win.save`) shown by a popover menu bar, a menu button or a popover; the actions
  are `GSimpleAction`s on the application or the application window; `set_menubar()` works once
  the application is registered (from `startup` on), like the action queries.
- `wrap()` falls back to a generated `<Interface>Object` class (`GListModelObject`, ...) for a
  GTK-private class whose only registered face is an interface: `GtkNotebook::get_pages()` is a
  usable list instead of a bare `GObject`. Fundamental handles keep
  identity (`===` for the same `GdkEvent`/`GParamSpec` while PHP holds it), and `GdkEventSequence`
  is bound as an opaque identity, which unlocks every per-sequence `GtkGesture` method.
- Wave 3 (event controllers, docs/PLAN.md §3): `GtkEventController`, `GtkEventControllerKey`,
  `GtkEventControllerMotion`, `GtkEventControllerScroll`, `GtkEventControllerFocus`,
  `GtkEventControllerLegacy`, `GtkGesture`, `GtkGestureSingle`, `GtkGestureClick`, `GtkGestureDrag`,
  `GtkGestureLongPress`, `GtkGestureSwipe`, `GtkGesturePan`, `GtkGestureZoom`, `GtkGestureRotate`
  with `GtkPropagationPhase`, `GtkPropagationLimit`, `GtkEventControllerScrollFlags`,
  `GtkPanDirection`, `GtkEventSequenceState`, `GdkScrollUnit` — generated — and the `GdkEvent`
  family hand-written on the fundamental registry: `GdkEvent` (type, time, modifier state,
  position, display, context-menu test) with `GdkKeyEvent`, `GdkButtonEvent`, `GdkScrollEvent`,
  `GdkCrossingEvent`, `GdkFocusEvent`, `GdkTouchEvent`, `GdkTouchpadEvent`, `GdkPadEvent`,
  `GdkGrabBrokenEvent` and the enums `GdkEventType`, `GdkScrollDirection`, `GdkCrossingMode`,
  `GdkNotifyType`, `GdkTouchpadGesturePhase`, `GdkKeyMatch`. Events come out of
  `GtkEventController::get_current_event()` and the legacy controller's `event` signal as their
  real subclass; there is no `new`. `EventControllerTest` drives real X input into the Xvfb
  display (`tests/XInput.php`, ext-ffi + libXtst; skipped where unavailable).
- Wave 2 (controls, docs/PLAN.md §3): `GtkEntry`, `GtkEditable`, `GtkEntryBuffer`,
  `GtkPasswordEntry`, `GtkCheckButton`, `GtkToggleButton`, `GtkSpinButton`, `GtkRange`, `GtkScale`
  (`set_format_value_func(callable)`), `GtkProgressBar`, `GtkImage`, `GtkPicture`, `GdkPaintable`,
  `GtkSpinner`, `GtkCalendar`, `GtkDropDown`, `GtkStringList`, `GtkStringObject`, with
  `GtkEntryIconPosition`, `GtkInputPurpose`, `GtkInputHints`, `GtkImageType`, `GtkIconSize`,
  `GtkContentFit`, `GtkSpinType`, `GtkSpinButtonUpdatePolicy`, `GtkStringFilterMatchMode`,
  `GdkPaintableFlags`, `GdkDragAction`, `GtkAccessiblePlatformState` — generated, one example page
  each, `ControlsTest`. Generator: nullable `GStrv` parameters are `?array`
  (`new GtkStringList(null)`).
- Wave 1 (layout, docs/PLAN.md §3): `GtkScrolledWindow`, `GtkViewport`, `GtkScrollable`,
  `GtkAdjustment`, `GtkGrid`, `GtkPaned`, `GtkFrame`, `GtkStack` + `GtkStackPage`/`GtkStackSwitcher`/
  `GtkStackSidebar`, `GtkNotebook` + `GtkNotebookPage`, `GtkOverlay`, `GtkRevealer`, `GtkFixed`,
  `GtkSeparator`, `GtkSizeGroup`, with `GtkPolicyType`, `GtkCornerType`, `GtkScrollablePolicy`,
  `GtkPositionType`, `GtkPackType`, `GtkStackTransitionType`, `GtkRevealerTransitionType`,
  `GtkSizeGroupMode` — generated, one example page each, `LayoutTest` for what round-trips cannot
  show. The demo's sidebar scrolls. Page objects come from their container (`new GtkStackPage()`
  is refused).
- Boxed records generated from GIR (`GtkRequisition` first): fields as properties, value
  semantics, generated registration. Out parameters of string/object/enum/record kind, including
  caller-allocated structs (`GtkWidget::get_color(): GdkRGBA`, `get_preferred_size()`).
- Async API: callback parameters with GIR scope `async`/`call` get a generated trampoline;
  `GtkAlertDialog` (`choose()` + `choose_finish()`), `GCancellable`, `GAsyncResult`.
- GTK interfaces implemented from PHP: `class M extends GObject implements GListModel` is a real
  `GListModel` (`SubclassTest`); `GObject::__construct()`.
- CSS (docs/PLAN.md milestone 8): `GtkCssProvider` (`load_from_string`/`_path`/`_bytes`/`_resource`,
  `load_named`, `to_string`) plus `Gtk::add_provider_for_display()` /
  `remove_provider_for_display()` and the `GtkStyleProviderPriority` constants attach a stylesheet
  to every widget on a display; `GtkStyleProvider` is the interface they take. Parse errors come
  back through the `parsing-error` signal as a `GtkCssSection` (`to_string()`, `get_parent()`,
  `get_start_location()`/`get_end_location()`) and a `GError` — CSS is parsed leniently and the
  loaders never throw, so connecting that signal is the only way to see a typo. `GdkDisplay` is
  bound with it (`get_default()`, `open()`, `get_monitors()`, …), and `GtkWidget::get_display()`,
  `GtkWindow::get_display()`/`set_display()` and `GtkRoot::get_display()` come along.

- PHP subclasses of GObject classes are real GTypes (`src/core/subtype`, docs/PLAN.md §2.6):
  `class MyWidget extends GtkWidget` gets its own GType at the first `new`, constructor arguments
  become construct properties, and `vfunc_<name>()` methods override the GTK class-struct slots
  (`measure`, `size_allocate`, `get_request_mode`, `match`/`get_strictness` on `GtkFilter`,
  `compare`/`get_order` on `GtkSorter`, `clicked`, `activate`/`startup`/`shutdown` on
  applications, …) with `parent::vfunc_<name>()` chaining down to GTK's implementation. Abstract
  GTK classes (`GtkWidget`, `GtkFilter`, `GtkSorter`, …) now have a public constructor that
  refuses the native class and works on a subclass.
- Windows build: `config.w32` (PHP SDK `phpize.bat` + `configure --with-gtk4=<root>` + `nmake`,
  GTK 4 from [gvsbuild](https://github.com/wingtk/gvsbuild)), `pin_gtk_library()` Win32
  counterpart (`GetModuleHandleEx` + `GET_MODULE_HANDLE_EX_FLAG_PIN`), `bin/php-gtk4.cmd`,
  `tests/run.cmd`, the `windows.yml` workflow (build + load + PHPUnit on PHP 8.4/8.5) and a
  `php_gtk4.dll` asset per supported PHP in every release.

- ZTS builds: per-request runtime state moved into module globals (`src/core/globals.h`);
  `config.m4` and `php_gtk4.h` no longer refuse thread-safe PHP, CI tests one ZTS variant.
  GTK itself stays single-threaded — `Gtk::init()`, `GtkApplication::run()`, `GMainLoop::run()`
  and `GLib::main_context_iteration()` throw `Error` from any thread other than the one that
  initialised GTK.
- `docs/BUILD.md` (build process, variants, Windows status and route, threads) and
  `docs/CONTRIBUTING.md`; README links to them.
- CI: the asan stage preloads `tests/asan-dlopen-shim.c` so PHP builds that `dlopen()` extensions
  with `RTLD_DEEPBIND` (setup-php) can load the sanitized module; phpt runs with `GTK_A11Y=none`.
- libgtk-4 is pinned in the process at MINIT (`RTLD_NODELETE`): PHP unloads extensions at
  MSHUTDOWN, and unmapping GTK under its fontconfig warm-up thread crashed at exit after a failed
  `Gtk::init()` on machines with a cold fontconfig cache (CI runners).

- Native Zend API runtime (`GObject` handles with property access, `GValue`/`GVariant`/boxed
  marshalling, GClosure-based signals, `emit()`, exception boundary with `ExceptionMode`).
- `GtkBox`, the first layout container: GTK 4 has no `GtkContainer`, so this is what lets a window
  hold more than one widget. With `GtkWidget::set_hexpand()`/`set_vexpand()` it turns
  `examples/demo.php` into a real application (header, sidebar, content) instead of a slideshow.
- Classes: `GObject`, `GParamSpec`, `GtkWidget`, `GtkWindow`, `GtkButton`, `GtkLabel`,
  `GtkApplication`, `GMainLoop`, `GLib` (sources), `GSimpleAction` + `GAction`/`GActionMap`/
  `GActionGroup`, `GdkRGBA`, `GdkRectangle`, `Gtk`, `ExceptionMode`, `GError`, `GdkTexture`,
  `PhpValue`, `GListStore` + `GListModel`, `GtkDrawingArea` + `CairoContext`, `GtkFilter`/
  `GtkCustomFilter`/`GtkFilterListModel`, `GtkSorter`/`GtkCustomSorter`/`GtkSortListModel`,
  enums `GtkAlign`, `GtkOrientation`, `GtkFilterChange`, `GtkSorterChange`, flags `GApplicationFlags`.
- Tooling: `ci.sh` stages, sanitizer/valgrind/coverage runs, stub-driven arginfo, IDE stub and
  method comments, git hooks, Dependabot.
- `gen/gir.php`, the GIR generator (milestone 3): `--install` writes per-namespace stubs
  (`src/<Ns>/<Ns>.stub.php` + arginfo), one `.cpp` per class, `src/gen_minit.inc`, example
  skeletons and `gen/report.md`; inputs `allowlist.txt`, `handwritten.txt`, `skip.txt`,
  `overrides/` (method bodies and class preludes). `./ci.sh --only=gen` fails on a stale tree.
  Wave 0: `GtkWidget`, `GtkWindow`, `GtkButton`, `GtkLabel`, `GtkBox`, `GtkDrawingArea`, the
  filter/sorter/list-model classes, `GtkApplication` (+ its real parent `GApplication`),
  `GdkTexture`, `GListStore`/`GListModel`, the `GAction*` interfaces and `GSimpleAction` are now
  generated with GTK's full 4.14 API (e.g. +93 methods on `GtkWidget`), plus every enum/flags type
  their signatures use.

- Generated smoke tests: `tests/Generated/<Class>SmokeTest.php` per generated class (construction,
  every setter/getter pair and writable property round trip; `gen/smoke-skip.txt` excludes what
  GTK legitimately does not honour). Hand-written `LabelTest`, `ButtonTest` and new cases in
  `WidgetTest`, `BoxTest`, `ApplicationTest`, `FilterSortTest`, `TextureTest`, `ActionTest`
  cover the semantically interesting new API; every generated class and enum has a visual
  `examples/` page.
- Constructors that GTK refuses (`g_return_val_if_fail`, e.g. an invalid application id) throw
  `Error` instead of leaving a dead handle.

### Changed (wave 0 - the API follows GTK's shape)

- Constructors mirror GTK: `new GtkWindow()` + `set_application()` (was `new GtkWindow($app)`),
  `GtkButton::new_with_label()` (was `new GtkButton($label)`), `GSimpleAction::new_stateful()`
  (was a third constructor argument), `new GtkBox($orientation, $spacing)` with no defaults,
  `GtkFilter/GtkSorter::changed($change)` requires the enum, `GtkApplication::__construct($id, $flags)`.
- `GtkWidget::show()/hide()` (deprecated in GTK 4.10) are not exposed; use `set_visible()`.
- `GdkTexture::save_to_png()` returns `bool` like GTK instead of throwing.
- PHP-side validations GTK does not make were dropped (`GtkBox` spacing/child checks, `GListStore`
  bounds and item type, application-id and action-name validity): GTK reports them as criticals.
- `GtkWidget` and other GIR-abstract classes are no longer `abstract` in PHP (`wrap()` needs to
  instantiate them for GTK-created objects); `new` is refused by a private constructor.
- `Gtk::testing_iterate_nested(int $iterations)` and `Gtk::testing_run_dispose(GObject $object)`
  drive from C what PHP cannot produce — a GTK-internal nested main loop, and disposal under a
  live handle — which lets the suite cover parked-exception `previous` chaining, the enclosing
  `run()` rethrow (`RethrowModeTest`, `tests/scripts/stress.php`) and disposed-handle errors
  (`WrapTest`). They are compiled into every build and their docblocks say they are not part of
  the supported surface; there is no configure switch, so the suite runs what ships.
- Constructor ownership rule for the generator (docs/PLAN.md "Ownership", gen/README.md):
  floating → `attach_new()`, `GtkRoot` implementor → `attach()`, plain transfer-full GObject →
  `attach_new()`, else a generator error; `attach_new()` now detects a `GtkRoot` at runtime
  (`g_critical`, then `attach()` semantics).
- `GLib::main_context_iteration(bool $may_block = false): bool` — one iteration of the default
  context without handing control to `run()`.
- `ExceptionMode::Rethrow` inside an *unregistered* nested main loop (GTK iterating the context
  itself, or `main_context_iteration()`): the Throwable is parked instead of left pending across
  C frames, handlers keep running so the inner loop can finish, and the next `run()` /
  `main_context_iteration()` returning to PHP rethrows it. A second Throwable meanwhile is chained
  as `previous`; anything still parked at request shutdown goes to the handler / `g_critical`.
- `get_property_ptr_ptr`/`unset_property` handlers on GObject and boxed handles: `$w->width++`,
  `$w->title .= 'x'`, `$rgba->red += 0.1` now write through (they used to create a shadow dynamic
  property and drop the write); `unset($w->title)` throws `Error`. `StubsTest` gates snake_case
  method and parameter names in the stub (`GError::getDomain()` is the one allowed exception).

### Changed

- **The GType -> PHP class registry no longer keeps a copy of itself per registration.** It was
  copy-on-write - readers on the `wrap()` path took no lock, and every registration published a
  fresh copy of the whole map that was never freed, so what a process retained grew with the
  square of the PHP subclasses it registered. It is an append-only hash table now: entries are
  written once and never changed or removed, so a fixed bucket array of atomic list heads needs
  no rehash, and a registration pushes one node. Readers still take no lock. Measured over 1 000
  PHP subclasses, RSS after registration went from +65.3 MB to +44.1 MB - the ~21 MB the
  snapshots were holding. `SubclassTest` registers 600 classes and has GTK hand every one of them
  back through `wrap()`.

- **Action queries on an unregistered `GApplication` refuse instead of answering.** `has_action()`,
  `get_action_enabled()`, `get_action_state()`, `get_action_parameter_type()`,
  `get_action_state_hint()`, `get_action_state_type()` and `change_action_state()` sat below
  `g_application_query_action()`, which is a `g_return_if_fail()` until `startup`: GLib CRITICALed
  and they answered `false` / `null`, which reads like "no such action" rather than "ask me
  later". All seven now raise the `LogicException` `list_actions()` already raised
  (`gen/overrides/Gio.ActionGroup.cpp`). `add_action`, `remove_action` and `lookup_action` are
  `GActionMap` and keep working at any time.
- **GLib's diagnostics are PHP errors** — a new `gtk4.diagnostics` ini directive (`PHP_INI_ALL`).
  GTK refuses a value by logging (`g_return_if_fail()` → `g_critical`), which means the *script*
  did something wrong; that used to reach stderr with no file, no line, no `error_reporting`, no
  `set_error_handler` and no `error_log`, and vanished entirely when stderr was redirected. They
  now arrive as PHP errors at the line that caused them:

  ```text
  PHP Warning:  Gtk: gtk_editable_get_chars: assertion 'end_pos == -1 || end_pos >= start_pos'
                failed in /home/you/app.php on line 12
  ```

  `warning` (the default) reports `CRITICAL` and `WARNING` as `E_WARNING`; `fatal` promotes a
  `CRITICAL` to `E_ERROR`; `stderr` restores GLib's own writer; `off` drops them. php-gtk4's own
  reports — the `ExceptionMode::Log` fallback for an uncaught handler exception, an unbound
  `vfunc_*()` — take the same path and are always `E_WARNING`, because `Log` promises GTK keeps
  running. Reporting happens at the VM's next safe point (`zend_interrupt_function`), never in the
  GLib writer, which runs inside arbitrary GTK frames where PHP must not (`src/core/diagnostics.cpp`).
  `G_LOG_LEVEL_MESSAGE` — GTK advising rather than refusing, "GtkDialog mapped without a transient
  parent" — becomes an `E_NOTICE` on the same path.
- `gen/gir.php` is six files: the loader, the model, the type map, the writer, and the emitters
  with the CLI that stay in `gir.php` (docs/TODO.md §10). Each step of the split was verified by
  regenerating - every one of the 178 generated files stayed byte-identical - and the type map is
  now reachable on its own, which `GeneratorTypeMapTest` uses.

- `./ci.sh` no longer does pointless work: `gen/gir.php` writes a generated file only when its
  content changed (rewriting identical files bumped their mtimes and cost ~50 s of recompiling on
  every run), and clang-tidy remembers what it linted at which content in `.ci/tidy-ok`
  (`GTK4_LINT_ALL=1` forces a full pass; any header change still relints everything). A run that
  touched no C++ went from ~5 min to ~35 s, so `pre-push` keeps running the full gate —
  `GTK4_PREPUSH=fast git push` drops `build,load,test,phpt` when you want it anyway.

- Interfaces with vfuncs declare only their vfunc-backed methods in PHP (`GListModel`:
  `get_item_type`, `get_n_items`, `get_item`); the utility methods remain on the implementing
  classes. Native `vfunc_*()` callable only from a PHP subclass; `emit()` arity errors are
  `ArgumentCountError`.
- The generator throws on an unresolvable MINIT parent (was a silent comment), skips
  caller-allocates out parameters (never a by-reference PHP parameter), emits `interface X
  extends Y` from GIR prerequisites, puts thunks in an anonymous namespace, and seeds the MINIT
  order with the hand-written classes. `gen/gir.php` is analysed by PHPStan against a baseline.
- Handles keep a *toggle* reference on their GObject: while GTK holds the object (a parented
  widget, a `GListStore` item, a window in the toplevel list) the PHP object stays alive with it,
  so a PHP subclass appended without keeping a reference is returned from `get_first_child()` as
  that subclass with its state, not as a fresh base-class wrapper. Released when GTK lets go and
  at request shutdown.
- Source layout follows the GIR namespaces: `src/GLib/` (`GLib`, `GMainLoop`, `GError`),
  `src/GObject/` (`GObject`, `GParamSpec`, `PhpValue`), `src/Gio/`, `src/Gdk/`, `src/Gtk/`,
  `src/Cairo/`; `src/core/` contains no `ZEND_METHOD` any more. The `GListModel` interface methods
  are implemented once (`src/Gio/GListModel.cpp`) and aliased into `GListStore`,
  `GtkFilterListModel` and `GtkSortListModel` via `@implementation-alias` in the stub. The MINIT
  block has three registration shapes only (`src/classes.h` declares the non-GObject hooks);
  `config.m4` compiles every `src/**/*.cpp` it finds instead of a hand-kept list.
- `ci.sh` rejects unknown `--options` and stages (exit 2) instead of forwarding them to phpunit,
  has `--help`, and falls back to the unversioned `clang-tidy`/`clang-format` when no
  `/usr/bin/clang-*-N` exists. `tests/run.sh`/`buildall.sh` run with `set -euo pipefail`.
- `.clang-tidy` enforces naming (`readability-identifier-naming`: snake_case functions/variables,
  CamelCase types, UPPER_CASE macros; `ce_<GTypeName>` class entries allowed). Parameter classes
  are resolved with `class_for_gtype(G_TYPE_X)` instead of by name string. `markdownlint-cli2` is
  pinned in `package.json` so Dependabot tracks it. `tests/TestPng.php` → `tests/PngFixture.php`.
- CI: the PR workflows declare a read-only token; `release.yml` pins its actions by commit SHA.
- Error vocabulary settled (CLAUDE.md): argument errors → `ValueError`/`TypeError`, wrong object
  state → `LogicException` (`GSimpleAction::set_state()` on a stateless action now throws
  `LogicException` instead of `Error`), handle-level impossibilities → `Error`. Shared helpers
  `PHPGTK_RETURN_STRING_OR_NULL` and `src/Gtk/children.h` (`?GtkWidget` arguments, unparented /
  child-of checks; `GtkBox` messages now say "this GtkBox" / "its parent").
- Parameter names in the public API are snake_case (`$handler_id`, `$interval_ms`, `$source_id`,
  `$item_type`, `$parameter_type`, `$application_id`, `$css_class`, `$draw_func`, `$match_func`);
  named-argument callers using the old camelCase spellings break.

### Fixed

- **A `throws` function that answers `false` is not necessarily failing.** The generator read a
  `gboolean` return as the success flag, so `GKeyFile::has_key()` threw "GTK returned no result
  and no error" for a key that was simply not there. The `GError` decides now, as it already did
  for every other return shape — GLib sets it on every real failure.

- **A boxed record whose `copy()` takes its operand non-const** (`pango_attr_list_copy()`) did
  not compile; the emitter now reads the instance parameter's constness the way it already did
  for `equal()`.

- **`GtkTextView::set_tabs(null)`** was refused: GIR does not mark the parameter nullable
  although GTK restores the default tab stops on NULL, exactly as its `GtkLabel` and `GtkEntry`
  siblings do. A new `NULLABLE_PARAMS` table is the mirror of the existing `NON_NULLABLE_PARAMS`.

- **A string-vector parameter declared mutable** (`gdk_pixbuf_save_to_streamv_async()`) did not
  compile: the emitter cast every one to `const char **`. It follows the declared type now, the
  way the boxed `copy()` fix above does.

- **`GMemoryOutputStream::steal_as_bytes()`** on a stream that is still open was a GLib
  assertion and a null dereference; it is a `LogicException` naming the missing `close()`.

- **`GtkBuilder::add_objects_from_file()`** and its two siblings now refuse an empty id list,
  which GTK asserts on, and **`GKeyFile::load_from_data_dirs()`** refuses an absolute path — the
  file is looked up *in* the data directories, so the binding no longer resolves it against
  PHP's working directory first.

- **A vfunc slot answered GTK with its default whenever a PHP exception was in flight**, which
  is a lie about the object rather than a safe fallback: a PHP `GListModel` asked how many rows
  it has while a throw was unwinding said "0", and `GtkListView`'s item manager was left holding
  rows GTK believes gone - `gtk_list_item_manager_clear_model: assertion failed
  (gtk_rb_tree_get_root (self->items) == NULL)` and `0xC0000409` on a GTK built with assertions
  (every Windows CI job died there, in `PhpSelectionModelTest`), a silent leak on one without.
  The generated thunks now *park* a pending Throwable for the duration of the call
  (`zend_exception_save()` / `zend_exception_restore()`, what Zend does around a `__destruct()`),
  so the slot answers with the object's own state and the parked Throwable comes back as the
  previous of anything the call itself threw - reported exactly once, as before.

- **`GtkFixedLayoutChild::$transform` read through the property system was a GLib CRITICAL** -
  GTK 4.14's getter hands the GValue the address of its pointer. The `@property` tag is gone
  (a new `<Ns>.<Type>.property:<name>` key in `gen/skip.txt`); `get_transform()`/`set_transform()`
  are what the stub promises.

- **A PHP property declared on a GObject subclass was hidden by a GObject property of the same
  name.** `class NotesWindow extends GtkApplicationWindow { private GtkEntry $title; }` wrote
  `GtkWindow`'s gchararray `title` (a `TypeError`, or a silent success for a string) and never read
  its own. The object handlers now leave a name the PHP class declares to the engine, so the
  author's property wins and GTK's stays reachable through its methods; `var_dump()` shows the
  PHP properties too, and leaves out the GObject ones they shadow (`PropertyAccessTest`).

- **Six unbound enums were checked as if they were flags.** `GtkSystemSetting`, `GtkScrollType`,
  `GtkDeleteType`, `GtkMovementStep`, `GtkTextExtendSelection` and `GtkTextViewLayer` are GIR
  `enumeration`s that nothing binds as PHP enums, so they cross as ints - and the generator
  checked them with `check_flags()`, which casts the `GEnumClass` to a `GFlagsClass` and reads
  `mask`, a field that is `minimum` there. It is 0 for all six, so *every* non-zero value was
  refused with a message about a mask of `0x0`, and the read itself was of the wrong type. They
  use `check_enum_member()` now. Found on a ZTS run where GTK delivered a real
  `GtkSystemSetting` to a PHP `vfunc_system_setting_changed()`.

- **A parameter now has to be inside the domain its C function documents**, not just inside its
  C type. GTK states these as `g_return_if_fail()`, which means a `CRITICAL` and the call
  silently not happening - `$calendar->set_month(12)` left the month where it was and told PHP
  nothing. `PARAM_DOMAINS` (`gen/gir/config.php`) lists each bound, copied from the assertion
  GTK itself printed: the `GtkCalendar` setters, `GtkDrawingArea`'s content size,
  `GtkEditable::get_chars()`/`delete_text()`, `GtkGrid::attach()`'s spans,
  `GtkGestureLongPress::set_delay_factor()`, `GtkIconTheme::lookup_icon()`'s scale and
  `PangoFontDescription::set_size()`. That retired 33 lines from
  `tests/robustness-criticals.txt`; four more went with the guards on
  `GdkClipboard::read_async()` (an empty mime list is refused), `GdkPaintable::compute_concrete_size()`
  (the CSS sizing algorithm's domain) and `GtkSelectionModel::selection_changed()` (a range past
  the end of the model).

- **`GtkEventController::get_widget()` declares `?GtkWidget`**, which is what it answers: GIR
  does not mark the return nullable, but `priv->widget` is NULL until the controller is added to
  a widget, and PHP can hold one that never was. `NULLABLE_RETURNS` in `gen/gir/config.php` is
  where such a correction goes; `TypeDeclarationTest` is what finds them.

- **`GdkClipboard`'s four async reads take a non-nullable callback.** GIR marks the
  `GAsyncReadyCallback` nullable because C allows a fire-and-forget call; GDK asserts
  `callback != NULL` and does nothing at all, so `?callable` was a promise the binding could not
  keep. `NON_NULLABLE_PARAMS` drops the `?`, making null an ordinary `TypeError`.

- `ci.sh` preloads `libubsan` alongside `libasan` in the `asan` stage. The build is
  `-fsanitize=address,undefined` and gcc keeps the UBSan handlers in their own library, so a
  finding surfaced as `php: symbol lookup error: gtk4-asan.so: undefined symbol:
  __ubsan_handle_builtin_unreachable` with no location instead of a report.

- Generated methods handed a transfer-full string parameter (`GtkStringList::take()`) Zend's own
  buffer, which GTK then `g_free()`d (`free(): invalid pointer`); the generator passes a
  `g_strdup()` copy for transfer-full strings now.
- Generated `throws` methods with a scalar return (`GtkAlertDialog::choose_finish()`,
  `GTask::propagate_int()`) checked the `GError` for nothing: a dismissed dialog came back as
  `-1` and the error leaked. They throw the `GError` now (`GTaskTest`).
- A `GError` *parameter* (`GTask::return_error()`) was generated as a boxed handle and
  dereferenced NULL in argument parsing: the generator builds a `GError` from the `Gtk4\GError`
  exception now (`gerror_from_php()`, domain `php-gtk4-error-quark` when the PHP side set none).
- A boxed record the GIR gives no constructor (`GtkTextIter` will be the first) refuses `new`
  like the fundamental handles do, and a boxed method on a handle without data throws an `Error`
  instead of dereferencing NULL (`PHPGTK_BOXED_SELF` is a statement pair now, like `PHPGTK_SELF`).
- Handles know when GTK *disposed* their object while PHP still held it (a weak notify set in
  `arm()`): methods and argument passing throw an `Error` from then on instead of driving a gutted
  widget. GTK 4's `gtk_window_destroy()` does not dispose a window PHP holds (it only drops GTK's
  reference — `WrapTest`), so this guards C-owned disposal; `Gtk::testing_run_dispose()` triggers
  it in test builds.
- `callback_invoke()` reports a Throwable that is already pending when a non-signal callback is
  entered in `Log` mode (it can only come from the trampoline's own argument conversion) instead of
  leaving it to unwind through the GLib frame.
- `throw_gerror()` on a NULL result without a `GError` (a failed GTK precondition) threw a plain
  `Error` instead of dereferencing NULL; `GtkFilter`'s `gptrarray_to_php`/`strv_to_php` no longer
  double-free (transfer full with a free func) or leak (transfer container).
- A PHP `__destruct` running inside a GTK frame (a handle GTK let go of in a main-loop dispatch)
  now goes through the exception boundary; a Throwable pending at request shutdown is reported.
- Generated vfunc thunks skip the PHP method while an exception is already pending (Rethrow
  mode reported one Throwable once per remaining thunk); native `vfunc_*()` empty slots are
  no-ops instead of errors.
- Two PHP classes whose names map to the same GType name (`App\Foo` / `App__Foo`, and *every*
  anonymous class — their names carry a NUL) no longer share one GType; the second one throws.
- `subtype` `instance_init` binds only the instance being constructed; `gui_thread` and the
  enum-verification flag are atomic (ZTS); the fundamental registry walks GType parents (a
  `GdkKeyEvent` handle is a `GdkEvent`); `emit()` with the wrong argument count throws
  `ArgumentCountError`, unknown signals/properties/actions name the argument.
- `.clang-tidy`'s header filter never matched (absolute include paths): `src/**/*.h` are linted
  now (`--header-filter` from `ci.sh`, gen_stub arginfo excluded), findings fixed; `--fix` runs
  clang-tidy one TU at a time so shared headers are patched once.
- Build: `config.m4` and `config.w32` derive the source directories from the tree (a new
  namespace directory such as `src/Pango` needs no edit); `ci.sh`'s `gen` gate covers
  `gen/report.md`, the map and new example skeletons and runs on pull requests (`cpp-lint.yml`)
  and in the pre-commit hook; `PHPT_TESTS` documented; `--only=tidy` rejected with a hint;
  compiler-warning gate limited to our sources.
- RSHUTDOWN teardown walks the live registries instead of a snapshot, so a disconnect that
  finalizes another tracked object can no longer leave a dangling pointer for the next iteration.
- `ci.sh --only=cpp-lint` on a fresh checkout failed with `'config.h' file not found` (the stage
  runs before `build`); it now runs `phpize && ./configure` itself when `config.h` is missing.
  This is what broke every `release.yml` verify job. The job also pins clang 20 now, like
  `cpp-lint.yml`.
- `config.m4` lacked the `src/Cairo` build directory (out-of-tree builds failed).
- `cpp-lint.yml` never ran clang-tidy/clang-format (it gated on a step that did not exist); it now
  installs clang 20 and runs `./ci.sh --only=cpp-lint` like the pre-commit hook.

## Release checklist

Full details in docs/RELEASING.md. `VERSION` is the only trigger — there is no tag to push.

1. `VERSION`: drop the `-dev` suffix (`0.2.0-dev` → `0.2.0`).
2. `./ci.sh --only=version,stubs --fix` — propagates into `src/php_gtk4.h` and `src/gtk4.stub.php`.
3. Move the Unreleased entries under `## [0.2.0] - YYYY-MM-DD`. The release workflow copies that
   section into the GitHub release body and fails if it is missing.
4. `GVSBUILD_VERSION` in `windows.yml` and `release.yml`: still the GTK you want the `.dll` built
   against? (nothing bumps it automatically — `docs/BUILD.md` "The pinned GTK version").
5. `./ci.sh --with=asan,coverage,valgrind` green.
6. Merge as a `release: 0.2.0` PR — `.github/workflows/release.yml` tags, builds and publishes.
7. Follow-up PR: `VERSION` → `0.3.0-dev`, `./ci.sh --only=version,stubs --fix`, fresh `## [Unreleased]`.

Between releases every merge into `main` publishes a `vX.Y.Z-dev.<run>` pre-release instead; the
newest five stay downloadable.
