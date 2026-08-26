# php-gtk4 — Implementation Plan

Goal: a native PHP extension binding GTK 4 to PHP, informed by the working php-gtk3 extension
(`/mnt/share/dev/code/php-gtk3`). Keep what worked there; replace the parts that were manual,
fragile, or GTK3-specific.

## 0. Decisions (fixed unless revisited)

| Topic | Decision | Reason |
|---|---|---|
| Zend abstraction | **None — native Zend API** (decided 2026-08-25, replacing PHP-CPP after one day of use) | PHP-CPP needed a fork for a heap bug, could not express return/nullable types, enums, attributes, object handlers (`$obj->prop`), threw `E_WARNING` on arity errors, hid the `Throwable`, and forced a non-standard build. Native + `gen_stub.php` gives typed arginfo from one stub file, `phpize`/PIE-installable builds, and idiomatic PHP 8.4 objects. |
| Language | C++20 (GCC 11+/Clang 14+) | PHP 8.4 itself needs a modern toolchain, so nothing is lost; gives `std::span`, concepts, designated initialisers for the marshaller/generator output |
| GTK target | GTK 4.14+ (`gtk4` pkg-config), GLib 2.76+ | Current LTS distros |
| Module name | `gtk4` everywhere (`config.m4`, `zend_module_entry`, `gtk4.ini`) | php-gtk3 lesson: the names must agree |
| PHP versions | **8.4+ only** (Makefile and `main.cpp` refuse older) | One Zend ABI to care about; lets stubs/tests use 8.4 syntax (property hooks, `#[\Deprecated]`, `new X()->m()`, typed constants) |
| Class naming | `Gtk4\` namespace + GType name (`Gtk4\GtkButton`, `Gtk4\GdkTexture`) | Generic C→PHP wrapping by `g_type_name()`; namespace lets gtk3 and gtk4 be installed side by side (using both in one process is still impossible — identical libgtk C symbols) |
| Naming | **snake_case methods and properties, final** (`set_default_size`, `$win->default_width`, `notify::title`) — decided 2026-08-25 | 1:1 with the C API and docs.gtk.org; same choice as PyGObject, gjs, gtk-rs, Vala; trivial for the generator (strip the type prefix, no camelCase exceptions); PHP's own function library is snake_case. **No camelCase aliases** — one spelling. PSR-1 camelCaps is a userland-class convention; the phpcs exclusion is deliberate. |
| Code generation | **Generated** wrapper skeletons from GObject-Introspection (`Gtk-4.0.gir`), hand-written runtime core | php-gtk3 hand-wrote 260 classes + a 5000-line `get_module()`; GTK4 ships complete GIR |

## 1. Architecture

```text
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
                      marshal.{h,cpp}   GValue <-> zval (complete, both directions)
                      gsignal.{h,cpp}   GClosure-based signal connection, PHP callable invocation
                      callback.{h,cpp}  one abstraction for every C callback entry point (timeouts, funcs, closures)
                      error.{h,cpp}     the exception boundary + Gtk::set_exception_handler
                      params.{h,cpp}    parameter validation helpers
  │
GTK4 / GLib C API
```

## 2. Core runtime (hand-written; status 2026-08-25: implemented natively, tested)

### 2.1 Objects (`src/core/object`)

- `struct Object { GObject *obj; zend_object std; }`; `create_object` allocates it for every
  registered class (one struct serves all — per-class state lives in the GObject).
- Handlers: `free_obj` (weak-unref, clear qdata, `g_object_unref`), `clone_obj = NULL`,
  `read/write/has_property` map `$obj->some_prop` (underscores → dashes) to GObject properties and
  fall back to the standard handlers, `get_debug_info` lists readable properties for `var_dump`.
- **Ownership**: `attach()` does `g_object_ref_sink`; the handle owns one ref. **Identity**: a qdata
  back-pointer makes `wrap()` return the existing `zend_object` (`ZVAL_OBJ_COPY`), so `===` holds and
  dynamic properties survive round trips. A `g_object_weak_ref` nulls `obj` if GTK finalizes anyway.
- **Registry**: `register_class("GTypeName", ce)` (GType name → `zend_class_entry`, installs
  `create_object`). `wrap()` walks `g_type_parent()` to the nearest registered class, so an
  unregistered subclass still yields e.g. `Gtk4\GObject` instead of failing.
- `unwrap(zv, expected_gtype)` and `PHPGTK_SELF(CType, GTYPE)` validate class, liveness and GType
  and raise `TypeError` / `Error("... on a dead GObject")`.
- Boxed types (`src/core/boxed.*`): `struct Boxed { GType; gpointer data; zend_object }` owning a
  `g_boxed_copy`, `clone_obj` copies, `compare` by fields, fields as PHP properties via a per-class
  reader/writer; `GdkRGBA`, `GdkRectangle`. `GStrv` ↔ `list<string>` is a value mapping, not a
  handle. Interfaces are real PHP interfaces (`implements` in the stub → `zend_class_implements`).
  `GParamSpec` is its own handle class. `G_TYPE_POINTER` stays unsupported on purpose.

### 2.2 Values (`src/core/marshal`)

- `to_php(const GValue*, zval*)` / `to_gvalue(zval*, GType, GValue*)`: all scalar fundamentals,
  enum/flags (as int for now — PHP enums come with the generator), object/interface (via
  `wrap`/`unwrap`), `GParamSpec` (name only until a class exists). Unsupported types raise
  `TypeError("... unsupported GType X")` — never crash. `to_php_supported()` lets `get_debug_info`
  skip them.
- `GVariant` ↔ PHP values (`src/core/variant.*`): b/y/n/q/i/u/x/t/h/d/s/o/g/v/m/a/dict/tuple; PHP →
  variant with the target `GVariantType` (action parameter/state) or inference. Used by actions
  and any `G_TYPE_VARIANT` property.
- Open: GBytes ↔ string, GError → exception, graphene types; `GList`/`GSList`/`GPtrArray` ↔ array
  helpers with GIR transfer semantics.

### 2.3 Signals (`src/core/gsignal`)

- `connect()`/`connect_after()` use `g_signal_parse_name` (detail support) and a `GClosure` with a
  custom marshaller receiving `GValue` arrays — no varargs. Closure data: the callable zval (kept
  alive), user args, signal name; freed by the closure finalize notifier.
- The callable is validated at connect time (`Z_PARAM_FUNC`), resolved with `zend_fcall_info_init`
  and invoked with `zend_call_function`; the handler's return value is converted to the signal's
  return `GValue` (e.g. `close-request` → bool).
- No user data (closures capture with `use`). Non-signal callbacks (`GLib::idle_add`,
  `timeout_add`, later sorters/draw funcs/factories) share `src/core/callback.*`: keeps the
  callable alive, invokes with zval args, routes throwables with the installing method as origin.
- Teardown: every closure and source is tracked and disconnected/destroyed in RSHUTDOWN
  (`src/core/teardown.*`); `PhpValue` instances are drained the same way.

### 2.4 Exception boundary (`src/core/error`)

- Rule unchanged from php-gtk3: a throwable never unwinds through GLib, and Zend refuses to run PHP
  while one is pending. Every trampoline ends with `report_pending_exception(origin)`: take
  `EG(exception)`, `zend_clear_exception()`, call the handler installed via
  `Gtk::set_exception_handler(callable(\Throwable, string $origin))` with the **real object**
  (class, file, line, trace intact); if none or if it throws itself, `g_critical()`. The emitting
  GTK call continues. Handler is request-scoped (released in RSHUTDOWN).
- `Gtk4\ExceptionMode::Rethrow` (`Gtk::set_exception_mode()`): the handler is still called, then
  the Throwable is handed back to the engine (`zend_throw_exception_object`) and every running
  loop (`src/core/mainloop.*` registry: `GMainLoop::run`, `GtkApplication::run`) is quit; Zend
  skips the remaining PHP callbacks of the emission and the Throwable propagates from the emitting
  method or from `run()`. Default stays `Log`.

### 2.5 Arguments

- Arginfo is generated from the stub; bodies parse with `ZEND_PARSE_PARAMETERS_*` → PHP 8
  `ArgumentCountError`/`TypeError` semantics for free. `Z_PARAM_OBJECT_OF_CLASS_OR_NULL` + `unwrap()`
  for GObject arguments.

## 3. Code generation (`gen/`)

- Input: `/usr/share/gir-1.0/{GLib,GObject,Gio,Gdk-4.0,Gsk-4.0,Gtk-4.0,Pango,GdkPixbuf}.gir` (XML).
- Generator: PHP script (like php-gtk3's php-gtk3's generator script (run.php) but reading GIR instead of `defs.txt`).
- Emits per namespace: a section of `src/gtk4.stub.php` (classes, typed signatures, enums,
  `#[\Deprecated]`, docblocks with docs.gtk.org links) and per class a `.cpp` with `ZEND_METHOD`
  bodies calling `marshal`/`wrap`/`unwrap`; plus the MINIT registration block in **dependency
  order** (topologically sorted by parent type). `gen_stub.php` then produces the arginfo, so
  types are declared exactly once.
- Honour GIR annotations: `transfer`, `nullable`, `optional`, `out`/`inout` (returned as array or
  by-ref), `array length=`, `deprecated` (`#[\Deprecated]` in the stub + `E_DEPRECATED` in the body).
- Skip-list / override mechanism: `gen/overrides/<Type>.<method>.cpp` replaces a generated body
  (for the handful of APIs needing hand code: `GtkDrawingArea::set_draw_func`, `GtkListItemFactory`,
  `Gio::Application::run` with argv). Generated code is **regenerable**; never hand-edit it.
- Also generate a reference-objects coverage doc coverage map and PHP stub files (`stubs/*.php`) for IDE
  autocompletion — the VS Code experience was a third-party afterthought in php-gtk3.
- Stubs target PHP 8.4: typed class constants for enums, `#[\Deprecated(since:, message:)]` on
  deprecated GTK APIs (so IDEs and `E_USER_DEPRECATED` agree), property hooks declaring GObject
  properties as virtual PHP properties (`$win->title` ↔ `get/set_property('title')`), `never`/union
  return types from GIR nullability.

## 4. GTK4-specific surface

- ✅ No `gtk_main`: `GtkApplication::run()` is the documented path (`activate` drives
  everything), `GMainLoop` + `GLib::idle_add/timeout_add` for bare scripts; `Gtk::init()` kept.
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

```text
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

Status 2026-08-25: PHPUnit 12 suite (`tests/*Test.php`, one class per core module + `ExtensionTest`,
`MainLoopTest`, `StubsTest`, `ExampleTest`, `EveryClassTest`), `tests/scripts/stress.php` for the
sanitizer/coverage runs, all driven by `ci.sh` (stages `cpp-lint php-qa build load test`, opt-in
`asan coverage`) and mirrored by the GitHub workflows.

- `tests/run.sh`: runs every `tests/*.php` under `xvfb-run -a php -n -dextension=./gtk4.so`,
  non-zero exit on any assert failure. (php-gtk3 had none; `-n` is mandatory to avoid double-loading.)
- Minimum suites: object identity & refcount (`===`, weak-ref after destroy), signal marshalling of
  every fundamental type (use `GObject::signal_new`-style test objects or `notify::` on real
  properties), exception boundary (handler called, app survives, rethrow mode), callback teardown
  (destroy notifies actually run), list-model factory round-trip, property get/set for every
  fundamental.
- CI: PHP {8.4, 8.5} on Ubuntu 24.04 (GTK 4.14 floor), plus a
  sanitizer job (ASan+UBSan on the suite, LSan on the `php -n` stress run) and a gcov coverage job
  (gcovr HTML artifact); C++ lint on both Ubuntus, PHP QA (phplint/phpcs/php-cs-fixer/phpstan max).

### Milestone 2b — generator prerequisites (2026-08-26)

Core mechanisms the generator emits against, decided before it exists so its output never has to be
rewritten: PHP enums for GEnum / constant classes for GFlags (with a GType→class registry in
marshal), collection helpers with GIR transfer semantics, an out-parameter convention, a generic
fundamental-type handle registry (GdkEvent, GskRenderNode, GtkExpression), a generation rule for
typed C callbacks, and `GError`/`GBytes` mappings. All done 2026-08-26, tracked in docs/TODO.md §7.

### Conventions the generator relies on (2026-08-26)

- **Out parameters**: a C function's `out` arguments become the PHP return value — one out → that
  value, several → a list in declaration order (`get_size_request(): array{int,int}`). A `gboolean`
  return combined with outs means "success": return the outs, or `null` on failure
  (`GtkLabel::get_selection_bounds(): ?array`). `inout` = the argument is passed, the new value
  returned. No PHP by-reference parameters anywhere.
- **Collections**: `GList`/`GSList`/`GPtrArray`/`char**` returns become PHP lists via
  `src/core/collections.*`, converted by element GType (GObject → handle, string, boxed) with the
  GIR `transfer` annotation deciding what is freed (`Transfer::None/Container/Full`). Array
  arguments: `strv_from_php()` for `char**`; object lists as needed.
- **Errors and bytes**: a C signature with a trailing `GError **` never returns false/null for
  failure in PHP — it throws `Gtk4\GError` (domain + code); the stub return type drops the failure
  branch. `GBytes`/`GByteArray` ↔ `string`.
- **Typed C callbacks** (GIR `<callback>` parameters): every callback parameter becomes a PHP
  `callable` (nullable where the C side accepts `NULL`); the `closure` argument carries a
  `phpgtk::Callback` (`src/core/callback.*`) and the `destroy` argument is a per-callback
  `*_free` notify. Trampoline shape: wrap each C argument with the type family rule (GObject →
  `wrap()`, `cairo_t` → `CairoContext`, ints/doubles → scalars), `callback_invoke()`, convert the
  return value (`gboolean` ← truthiness, `gint` ← sign of `zval_get_long`), dtor the zvals.
  Scope rules: `call` — no notify, the Callback lives on the C stack of the method;
  `async` — freed by the trampoline after the single invocation; `notified` — freed by the
  destroy notify, and the installing method registers `teardown_track_notified(cb, owner,
  clear)` so RSHUTDOWN can clear callables on objects that outlive the request. Destroy notifies
  run inside GTK frames (dispose, the setter itself) so `callback_free()` parks the callable and
  `callback_drain()` releases it at the next safe point. Reference implementations:
  `GtkDrawingArea::set_draw_func`, `GtkCustomFilter`, `GtkCustomSorter`.
- **Type families**: GObject → handle (`object.*`), boxed → value handle (`boxed.*`), refcounted
  fundamentals (`GParamSpec`, `GdkEvent`, …) → `fundamental.*` registry with the type's ref/unref
  pair, GEnum → PHP enum, GFlags → constant class, GVariant → PHP values, `G_TYPE_POINTER` → not
  bound.

### GL / GPU rendering (verified 2026-08-26)

The test infrastructure runs GTK with `GSK_RENDERER=cairo` and `GDK_DEBUG=gl-disable` because Xvfb has
no GL and Mesa's llvmpipe leaks under valgrind/LSan on CI runners. Real applications use GTK's
defaults; verified manually on a Wayland session with an AMD GPU (Mesa 25.2, radeonsi):
`examples/CairoContext.php` runs with the default renderer (EGL context), with `GSK_RENDERER=gl`, and
with `GSK_RENDERER=vulkan` (`GskVulkanRenderer` on `GdkWaylandToplevel`). Nothing in the binding
is renderer-specific; repeat this check before a release (`docs/TODO.md` §6). Note: the automated
suite pins `GDK_BACKEND=x11` because GTK 4.14's Wayland backend corrupts the heap under the test
load (verified with valgrind: write into a freed block inside libgtk, no php-gtk4 frames).

### Tooling decisions (2026-08-25)

- **Leak/memory checking = ASan/UBSan/LSan via `--enable-gtk4-sanitize`, plus valgrind memcheck** on the
  uninstrumented build (`ci.sh --only=valgrind`) for what ASan cannot see (uninitialised reads).
  LSan only on the `php -n`
  stress process (only our module loaded); in the PHPUnit process other PHP extensions leak at their
  own shutdown. Suppressions in `tests/lsan.supp` are third-party only.
- **Coverage = gcov of the C++ (`--enable-gtk4-coverage`, gcovr)**, not pcov/xdebug: the code under test is the
  extension, PHP-side coverage of `tests/` would measure nothing.
- **Build system = standard `phpize`/`config.m4`** (the only build PECL/PIE and the php-sdk on
  Windows understand). Variants via `--enable-gtk4-sanitize` / `--enable-gtk4-coverage`. The former
  PHP-CPP-template Makefile is gone.
- `EveryClassTest` is the generic "instantiate every class, call every getter" smoke test — it must
  never need editing when classes are added; the generator's output is covered by it automatically.
- Tests never run under xdebug (`XDEBUG_MODE=off` in `tests/run.sh`): its develop-mode observer
  segfaults after `ReflectionMethod::invoke()` on PHP-CPP methods.

## 7. Milestones

1. ✅ **Skeleton** — Makefile, `main.cpp`, `gtk4.ini`, `Gtk::init()`/`main()`, `GtkWindow` (done, plus
   the complete CI/QA/test infrastructure: `ci.sh`, three workflows with matrices, sanitizer and
   coverage jobs, stubs, badges). `GtkApplication`/`GtkButton` moved to milestone 2/3.
2. ✅ **Core runtime** — done and tested (2026-08-26): objects (owned ref with `attach`/`attach_new`
   ownership rules, qdata identity, weak ref, GType→class registry, property handlers), marshal
   (all fundamentals, enum/flags as int, object/interface, `GParamSpec`, boxed + `GStrv`,
   `GVariant`), signals (GClosure marshaller, `emit()`), callbacks (`GLib` sources), exception
   boundary with `ExceptionMode`, RSHUTDOWN teardown, `GtkWidget` layer with `GtkButton`/`GtkLabel`,
   `GtkApplication` + `GMainLoop`, actions with real PHP interfaces, `PhpValue` + `GListStore`.
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
- hand-maintained `get_module()` ordering and a reference-objects coverage doc
- the C++11 constraint and object rules without source prerequisites
