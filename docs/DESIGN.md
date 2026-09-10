# Design

The decisions php-gtk4 is built on, and what follows from each. This is the "why" the rest of the
documentation refers back to: `CLAUDE.md` has the working rules that follow from these decisions,
[gen/README.md](../gen/README.md) the generator's flow, [CHANGELOG.md](../CHANGELOG.md) what
shipped when, and [docs/TODO.md](TODO.md) what is still open.

A decision leaves this file when it stops being true, not when it stops being new.

- **Native Zend API, no framework.** PHP-CPP was dropped after a day: it could not express typed
  signatures, enums, attributes or object handlers and forced a non-standard build. The API is
  declared once in a php-src style stub (`src/gtk4.stub.php` and the generated per-namespace
  stubs), `gen_stub.php` derives the arginfo, `phpize`/`config.m4` build it (PIE-installable), and
  the same sources build on Windows through `config.w32`. PHP 8.4+, C++20, GTK 4.14+, CLI only.
- **Most of the code is generated, and the rest was written with an AI.** The class bindings
  (`src/<Ns>/`, the stubs, the smoke tests, the example skeletons, the class map's status) come
  out of `gen/gir.php`; the runtime core, the generator itself, the tests and the documentation
  were written with Claude Code, reviewed and driven by the maintainer. That is why the gates are
  strict and generic: every method is swept with hostile values, every getter checked against its
  declared type, every GLib critical fails a test, clang-tidy treats every finding as an error.
  Read the code with that in mind, and hold changes to the same gates.
- **Generated from GObject-Introspection, hand-written runtime.** `gen/gir.php` emits one `.cpp`
  per class from the installed `.gir` files for an allow-list with its transitive closure; a type
  outside the closure means the member is skipped and reported, never a placeholder. Hand work lives
  in exactly four places: `gen/overrides/` (a method body or a class prelude), `gen/skip.txt` (what
  is deliberately not exposed, each line with its reason), `gen/handwritten.txt` (a class owned by
  hand from then on) and the MINIT of the non-GObject types in `src/gtk4.cpp`. Generated files
  are never edited; CI fails when a fresh run differs.
- **Modern GTK 4 only.** Everything GIR marks deprecated is skipped in favour of its replacement;
  nothing carries `#[\Deprecated]`. `GtkTreeView`, `GtkDialog` and the pixbuf calls GTK 4.12
  deprecated (`gtk_image_set_from_pixbuf`, `gdk_pixbuf_get_from_texture`) are not bound; the list
  widgets, the async dialogs and the texture bridges that replaced them are
  (`GdkTexture::new_from_bytes()`, `new_for_pixbuf()`).
- **The C API's names, in snake_case, one spelling.** `gtk_window_set_title` is `set_title`,
  properties keep GTK's names with underscores (`$win->default_width`), enum cases are CamelCase
  PHP enums, flags are constant classes. No camelCase aliases.
- **One handle per object, identity preserved.** A PHP handle holds a toggle reference on its
  GObject; while GTK holds other references the GObject holds the `zend_object`, so a PHP subclass
  and its state survive `$box->append(new MyButton())` and come back as the same object from
  `get_first_child()`. A qdata back-pointer makes `===` hold. The ownership rule at construction is
  fixed, not per class: floating references are sunk, `transfer full` results adopted, a `GtkRoot`
  belongs to GTK's toplevel list. Boxed structs are value handles (cloneable, compared by value,
  in-place C operations bound as copies); refcounted non-GObject types (`GdkEvent`, cairo,
  `GskRenderNode`) are handles on a registry with the type's ref/unref pair.
- **PHP subclasses are real GTypes.** `class MyWidget extends GtkWidget` registers a GType at its
  first `new`; `vfunc_<name>()` methods override class-struct slots through generated thunks and
  `parent::vfunc_<name>()` chains down to GTK; `implements GListModel` adds the GTK interface to the
  type. Abstract GTK classes are constructible only through a PHP subclass.
- **A Throwable never crosses GLib.** Every trampoline ends by reporting a pending exception to
  `Gtk::set_exception_handler()`; `ExceptionMode::Log` lets GTK continue, `Rethrow` quits the
  running loops and propagates it. GLib's own criticals and warnings are PHP errors
  (`gtk4.diagnostics`), and a critical on an ordinary path is a missing guard, so the test suite
  fails on it.
- **Convert at the boundary, never coerce.** A value GTK would only assert on is refused first with
  the PHP 8 vocabulary: a bad argument is a `ValueError`/`TypeError` naming the parameter, a wrong
  state a `LogicException`, a dead handle an `Error`. The generator emits those checks from tables
  that mirror GTK's own assertions; a value PHP can build must not end the process. A declared
  return type is a promise the engine does not verify for an internal class, so every getter
  returns what it declares and generic sweeps check every method and getter.
- **Values, not GLib shapes, at the PHP side.** Out parameters are return values (several become a
  list; a boolean-plus-outs returns the outs or null); `GError **` throws `Gtk4\GError`; `GBytes` is
  a string, `GVariant` a PHP value, a `GFile` a path, a `GType` a class name, C arrays are lists;
  signals take exactly `(string $signal, callable $handler)` and closures capture their context.
- **Single GUI thread.** ZTS builds keep per-request state in module globals, but GTK stays
  single-threaded: only the thread that ran `Gtk::init()` may drive the main loop, and the
  loop-driving methods throw `Error` from any other. Worker threads must never touch widgets — hand
  results over with `GLib::idle_add()`. Serving GUI windows from several request threads at once is
  not possible with GTK in any language binding
  ([docs/BUILD.md § Threads](BUILD.md#threads-and-zts)).
- **WebKitGTK is an optional feature, generated like the rest.** `--enable-gtk4-webkit` compiles
  the `WebKit*` classes (the web view, its settings, session, user content, policy decisions,
  permission requests) and JavaScriptCore's `JSCContext`/`JSCValue` - `evaluate_javascript()`
  answers with a value, a script message arrives as one - from `WebKit-6.0.gir` into
  `src/WebKit/` and `src/JavaScriptCore/`, the two namespaces `CONDITIONAL_NAMESPACES` gates:
  left out of the source glob without the flag, registered under `#ifdef` with it, their tests
  skipped where the build lacks them (`tests/Features.php`), `Gtk4\FEATURES` saying which.
  libsoup rides on the same gate (`SoupCookie`, `SoupMessageHeaders`: WebKitGTK's HTTP library
  is how cookies and headers reach PHP). Linux only; Windows gets WebView2 later.
  **WebKitGTK's WebExtensions API (2.52) is deliberately not bound**: Ubuntu builds WebKitGTK
  with `ENABLE(WK_WEB_EXTENSIONS)` off, so every entry point - `webkit_web_extension_new()`,
  every `webkit_web_extension_match_pattern_new_*()` - is a stub that answers NULL without so
  much as a warning. The symbols and the GIR are there; the feature is not. Binding it against
  the WebKitGTK this project builds and tests on would ship a class whose every constructor
  throws. Revisit when a distribution enables it.
- **What is deliberately not bound**, and why - the classes `docs/GTK3-MAP.md` still marks ❌
  are not a backlog:
  - *deprecated in GTK 4.10* - `GtkDialog`, `GtkInfoBar`, `GtkStatusbar`, `GtkEntryCompletion`,
    `GtkAppChooser*`, and the `GtkColorButton`/`GtkFontButton` pair. The binding exposes the
    modern API only and every replacement is bound (`GtkAlertDialog` and the other async dialogs,
    `GtkRevealer` with a label, `GtkDropDown`, the colour and font dialogs with their
    `GtkColorDialogButton`/`GtkFontDialogButton`). Port one only if a real program turns out to
    need it.
  - *out of scope by design* - `GtkPrinter`/`GtkPrintJob`/`GtkPrintUnixDialog` (a separate
    library, gtk4-unix-print, built on the deprecated `GtkDialog`), `GskGLShader` (deprecated in
    4.16), the Broadway/NGL/Vulkan renderer classes, and GIO's stream hierarchy beyond the memory
    streams an image needs to decode and encode (`GInputStream`/`GMemoryInputStream`,
    `GOutputStream`/`GMemoryOutputStream`): no file, socket, buffered or data streams.
  - WebKitGTK's WebExtensions API, for the reason in the WebKitGTK entry above.
