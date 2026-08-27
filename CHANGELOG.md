# Changelog

All notable changes to php-gtk4. Format: [Keep a Changelog](https://keepachangelog.com/),
versions follow [SemVer](https://semver.org/). The version lives in `VERSION` at the repo root and is
mirrored into `src/php_gtk4.h`, `src/gtk4.stub.php` and the built module by `./ci.sh --only=version`
— see docs/RELEASING.md.

## [Unreleased]

### Added

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
- `--enable-gtk4-testing` (`FEATURES` gains `testing=yes|no`): compiles `Gtk::testing_*` hooks
  declared in `#if defined(PHPGTK_TESTING)` stub blocks; never in the shipped `.so`, used by the
  `asan`/`coverage` variants. First hook: `Gtk::testing_iterate_nested(int $iterations)` — a
  C-driven nested main loop, which lets the suite cover parked-exception `previous` chaining and
  the enclosing `run()` rethrow (`RethrowModeTest`, `tests/scripts/stress.php`).
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
