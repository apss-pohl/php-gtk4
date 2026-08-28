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
| PHP versions | **8.4+ only** (`config.m4` and `src/php_gtk4.h` refuse older) | One Zend ABI to care about; lets stubs/tests use 8.4 syntax (property hooks, `#[\Deprecated]`, `new X()->m()`, typed constants) |
| Class naming | `Gtk4\` namespace + GType name (`Gtk4\GtkButton`, `Gtk4\GdkTexture`) | Generic C→PHP wrapping by `g_type_name()`; namespace lets gtk3 and gtk4 be installed side by side (using both in one process is still impossible — identical libgtk C symbols) |
| Naming | **snake_case methods and properties, final** (`set_default_size`, `$win->default_width`, `notify::title`) — decided 2026-08-25 | 1:1 with the C API and docs.gtk.org; same choice as PyGObject, gjs, gtk-rs, Vala; trivial for the generator (strip the type prefix, no camelCase exceptions); PHP's own function library is snake_case. **No camelCase aliases** — one spelling. PSR-1 camelCaps is a userland-class convention; the phpcs exclusion is deliberate. |
| Code generation | **Generated** wrapper skeletons from GObject-Introspection (`Gtk-4.0.gir`), hand-written runtime core | php-gtk3 hand-wrote 260 classes + a 5000-line `get_module()`; GTK4 ships complete GIR |

## 1. Architecture

```text
PHP script
  │
Zend engine        arginfo + class tables generated from src/gtk4.stub.php (gen/gen_stub.php)
  │
src/gtk4.cpp       MINIT: register_class()/register_enum()/register_boxed()/register_fundamental()
  │                parents first; RINIT enums_verify(); RSHUTDOWN teardown/phpvalue/exception state
src/Gtk|Gdk|Gio|Cairo/   one .cpp per class: ZEND_METHODs (hand-written today, generated in milestone 3)
  │
src/core/          hand-written runtime (the real work):
                     object       PHP handle <-> GObject* (toggle ref + hold, qdata identity,
                                  property handlers, GType -> class registry, wrap()/unwrap())
                     marshal      GValue <-> zval (all fundamentals, enums/flags, boxed, variant)
                     gsignal      GClosure-based connect()/emit(), PHP callable invocation
                     callback     non-signal C callbacks (sources, draw/filter/sort funcs), deferred release
                     error        the exception boundary + Gtk::set_exception_handler / ExceptionMode
                     boxed, fundamental, enums, collections, variant, gerror, phpvalue, paramspec,
                     teardown (RSHUTDOWN), mainloop (Rethrow support)
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
- **Ownership**: `attach()` does `g_object_ref_sink` and turns that reference into a *toggle*
  reference (`g_object_add_toggle_ref`, decided 2026-08-28): while it is the only reference the
  handle behaves like an owner (last PHP ref → free → finalize); as soon as GTK holds another
  one (parented widget, store item, toplevel list) the GObject takes a `GC_ADDREF` on the
  `zend_object` (`Object::held`), so `$box->append(new MyButton())` keeps the PHP subclass with
  its state and `get_first_child()` returns that very object. The toggle notify releases the
  hold when GTK lets go (freeing the handle right there if PHP had let go too — GLib does not
  touch the object after the notify, the pattern of every toggle-ref binding); RSHUTDOWN releases
  every hold first (`object_release_holds()`, tracked in module globals), because Zend reports
  objects still referenced at shutdown as leaks instead of freeing them. Constructors use
  `attach_new()` instead, and the choice is a *rule*, not a per-class judgement (GIR's `transfer`
  is `none` on `gtk_window_new()` and `gtk_button_new()` alike, so it cannot decide this):
  a floating return (`GInitiallyUnowned`) is sunk; a plain `GObject` with `transfer="full"` is
  adopted; a `GtkRoot` implementor (`GtkWindow` and subclasses) is *not* ours — GTK's toplevel
  list holds the initial reference — so its constructor uses `attach()`; anything else is a
  generator error that goes to the `overrides/` directory under `gen/`. `attach_new()` enforces
  the root case at runtime (`g_critical` + `attach()` semantics). **Identity**: a qdata
  back-pointer makes `wrap()` return the existing `zend_object` (`ZVAL_OBJ_COPY`), so `===` holds and
  subclass state survives round trips; the toggle ref guarantees the object cannot be finalized
  while a handle exists, so `obj` is only `nullptr` before `attach()`.
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

### 2.6 PHP subclasses as GTypes (`src/core/subtype`, decided 2026-08-28)

- **Every PHP user class extending a registered GObject class is a real GType**, registered
  lazily at its first `new` (`g_type_register_static`, name `Php__<Ns>__<Class>`, parent = the
  GType of the nearest registered ancestor, PHP ancestors first). Instances are created with
  `g_object_new()` of that type; the constructor's arguments become construct properties
  (matched by name, `gen/ctor-props.txt` for renames such as `gtk_label_new(str)` → `label`).
  A class whose constructor arguments are not properties (`GtkCustomFilter`'s callback) keeps
  the old behaviour: `new` builds the native class, the PHP object is a plain wrapper subclass
  (listed in `gen/report.md`). Final GTK classes cannot be subtyped either.
- **Abstract GTK classes** (`GtkWidget`, `GtkFilter`, `GtkSorter`, …) have a *public*
  constructor that refuses the native class (`Error: GtkWidget is abstract in GTK: subclass it
  in PHP`) and works on a PHP subclass — that is how a widget is written in PHP. Never
  `abstract` in PHP: `wrap()` must instantiate them for whatever GTK hands back.
- **vfuncs**: a PHP method `vfunc_<name>()` overrides the class-struct slot of the same name.
  The generator emits, per GIR `<virtual-method>` whose types are in the closure, a C thunk (C
  arguments → zvals, `zend_call_known_instance_method`, result → C, out parameters from the
  returned list as for methods, exceptions through `report_pending_exception`), an installer,
  and a native `vfunc_<name>()` method on the owning class that calls the implementation of the
  nearest non-PHP ancestor — so `parent::vfunc_<name>()` chains correctly through any number of
  PHP levels (on a native instance it throws `LogicException`: slots bypass the public API's
  preconditions; an empty slot is a no-op yielding the zero value). The GType's `class_init`
  installs a thunk only for the slots the PHP class defines (`vfunc_*` in its function table,
  user functions only), so undefined vfuncs cost nothing. PHP enforces the stub's signature on
  overrides (`vfunc_match(?GObject $item): bool`).
- **Construction and handles**: `subtype_new()` parks the handle in `GTK4_G(constructing)`;
  the type's `instance_init` pre-binds it (`object_prebind`: `obj` + qdata, no reference yet) so
  vfuncs and `wrap()` during the rest of `g_object_new()` find the PHP object; `attach*()` then
  settles the reference under the normal ownership rule (a `GtkWindow` subtype still uses
  `attach()`). A thunk on an instance without a handle (mid-construction before our
  `instance_init`, after RSHUTDOWN released the holds, an instance GTK created itself) chains to
  the native implementation instead of creating one.
- **Process-wide, per-request**: GTypes cannot be unregistered, so the GType → PHP class *name*
  map is a process-wide static behind a mutex (ZTS); `wrap()` resolves the class per request by
  name (no autoload) and falls back to the native parent when it does not exist there. Which
  vfuncs a GType has is decided by the first request that instantiates the class.
- **Not covered (yet)**: implementing GTK *interfaces* from PHP (`g_type_add_interface_static`
  and iface_init — `GListModel` in PHP would be the first consumer), declaring GObject properties
  or signals in PHP, `snapshot()` (needs `GtkSnapshot`, wave 4/milestone 4), vfuncs whose
  arguments are pointers to scalars without a GIR direction (`compute_expand`), interface
  vfuncs. `GObject`'s own vfuncs (`dispose`, `set_property`, …) are hand-written territory and
  not exposed.

## 3. Code generation (`gen/`)

- Input: `/usr/share/gir-1.0/{GLib,GObject,Gio,Gdk-4.0,Gsk-4.0,Gtk-4.0,Pango,GdkPixbuf}.gir` (XML).
- Generator: PHP script (like php-gtk3's php-gtk3's generator script (run.php) but reading GIR instead of `defs.txt`).
- Emits per namespace: `src/<Ns>/<Ns>.stub.php` (classes, typed signatures, enums, `@property`
  tags, docblocks from GIR) and per class a `.cpp` with `ZEND_METHOD`
  bodies calling `marshal`/`wrap`/`unwrap`; plus the MINIT registration block in **dependency
  order** (topologically sorted by parent type). `gen_stub.php` then produces the arginfo, so
  types are declared exactly once.
- Honour GIR annotations: `transfer`, `nullable`, `optional`, `out`/`inout` (returned, never
  by-ref), `array length=`, `deprecated` (**skipped** — decided 2026-08-27: the binding exposes the
  modern API only; `#[\Deprecated]` emission stays possible if ever wanted).
- Skip-list / override mechanism: `gen/overrides/<Type>.<method>.cpp` replaces a generated body
  (for the handful of APIs needing hand code: `GtkDrawingArea::set_draw_func`, `GtkListItemFactory`,
  `Gio::Application::run` with argv). Generated code is **regenerable**; never hand-edit it.
- Also generate a reference-objects coverage doc coverage map and PHP stub files (`stubs/*.php`) for IDE
  autocompletion — the VS Code experience was a third-party afterthought in php-gtk3.
- Stubs target PHP 8.4: backed enums for GEnum, constant classes for GFlags, GObject properties
  declared as `@property` tags (`$win->title` ↔ `get/set_property('title')` through the object
  handlers — not PHP property hooks, which would need per-property engine code), nullable return
  types from GIR nullability.

### Rollout: map-driven generation, drafts, hand-finishing (decided 2026-08-26)

The generator does **not** emit all of Gtk/Gdk/Gio (≈ 500 classes, 7 000 methods) in one go. The
port target is `docs/GTK3-MAP.md`: the ≈ 77 classes php-gtk3 users actually need (❌ rows) plus
their GTK 4 replacements, in the map's "Recommended port order". The generator is run *per wave*
on an allow-list taken from that map, its output is reviewed as a draft, and what the project
requires beyond the mechanical translation is hand-written. Reviewed and adopted because:

- it validates the generator on a realistic subset before it touches 500 classes (the 27
  hand-written classes are wave 0: regenerate them, the existing suite must stay green);
- a human reads every generated class once, which is the only way to catch convention mistakes
  early (a wrong template is a wrong template 7 000 times);
- the definition of done (tests, example, docs) stays feasible per wave (10–15 classes).

Rules that make "draft then hand-write" work without losing regenerability:

1. **Allow-list + transitive closure.** `allowlist.txt` (in `gen/`, planned) names the classes/enums of the wave.
   The generator adds what they need: parent classes, implemented interfaces, and every type
   that appears in a kept signature (parameters, returns, properties, signal arguments). Methods
   whose types fall outside the closure are *skipped* and listed in `report.md` (in `gen/`), never
   emitted with a `mixed`/unbound placeholder. The allow-list selects **classes**; for a selected
   class the whole GTK 4 method set is generated (not php-gtk3's subset — GTK 4 is the API).
2. **Generated files are owned by the generator.** They carry a `// GENERATED by gen/gir.php -
   do not edit` header and `./ci.sh --only=stubs` (later `--only=gen`) fails when a fresh run
   differs. "Hand-writing what's needed" happens in exactly three places:
   - `gen/overrides/<Type>.<method>.cpp` — replaces one generated method body (callbacks with
     non-standard scopes, argv handling, anything with a `G_TYPE_POINTER`);
   - `skip.txt` — `<Type>.<method>` entries the binding deliberately does not expose
     (deprecated-in-4.10 ballast, `*_get_type`, vfunc plumbing), with a reason per line;
   - `handwritten.txt` — a class listed here is **promoted to hand-written**: the generator
     stops emitting it and `src/<Ns>/<Type>.cpp` + its stub section are owned by hand from then
     on. This is the "use the draft" path: generate, review, promote when the class needs more
     than overrides can express (`GtkListItemFactory`, `GtkBuilder` scope, future PHP subclassing).
     Promotion is one-way and rare; a promoted class is no longer updated on GTK upgrades.
3. **Stub sections per namespace, one MINIT block.** Generated stub text lives in
   `src/<Ns>/<Ns>.stub.php` with its own `<Ns>_arginfo.h` (gen_stub.php runs once per stub file;
   `src/gen_arginfo.h` includes them all), hand-written classes stay in `src/gtk4.stub.php`. The
   MINIT block for generated classes is `src/gen_minit.inc` (parents first, never maintained by
   hand); the hand-written classes register before it in `src/gtk4.cpp`.
4. **Version policy.** Emit API with GIR `version ≤ 4.14` (the CI floor) unconditionally; anything
   newer is wrapped in `GTK_CHECK_VERSION` *and* marked `@since 4.16` in the stub, or skipped if
   the wave does not need it (wave 0: skipped and reported). Deprecated members are skipped
   (see §3 above); classes deprecated in 4.10 that the map marks "(dep. 4.10 → X)" are skipped in
   favour of X.
5. **Definition of done per wave** (CLAUDE.md, adapted for generated code): generated methods are
   covered by the generic suites (`EveryClassTest`, `RobustnessTest`, `StubsTest`, `ExampleTest`)
   plus one generated smoke test per class (constructor + every arg-less getter, property
   round-trips); every **override** and every **promoted** class gets hand-written tests like
   today; every class gets its `examples/<Class>.php`; `docs/GTK3-MAP.md`'s status column is
   regenerated from the stubs (`gen/map-status.php`, run by `gir.php --install`). A wave is merged
   only with `./ci.sh --with=asan,coverage,valgrind` green.

Waves (from the map's port order; each = allow-list → generate → review → overrides/tests/
examples → CI → commit):

| wave | allow-list (❌ rows of docs/GTK3-MAP.md) | new runtime needs |
| --- | --- | --- |
| 0 | the 27 existing classes, regenerated; suite unchanged | override + promote mechanism, `report.md` (in `gen/`) |
| 1 | layout: `GtkScrolledWindow`, `GtkGrid`, `GtkPaned`, `GtkFrame`, `GtkStack`(+Switcher/Sidebar), `GtkNotebook`, `GtkOverlay`, `GtkRevealer`, `GtkFixed`, `GtkSeparator`, `GtkSizeGroup`, `GtkWidget` margins | — |
| 2 | controls: `GtkEntry`/`GtkEditable`/`GtkEntryBuffer`, `GtkCheckButton`, `GtkToggleButton`, `GtkSpinButton`, `GtkScale`, `GtkAdjustment`, `GtkProgressBar`, `GtkImage`, `GtkSpinner`, `GtkCalendar` | `GtkEditable` interface methods once per interface |
| 3 | event controllers: `GtkEventController*`, `GtkGestureClick/Drag`, `GdkEvent` family | `GdkEvent` on the fundamental registry; `GdkModifierType` flags |
| 3b | drag and drop: `GtkDragSource`, `GtkDropTarget`, `GdkContentProvider`, `GdkDrop`/`GdkDrag` | GValue payloads (boxed/variant exist); `GdkContentFormats` boxed |
| 4 | menus/actions: `GMenu`, `GMenuItem`, `GtkPopoverMenu(Bar)`, `GtkMenuButton`, `GtkHeaderBar`, `GtkApplicationWindow`, accels | `GMenuModel` |
| 5 | dialogs (4.10 async API): `GtkAlertDialog`, `GtkFileDialog`, `GtkColorDialog`, `GtkFontDialog`, `GtkAboutDialog`, `GtkFileFilter` | `GAsyncReadyCallback` scope (async) + `*_finish` → `GError` throws |
| 6 | text: `GtkTextView`, `GtkTextBuffer`, `GtkTextIter` (boxed), `GtkTextMark/Tag/TagTable` | boxed with many methods (`GtkTextIter`) |
| 7 | list models/views: `GtkStringList`, `GtkSingleSelection`, `GtkMultiSelection`, `GtkListView`, `GtkColumnView(+Column)`, `GtkSignalListItemFactory`, `GtkTreeListModel` | `GtkListItemFactory` promoted |
| 8 | styling/builder/Gdk: `GtkCssProvider`, `GtkBuilder`, `GtkIconTheme`, `GdkDisplay`, `GdkMonitor`, `GdkSurface`, `GdkCursor`, `GdkClipboard` | `GtkBuilder` scope promoted |
| later | printing, `GdkPixbuf*` (prefer `GdkTexture`), WebKitGTK 6 | — |

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
  VERSION             single source of the version (mirrored into php_gtk4.h / the stub by ci.sh)
  config.m4           phpize build: PHP >= 8.4 (NTS or ZTS), gtk4 >= 4.14 + cairo-gobject, variants
                      --enable-gtk4-sanitize / -coverage / -webkit, build info baked into config.h
  ci.sh, buildall.sh  the pipeline (see CLAUDE.md) and build+install for the enabled PHP versions
  bin/php-gtk4        launcher that filters php-gtk3 out of the ini scan dir
  src/gtk4.cpp        module entry, MINIT registration block, RINIT/RSHUTDOWN
  src/gtk4.stub.php   the API declaration -> src/gtk4_arginfo.h (generated, committed)
  src/core/           hand-written runtime (§2)
  src/<Ns>/               one .cpp per class, generated in place (GENERATED header) or hand-written
  gen/                gen_stub.php (vendored), ide-stub.php, method-comments.php; the GIR generator + overrides
  stubs/gtk4.php      generated IDE stub
  tests/              PHPUnit 12 suite, tests/phpt (run-tests.php), tests/scripts (stress, shutdown)
  examples/           demo.php + bootstrap.php + one <Class>.php page per registered class
  docs/               PLAN, TODO, GTK3-MAP, RELEASING
  gtk4.ini
```

## 6. Testing

Status 2026-08-26: PHPUnit 12 suite (`tests/*Test.php`, one class per core module and per GTK
class, plus the generic `ExtensionTest`, `StubsTest`, `ExampleTest`, `EveryClassTest`,
`RobustnessTest`, `DocsTest`), `tests/phpt` under php-src's `run-tests.php`,
`tests/scripts/stress.php` + `shutdown.php` for the sanitizer/valgrind/coverage runs, all driven by
`ci.sh` (stages `version stubs cpp-lint md-lint php-qa build load test phpt`, opt-in
`asan coverage valgrind`) and mirrored by the GitHub workflows.

- `tests/run.sh`: `xvfb-run -a bin/php-gtk4 vendor/bin/phpunit` with `GDK_BACKEND=x11`,
  `GSK_RENDERER=cairo`, `GDK_DEBUG=gl-disable`, `XDEBUG_MODE=off` (php-gtk3 had no tests at all).
- Minimum suites: object identity & refcount (`===`, weak-ref after destroy), signal marshalling of
  every fundamental type (use `GObject::signal_new`-style test objects or `notify::` on real
  properties), exception boundary (handler called, app survives, rethrow mode), callback teardown
  (destroy notifies actually run), list-model factory round-trip, property get/set for every
  fundamental.
- CI: PHP {8.4, 8.5} on Ubuntu 24.04 (GTK 4.14 floor), plus a
  sanitizer job (ASan+UBSan on the suite, LSan on the `php -n` stress run) and a gcov coverage job
  (gcovr HTML artifact); C++ lint, PHP QA (phplint/phpcs/php-cs-fixer/phpstan max).

### Milestone 2b — generator prerequisites (2026-08-26)

Core mechanisms the generator emits against, decided before it exists so its output never has to be
rewritten: PHP enums for GEnum / constant classes for GFlags (with a GType→class registry in
marshal), collection helpers with GIR transfer semantics, an out-parameter convention, a generic
fundamental-type handle registry (GdkEvent, GskRenderNode, GtkExpression), a generation rule for
typed C callbacks, and `GError`/`GBytes` mappings. All done 2026-08-26, tracked in docs/TODO.md §5.

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
  segfaults after `ReflectionMethod::invoke()` on internal methods.

## 7. Milestones

1. ✅ **Skeleton** — first build (then PHP-CPP, replaced by phpize in milestone 2), `gtk4.ini`,
   `Gtk::init()`, `GtkWindow` (done, plus
   the complete CI/QA/test infrastructure: `ci.sh`, three workflows with matrices, sanitizer and
   coverage jobs, stubs, badges). `GtkApplication`/`GtkButton` moved to milestone 2/3.
2. ✅ **Core runtime** — done and tested (2026-08-26): objects (owned ref with `attach`/`attach_new`
   ownership rules, qdata identity, toggle-ref hold, GType→class registry, property handlers), marshal
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
6. **Optional features** — WebKitGTK 6, WebView2 on Windows following php-gtk3's separate
   `_Unix.cpp` / `_Windows.cpp` pattern, AppImage packaging. The Windows *build* itself is done
   (2026-08-27): `config.w32` through the PHP SDK against gvsbuild's MSVC GTK 4, `windows.yml`
   builds and runs the PHPUnit suite on PHP 8.4/8.5, releases ship `php_gtk4.dll` (docs/BUILD.md).

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
