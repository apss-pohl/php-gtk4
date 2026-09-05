# gen/

- `gen_stub.php` — vendored from php-src (`/usr/lib/php/<api>/build/gen_stub.php`, PHP 8.4). Turns
  every `*.stub.php` into its `*_arginfo.h`. Its `initPhpParser()` is patched to use composer's
  `nikic/php-parser` (`vendor/`, the pin in `composer.json`) and only falls back to php-src's own
  download of PHP-Parser 5.0.0 (`gen/PHP-Parser-5.0.0/`, gitignored) when `vendor/` is absent -
  `ci.sh` runs `composer install` first, so that copy never appears in a normal checkout.
- `ide-stub.php` — derives `stubs/gtk4.php` (dummy bodies) from the stub source.
- `method-comments.php` — writes the comment block above every `ZEND_METHOD` from the stub's
  signature/docblock; `--check` also reports any other C++ function without a comment.
- `map-status.php` — rewrites the status column and the summary counts of `docs/GTK3-MAP.md`
  from the stub files (a row is ✅/🟡/❌ by whether its GTK 4 classes are declared; ⛔/🧩 rows,
  non-class rows and rows without a php-gtk3 counterpart stay as written, notes are never touched).
  Run by `gir.php --install`; `--check` only reports.
- Hand-written and generated files share directories: `src/Gtk/Gtk.cpp` is the hand-written `Gtk4\Gtk`
  class, `src/Gtk/Gtk.stub.php` the *generated* per-namespace stub (same in `src/Gdk/`); the
  `GENERATED` header is the only distinction. An enum-only namespace (`src/Pango/`) has just the
  stub and its arginfo, no `.cpp`.
- `gir.php` — the GIR generator (docs/PLAN.md milestone 3), one program in six files: `gir.php`
  itself (the emitters and the CLI) plus `gir/config.php` (what is read, what is in scope),
  `gir/model.php` (`Type`/`Param`/`Func`/`Node` and the naming helpers), `gir/loader.php` (the
  .gir files parsed into that model, `Gir::locate()`), `gir/type-map.php` (`TypeSet`: what is in
  scope; `TypeMap`: what a GIR type becomes in PHP, the ZPP line, the conversions both ways) and
  `gir/writer.php` (clang-format, write-only-on-change, the file list `report.md` prints).
  `GeneratorTypeMapTest` pins the mappings one at a time; the `gen` stage proves the whole output.
  `gir.php` is still where a member's *fate* is decided - skip, override, emit. `php gen/gir.php --install` (what
  `./ci.sh --only=gen` runs and checks) reads the installed GIR files (`gir1.2-gtk-4.0`), takes
  `allowlist.txt` (+ parents, and the enums kept signatures use), and writes per GIR namespace
  `src/<Ns>/<Ns>.stub.php` and one `src/<Ns>/<Class>.cpp` per class, `src/gen_minit.inc` (the
  registration block, parents first), `src/gen_prototypes.h` + `src/gen_arginfo.h`, an
  `examples/<Class>.php` skeleton for every class that has none yet (never overwritten) plus
  `examples/generated-sections.inc`, and `report.md` here (every skipped member with its reason).
  `--out=gen/out` previews the same into a scratch directory. Output is clang-formatted. Inputs:
  - `allowlist.txt` — `Ns.Name` per line, the classes/enums of the wave;
  - `handwritten.txt` — types the generator must know as parents/parameter types but never
    emits (`GObject.Object`, the boxed and fundamental handles);
  - `skip.txt` — `Ns.Type.member reason`: members deliberately not exposed; `Ns.Type.__construct`
    on an abstract class keeps its constructor private (no PHP subtypes: `GdkTexture` needs state
    only its factories set);
  - `ctor-props.txt` — `Ns.Type.param property`: constructor arguments that are construct
    properties under another name (`Gtk.Label.str label`), so `new MyLabel('x')` on a PHP subclass
    (its own GType, `src/core/subtype`) can apply them through `g_object_new()`; arguments that are
    no property at all are reported and leave that class without the subtype path;
  - `overrides/<Ns>.<Type>.<method>.cpp` — a complete `ZEND_METHOD` preceded by a docblock whose
    first line is the stub declaration (`public function x(...): T`); replaces the generated
    method or adds one GIR does not have (`Gtk.Box.get_children.cpp`). On an interface
    (`Gio.Action.activate.cpp`) it replaces the one shared implementation.
  - `overrides/<Ns>.<Type>.cpp` — the class prelude: includes and file-static helpers (callback
    trampolines) emitted verbatim before the methods (`Gtk.CustomFilter.cpp`);
  - `NULLABLE_RETURNS` in `gir/config.php` — C identifier -> reason, for returns GIR calls
    non-null that really do answer NULL (`gtk_event_controller_get_widget()` before the
    controller is added to a widget). The declared return type is a promise the engine never
    verifies, so `TypeDeclarationTest` is what finds these;
  - `NON_NULLABLE_PARAMS` — `<C identifier>.<param>` -> reason, the same from the other side: a
    parameter GIR marks nullable that the function refuses with a `g_return_if_fail()` (the
    `GAsyncReadyCallback` of `gdk_clipboard_read_async()` and friends). Dropping the `?` makes
    null an ordinary TypeError;
  - `ARG_PRECONDITIONS` — what GLib asserts about the *arguments* after the type check (a position
  past the end, a minimum above the maximum, a name nothing answers to): a C predicate over the
  parsed parameters and `$this`, emitted before the call as a `ValueError` naming the argument.
- `PARAM_VALIDATORS` / `SELF_PRECONDITIONS` — a string parameter GLib validates with a predicate of
  its own (an application id, an absolute resource path): a `ValueError` naming the argument; a
  method whose object must be in a state (registered): a `LogicException`.
- `CHILD_PARAMS` / `SELF_UNPARENTED_OR` — widget parameters GTK requires in a place (a child of
  `$this`, a page of the notebook, a child of another parameter; `$this` unparented or under the
  given parent): a `LogicException` naming the argument, where GTK would CRITICAL and do nothing.
- `PARAM_DOMAINS` — `<C identifier>.<param>` -> `[min, max]` (`null` for an open end, a float
    bound for a float check), for parameters whose function accepts less than their type
    (`gtk_calendar_set_month()`: 0..11). GTK states these as `g_return_if_fail()` — a CRITICAL and
    a call that silently does nothing — so the generator emits `check_domain()` /
    `check_domain_double()` after the type's own `check_range<T>()`, a `ValueError` naming the
    argument. Every bound is copied from the assertion GTK printed under `RobustnessTest`
    (`tests/robustness-criticals.txt`), never from documentation alone;
  - `RETURNS_HOLD_SELF` — C identifier -> reason, for members whose returned object keeps a bare
    pointer to the object it came from (`gtk_stack_get_pages()`, `gtk_widget_get_first_child()`
    on a composite widget). The emitted `object_hold_owner(return_value, ZEND_THIS)` makes the
    returned *handle* keep `$this`'s handle alive - the object counterpart of `BOXED_OWNERS`,
    held between handles so a parent already owning its child is not turned into a leak.
  Every emitted parameter carries its argument checks: a `utf8` string gets
  `phpgtk::check_utf8()` (no embedded NUL, valid UTF-8 - a `filename` does not, it is bytes and
  `Z_PARAM_PATH_STR` already refuses the NUL), and an integer narrower than `zend_long` or
  unsigned gets `phpgtk::check_range<T>()`; both throw `ValueError` naming the argument.
  A boxed **record** (`glib:get-type`) in the allow-list becomes a value handle on `core/boxed`:
  public scalar fields are PHP properties (`@property` tags, read/write through the field table),
  GIR methods and constructors are emitted like a class's (`copy`/`free`/`ref`/`unref` are the
  handle's business and skipped), a plain struct without a `new` constructor gets one built from
  its fields; the registration `register_<Class>()` is generated too. **Out parameters** of scalar,
  string, object, enum and (caller-allocated) record type are returned - one out is the return
  value, several a list, a `gboolean` return with outs means "or null". **Callback parameters**
  with GIR scope `async` (released after one invocation) or `call` (released after the call) whose
  arguments convert become `callable` parameters with a generated trampoline (`GAsyncReadyCallback`
  of every `*_async`/`choose()`-style method); `notified` scope still needs an override (the owner's
  clear function). An **interface** with vfuncs (`GListModel`, `GAction`) can be implemented from
  PHP: its PHP interface declares only the vfunc-backed methods, `core/subtype` adds the GTK
  interface to the implementing class' GType and generated thunks call the PHP methods of the same
  name; a thunk converts strings, scalars, enums, objects, registered boxed records, `GVariant`
  (a plain value) and `GVariantType` (a type string) in both directions, a `GVariant` return
  against the type the object declares for it where the interface has one (`VFUNC_VARIANT_TYPE`
  in `gen/gir/config.php`), and a borrowed return (`get_name()`'s `const char *`) is kept by the
  instance. The interface's *properties* (`GAction`'s `name`, `state`, ...) are overridden on the
  GType and routed to the PHP accessors of the same name (`GtkOrientable`, all property, is
  implementable for that reason alone).
  Every GIR `<virtual-method>` of a non-final class whose types are in the closure becomes a
  thunk + installer (`vfunc_thunk_x`/`vfunc_install_x`, registered by `register_vfuncs_<Class>()`
  from `gen_minit.inc`) and a native `vfunc_x()` method for `parent::` chaining; the rest is
  reported. Interfaces in the allow-list are emitted once (`<Iface>.cpp`) and aliased into implementors
  with `@implementation-alias`; interfaces not in the allow-list are not declared on the class.
  Methods whose types fall outside the closure, callbacks, varargs, deprecated and post-4.14 API
  are skipped and reported, never emitted with a placeholder. Classes are never `abstract` in PHP
  (`wrap()` must instantiate them for whatever GTK hands back); a GIR-abstract class gets a public
  constructor that only works on a PHP subclass (`Error` on the native class) — a `skip.txt` entry
  for its `__construct` makes it private instead (`GdkTexture`).

## The GIR flow in one picture

```text
/usr/share/gir-1.0/*.gir          allowlist.txt  handwritten.txt  skip.txt  smoke-skip.txt  overrides/
            |                            |              |            |            |            |
            +----------------------------+--------------+------------+------------+------------+
                                         |
                              php gen/gir.php --install            (ci.sh --only=gen: no diff allowed)
                                         |
   src/<Ns>/<Ns>.stub.php   src/<Ns>/<Class>.cpp   src/gen_minit.inc   src/gen_prototypes.h   src/gen_arginfo.h
   tests/Generated/<Class>SmokeTest.php   examples/<Class>.php (skeleton, once)   examples/generated-sections.inc
   gen/report.md   docs/GTK3-MAP.md (status column, via map-status.php)
                                         |
                              ./ci.sh --only=stubs --fix           (gen_stub.php -> <Ns>_arginfo.h,
                                         |                          ide-stub.php -> stubs/gtk4.php)
                              ./ci.sh --only=build,test            (config.m4/config.w32 glob src/**/*.cpp)
```

Everything under the first line is committed with a `GENERATED by gen/gir.php` header and never
edited; `src/gtk4.cpp` only `#include`s `gen_minit.inc` after the hand-written registrations, and
`wrap()` finds generated classes through the GType registry like any other. Hand code lives in
`src/core/` (runtime) and `overrides/` (per method/class) — never in a generated file — so a rerun is
idempotent, down to the mtimes: the generator compares content and writes only what actually
changed, because rewriting identical files makes `make` recompile them (`GeneratorIdempotenceTest`
guards it).

Adding a class (a "wave"):

1. add `Ns.Type` to `allowlist.txt`, run `php gen/gir.php --install`;
2. read `report.md`: every skipped member is either accepted (`skip.txt`, with a reason) or gets an
   override;
3. `./ci.sh --only=stubs --fix`, then `./ci.sh --only=build,test`; a smoke round-trip GTK genuinely
   refuses goes to `smoke-skip.txt`;
4. turn the `examples/<Class>.php` skeleton into a visual page and list it in `Demo::SECTIONS`,
   write `tests/<Class>Test.php` for behaviour beyond the smoke test;
5. commit inputs and outputs together.

## Constructor ownership rule (for the generator)

Which `attach*()` a generated constructor emits is decided by the return type, not by GIR's
`transfer` (which is `none` for both `gtk_window_new()` and `gtk_button_new()`):

| constructor returns                              | emit          | why                                  |
|--------------------------------------------------|---------------|--------------------------------------|
| a `GInitiallyUnowned` subclass (floating)        | `attach_new()` | sinks the floating ref, handle owns it |
| a `GtkRoot` implementor (`GtkWindow`, …)         | `attach()`     | GTK's toplevel list owns the initial ref |
| a plain `GObject`, `transfer="full"`             | `attach_new()` | adopts the returned ref              |
| anything else                                    | error          | hand-write it in `overrides/`        |

`GtkRoot` is read from GIR (`implements`), never from a name list. Wave 0 diffed this choice against
the then hand-written constructors before anything else was generated; `attach_new()` also catches a
root at runtime with a `diagnostic()`.

Run everything via `./ci.sh --only=stubs [--fix]`.
