# php-gtk4 — Implementation Plan

Goal: a native PHP extension binding GTK 4 to PHP, informed by the working php-gtk3 extension
(`/mnt/share/dev/code/php-gtk3`). Keep what worked there; replace the parts that were manual,
fragile, or GTK3-specific.

## 0. Decisions (fixed unless revisited)

| Topic | Decision | Reason |
|---|---|---|
| Zend abstraction | **PHP-CPP** (fast-debug fork, PHP 8.4-capable), same as php-gtk3 | Proven; maintainer knows it; exceptions ↔ throwables for free |
| Language | C++20 (GCC 11+/Clang 14+) | PHP 8.4 itself needs a modern toolchain, so nothing is lost; gives `std::span`, concepts, designated initialisers for the marshaller/generator output |
| GTK target | GTK 4.14+ (`gtk4` pkg-config), GLib 2.76+ | Current LTS distros |
| Module name | `gtk4` everywhere (Makefile `NAME`, `Php::Extension("gtk4")`, `gtk4.ini`) | php-gtk3 lesson: the four names must agree |
| PHP versions | **8.4+ only** (Makefile and `main.cpp` refuse older) | One Zend ABI to care about; lets stubs/tests use 8.4 syntax (property hooks, `#[\Deprecated]`, `new X()->m()`, typed constants) |
| Class naming | PHP class name == GType name (`GtkButton`, `GdkTexture`, `GtkEventControllerKey`) | Enables generic C→PHP wrapping by `g_type_name()` |
| Code generation | **Generated** wrapper skeletons from GObject-Introspection (`Gtk-4.0.gir`), hand-written runtime core | php-gtk3 hand-wrote 260 classes + a 5000-line `get_module()`; GTK4 ships complete GIR |

## 1. Architecture

```
PHP script
  │
PHP-CPP
  │
main.cpp            get_module(): calls generated registrar per namespace (G, Gio, Gdk, Gsk, Gtk, Pango)
  │
gen/ output         src/gen/<Ns>/<Type>.{h,cpp}  — one class per GType, method bodies generated
  │
src/core/           hand-written runtime (the real work):
                      wrap.{h,cpp}      PHP object <-> GObject* mapping, refcounting, identity
                      marshal.{h,cpp}   GValue <-> Php::Value (complete, both directions)
                      gsignal.{h,cpp}   GClosure-based signal connection, PHP callable invocation
                      callback.{h,cpp}  one abstraction for every C callback entry point (timeouts, funcs, closures)
                      error.{h,cpp}     the exception boundary + Gtk::set_exception_handler
                      params.{h,cpp}    parameter validation helpers
  │
GTK4 / GLib C API
```

## 2. Core runtime (hand-written, build first)

### 2.1 Object wrapping and lifetime (`wrap`)
- `GObjectWrapper : Php::Base` holds `GObject *obj` (typed, not `gpointer*`).
- **Own a reference**: on wrap, `g_object_ref_sink()`; in the C++ destructor, `g_object_unref()`.
  php-gtk3 never ref'd anything and relied on GTK container ownership — works for widgets, leaks or
  dangles for everything else (GdkTexture, GListStore, GtkStringList...).
- **Identity**: store a weak back-pointer to the PHP object in `g_object_set_qdata(obj, PHPGTK_QUARK, …)`.
  `wrap(GObject*)` returns the *existing* PHP object if one exists, else creates one — so
  `$a->get_parent() === $box` holds and per-object PHP state (dynamic properties) survives round trips.
  Clear the qdata in the destructor. Use a weak ref (`g_object_weak_ref`) so a GTK-side finalize
  nulls the PHP handle instead of leaving a dangling pointer.
- **Dynamic class selection**: `wrap()` walks `G_TYPE_FROM_INSTANCE` up the parent chain until it
  finds a registered PHP class (so an unregistered subclass still returns e.g. `GtkWidget` rather than
  throwing "Unknown class name" as php-gtk3 does on `query-tooltip`).
- Boxed types: `BoxedWrapper` holding `GType` + `gpointer`, copied with `g_boxed_copy`/freed with
  `g_boxed_free`. Covers `GdkRectangle`, `GdkRGBA`, `GtkTreeIter`, `GVariant`-likes, `graphene_*`.
- Interfaces registered as real `Php::Interface`s (`GtkOrientable`, `GListModel`, `GtkSelectionModel`…) and
  attached with `implements()` — no `liststore_type` bool hacks.
- Enums/flags: PHP classes with constants (same as php-gtk3), *plus* GType-aware conversion in the
  marshaller so `set_property('orientation', GtkOrientation::VERTICAL)` works.

### 2.2 Value marshalling (`marshal`)
- Single table-driven `to_gvalue(Php::Value, GType)` / `to_php(const GValue*)` covering **all**
  fundamentals: CHAR, UCHAR, BOOL, INT, UINT, LONG, ULONG, INT64, UINT64, FLOAT, DOUBLE, STRING,
  ENUM, FLAGS, OBJECT, INTERFACE, BOXED, POINTER (opaque handle class), PARAM, VARIANT, GTYPE.
- Everything that touches values — properties, signal args, list-model items, callback returns —
  goes through this one place. php-gtk3 had three partial copies (connect_callback, generic_callback,
  phpgtk_get_gvalue) that drifted.
- `GList`/`GSList`/`GPtrArray`/`char**` → `Php::Array` helpers with explicit ownership flags
  (transfer none / container / full), taken from GIR.

### 2.3 Signals (`signal`)
- Connect with `g_signal_connect_closure()` using a **custom `GClosure` with `g_closure_set_marshal`**
  (or `g_cclosure_new` + `g_closure_set_meta_marshal`). The marshaller receives
  `(GValue *return, guint n_params, const GValue *params)` — **no varargs**. This removes the
  entire "unsupported type, cannot skip the va_arg slot" bug class and the `BOXED == GdkEvent`
  assumption from php-gtk3.
- Closure data = `PhpClosure { Php::Value callable; Php::Array user_args; }` freed in the
  `GClosureNotify` (php-gtk3 leaked these on purpose because destroying `Php::Value` from a notify
  crashed — root-cause it here: the fix is to make sure the notify runs while PHP is alive, i.e.
  disconnect all closures in MSHUTDOWN/RSHUTDOWN before Zend tears down).
- Return values honoured: handler return converted to `return_value` GValue via `marshal` (needed
  for `gboolean`-returning signals like `close-request`).
- Handler ID returned as int; `disconnect`, `block`, `unblock`, `is_connected` on `GObject`.
- Detailed signals (`notify::title`) supported by parsing the detail with `g_signal_parse_name`.

### 2.4 Callbacks (`callback`)
- One `PhpCallable` class (callable + bound args + context string) with `invoke(args…) -> Php::Value`
  that contains the *only* try/catch for the C→PHP direction. Every entry point
  (`GLib::timeout_add`, `idle_add`, `GtkSortListModel` sorters, `GtkListItemFactory` signals,
  `GtkCustomFilter`, `GtkDrawingArea::set_draw_func`, tick callbacks…) wraps a `PhpCallable`.
  No per-site re-implementation of marshal-and-catch.
- Destroy-notify frees the `PhpCallable`; `GDestroyNotify` is always supplied.

### 2.5 Exception boundary (`error`)
Keep php-gtk3's hard-won rules exactly:
- A PHP throwable must never unwind through GLib frames. Catch `Php::Throwable` (not just
  `Php::Exception`) around the *whole* trampoline body including marshalling.
- Capture message/code, **leave the catch scope** (PHP-CPP clears the pending Zend exception on
  destruction of the caught object), then report.
- `Gtk::set_exception_handler(?callable)` stores a *registered* `Php::Value` (never looked up by
  name at report time); fall back to `g_critical()`. Handler slot is heap-allocated and never freed.
- Improvement: also capture the throwable's class name and, if PHP-CPP allows, keep the exception
  object alive and re-throw it from `Gtk::main()` / `GtkApplication::run()` return when the app
  opts in (`Gtk::set_exception_mode(Gtk::EXCEPTION_RETHROW)`). This gives users a real `try/catch`
  around the main loop. Implement by stashing the exception, calling `g_main_loop_quit`, rethrowing
  after the C frame returns.

### 2.6 Parameter validation (`params`)
- Typed helpers: `arg_string(params, i)`, `arg_int`, `arg_object<T>(params, i, "GtkWidget")`
  (does `Php::is_a` + `implementation()` — php-gtk3 skipped the `is_a` check and read bogus pointers),
  `arg_optional_*`. Required → throw `TypeError`-like `Php::Exception`; optional → default.

## 3. Code generation (`gen/`)

- Input: `/usr/share/gir-1.0/{GLib,GObject,Gio,Gdk-4.0,Gsk-4.0,Gtk-4.0,Pango,GdkPixbuf}.gir` (XML).
- Generator: PHP script (like php-gtk3's `gen/run.php` but reading GIR instead of `defs.txt`).
- Emits per class: header, `.cpp` with method bodies calling `params`/`marshal`/`wrap`, and a
  `register_<Ns>(Php::Extension&)` function with all `Php::Class`, `extends`, `implements`,
  `method<>`, `constant` lines in **dependency order** (topologically sorted by parent type — this
  was a manual, error-prone ordering in php-gtk3's `get_module()`).
- Honour GIR annotations: `transfer`, `nullable`, `optional`, `out`/`inout` (returned as array or
  by-ref), `array length=`, `deprecated` (emit `Php::deprecated` and still register).
- Skip-list / override mechanism: `gen/overrides/<Type>.<method>.cpp` replaces a generated body
  (for the handful of APIs needing hand code: `GtkDrawingArea::set_draw_func`, `GtkListItemFactory`,
  `Gio::Application::run` with argv). Generated code is **regenerable**; never hand-edit it.
- Also generate `docs/reference-objects.md` coverage map and PHP stub files (`stubs/*.php`) for IDE
  autocompletion — the VS Code experience was a third-party afterthought in php-gtk3.
- Stubs target PHP 8.4: typed class constants for enums, `#[\Deprecated(since:, message:)]` on
  deprecated GTK APIs (so IDEs and `E_USER_DEPRECATED` agree), property hooks declaring GObject
  properties as virtual PHP properties (`$win->title` ↔ `get/set_property('title')`), `never`/union
  return types from GIR nullability.

## 4. GTK4-specific surface

- No `gtk_init`/`gtk_main`: expose `Gtk::init()` for scripts but make `GtkApplication` +
  `run()` the documented path; `activate` signal drives everything.
- Event controllers replace `GdkEvent` unions: `GtkGestureClick`, `GtkEventControllerKey`,
  `GtkEventControllerMotion`, `GtkDropTarget`. `GdkEvent` is a real (non-boxed) fundamental type in
  GTK4 → wrap as opaque `GdkEventWrapper` with typed getters, no field-copy `populate()`.
- List widgets: `GtkListView`/`GtkColumnView` + `GListModel`/`GtkStringList`/`GtkSelectionModel`;
  `GtkListItemFactory` via `GtkSignalListItemFactory` signals (`setup`/`bind`) — good stress test
  for the `PhpCallable` design. `GtkTreeView` still exists (deprecated 4.10) — generate it, mark deprecated.
- Rendering: `GtkSnapshot`, `GdkTexture`, `GdkPaintable`; cairo access via `GtkDrawingArea` draw func
  (pass a `CairoContext` wrapper; consider interop with ext-cairo if present).
- `GtkBuilder` + `GtkBuilderScope` so `.ui` files can bind PHP callables by name (php-gtk3's Glade
  path).
- WebKit: `webkitgtk-6.0` behind `WITH_WEBKIT`, same feature-flag scheme.

## 5. Build / project layout

```
php-gtk4/
  Makefile            PHP-CPP template, NAME=gtk4, -std=c++17, pkg-config gtk4 (+ optional webkitgtk-6.0)
  main.cpp / main.h   get_module(): constants, ini directives, then register_G(), register_Gio()…
  version.cpp         build info constants (copy php-gtk3's approach: FORCE-rebuilt TU)
  src/core/           hand-written runtime (§2)
  src/gen/            generated wrappers (committed, but regenerable)
  gen/                generator + overrides
  stubs/              generated PHP stubs
  tests/              PHPUnit-free plain PHP scripts with asserts, run under xvfb-run (see §6)
  examples/
  docs/
  gtk4.ini
```
Fix php-gtk3's Makefile pain points: real source prerequisites on object rules (`.d` files via
`-MMD`), version-tagged build dirs (`build/php8.3/`), `PHPCPP_STATIC` pairing check that fails
early when `php-config` differs from the one PHP-CPP was built with.

## 6. Testing

- `tests/run.sh`: runs every `tests/*.php` under `xvfb-run -a php -n -dextension=./gtk4.so`,
  non-zero exit on any assert failure. (php-gtk3 had none; `-n` is mandatory to avoid double-loading.)
- Minimum suites: object identity & refcount (`===`, weak-ref after destroy), signal marshalling of
  every fundamental type (use `GObject::signal_new`-style test objects or `notify::` on real
  properties), exception boundary (handler called, app survives, rethrow mode), callback teardown
  (destroy notifies actually run), list-model factory round-trip, property get/set for every
  fundamental.
- CI: build against PHP-CPP for one PHP version + run tests headless; lint (clang-tidy/format) as
  a second job.

## 7. Milestones

1. **Skeleton** — Makefile, `main.cpp`, `gtk4.ini`, `Gtk::init()`, `GtkApplication::run()` with
   `activate` signal, a `GtkWindow` with a `GtkButton` and `clicked`. Proves toolchain + PHP-CPP pairing.
2. **Core runtime** — `wrap`, `marshal`, `signal` (GClosure marshaller), `callback`, `error`, `params`
   fully implemented and unit-tested on a hand-written `GObject`/`GtkWidget`/`GtkWindow`/`GtkButton`.
3. **Generator** — GIR parser + emitter producing Gtk/Gdk/Gio/GLib/Pango namespaces; replace the
   hand-written milestone-2 classes with generated ones (they must be byte-for-byte compatible in
   behaviour). Topological registration order. Stubs + coverage doc output.
4. **GTK4 surface** — event controllers, list views/factories, snapshot/texture, builder scope.
   Overrides directory populated.
5. **Hardening** — refcount/identity edge cases, shutdown ordering (disconnect closures before Zend
   teardown), exception rethrow mode, deprecation coverage, docs/examples ported from php-gtk3.
6. **Optional features** — WebKitGTK 6, Windows (MSVC + WebView2) following php-gtk3's separate
   `_Unix.cpp` / `_Windows.cpp` pattern, AppImage packaging.

## 8. Things explicitly *not* carried over from php-gtk3

- varargs trampolines (`connect_callback(gpointer, ...)`, `generic_callback` pointer sniffing)
- `cobject_to_phpobject()` allocating a fresh `GtkWidget_` per return (no identity)
- leaked `st_callback` structs with `NULL` destroy-notify
- `malloc`+`memset` of structs containing C++ members
- `GdkEvent_::populate()` field copying
- `GtkTreeModel_` dual `instance`/`model` pointers and the `"model"` special case in `set_property`
- `get_property` initialising the GValue as `G_TYPE_OBJECT` unconditionally
- `set_data`/`get_data` storing raw pointers to temporaries
- hand-maintained `get_module()` ordering and `docs/reference-objects.md`
- the C++11 constraint and object rules without source prerequisites
