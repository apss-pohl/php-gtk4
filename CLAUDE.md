# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

php-gtk4 is a PHP extension written in C++20 against the **native Zend API** (no PHP-CPP — see
docs/TODO.md §1 / docs/PLAN.md for why it was dropped), built with the standard `phpize`/`config.m4` flow.
It is the successor to `/mnt/share/dev/code/php-gtk3`; `docs/PLAN.md` records the design and what is
deliberately *not* carried over, `docs/TODO.md` the open review findings. Read both before changing
anything under `src/core/`.

Module name is **`gtk4`** everywhere: `config.m4`, `zend_module_entry` in `src/gtk4.cpp`,
`extension=gtk4` in `gtk4.ini`. Keep them in sync.

Hard constraints:

- **PHP 8.4+ only** — 8.4 and 8.5 in the CI matrix; **locally only 8.4 is built/tested** (`ci.sh`
  defaults, `buildall.sh` has 8.5 disabled — `ONLY=8.5 ./buildall.sh` to force). `config.m4` and
  `src/php_gtk4.h` (`#error` on `PHP_VERSION_ID < 80400`) both refuse older PHP. Don't add
  compatibility shims for older PHP; do use PHP 8.4 features in stubs/tests/examples.
- **C++20** (`-std=c++20`, GCC 11+/Clang 14+). `ci.sh` (clang-tidy) and `.clang-format` use the
  same standard.
- **GTK 4.14+** (`pkg-config gtk4`); CI builds and tests on Ubuntu 24.04 (GTK 4.14, the floor) —
  guard anything newer than 4.14 with `GTK_CHECK_VERSION`. Never
  use GTK 3 APIs (`gtk_main`, `GdkEvent` unions, `GtkContainer`, …).
- Linux is the primary target; Windows/WebKit follow later (see docs/PLAN.md milestones).

## Build

```sh
./ci.sh --only=build                   # phpize + configure + make -> ./gtk4.so (what everything else uses)
./buildall.sh                          # build + install for every enabled version in its table (sudo for install)
phpize8.4 && ./configure --with-php-config=/usr/bin/php-config8.4 && make -j"$(nproc)"   # by hand
./configure ... --enable-gtk4-sanitize | --enable-gtk4-coverage | --enable-gtk4-webkit
bear -- make                           # compile_commands.json for clangd / clang-tidy
```

- Requires `php8.4-dev` (phpize/php-config) and `libgtk-4-dev`. `config.m4` refuses PHP < 8.4 and
  GTK < 4.14. Build metadata (git hash, date, features) is baked in at configure time
  (`PHPGTK_BUILD_INFO` in config.h).
- **Editing `config.m4` requires re-running `phpize`** (configure is generated from it); `ci.sh`
  does that on every build. **Never run `phpize --clean`**: it deletes `tests/*.php` (php-src
  assumes `.phpt` tests there). Use `make clean` — and note that phpize's `make clean` removes every
  `*.so` under the tree, so `ci.sh` stashes the built variants around it. Variants
  (sanitize/coverage) rebuild in place and copy `modules/gtk4.so` to `gtk4-asan.so` / `gtk4-cov.so`.
- **Version source of truth = `VERSION`** (repo root, one line, `X.Y.Z` or `X.Y.Z-dev`). Never edit
  `PHP_GTK4_VERSION` (`src/php_gtk4.h`) or `const VERSION` (`src/gtk4.stub.php`) by hand — they are
  mirrors written by `./ci.sh --only=version --fix`, checked by the `version` stage, again by
  `config.m4` at configure time, again by the `load` stage against the built `.so`, and again by
  `ExtensionTest`. Bumping the file is what triggers a release (docs/RELEASING.md).
- **API declaration = `src/gtk4.stub.php`.** `gen/gen_stub.php` (vendored from php-src) generates
  `src/gtk4_arginfo.h` (class entries, method tables, typed arginfo); `gen/ide-stub.php` generates
  `stubs/gtk4.php` for IDEs. Both generated files are committed; `./ci.sh --only=stubs` (and CI)
  fail if they are stale (`--fix` regenerates). **Never edit the generated files.** Adding a
  method = declare it in the stub, regenerate, implement the `ZEND_METHOD(Gtk4_Class, name)`.
- GObject properties exposed as PHP properties are declared with `@property` tags on the class in
  the stub (the generator will emit them from GIR); the IDE stub adds `__get/__set/__isset` to
  `GObject` so PHPStan/IDEs honour them (`StubsTest` ignores those three).
- **Every C++ function has a comment block directly above it.** For `ZEND_METHOD`s the block is
  generated from the stub (`php gen/method-comments.php`, run by `./ci.sh --only=stubs --fix`):
  PHP signature + first docblock paragraph — never edit those by hand, edit the stub. Helpers,
  handlers and trampolines get a hand-written `//` line (what it is for / which handler slot).
  `./ci.sh --only=stubs` fails on a missing or stale block.
- `src/gtk4_arginfo.h` is included by exactly one TU (`src/gtk4.cpp`, which owns MINIT and class
  registration); method files only define `ZEND_METHOD`s.
- **php-gtk3 and gtk4 must not be loaded in the same process.** PHP names are safe (everything is
  in the `Gtk4\` namespace) but libgtk-3/libgtk-4 export identical C symbols and the first loaded
  library wins, so gtk4 calls misbehave when gtk3 is loaded (gtk4 warns at MINIT). `buildall.sh`
  installs but does not enable `gtk4.ini`; run scripts with `bin/php-gtk4 script.php` (filters gtk3
  out via `PHP_INI_SCAN_DIR`; prefers the repo's freshly built `./gtk4.so` over the installed copy,
  `GTK4_SO=gtk4` forces the installed one) or `php8.4 -n -dextension=gtk4`. This machine has gtk3
  enabled globally.

## Lint, QA, build, test — `./ci.sh`

One script, same stages as GitHub Actions: `version` → `stubs` → `cpp-lint` → `md-lint` → `php-qa` →
`build` → `load` → `test` → `phpt` (php-src `run-tests.php` over `tests/phpt`), plus the opt-in `asan` (ASan+UBSan
on the suite, LSan on `tests/scripts/stress.php`,
`gtk4-asan.so`), `coverage` (gcov per-file C++ line coverage via gcovr, `gtk4-cov.so`, HTML in
`coverage/`) and `valgrind` (memcheck on the stress script — uninitialised reads, definite leaks;
`tests/valgrind.supp` + GLib's `glib.supp`).

```sh
./ci.sh                                 # default stages (tests included)
./ci.sh --fix                           # clang-tidy/clang-format/phpcbf/php-cs-fixer fixes + stub regeneration
./ci.sh --only=test                     # one stage; --only=cpp-lint,php-qa for several
./ci.sh --skip=cpp-lint,php-qa          # fast edit-build-test loop
./ci.sh --filter SignalTest             # unknown args go to phpunit
./ci.sh --no-stan                       # php-qa without phpstan
./ci.sh --with=asan,coverage,valgrind   # default stages + all extra ones (what CI runs in total)
./ci.sh --only=asan --filter X          # sanitizer run of one test class
./ci.sh --skip=tidy | --skip=format     # sub-steps of cpp-lint
./ci.sh --only=phpt                     # run-tests.php only (PHPT_TESTS=tests/phpt/x.phpt for one)
./ci.sh --only=md-lint --fix            # markdownlint over **/*.md, applying the fixable rules
./ci.sh --only=version --fix            # rewrite the version mirrors from ./VERSION (then --only=stubs --fix)
```

Env: `PHP`, `PHP_CONFIG`, `PHPIZE`, `JOBS` (default nproc, used by every tool), `CLANG_TIDY`,
`CLANG_FORMAT` (default: newest installed `clang-*-N`; CI and `.vscode` use 20), `COMPOSER`,
`PHPT_TESTS` (default `tests/phpt`).

C++: `.clang-tidy` has a documented deny-list (GLib and Zend macro expansions: do/while, varargs,
void* casts, zval union access, ZPP cognitive complexity, C-array tables); everything else is
enforced with `WarningsAsErrors: '*'` and **CI fails on any finding** — there is no backlog, keep
it that way. `SortIncludes` is off; don't reorder includes. Use `// NOLINTNEXTLINE(check) reason`
(not trailing `// NOLINT`, which clang-format wraps onto its own line and thereby disables) only
for findings inside GLib/Zend macro expansions. The generated `src/gtk4_arginfo.h` is not linted.

Markdown: `markdownlint-cli2` over every `*.md`, configured in `.markdownlint-cli2.jsonc`. Prose
wraps at 110 columns (tables, fenced code and headings exempt), emphasis is `*asterisks*` (MD049/
MD050) because underscores are everywhere in snake_case identifiers, and MD060 table padding is
off. `--fix` applies the structural rules (blank lines around headings/lists/fences); line length
is rewrapped by hand. The stage prefers a global `markdownlint-cli2` and falls back to `npx`.

## PHP QA

`php-qa` = phplint → phpcs (PSR-12) → php-cs-fixer check (PER-CS 2.0) → phpstan (level max) over
`tests/`, `examples/`, `gen/ide-stub.php`. Every tool runs on all cores. The tools run with
gtk3/gtk4 filtered out of the ini scan dir, so PHPStan resolves `Gtk4\*` from the generated
`stubs/gtk4.php` only — a stub with wrong types fails PHPStan on the tests that use it. Generated
`stubs/gtk4.php`, the php-src-syntax `src/gtk4.stub.php` and vendored `gen/gen_stub.php` are
excluded from the style tools. Configs: `phpstan.neon`, `.php-cs-fixer.dist.php`, `phpcs.xml.dist`,
`.phplint.yml`.

## Definition of done for a new element

Every new class, method or constant ships with **all four** in the same change:

1. the `ZEND_METHOD` implementation + registration in `src/gtk4.cpp` MINIT,
2. **tests** in `tests/` — a test class per new GTK class (extend `GtkTestCase`), every method
   exercised at least once, including its error path,
3. its declaration in **`src/gtk4.stub.php`** (typed signature, docblock, `@property` tags) +
   regenerated `src/gtk4_arginfo.h` and `stubs/gtk4.php` (`./ci.sh --only=stubs --fix`;
   `StubsTest`/CI enforce),
4. **`examples/<Class>.php`** — the examples are atomic, one runnable file per registered class,
   named after it and requiring `examples/bootstrap.php` for the shared harness. Make it *visual*:
   the file opens a window that shows what the class does (a markup `GtkLabel` or a cairo
   `GtkDrawingArea`, often inside a `GtkButton` so clicking advances the demo) rather than printing
   about it. `ExampleTest` fails if a registered class has no file, and `examples/README.md` indexes
   them.

## Tests

Two harnesses. **PHPUnit 12** (`tests/*.php`, `composer install` once; `vendor/` is gitignored) is
the primary one and where every new class/method is tested. **`tests/phpt/`** is php-src's
`run-tests.php`, run by `./ci.sh --only=phpt`, and only carries assertions PHPUnit structurally
cannot make (see the end of this section).

```sh
./ci.sh --only=test                             # whole suite
./ci.sh --only=test --filter SignalTest         # one class
./tests/run.sh --filter 'ErrorTest::testNullRemovesHandler$'
GTK4_SO=/path/gtk4.so ./tests/run.sh            # against another build (default ./gtk4.so)
./ci.sh --only=phpt                             # run-tests.php over tests/phpt
PHPT_TESTS=tests/phpt/error-log-signal.phpt ./ci.sh --only=phpt   # one .phpt
```

`tests/run.sh` = `xvfb-run -a bin/php-gtk4 vendor/bin/phpunit`: Xvfb for the display, `bin/php-gtk4`
so gtk3 is filtered out while dom/mbstring/tokenizer (needed by PHPUnit) stay loaded — `php -n`
does not work here. `tests/bootstrap.php` refuses to run without gtk4, with gtk3, or without a
display, and calls `Gtk::init()` once.

- One test class per `src/core` module: `WrapTest`, `MarshalTest`, `SignalTest`, `ErrorTest`,
  `PropertyAccessTest`, `RethrowModeTest`, `BoxedTest`, `ParamSpecTest`, `WidgetTest`, `ActionTest`
  (variants + interfaces), `ShutdownTest`, plus `ExtensionTest`, `MainLoopTest` (GMainLoop + GLib
  sources), `ApplicationTest`, `StubsTest`, `ExampleTest`, `EveryClassTest`. Extend `GtkTestCase`
  — `$this->window()` gives a `GtkWindow` destroyed in
  `tearDown()`, `captureHandlerException()` installs a temporary `Gtk::set_exception_handler`,
  `latch()`/`latched()` for flags set from GTK callbacks, `opaque()` to pass deliberately wrong
  arguments past static analysis.
- Tests are the *only* thing that exercises the C++ — a segfault shows up as PHPUnit dying
  mid-run; isolate with `--filter 'Class::method$'` per test to find it.
- `EveryClassTest` constructs every instantiable class and calls every arg-less `get_*/is_*/has_*`
  — generic on purpose, never edit it for a new class.
- `ExampleTest` checks that every registered class has its own `examples/<Class>.php`, that the file
  mentions the class outside its imports, that it requires the shared harness, and lints all of them.
- `MarshalTest` uses real `GtkWindow` properties per fundamental type (`title` string,
  `default-width` int, `resizable` bool, `opacity` double, `halign` enum, `display` object,
  `css-classes` = unsupported GStrv). Add a row when the marshaller learns a type.
- `tests/scripts/stress.php` (not PHPUnit) churns handles/signals/exceptions/lifetimes N rounds and
  exits normally; used by the `asan` (with LSan), `coverage` and `valgrind` stages. Extend it when
  adding runtime paths. `tests/lsan.supp` and `tests/valgrind.supp` may only contain third-party
  symbols.
- `tests/run.sh` pins **`GDK_BACKEND=x11`** (and `GSK_RENDERER=cairo`, `GDK_DEBUG=gl-disable`): with
  `WAYLAND_DISPLAY` set in a developer session GDK would ignore the Xvfb `DISPLAY` and run the suite
  on the real compositor — GTK 4.14's Wayland backend then corrupts the heap after enough windows
  are presented/destroyed (`malloc(): unaligned fastbin chunk`, write into a freed block inside
  libgtk, zero frames of ours). Xvfb/X11 is the test target; Wayland is exercised manually.
- `tests/run.sh` forces `XDEBUG_MODE=off`: xdebug's develop-mode observer segfaults at request
  shutdown after `ReflectionMethod::invoke()` on internal methods. Not our bug; don't debug it.
- Arginfo comes from the stub, argument parsing from `ZEND_PARSE_PARAMETERS_*` — arity/type
  violations are `ArgumentCountError`/`TypeError` (PHP 8 semantics).
- GLib `CRITICAL` lines on stderr from `ErrorTest` are expected (the g_critical fallback path for
  the no-handler case). They cannot be captured from PHP and must not be turned into PHP warnings
  (PHPUnit would throw inside the C callback). `tests/run.sh` sets `GSK_RENDERER=cairo` so GTK
  does not try EGL under Xvfb. **Their text is asserted in `tests/phpt/` instead**, where
  run-tests.php compares the process's stderr.
- `tests/phpt/` (`make test` / `./ci.sh --only=phpt`, see `tests/phpt/README.md`): one process per
  test, expected output covers stdout **and** stderr. Put a test here only for what PHPUnit cannot
  reach — `g_critical`/GLib warning text, uncaught fatals and exit codes, RSHUTDOWN teardown
  output, `--INI--`/`--ENV--` dependent startup, and crash isolation (run-tests names the crashing
  test and continues, PHPUnit just dies). Everything else belongs in `tests/*.php`.
  `make test` strips every `extension=` line from the scanned ini into `tmp-php.ini` and re-adds
  only `modules/gtk4.so`, so gtk3 is filtered out here without `bin/php-gtk4`
  (`extension-isolation.phpt` guards that). Failures leave `.out`/`.diff` next to the `.phpt`
  (gitignored). Display-dependent tests guard with `--SKIPIF--` on `tests/phpt/skipif-display.inc`.

## Local gates

`git config core.hooksPath .githooks` once per clone: `pre-commit` runs the fast checks (stubs,
PHP QA without phpstan, clang-format, markdownlint), `pre-push` runs `./ci.sh`. `--no-verify` skips once.
`config.m4` refuses ZTS PHP (the runtime uses plain statics — NTS only). Coverage has a floor
(`COVERAGE_MIN_LINES`, default 80). `RobustnessTest` calls every method with garbage arguments;
`DocsTest` guards CLAUDE.md sections and doc-mentioned paths. `CHANGELOG.md` has the release
checklist; Dependabot watches composer and actions.

## CI

Four workflows (three have a README badge): `.github/workflows/cpp-lint.yml`, `php-qa.yml`,
`tests.yml`, `release.yml`. (1) static analysis — setup-php 8.4, GTK4 headers, phpize build under `bear` for
`compile_commands.json`, stubs up-to-date check, cpp-linter over the whole tree, fails on
findings; (2) `./ci.sh --only=php-qa` + `--only=md-lint`; (3) build the extension and run the
PHPUnit suite *and* `./ci.sh --only=phpt` for PHP 8.4 and 8.5 on Ubuntu 24.04 (`fail-fast: false`;
failing `.out`/`.diff` files upload as the `phpt-failures-php*` artifact), plus `sanitizers`
(`ci.sh --only=valgrind` + `--only=asan`) and `coverage` (`--only=coverage`, gcovr HTML artifact)
jobs on 8.4. The apt package lists must mirror `config.m4`'s pkg-config modules.
(4) `release.yml` on every push to `main`: reads `VERSION` and either publishes an immutable
`vX.Y.Z-dev.<run>` pre-release (suffix `-dev`; the newest 5 are kept, older ones deleted with their
tags) or the real `vX.Y.Z` release (no suffix, once),
after running `./ci.sh` in full itself — it does not key off `tests.yml`. Assets are one `.so` per
supported PHP named with its whole ABI identity, plus the source tarball and `SHA256SUMS`, with
build provenance attestation instead of a signed tag. **`VERSION` is the only release trigger; never
create a tag or a release by hand.** docs/RELEASING.md is the full description.
`.github/copilot-instructions.md` is a one-liner pointing at this file — keep project-wide
conventions here only.

## Architecture

- `src/core/` — the runtime. `object` (`struct Object { GObject *obj; zend_object std; }`, the
  object handlers: `free_obj`, no clone, `read/write/has_property` mapping `$obj->prop`
  (underscores → dashes) to GObject properties, `get_debug_info` for `var_dump`; owned ref + qdata
  identity + weak ref; the GType-name → `zend_class_entry` registry; `wrap()`/`unwrap()`/
  `PHPGTK_SELF`), `marshal` (the single `GValue` ↔ `zval` bridge), `gsignal` (`connect()` via a
  `GClosure` with a GValue-array marshaller, callable resolved with `zend_fcall_info_init` and
  invoked with `zend_call_function`), `error` (the exception boundary:
  `report_pending_exception()` takes `EG(exception)`, hands the real `Throwable` to
  `Gtk::set_exception_handler`, else `g_critical`), `boxed` (value-type handles: owned
  `g_boxed_copy`, clone/compare by value, fields as properties via per-class reader/writer;
  `GdkRGBA`, `GdkRectangle`; `GStrv` ↔ `list<string>` is a value mapping), `variant` (`GVariant` ↔
  PHP values, type-directed or inferred), `paramspec` (`GParamSpec` handle), `phpvalue` (GType
  `PhpValue`: a GObject subclass carrying a zval so PHP data can sit in
  `GListStore`; instances drained in RSHUTDOWN), `collections` (`GList`/`GSList`/`GPtrArray`/`char**`
  → PHP lists with GIR transfer semantics),
  `fundamental` (registry-driven handles for refcounted non-GObject types: `GParamSpec`,
  `CairoContext` (cairo_t via cairo-gobject; marshal's boxed arm falls back to this registry),
  `GdkEvent` etc. later; `new X()` on them throws), `enums` (GEnum ↔ int-backed PHP enum via a GType
  registry; cases declared literally in the stub
  and verified against the `GEnumClass` at RINIT — a mismatch is fatal; GFlags stay ints with
  constant classes (values literal in the stub, verified against the `GFlagsClass` at RINIT);
  unregistered enum types stay ints), `callback` (non-signal
  callables; `callback_free()` only parks the callable, `callback_drain()` releases it at a safe
  point because destroy notifies run inside GTK frames), `teardown` (RSHUTDOWN disconnects every
  tracked closure/source and clears every notified-scope callable so nothing finalizes
  after Zend is gone — `tests/scripts/shutdown.php` guards it), `mainloop` (running-loop registry
  for `ExceptionMode::Rethrow`).
- `src/Gtk/`, `src/Gdk/`, `src/Gio/`, `src/Cairo/` — `ZEND_METHOD` implementations per class;
  registration lives in `src/gtk4.cpp`
  MINIT: `register_class("GTypeName", register_class_Gtk4_X(parent_ce))` parents first —
  `register_class()` installs `create_object` (inherited by subclasses registered afterwards) and
  records the GType → class mapping `wrap()` uses — pass the `*_TYPE_*` macro, never look types up by
  name at MINIT (GTK registers GTypes lazily). Enums:
  `register_enum(GTK_TYPE_X, register_class_Gtk4_X())`. Boxed classes use `register_boxed()` with
  their
  field table; interfaces (`GAction`, `GActionMap`, `GActionGroup`) come from `implements` in the
  stub (gen_stub emits `zend_class_implements`). `G_TYPE_POINTER` is unsupported on purpose.
- `gen/` — `gen_stub.php` (vendored), `ide-stub.php`; the GIR generator (docs/PLAN.md milestone 3) will
  live here and emit stub sections, `ZEND_METHOD` skeletons and the MINIT block.
- **Everything PHP-visible is in the `Gtk4\` namespace**; PHP class name = `Gtk4\<GTypeName>`, and
  `wrap()` walks the GType parent chain to the nearest registered class. Constants:
  `Gtk4\VERSION`, `BUILD_INFO`, `FEATURES`. One `Object` struct serves every class (all per-class
  state lives in the GObject).
- Exception rule (from php-gtk3, keep it): a PHP throwable must never unwind through GLib frames,
  and Zend refuses to run PHP while one is pending. Every trampoline ends with
  `phpgtk::report_pending_exception(origin)`, which implements `Gtk4\ExceptionMode`: `Log`
  (handler/g_critical, GTK continues) or `Rethrow` (handler, then the Throwable stays pending,
  `quit_running_loops()` stops `GMainLoop::run`/`GtkApplication::run`, and it propagates to PHP).
  Non-signal callbacks go through `src/core/callback.*` with the installing method as origin;
  typed C callbacks (`GtkDrawingAreaDrawFunc`, `GtkCustomFilterFunc`, `GCompareDataFunc`) follow
  the trampoline rule in docs/PLAN.md ("Typed C callbacks") — `src/Gtk/GtkDrawingArea.cpp`,
  `GtkFilter.cpp`, `GtkSorter.cpp` are the templates.
- Main loop: `GtkApplication::run()` (preferred) or `GMainLoop` + `GLib::idle_add/timeout_add`.
  There is no `Gtk::main()`. `connect()` takes exactly `(string $signal, callable $handler)` —
  no user data, closures capture with `use`. `emit()` emits with converted arguments (use it in
  tests instead of `activate()`, whose `clicked` needs a realized widget).
- Actions: `GSimpleAction` + `GtkApplication::add_action()`; GVariant parameters/states are plain
  PHP values. `has_action/list_actions/activate_action` only work once the app is registered
  (from `startup` on); `add/remove/lookup_action` always. Errors raised *to* PHP from methods use the PHP 8
  vocabulary: `zend_value_error`, `zend_type_error`, `zend_argument_*`, `spl_ce_LogicException`.
- **Naming is snake_case, final** (decided 2026-08-25, docs/PLAN.md): methods mirror the GTK C API
  with the type prefix stripped (`gtk_window_set_title` → `set_title`), properties keep GTK's names
  with underscores (`$win->default_width`). Never add camelCase aliases; the phpcs camelCaps
  exclusion for the stub is intentional.
