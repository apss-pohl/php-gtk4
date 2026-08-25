# TODO

Findings of the 2026-08-25 review ("best practice or php-gtk3 ballast?"). Ordered by impact.
Tick items off here; design rationale lives in PLAN.md.

## 1. Runtime foundation — replace PHP-CPP with the native Zend API  ✅ done 2026-08-25

Inherited from php-gtk3 without re-examination. PHP-CPP's 2018 rationale (Zend API churn) no
longer holds for a PHP 8.4+ target, and it blocks idiomatic PHP:

- [x] `struct { GObject *obj; zend_object std; }` objects with custom handlers
      (`free_obj`, no clone, `get_debug_info`, `read_property`/`write_property` → GObject props)
- [x] `stubs/gtk4.stub.php` as the **single source**: `gen_stub.php` → `gtk4_arginfo.h`
      (typed arginfo, return types, nullable class types, enums, `#[\Deprecated]`); IDE stub is
      derived, not hand-synced → delete the sync half of `StubsTest`
- [x] `phpize` / `config.m4` build (PIE/PECL-installable, Windows via php-sdk); drop the
      PHP-CPP template Makefile, `PHPCPP_STATIC`/`PHPCPP_BASE` pairing logic, the PHP-CPP fork
- [x] signals: `zend_fcall_info` + cache resolved once at `connect()`, `zend_call_function`
      directly (no `call_user_func_array` round trip)
- [x] arity/type violations = `ArgumentCountError`/`TypeError` (PHP 8 semantics), not `E_WARNING`
- [x] exception boundary: capture the real `Throwable` (`EG(exception)`), hand the object to
      `Gtk::set_exception_handler` (done); optional rethrow after the main loop returns (open)
- [x] `var_dump($obj)` shows GObject properties (`get_debug_info`)

## 2. GTK3 mental-model ballast in the PHP API

- [ ] `Gtk::main()` / `Gtk::main_quit()` (+ the `quit_pending` hack) → `GtkApplication::run()`
      as the documented path, `GLib\MainLoop` for scripts that need a bare loop; keep `Gtk::init()`
- [ ] `connect($signal, $handler, ...$userData)` → `connect(string $signal, callable $handler): int`;
      PHP closures capture context with `use`
- [x] `Gtk4\PHPGTK_VERSION` → `Gtk4\VERSION` etc. (double prefix)
- [ ] exception model: keep log-and-continue as default, add rethrow mode (see 1)

## 3. Decisions to write down (currently inherited by inertia)

- [ ] **snake_case methods** (`set_title`) — keep, for 1:1 mapping to docs.gtk.org and every other
      binding (PyGObject, gjs, Vala); record in PLAN.md; phpcs exclusions become intentional
- [x] property access: `get_property()/set_property()` *and* `$obj->prop` via handlers

- [ ] **Branch protection for `main`** — blocked: private repo on a Free plan (GitHub API returns
      403 "Upgrade to GitHub Pro or make this repository public"). Ruleset is ready in
      `.github/ruleset-main.json`; once public/Pro:
      `gh api -X POST repos/apss-pohl/php-gtk4/rulesets --input .github/ruleset-main.json`
      (drop `required_approving_review_count` to 0 while there is a single maintainer)
- [x] **License file** — MIT, `LICENSE` added 2026-08-25 (matches composer.json);
      keep php-src header on `gen/gen_stub.php` (PHP License 3.01, MIT-compatible)

## 4. Milestone 2 leftovers (unchanged by the review)

- [ ] `BoxedWrapper` (GdkRectangle, GdkRGBA, GStrv, graphene types)
- [ ] `G_TYPE_POINTER` (opaque handle), `G_TYPE_VARIANT`, real `GParamSpec` wrapper
- [ ] interfaces as PHP interfaces (`GtkOrientable`, `GListModel`, …)
- [ ] one `PhpCallable` abstraction for non-signal callbacks (timeout/idle, sorters, draw funcs)
- [ ] `GtkWidget` layer between `GObject` and `GtkWindow`
- [ ] closure teardown before Zend shutdown (MSHUTDOWN/RSHUTDOWN disconnect)

## 5. Keep (verified good, do not "clean up")

Namespace `Gtk4\`; PHP class == GType name + registry; owned refs + qdata identity + weak ref;
GValue-array GClosure marshaller; single GValue bridge; catch → leave scope → report; `ci.sh`
stages; ASan/UBSan/LSan + valgrind + gcov; `EveryClassTest`/`ExampleTest`; PHP 8.4/8.5 ×
GTK 4.14/4.22 matrix; C++20.
