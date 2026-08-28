# Contributing

Everything below is enforced by `./ci.sh` and GitHub Actions; this page is the short version of
what those checks expect. `CLAUDE.md` is the exhaustive reference (it is written for an AI pair
programmer, but it is the same set of rules).

## Setup

```sh
git clone https://github.com/apss-pohl/php-gtk4 && cd php-gtk4
composer install                            # PHPUnit, phpstan, php-cs-fixer, phpcs
git config core.hooksPath .githooks         # pre-commit = fast QA, pre-push = full ./ci.sh
./ci.sh --only=build                        # see docs/BUILD.md for requirements
./ci.sh                                     # everything green before you start
```

The hooks are the local gate: `pre-commit` runs version/stub checks, PHP style (without phpstan),
clang-format and markdownlint in about ten seconds and remembers which tree passed; `pre-push` runs
the rest of `ci.sh` (clang-tidy, phpstan, build, load, tests, phpt) and only repeats the pre-commit
steps if the tree being pushed was never checked (a `--no-verify` commit). Skip once with
`--no-verify` when you know why.

## Hard constraints

- **PHP 8.4+ only**, NTS. No compatibility shims for older PHP; use 8.4 features freely.
- **C++20**, **GTK 4.14+** — guard anything newer with `GTK_CHECK_VERSION`. Never GTK 3 APIs.
- **Naming is snake_case, final.** Methods mirror the C API minus the type prefix
  (`gtk_window_set_title` → `set_title`); properties keep GTK's names with underscores. No
  camelCase aliases.
- **Everything PHP-visible lives in the `Gtk4\` namespace**; PHP class name = `Gtk4\<GTypeName>`.
- Zero clang-tidy findings, zero phpstan (level max) findings, no exceptions — there is no
  backlog and CI fails on the first one.

## Definition of done for a new class / method / constant

All four in the same change, or CI rejects it:

1. **Implementation** — `ZEND_METHOD(Gtk4_Class, name)` in `src/<Namespace>/<Class>.cpp` and
   registration in `src/gtk4.cpp` MINIT (`register_class(...)` for GObject handles, parents first).
2. **Tests** — a class per GTK class in `tests/`, extending `GtkTestCase`; every method exercised
   at least once, including its error path.
3. **Declaration** — `src/gtk4.stub.php` (typed signature, docblock, `@property` tags), then
   `./ci.sh --only=stubs --fix` to regenerate `src/gtk4_arginfo.h`, `stubs/gtk4.php` and the
   method comment blocks. Never edit generated files.
4. **Example** — `examples/<Class>.php` returning `Demo::page(...)`, plus the class in
   `Demo::SECTIONS` in `examples/bootstrap.php`. Visual, and it must not run itself.

`EveryClassTest`, `RobustnessTest`, `ExampleTest` and `StubsTest` are generic — they pick up new
classes automatically and must not be edited for one.

## Code rules worth knowing before the first patch

- A PHP `Throwable` must never unwind through GLib frames. Every trampoline ends with
  `phpgtk::report_pending_exception(origin)`; non-signal callbacks go through `src/core/callback.*`.
  `gen/overrides/Gtk.DrawingArea.cpp`, `Gtk.CustomFilter.cpp`, `Gtk.CustomSorter.cpp` are the templates.
- Errors raised to PHP use the PHP 8 vocabulary: bad argument → `ValueError`/`TypeError`; wrong
  object state → `LogicException`; the handle itself cannot do it → plain `Error`.
- Every C++ function has a comment block above it. For `ZEND_METHOD`s it is generated from the
  stub; helpers get a hand-written `//` line.
- `// NOLINTNEXTLINE(check) reason` only for findings inside GLib/Zend macro expansions, never a
  trailing `// NOLINT`. Don't reorder includes.
- Markdown wraps at 110 columns, emphasis with `*asterisks*`.
- Read `docs/PLAN.md` and `docs/TODO.md` before touching `src/core/`.

## Running the checks

```sh
./ci.sh --fix                              # apply every auto-fix (format, cs, stubs, md-lint)
./ci.sh --skip=cpp-lint,php-qa             # fast edit-build-test loop
./ci.sh --only=test --filter WidgetTest    # one test class
./tests/run.sh --filter 'ErrorTest::testNullRemovesHandler$'
./ci.sh --only=phpt                        # php-src run-tests.php over tests/phpt (stderr, fatals, exit codes)
./ci.sh --only=asan --filter X             # sanitizer run of one class
./ci.sh --with=asan,coverage,valgrind      # what CI runs in total
```

A segfault shows up as PHPUnit dying mid-run; bisect with `--filter 'Class::method$'`. GLib
`CRITICAL` lines from `ErrorTest` are expected; their text is asserted in `tests/phpt/`.

## Pull requests

- Branch from `main`; one topic per PR. `pre-push` has already run `./ci.sh` when you push.
- Add a line under `## [Unreleased]` in `CHANGELOG.md` for anything user-visible.
- Don't bump `VERSION` in a feature PR — that is the release trigger (`docs/RELEASING.md`).
- Dependabot handles composer and actions updates; don't bundle those.
- Windows: the build is `config.w32` (PHP SDK + gvsbuild GTK, see the Windows section of
  `docs/BUILD.md`). Nothing under `src/` may become platform-specific except `pin_gtk_library()`;
  anything that touches `config.m4` (sources, defines, features) needs the same change in `config.w32`.
  WebKit: milestone 6 in `docs/PLAN.md` first.
