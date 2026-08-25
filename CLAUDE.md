# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

php-gtk4 is a PHP extension written in C++ that binds GTK 4 to PHP via
[PHP-CPP](https://github.com/fast-debug/PHP-CPP) (the fast-debug fork). It is the successor to
`/mnt/share/dev/code/php-gtk3`; `PLAN.md` records the design and what is deliberately *not* carried
over from php-gtk3. Read `PLAN.md` before changing anything under `src/core/`.

Module name is **`gtk4`** everywhere: Makefile `NAME`, `Php::Extension("gtk4")` in `main.cpp`,
`extension=gtk4` in `gtk4.ini`. Keep them in sync.

Hard constraints:

- **PHP 8.4+ only.** The Makefile (`php-config --vernum >= 80400`) and `main.cpp`
  (`#error` on `PHP_VERSION_ID < 80400`) both refuse older PHP. Don't add compatibility shims for
  older PHP; do use PHP 8.4 features in stubs/tests/examples (property hooks, `#[\Deprecated]`,
  `new X()->m()`, typed constants).
- **C++20** (`-std=c++20`, GCC 11+/Clang 14+). `ci.sh` (clang-tidy) and `.clang-format` use the same standard.
- **GTK 4.14+** (`pkg-config gtk4`). Never use GTK 3 APIs (`gtk_main`, `GdkEvent` unions,
  `GtkContainer`, …).
- Linux is the primary target; Windows/WebKit follow later (see PLAN.md milestones).

## Build

```sh
./buildall.sh                          # build + install every enabled version in its table (sudo)
ONLY=8.4 ./buildall.sh                 # one version
make PHP_CONFIG=/usr/bin/php-config8.4 PHPCPP_STATIC=/path/libphpcpp.a.2.4.16 -j"$(nproc)"
make test                              # tests/*.php under xvfb-run against ./gtk4.so
make compile_commands                  # bear -- make objects (clangd / clang-tidy)
make clean
```

- `buildall.sh` expects per-version static libphpcpp in `PHPCPP_BASE/php<ver>/`
  (default `/mnt/share/dev/code/PHP-CPP/dist`, produced by `PHP-CPP/build-dist.sh`). **The
  `php-config` used must be the one PHP-CPP was built against**; a mismatch builds fine and corrupts
  the heap at shutdown.
- Objects live in `build/php<ver>/` with real source + header dependencies (`-MMD`), so editing a
  `.cpp`/`.h` rebuilds correctly (unlike php-gtk3). `version.o` is force-rebuilt for git hash/date.
- Feature flags: `WITH_WEBKIT=1` (compared with `ifeq (...,1)`). `PHPFLAGS`/`GTKFLAGS` are
  overridable (CI passes `-isystem` variants).
- **php-gtk3 and gtk4 must not be loaded in the same process.** PHP names are safe (everything is
  in the `Gtk4\` namespace) but libgtk-3/libgtk-4 export identical C symbols and the first loaded
  library wins, so gtk4 calls misbehave when gtk3 is loaded (gtk4 warns at startup). `buildall.sh`
  installs but does not enable `gtk4.ini`; run scripts with `bin/php-gtk4 script.php` (filters gtk3
  out via `PHP_INI_SCAN_DIR`) or `php8.4 -n -dextension=gtk4`. This machine has gtk3 enabled globally.

## Lint, QA, build, test — `./ci.sh`

One script, five stages, same as GitHub Actions: `cpp-lint` → `php-qa` → `build` → `load` → `test`.

```sh
./ci.sh                          # everything (tests included)
./ci.sh --fix                    # clang-tidy/clang-format/phpcbf/php-cs-fixer fixes, then check
./ci.sh --only=test              # one stage; --only=cpp-lint,php-qa for several
./ci.sh --skip=cpp-lint,php-qa   # fast edit-build-test loop
./ci.sh --filter SignalTest      # unknown args go to phpunit
./ci.sh --no-stan                # php-qa without phpstan
```

Env:
`PHP`, `PHP_CONFIG`, `PHPCPP_STATIC`/`PHPCPP_BASE`, `BUILD_DIR`, `JOBS` (default nproc, used by every
tool), `CLANG_TIDY`, `CLANG_FORMAT`.


C++: `.clang-tidy` has a documented deny-list (GLib macros, PHP-CPP slicing); everything else is
enforced and **CI fails on any finding** — there is no backlog, keep it that way. `SortIncludes` is
off; don't reorder includes. Use `// NOLINT(check)` only for findings inside GLib macro expansions.

## Definition of done for a new element

Every new class, method or constant ships with **all four** in the same change:

1. the C++ wrapper + registration (`register_*()`),
2. **tests** in `tests/` — a test class per new GTK class (extend `GtkTestCase`), every method
   exercised at least once, including its error path,
3. an entry in **`stubs/gtk4.php`** (typed signature, docblock, dummy body — `StubsTest` enforces),
4. a realistic use in **`examples/example.php`**, the canonical showcase — `ExampleTest` fails if a
   registered class is not used there.

## PHP QA

Covers `tests/`, `examples/`, `stubs/`, `gen/`. Every tool runs on all cores (`JOBS`, default
`nproc`: `phplint -j`, `phpcs --parallel`, `phpstan --threads`, php-cs-fixer auto-detects).
The tools run with gtk3/gtk4 filtered out of the ini scan dir, so PHPStan resolves `Gtk4\*` from
`stubs/gtk4.php` only — a stub with wrong types fails PHPStan on the tests that use it. Configs:
`phpstan.neon`, `.php-cs-fixer.dist.php`, `phpcs.xml.dist`, `.phplint.yml`. CI job `php-qa` runs
the same script and fails on any finding.

## Tests

PHPUnit 11 (`composer install` once; `vendor/` is gitignored).

```sh
./tests/run.sh                                  # whole suite  (= make test)
./tests/run.sh --filter SignalTest              # one class
./tests/run.sh --filter 'ErrorTest::testNullRemovesHandler$'
GTK4_SO=/path/gtk4.so ./tests/run.sh            # against another build (default ./gtk4.so)
```

`tests/run.sh` = `xvfb-run -a bin/php-gtk4 vendor/bin/phpunit`: Xvfb for the display, `bin/php-gtk4`
so gtk3 is filtered out while dom/mbstring/tokenizer (needed by PHPUnit) stay loaded — `php -n`
does not work here. `tests/bootstrap.php` refuses to run without gtk4, with gtk3, or without a
display, and calls `Gtk::init()` once.

- One test class per `src/core` module: `WrapTest`, `MarshalTest`, `SignalTest`, `ErrorTest`, plus
  `ExtensionTest`, `MainLoopTest`, `StubsTest`. Extend `GtkTestCase` — `$this->window()` gives a
  `GtkWindow` that is destroyed in `tearDown()`, `captureHandlerException()` installs a temporary
  `Gtk::set_exception_handler`.
- Tests are the *only* thing that exercises the C++ — a segfault shows up as PHPUnit dying
  mid-run; isolate with `--filter 'Class::method$'` per test to find it.
- `ExampleTest` checks `examples/example.php` uses every registered class and lints it.
- `MarshalTest` uses real `GtkWindow` properties per fundamental type (`title` string,
  `default-width` int, `resizable` bool, `opacity` double, `halign` enum, `display` object,
  `css-classes` = unsupported GStrv). Add a row when the marshaller learns a type.
- GLib `CRITICAL` lines on stderr from `ErrorTest` are expected (the g_critical fallback path for
  the no-handler case). They cannot be captured from PHP and must not be turned into PHP warnings
  (PHPUnit would throw inside the C++ callback). `tests/run.sh` sets `GSK_RENDERER=cairo` so GTK
  does not try EGL under Xvfb (silences `libEGL warning: DRI3`).

- `stubs/gtk4.php` is the IDE stub file (classes, typed signatures, docblocks). Every new class,
  method or constant must be added there; `tests/StubsTest.php` diffs it against
  `ReflectionExtension('gtk4')` and fails otherwise. **Stub methods must have dummy bodies**, not
  `{}`: `unset()` every parameter and end non-void methods with a placeholder `return` matching the
  declared type (`return 0;`, `return false;`, `return null;`, `return '';`) — otherwise VS Code /
  Intelephense reports "Not all paths return a value" and unused-parameter warnings.

- `.github/copilot-instructions.md` is a one-liner pointing at this file — keep project-wide
  conventions here only, never in both.

## CI

Three workflows (one per README badge): `.github/workflows/cpp-lint.yml`, `php-qa.yml`,
`tests.yml`. (1) static analysis — setup-php 8.4, GTK4 headers, PHP-CPP
*headers only*, `make compile_commands`, cpp-linter over the whole tree, fails on findings;
(2) build PHP-CPP + extension and run `tests/run.sh`. The apt package list must mirror the
Makefile's `GTK_PKGS`.

## Architecture

- `src/core/` — hand-written runtime. `wrap` (GObject handle: owns a ref, qdata back-pointer for
  identity, nearest-registered-class lookup), `marshal` (the single `GValue` ↔ `Php::Value`
  bridge), `gsignal` (`connect()` via a `GClosure` with a GValue-array marshaller — no varargs),
  `error` (the exception boundary + `Gtk::set_exception_handler`), `params` (typed arg helpers).
- `src/Gtk/` — hand-written classes until the generator exists; `register_Gtk()` is called from
  `main.cpp`. PHP-CPP initialises classes in `ext.add()` order: **add the parent first** (copy overload),
  then pass it to children for `extends()` — a derived class added before its base silently loses it.
- `src/gen/` — generator output (milestone 3, GIR-driven; never hand-edit). `gen/overrides/`
  replaces individual generated method bodies.
- **Everything PHP-visible is in the `Gtk4\` namespace** (`Php::Namespace` in `main.cpp`;
  `register_*()` take a `Php::Namespace&`). Class name = `Gtk4\<GTypeName>` via
  `phpgtk::php_class_name()`; `wrap()` depends on it. Constants too (`Gtk4\PHPGTK_VERSION`).
  Stubs declare `namespace Gtk4;`, scripts `use Gtk4\{Gtk, GtkWindow};`.
- Exception rule (from php-gtk3, keep it): a PHP throwable must never unwind through GLib frames.
  Trampolines catch `Php::Throwable` around their whole body, copy message/code, leave the catch
  scope, then call `phpgtk::report_callback_exception()`.
