# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

php-gtk4 is a PHP extension written in C++20 against the **native Zend API** (no PHP-CPP — see
docs/DESIGN.md for why it was dropped), built with the standard `phpize`/`config.m4` flow.
It is the successor to [php-gtk3](https://github.com/scorninpc/php-gtk3); docs/DESIGN.md records the
decisions and what is deliberately *not* carried over, `docs/TODO.md` what is open. Read both before changing
anything under `src/core/`.

Module name is **`gtk4`** everywhere: `config.m4`, `zend_module_entry` in `src/gtk4.cpp`,
`extension=gtk4` in `gtk4.ini`. Keep them in sync.

Hard constraints:

- **CLI only.** php-gtk4 runs desktop applications from `php script.php`; web SAPIs (FPM, Apache,
  CGI) are not a target and never a design consideration. ZTS is built and tested because it is a
  PHP build flavour, not because of any multi-threaded server use.

- **PHP 8.4+ only** — 8.4 and 8.5 in the CI matrix; **locally only 8.4 is built/tested** (`ci.sh`
  defaults, `buildall.sh` has 8.5 disabled — `ONLY=8.5 ./buildall.sh` to force). `config.m4` and
  `src/php_gtk4.h` (`#error` on `PHP_VERSION_ID < 80400`) both refuse older PHP. Don't add
  compatibility shims for older PHP; do use PHP 8.4 features in stubs/tests/examples.
- **C++20** (`-std=c++20`, GCC 11+/Clang 14+). `ci.sh` (clang-tidy) and `.clang-format` use the
  same standard.
- **GTK 4.14+** (`pkg-config gtk4`); CI builds and tests on Ubuntu 24.04 (GTK 4.14, the floor) —
  guard anything newer than 4.14 with `GTK_CHECK_VERSION`. Never
  use GTK 3 APIs (`gtk_main`, `GdkEvent` unions, `GtkContainer`, …).
- Linux is the primary target. **Windows builds through `config.w32`** (PHP SDK `phpize.bat` +
  `configure --with-gtk4=<gvsbuild root>` + `nmake` → `php_gtk4.dll`, GTK 4 from gvsbuild — MSVC,
  same CRT as PHP; `docs/BUILD.md` "Windows"). Whatever changes in `config.m4` (defines, features,
  source dirs) changes in `config.w32` too. The only platform-specific code allowed in `src/` is
  `pin_gtk_library()` in `src/gtk4.cpp` and the one `GIOChannel` constructor switch in
  `GLib::io_add_watch()` (a Windows `SOCKET` is not a C fd); everything else compiles unchanged on both. `ci.sh`,
  `tests/phpt` and the sanitizer/coverage stages are Linux-only; `bin/php-gtk4.cmd` and
  `tests/run.cmd` are the Windows launchers. **No header under `src/` may equal a GLib/GTK header
  path case-insensitively** (a former src/Gio/GListModel.h shadowed `gio/glistmodel.h` on Windows — hence
  the generated prototypes live in `src/gen_prototypes.h`; `HeaderNamesTest` enforces it).
- **WebKitGTK is optional and Linux-only.** `--enable-gtk4-webkit` compiles `src/WebKit/` and
  `src/JavaScriptCore/` (the `WebKit*`/`JSC*` classes, generated from `WebKit-6.0.gir` and
  `JavaScriptCore-6.0.gir` like everything else); without it those two directories are left out
  of the source glob (`config.m4`, `config.w32`, and ci.sh's clang-tidy when the headers are
  absent) and their MINIT/arginfo blocks sit under `#ifdef PHPGTK_WITH_WEBKIT`.
  `CONDITIONAL_NAMESPACES` in `gen/gir/config.php` is the table; `tests/Features.php` mirrors it
  by class prefix so a test of a feature the build lacks skips itself; `FeatureGateTest` pins the
  five places to each other. `Gtk4\FEATURES` (`webkit=yes|no`) says what a module has. Nothing
  outside those two directories may include a WebKit header.

## Build

User-facing version of this section (plus the Windows status): `docs/BUILD.md`; contributor
summary: `docs/CONTRIBUTING.md`. Keep all three consistent.

```sh
./ci.sh --only=build                   # phpize + configure + make -> ./gtk4.so (what everything else uses)
./buildall.sh                          # build + install for every enabled version in its table (sudo for install)
phpize8.4 && ./configure --with-php-config=/usr/bin/php-config8.4 && make -j"$(nproc)"   # by hand
./configure ... --enable-gtk4-sanitize | --enable-gtk4-coverage | --enable-gtk4-webkit
```

- `./ci.sh --only=build` also writes `compile_commands.json` (from `make -Bn`, no `bear`
  needed) — the include paths clangd in `.vscode/settings.json` reads; without it every
  header reports `'php_gtk4.h' file not found`. Rebuild after `config.m4` changes.
- Requires `php8.4-dev` (phpize/php-config) and `libgtk-4-dev`; the default `gen` stage (and so
  the pre-commit hook) also needs `gir1.2-gtk-4.0` **and `libwebkitgtk-6.0-dev`** (which brings
  `gir1.2-webkit-6.0`: the WebKit namespaces are generated whether or not the build has the
  flag). `config.m4` refuses PHP < 8.4 and GTK < 4.14.
  Build metadata (git hash, date, features) is baked in at configure time (`PHPGTK_BUILD_INFO` in
  config.h).
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
  fail if they are stale (`--fix` regenerates). **Never edit the generated files.** `stubs/` is
  also the Composer package `php-gtk4/stubs` (`stubs/composer.json`, `stubs/extension.neon` for
  PHPStan, no autoload - the extension defines the classes): `release.yml` pushes the directory to
  the `apss-pohl/php-gtk4-stubs` repository and tags it on every real release
  (docs/RELEASING.md "The stubs package"). Adding a
  method = declare it in the stub, regenerate, implement the `ZEND_METHOD(Gtk4_Class, name)`.
- GObject properties exposed as PHP properties are declared with `@property` tags on the class in the stub
  (the generator emits them from GIR, `@property-read` / `@property-write` where GIR says the property is
  one-way, and both sweeps hold the tag to its word); the IDE stub adds `__get/__set/__isset` to `GObject` so
  PHPStan/IDEs honour them (`StubsTest` ignores those three).
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

One script, same stages as GitHub Actions: `commits` (Conventional Commits, `bin/commit-lint`) →
`version` → `gen` (`gir.php --install` must reproduce
the committed generated files — it regenerates in place even without `--fix` and fails on a diff,
like `stubs`) → `stubs` → `cpp-lint` → `md-lint` → `php-qa` →
`build` → `load` → `test` → `phpt` (php-src `run-tests.php` over `tests/phpt`), plus the opt-in `asan` (ASan+UBSan
on the suite, LSan on `tests/scripts/stress.php`,
`gtk4-asan.so`; `tests/asan-dlopen-shim.c` is preloaded ahead of libasan to strip `RTLD_DEEPBIND`
from php's `dlopen()`, which the sanitizer runtime otherwise refuses — setup-php's PHP builds use it),
`coverage` (gcov per-file C++ line coverage via gcovr, `gtk4-cov.so`, HTML in
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
./ci.sh --skip=tidy | --skip=format     # sub-steps of cpp-lint; --skip=stan | --skip=style for php-qa
./ci.sh --only=phpt                     # run-tests.php only (PHPT_TESTS=tests/phpt/x.phpt for one)
./ci.sh --only=md-lint --fix            # markdownlint over **/*.md, applying the fixable rules
./ci.sh --only=version --fix            # rewrite the version mirrors from ./VERSION (then --only=stubs --fix)
```

Env: `PHP`, `PHP_CONFIG`, `PHPIZE`, `JOBS` (default nproc, used by every tool), `CLANG_TIDY`,
`CLANG_FORMAT` (default: newest installed `clang-*-N`; CI and `.vscode` use 20), `COMPOSER`,
`PHPT_TESTS` (default `tests/phpt`), `GTK4_CONFIGURE_ARGS` (the configure switches for the
default `build`; **unset** it and the build enables `--enable-gtk4-webkit` wherever the
WebKitGTK headers are installed, which is the widest coverage one build can have - set it to the
empty string to force a build without them). A build is incremental (plain `make`) when the
configure arguments are unchanged and `Makefile` is newer than `config.m4`; otherwise it
reconfigures from clean (`.ci/configure.args` remembers the arguments).

clang-tidy is by far the most expensive thing in the pipeline (~280 s cold over the whole tree on
16 cores, 748 s of an 851 s job on a CI runner — the entire critical path), so a run remembers
which files it linted at which content in `.ci/tidy-ok` (gitignored; `cpp-lint.yml` restores it
through `actions/cache`, keyed additionally on the clang-tidy and GTK/PHP versions that key
cannot see, so a new toolchain still relints everything) and skips them next time; editing a header,
`.clang-tidy` or a compiler flag invalidates the lot, and `GTK4_LINT_ALL=1` forces it.
`gen/gir.php` and `gen_stub.php` write a
generated file **only when its content changed**, so an unchanged tree keeps its mtimes and `make`
stays a no-op — rewriting identical files used to cost ~50 s of recompiling on every `ci.sh` run.
Together those two make a push that touched no C++ take seconds.

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

`php-qa` = phplint → phpcs (PSR-12) → php-cs-fixer (PER-CS 2.0) → phpstan (level max) over
the whole PHP side — `tests/`, `examples/`, `gen/*.php` (phpstan: `gen/gir.php` against a baseline,
`phpstan-baseline-gir.neon`, that only shrinks), `bin/` and the hand-written stub for phplint — minus
the vendored/generated files each config excludes. Like `cpp-lint`, each formatter is **one** step that either
checks or fixes: without `--fix` phpcs reports and php-cs-fixer prints a diff; with `--fix` phpcbf and
`php-cs-fixer fix` apply the changes and no diff is printed. phpcs still gates in `--fix` mode, because
it is the only one that reports what no fixer can repair (line length above 120). Every tool runs on
all cores. The tools run with
gtk3/gtk4 filtered out of the ini scan dir, so PHPStan resolves `Gtk4\*` from the generated
`stubs/gtk4.php` only — a stub with wrong types fails PHPStan on the tests that use it. Generated
`stubs/gtk4.php`, the php-src-syntax `src/gtk4.stub.php` and vendored `gen/gen_stub.php` are
excluded from the style tools. Configs: `phpstan.neon`, `.php-cs-fixer.dist.php`, `phpcs.xml.dist`,
`.phplint.yml`.

## Definition of done for a new element

Every new class, method or constant ships with **all four** in the same change:

1. the `ZEND_METHOD` implementation + registration in `src/gtk4.cpp` MINIT,
2. **tests** in `tests/` — the generated smoke test (`tests/Generated/<Class>SmokeTest.php`) always;
   a hand-written `tests/<Class>Test.php` (extend `GtkTestCase`) for every behaviour beyond
   round-tripping — every hand-written method exercised at least once, including its error path,
3. its declaration in **`src/gtk4.stub.php`** (typed signature, docblock, `@property` tags) +
   regenerated `src/gtk4_arginfo.h` and `stubs/gtk4.php` (`./ci.sh --only=stubs --fix`;
   `StubsTest`/CI enforce),
4. **`examples/<Class>.php`** — one source file per registered class, named after it, ending in
   `return Demo::page('<Class>', '<summary>', function (GtkWindow $win): GtkWidget {...})` (the
   builder may take a second `GtkApplication $app` parameter; `page()` also accepts width, height
   and an `$alone` callback for running stand-alone — see `examples/bootstrap.php`)
   and requiring `examples/bootstrap.php` (with `require_once`). The file *describes* a demo and runs
   nothing — `examples/demo.php` requires them all, so anything that ran itself would fire on import.
   That one application mounts every page (a `GtkHeaderBar` titlebar, a `GtkPaned` over a sidebar
   whose sections are a `GtkDropDown` and whose classes scroll, and the page in a `GtkFrame`);
   `demo.php <Class>` shows one on its own. Also add the class to `Demo::SECTIONS` in `examples/bootstrap.php`
   or it is unreachable in the sidebar. Make it *visual*: open a window that shows what the class does
   (a markup `GtkLabel` or a cairo `GtkDrawingArea`, often inside a `GtkButton` so clicking advances
   the demo) rather than printing about it, and use `Demo::status()` rather than `set_title()` for
   incidental state. **The `$win` a page is handed is the application's own window** when `demo.php`
   mounts it: read it, but never veto its `close-request`, make it modal, or destroy it — a page that
   did made the application unclosable. A page that needs a window to play with creates one
   (`GtkWindow.php`, `GParamSpec.php`). `ExampleTest` enforces all of this; `examples/README.md`
   indexes them.

   `examples/notes/` is the second application, **Notes** (`bin/php-gtk4 examples/notes/notes.php`):
   not a page but a real program that uses the widgets the way an application does - a
   `GtkApplicationWindow` subclass, header bar and primary menu, list models over a `GListStore` of
   PHP objects, autosave to JSON. `ExampleTest` ignores the subdirectory; `NotesAppTest` drives the
   window through its actions and entries. When a new class changes how an application would be
   written (a widget Notes fakes with something simpler), use it there too.

### The declared type is a promise the engine does not keep

PHP verifies neither the return type nor the property type of an *internal* class: a wrong
declaration is invisible at runtime, and PHPStan believes it anyway. So the type in the stub is
only as true as the code behind it, and every new element is checked against these:

- **Return an instance of what you declared.** A declared interface (`?GtkEditable`) means every
  concrete implementation that can come back is bound — `wrap()` answers with the nearest
  registered *class*, so an unbound one silently yields a value that does not implement it. Fix
  that by adding the class to `gen/allowlist.txt`, never by weakening the signature.
  `TypeDeclarationTest` calls every arg-less getter and compares.
- **Convert at the boundary, do not coerce.** Anything crossing PHP → C goes through the shared
  checks in `src/php_gtk4.h` — `check_utf8` (no NUL, valid UTF-8; not for `GBytes`, which is
  binary, nor for a `filename`, which is bytes), `check_range<T>` (a `zend_long` is 64-bit and
  signed, the C type usually is not), `check_flags` (a flags int must fit the type's mask) — and
  a property write converts exactly like the equivalent setter's parameter (`core/marshal`,
  `caller_is_strict()`). The same header holds the narrower checks: `check_domain` /
  `check_domain_double` (a bound GTK only enforces with `g_return_if_fail`, listed per parameter
  in `PARAM_DOMAINS`, `gen/gir/config.php`; a third element `open` makes the lower bound
  exclusive, `check_domain_above`), `check_enum_member` (a GIR enum that crosses as an
  int) and `absolute_filename` (a path resolved against PHP's own cwd, which under ZTS is not the
  process cwd). The generator emits all of this; hand-written methods must not forget it
  (`GdkRGBA::parse()` did).
- **A value PHP can build must not end the process.** Unbounded recursion, a state precondition
  GLib enforces with an abort — guard it (`gen/overrides`) or refuse the member
  (`gen/skip.txt`), with the reason. `RobustnessTest` sweeps every method with hostile values of
  the right type and `ArgumentGuardTest` pins the individual cases.

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

- `tests/Generated/<Class>SmokeTest.php` are **generated** with the class (`gen/gir.php --install`):
  construction, setter/getter pairs and writable properties round-trip a sample value;
  `gen/smoke-skip.txt` lists what GTK does not honour. Never edit them; behaviour beyond that is
  tested by hand in `tests/<Class>Test.php` like everything else.
- One test class per `src/core` module (`WrapTest`, `MarshalTest`, `SignalTest`, `ErrorTest`,
  `PropertyAccessTest`, `RethrowModeTest`, `BoxedTest`, `ParamSpecTest`, `EnumTest`, `FoundationTest`,
  `SubclassTest` + `VfuncTest` for `subtype`, `MainLoopTest`, `ShutdownTest`), one per hand-tested
  class (`WidgetTest`, `LabelTest`, `ButtonTest`, `BoxTest`, `ActionTest`, `TextureTest`,
  `ListStoreTest`, `FilterSortTest`, `DrawingAreaTest`, `ApplicationTest`, `RenderNodeTest`,
  `GskPathTest`, `GskTransformTest`, `GskRoundedRectTest`, `PrintTest`, `NotesAppTest`, …), and the
  meta tests (`ExtensionTest`, `StubsTest`, `ExampleTest`, `EveryClassTest`, `RobustnessTest`,
  `DeprecationTest` (no deprecated GIR member is bound), `DocsTest`,
  `HeaderNamesTest`, `WorkflowsTest`, `CommitLintTest`, `ReleaseNotesTest`,
  `GeneratorIdempotenceTest`, `VscodeConfigTest`, `PiePackageTest`). Every test that touches GTK
  extends `GtkTestCase`; the meta
  tests that never load a widget extend PHPUnit's `TestCase` directly. Fixtures that subclass GTK
  classes live in `tests/Subclass/` (namespace `PhpGtk4\Tests\Subclass`), script-only ones next to
  their script in `tests/scripts/`. `GtkTestCase`
  — `$this->window()` gives a `GtkWindow` destroyed in
  `tearDown()`, `captureHandlerException()` installs a temporary `Gtk::set_exception_handler`,
  `latch()`/`latched()` for flags set from GTK callbacks, `opaque()` to pass deliberately wrong
  arguments past static analysis.
- **A test of an optional feature skips itself where the build lacks it.** `tests/Features.php`
  reads `Gtk4\FEATURES` and knows which class prefixes belong to which feature (`WebKit*` and
  `JSC*` → `webkit`); a hand-written test calls `Features::requires('webkit')` in `setUp()`, the
  generated smoke tests of a conditional namespace do the same, and the meta tests that compare
  the stub or the example pages with the registered classes filter through
  `Features::available()`. A local build takes WebKit whenever the headers are there, so the
  suite covers those classes by default; CI is the other way round - only the `webkit` job has
  them, and every other job proves the build without them still works. Run both when touching
  them (`GTK4_CONFIGURE_ARGS= ./ci.sh --only=build,test` is the one without).
- **`tests/GtkInstances.php` is the shared sweep target factory**: one named branch per class
  that `new` cannot build (an abstract base, a handle only GTK hands out, a constructor argument
  the generic path cannot invent), used by both generic sweeps — `RobustnessTest` and
  `TypeDeclarationTest` — so a class is either live in both or skipped by both. Adding a branch
  puts a whole method surface under both; `GtkInstances::UNREACHABLE` names what nothing can
  build and why (real input events, a drag, the widget-interface `*Object` fallbacks `wrap()`
  never reaches), and those reasons are what the remaining skips print. `release()` (called from
  both tearDowns) drops the owners a handle needs alive.
- The two `Gtk::testing_*` hooks — `testing_iterate_nested()` (a GTK-internal nested main loop)
  and `testing_run_dispose()` — are compiled into **every** build. They exist for tests that need
  C-driven behaviour PHP cannot produce; their docblocks say they are not part of the supported
  surface. There is no build flag: what the suite runs is what ships, and nothing skips itself for
  want of a configure switch.
- Tests are the *only* thing that exercises the C++ — a segfault shows up as PHPUnit dying
  mid-run; isolate with `--filter 'Class::method$'` per test to find it.
- `EveryClassTest` constructs every instantiable class and calls every arg-less `get_*/is_*/has_*`
  — generic on purpose, never edit it for a new class.
- `ExampleTest` checks that every registered class has its own `examples/<Class>.php`, that the file
  mentions the class outside its imports, requires the shared harness, returns a page and never runs
  itself, that the page set and the registered-class set are identical, that `Demo::SECTIONS` lists
  every class exactly once, and lints every file in `examples/`.
- `MarshalTest` uses real `GtkWindow` properties per fundamental type (`title` string,
  `default-width` int, `resizable` bool, `opacity` double, `halign` enum, `display` object,
  `css-classes` = unsupported GStrv). Add a row when the marshaller learns a type.
- `tests/scripts/stress.php` (not PHPUnit) churns handles/signals/exceptions/lifetimes N rounds and
  exits normally; used by the `asan` (with LSan), `coverage` and `valgrind` stages. Extend it when
  adding runtime paths. `tests/lsan.supp` and `tests/valgrind.supp` may only contain third-party
  symbols.
- `tests/run.sh` **forces `GDK_BACKEND=x11`** and unsets `WAYLAND_DISPLAY` (forced, not defaulted;
  `GSK_RENDERER=cairo` and `GDK_DEBUG=gl-disable` stay overridable — a follow-up wants a
  `GSK_RENDERER=gl` run). A desktop session exports `GDK_BACKEND=wayland`, GDK then prefers the real
  compositor over the display Xvfb provides, and GTK 4.14's Wayland backend corrupts the heap
  partway through the suite (`gtk_widget_queue_draw: assertion 'GTK_IS_WIDGET (widget)' failed`,
  `malloc(): unaligned fastbin chunk`, a write into a freed block inside libgtk with zero frames of
  ours). CI never saw it because runners have no compositor. Xvfb/X11 is the test target; Wayland
  is exercised manually.
- `tests/run.sh` forces `XDEBUG_MODE=off`: xdebug's observer segfaults at request
  shutdown after `ReflectionMethod::invoke()` on internal methods (`debug` mode too, not only
  `develop` — verified 2026-08-29 with Xdebug 3.5: the suite passes, then the process dies after
  the summary). Not our bug; don't debug it. Debugging one *filtered* test is fine, and so is
  debugging `examples/` — `.vscode/launch.json` and `bin/php-gtk4-debug` do exactly that
  (docs/CONTRIBUTING.md "Debugging"); breakpoints inside signal handlers and GLib callbacks work,
  the stack shows the closure above `GtkApplication::run()`.
- Arginfo comes from the stub, argument parsing from `ZEND_PARSE_PARAMETERS_*` — arity/type
  violations are `ArgumentCountError`/`TypeError` (PHP 8 semantics).
- **A GLib `CRITICAL` fails the test that caused it.** It is how GTK says "PHP handed me something
  I refuse", so on an ordinary test it means a missing guard at the boundary — the binding should
  have raised a PHP error before GTK saw the value. GLib's messages are PHP errors at runtime
  (`src/core/diagnostics.cpp`, `gtk4.diagnostics`), so `GtkTestCase` needs no C++ hook: it
  installs a `set_error_handler` in `setUp()` and fails in `tearDown()` on anything collected.
  Nothing is configured for the suite: `gtk4.diagnostics` defaults to `warning`, so the gate runs
  on exactly what ships. GTK's *advice* (`G_LOG_LEVEL_MESSAGE` → `E_NOTICE`) is collected too but
  never fails a test — `takeGtkNotices()` is how one asserts it. Fix the boundary; only a suite
  whose job is to hand GTK bad values on purpose (`RobustnessTest`, `ArgumentGuardTest`,
  `DiagnosticsTest`) or one that exercises the uncaught-handler report itself (`ErrorTest`)
  overrides `toleratesGtkCriticals()`, and a single deliberate case declares the message with
  **`expectsGtkCritical($substring)`** — asserted both ways, so a *new* complaint in that test
  fails it and so does a declaration nothing matched. `RobustnessTest` gates itself against
  **`tests/robustness-criticals.txt`** instead, which pins the `<class>::<method>#<sweep>` keys
  GTK is known to complain about (`#arguments` / `#values` for the two method sweeps,
  `<class>::$<property>#properties`, `<class>::<signal>#emit` and `<class>::vfunc_<slot>#return`
  for the property-write, signal-emission and vfunc-return sweeps) and fails on an
  unlisted complaint or a line that has gone quiet — a review surface, not a suppression file
  (`tests/README-robustness-pin.md`). The `fatal` and `stderr` modes and the mode switching are
  asserted in **`tests/phpt/`** (`diagnostics-fatal.phpt`, `diagnostics-modes.phpt`,
  `diagnostics-stderr.phpt`), where run-tests.php can compare a whole process's output — stderr
  included — and an `E_ERROR` may end it. `tests/run.sh` sets
  `GSK_RENDERER=cairo` so GTK does not try EGL under Xvfb.
- `tests/phpt/` (`make test` / `./ci.sh --only=phpt`, see `tests/phpt/README.md`): one process per
  test, expected output covers stdout **and** stderr. Put a test here only for what PHPUnit cannot
  reach — a `fatal`-mode GLib CRITICAL (an `E_ERROR` ends the process), uncaught fatals and exit
  codes, RSHUTDOWN teardown
  output, `--INI--`/`--ENV--` dependent startup, and crash isolation (run-tests names the crashing
  test and continues, PHPUnit just dies). Everything else belongs in `tests/*.php`.
  `make test` strips every `extension=` line from the scanned ini into `tmp-php.ini` and re-adds
  only `modules/gtk4.so`, so gtk3 is filtered out here without `bin/php-gtk4`
  (`extension-isolation.phpt` guards that). Failures leave `.out`/`.diff` next to the `.phpt`
  (gitignored). Display-dependent tests guard with `--SKIPIF--` on `tests/phpt/skipif-display.inc`.

## Local gates

`git config core.hooksPath .githooks` once per clone: `commit-msg` runs `bin/commit-lint` on the message
(Conventional Commits are **mandatory** — they are the release notes, see below); `pre-commit` runs the
fast checks (version, stubs,
PHP style without phpstan, clang-format, markdownlint) in one `ci.sh` call and stamps the tree hash in
`.git/gtk4-precommit-tree`; `pre-push` runs `./ci.sh` and, when the stamp matches `HEAD^{tree}` and the
tree is clean, skips exactly those steps (`--skip=version,stubs,md-lint,format,style`) — a `--no-verify`
commit gets the full run. `--no-verify` skips once; `GTK4_PREPUSH=fast git push` keeps the checks but
drops `build,load,test,phpt` (CI still runs them on the PR). The build and the C++ lint are both
incremental, so the full gate on a push that changed no C++ is seconds, not minutes — measure before
weakening it.
ZTS builds are supported: all per-request state is in the module globals (`src/core/globals.h`,
`GTK4_G(x)`), never in a plain static — GType/class registries filled once in MINIT are the only
process-wide statics allowed. GTK itself stays single-threaded (`assert_gui_thread()`); CI builds
and tests NTS and ZTS on both platforms. Coverage has a floor
(`COVERAGE_MIN_LINES`, default 80). `RobustnessTest` calls every method with garbage arguments;
`TypeDeclarationTest` calls every arg-less getter and asserts the value matches the declared
type (PHP never verifies an internal function's return type, so a wrong declaration is
invisible: `get_delegate(): ?GtkEditable` once answered with a bare `GtkWidget` because
the concrete class was unbound — bind the class, never weaken the signature);
`DocsTest` guards CLAUDE.md sections and doc-mentioned paths; `CommitLintTest` and
`ReleaseNotesTest` pin every accepted/rejected commit shape and the grouping of the release body.
`CHANGELOG.md` has the release
checklist; Dependabot watches composer, actions and npm. **`./update-deps.sh`** is the local
equivalent of those PRs plus what Dependabot cannot see: it updates `composer.lock` (`--major` also
raises a constraint), the `package.json` pins and every `uses: owner/repo@v<N>` in the workflows, and
*reports* the three pins that are bumped by hand — `GVSBUILD_VERSION`/`GVSBUILD_SHA256`
(`--gvsbuild` writes them), the vendored `gen/gen_stub.php` against the installed php-src copy, and
the runner images / clang pins. `--check` writes nothing and exits non-zero when anything is behind.

## CI

Five workflows plus one reusable recipe: `.github/workflows/cpp-lint.yml`,
`php-qa.yml`, `tests.yml`, `windows.yml`, `release.yml`, and `windows-build.yml` (`workflow_call`,
the single Windows build recipe both `windows.yml` and `release.yml` use).
**They run on pull requests only** — `release.yml`'s own `verify` re-runs `./ci.sh` on `main`, so a
`push: [main]` trigger elsewhere validated the same commit a second and third time (docs/RELEASING.md
"Gating"). `cpp-lint.yml` is the exception: it keeps `push: [main]` because a run reads its own branch's
caches and the default branch's, so linting main is what keeps every PR's clang-tidy cache warm. A
required status check must come from a workflow that always starts, so `cpp-lint.yml` carries no `paths:`
filter (`windows.yml`, which is not required, does) — `WorkflowsTest` pins both. (1) static analysis —
setup-php 8.4, GTK4/WebKitGTK headers, clang 20 from apt.llvm.org, `phpize && ./configure` (for
`config.h`), then `./ci.sh --only=gen,stubs` (the only PR-time run of the generator gate) and
`./ci.sh --only=cpp-lint` (same clang-tidy/clang-format stage as locally, any finding fails);
(2) `./ci.sh --only=php-qa` + `--only=md-lint`, plus a `commits` job that runs `bin/commit-lint`
over the PR title (a squash merge makes it the commit) and `./ci.sh --only=commits` over the
branch's commits (a rebase merge keeps them); (3) build the extension and run the
PHPUnit suite *and* `./ci.sh --only=phpt` for PHP 8.4 and 8.5 — NTS always, **ZTS when something under
`src/core/` changed** (a `changes` job decides; ZTS is there to catch per-request state in a plain static,
and a release runs it regardless), on Ubuntu 24.04 (`fail-fast: false`;
failing `.out`/`.diff` files upload as the `phpt-failures-php*-<ts>` artifact), plus `sanitizers`
(`ci.sh --only=valgrind` + `--only=asan`) and a
`webkit` job on 8.4 (`--enable-gtk4-webkit` build, load and the whole PHPUnit suite — the only run
in which the `WebKit*`/`JSC*` classes and their tests exist). The apt package lists must
mirror `config.m4`'s pkg-config modules (plus `gir1.2-gtk-4.0` where the `gen` stage runs).
(4) `windows.yml`: the 8.4/8.5 × NTS/ZTS matrix calling `windows-build.yml` — `windows-2022`,
setup-php + the matching devel pack from windows.php.net + php-sdk-binary-tools + the cached
`GTK4_Gvsbuild_<ver>_x64.zip` release asset (`GVSBUILD_VERSION`, pinned by hand in
`windows-build.yml` only — `WorkflowsTest` enforces that, nothing bumps it; docs/BUILD.md "The
pinned GTK version"), `phpize && configure --with-gtk4 && nmake` with a warning gate over `src\`
(`/W3`), load check, PHPUnit via `tests/run.cmd` on the runner's desktop (no phpt) — on the pull requests
that touch what it depends on (`paths:`) and once a week, because Windows minutes bill at nearly twice the
Linux rate. (5) `release.yml` on
every push to `main`: reads `VERSION` and either publishes an
immutable `vX.Y.Z-dev.<run>` pre-release (suffix `-dev`; the newest 5 are kept, older ones deleted with their
tags) or the real `vX.Y.Z` release (no suffix, once). **The gate (`verify`, and `coverage` behind it) does
not depend on whether there is anything to publish** — it used to, so a `main` parked on an already-released
`VERSION`, which is where every real release leaves it until the follow-up bump, ran no tests, no coverage
and no badge under a green tick (`WorkflowsTest` pins it); only `build`, `build-windows`, `release-gate` and
`publish` ask for `publish == 'true'`,
after running `./ci.sh --skip=cpp-lint` itself — it does not key off `tests.yml`. It also runs `coverage`
on every merge, and a **real** release additionally gets the ZTS verify cells, a `release-gate` job
(`--only=valgrind` + `--only=asan`) and Windows binaries that ran the suite; `publish` waits for all of it,
so what docs/RELEASING.md "Cutting a release" asks the maintainer to run locally is enforced here
too. A real release also runs
`publish-stubs`, which pushes `stubs/` to the stubs package repository (`STUBS_REPO`, secret
`STUBS_DEPLOY_KEY`; skipped with a warning when the secret is absent). The body is the
`CHANGELOG.md` section for the version (required, verbatim) followed by `bin/release-notes`,
which groups the commits since the previous release by Conventional Commit type. Assets are one `.so` per
supported PHP named with its whole ABI identity, one
`php_gtk4-<ver>-<X.Y>-<nts|ts>-vs17-x86_64.zip` per supported PHP and thread model (the
`build-windows` job calling `windows-build.yml`, then zipped in `publish`), plus the source tarball
and `SHA256SUMS`, with build provenance attestation instead of a signed tag.
**Two packages ship from this repository**: the extension itself as a PIE package
(`pie install php-gtk4/php-gtk4` — the root `composer.json` is `"type": "php-ext"` with a
`php-ext` block, and doubles as the dev manifest) and `stubs/` as the Composer package
`php-gtk4/stubs`. PIE builds from source on Linux and downloads the Windows zip, whose name it
derives itself from `php-ext.extension-name` and the package version — `PiePackageTest` pins the
manifest, `config.m4`'s configure switches and the workflow's asset name to each other, because
nothing else fails when they drift (docs/RELEASING.md "Shipping"). **`VERSION` is the only release trigger; never
create a tag or a release by hand.** docs/RELEASING.md is the full description.
**README badges may only come from a workflow that runs on `main`.** A `badge.svg?branch=main`
shows that workflow's newest run *on main*, so a pull-request-only workflow freezes its badge at
whatever it last said there - `php-qa.yml` and `tests.yml` kept claiming "passing" from 2026-08-27
after they lost their push trigger, while the Release run on main was failing.
`WorkflowsTest::testEveryMainBranchBadgeComesFromAWorkflowThatRunsOnMain()` enforces it, so the
README carries `release.yml` (the whole gate on main) and `cpp-lint.yml`, and nothing else with a
`?branch=main`. The **coverage badge** is a shields.io endpoint reading `coverage.json` from the
orphan `badges` branch, which the `coverage` job rewrites on every push to main - including when
it fails the floor, because a badge that only updates on success is the same lie.

`.github/copilot-instructions.md` is a one-liner pointing at this file — keep project-wide
conventions here only. `.github/ISSUE_TEMPLATE/` holds two issue forms (bug, feature) with the
context fields marked required and blank issues disabled; `IssueTemplateTest` keeps them that way.

## Architecture

- `src/core/` — the runtime. `object` (`struct Object { GObject *obj; bool held; zend_object std; }`,
  the object handlers: `free_obj`, no clone, `read/write/has_property` mapping `$obj->prop`
  (underscores → dashes) to GObject properties unless the PHP class declares a property of that
  name - a declared property is the author's and wins (a `GtkWindow` subclass may have its own
  `$title`; GTK's stays reachable through `get_title()`), `get_debug_info` for `var_dump`; a **toggle ref** +
  qdata identity: while GTK holds other refs the GObject holds the `zend_object` (`held`), so a
  PHP subclass' state survives the script dropping its reference, released in the toggle notify
  and in RSHUTDOWN (`object_release_holds()`, or Zend reports the handle as a leak);
  `object_hold_owner()` for the getters whose result keeps a bare pointer into its owner
  (`gtk_stack_get_pages()`, a composite widget's `get_first_child()` — `RETURNS_HOLD_SELF` in
  `gen/gir/config.php`, the object counterpart of `BOXED_OWNERS`): the reference is held between
  the *handles*, not the GObjects, because a parent already owns its child and a GObject-level
  back-reference would be an uncollectable cycle — `get_gc` shows it to the collector, and the
  owner's `get_gc` reports the held child handles it is the parent of, the edge that runs
  through C (`OwnerCycleTest`); a boxed value holds its owner's *handle* for the same reason; the
  GType-name → `zend_class_entry` registry; `wrap()`/`unwrap()`/
  `PHPGTK_SELF`; when no class up the parent chain is registered but a registered *interface* is,
  `wrap()` uses that interface's generated `Gtk4\<Interface>Object` fallback class, most derived
  interface first), `marshal` (the single `GValue` ↔ `zval` bridge; a property write or signal argument converts
  like a typed parameter - `caller_is_strict()` honours the assigning file's `strict_types`,
  weak coercion otherwise - and then `check_range`/`check_flags`/`check_utf8` from
  `php_gtk4.h`), `gsignal` (`connect()` via a
  `GClosure` with a GValue-array marshaller, callable resolved with `zend_fcall_info_init` and
  invoked with `zend_call_function`), `error` (the exception boundary:
  `report_pending_exception()` takes `EG(exception)`, hands the real `Throwable` to
  `Gtk::set_exception_handler`, else a `diagnostic()`), `diagnostics` (GLib's own
  `g_critical`/`g_warning` as PHP errors: a `g_log_set_writer_func` *records* — it runs inside GTK
  frames, where `zend_error()` could run a throwing error handler or `longjmp` across them — and
  sets `EG(vm_interrupt)`; the chained `zend_interrupt_function` then reports at the VM's next safe
  point, which the engine documents as after an internal call, i.e. the PHP line that made it.
  Each entry carries the file/line it was recorded at, so `zend_error_at()` keeps the attribution.
  **`gtk4.diagnostics`** (`PHP_INI_ALL`) chooses: `warning` (default — both `E_WARNING`),
  `fatal` (`CRITICAL` → `E_ERROR`), `stderr` (GLib's own writer), `off`.
  php-gtk4's own reports go through `diagnostic()` and are *always* `E_WARNING` —
  `ExceptionMode::Log` promises GTK keeps running, so its report must not be what stops it),
  `boxed` (value-type handles: owned `g_boxed_copy`, clone/compare by value, fields as properties
  via per-class reader/writer; `GdkRGBA`, `GdkRectangle`; `GStrv` ↔ `list<string>` is a value
  mapping), `variant` (`GVariant` ↔
  PHP values, type-directed or inferred), `paramspec` (`GParamSpec` handle), `gerror`
  (`throw_gerror()` for `GError **` APIs,
  `GError` values → `Gtk4\GError` exceptions), `cairo` (`CairoContext` registration),
  `phpvalue` (GType `PhpValue`: a GObject subclass carrying a zval so PHP data can sit in
  `GListStore`; instances drained in RSHUTDOWN), `collections` (`GList`/`GSList`/`GPtrArray`/`char**`
  → PHP lists with GIR transfer semantics),
  `subtype` (PHP subclasses as real GTypes, registered at first `new`, and GTK interfaces
  implemented from PHP — `implements GListModel` adds the interface to the GType with thunks into
  the PHP methods, and the interface's properties (`GAction`'s `state`) are overridden and routed
  to the PHP accessors of the same name, plain PHP properties on the PHP side; constructors go through
  `subtype_new()` with the arguments as construct properties (`gen/ctor-props.txt` for renames),
  `vfunc_<name>()` methods override class-struct slots through generated thunks, the generated
  native `vfunc_<name>()` on the owning class is what `parent::` chains to; abstract GTK classes
  have a *public* constructor that refuses the native class and works on a subclass —
  docs/DESIGN.md), `fundamental` (registry-driven handles for refcounted non-GObject types: `GParamSpec`,
  `CairoContext` (cairo_t via cairo-gobject; marshal's boxed arm falls back to this registry),
  `GtkCssSection`, `GdkEvent` + subclasses, `GdkEventSequence` as a ref-less identity; `new X()` on
  the hand-written ones throws; every GIR class marked `glib:fundamental` - `GskRenderNode` and its
  subclasses - is **generated** onto the same registry (`emitFundamental()` in
  `gen/gir/emit-class.php`, `fundamental_adopt()` for a generated `new`, `fundamental_self()` for
  `$this`); a record GIR gives no GType (`GskRoundedRect`) is hand-written on `core/boxed` with a
  synthetic boxed GType, mapped through `SYNTHETIC_GTYPES`/`TYPE_MACROS` in `gen/gir/config.php`;
  a realized `GskRenderer` is unrealized in `detach()` when the handle holds the last reference,
  because `gsk_renderer_dispose()` aborts on one; the same instance wraps to the same handle while
  PHP holds it —
  `GTK4_G(fundamental_handles)`), `enums` (GEnum ↔ int-backed PHP
  enum via a GType
  registry; cases declared literally in the stub
  and verified against the `GEnumClass` at RINIT — a mismatch is fatal; GFlags stay ints with
  constant classes (values literal in the stub, verified against the `GFlagsClass` at RINIT);
  unregistered enum types stay ints), `callback` (non-signal
  callables; `callback_free()` only parks the callable, `callback_drain()` releases it at a safe
  point because destroy notifies run inside GTK frames), `teardown` (RSHUTDOWN disconnects every
  tracked closure/source and clears every notified-scope callable so nothing finalizes
  after Zend is gone — `tests/scripts/shutdown.php` guards it), `mainloop` (running-loop registry
  for `ExceptionMode::Rethrow`).
- `src/GLib/`, `src/GObject/`, `src/Gio/`, `src/Gdk/`, `src/Gtk/`, `src/Cairo/` (and
  `src/WebKit/`, `src/JavaScriptCore/`, compiled only with `--enable-gtk4-webkit`) — `ZEND_METHOD`
  implementations, one directory per GIR namespace, one file per class. Most of them are
  **generated** by `gen/gir.php --install` from GIR (header `GENERATED by gen/gir.php`, never
  edited; `./ci.sh --only=gen` fails on a diff) with the per-namespace stub `src/<Ns>/<Ns>.stub.php`
  and its `<Ns>_arginfo.h`; hand-written ones (`GObject`, `GParamSpec`, `GMainLoop`, `GError`,
  `PhpValue`, `GdkRGBA`, `GdkRectangle`, `CairoContext`, `GtkCssSection`, `GtkStyleProviderPriority`, `Gtk`, `GLib`)
  stay in `src/gtk4.stub.php`.
  Hand code for a generated class goes to `gen/overrides/<Ns>.<Type>.<method>.cpp` (a docblock with
  the stub declaration + the `ZEND_METHOD`) or the class prelude `gen/overrides/<Ns>.<Type>.cpp`
  (shared trampolines/statics); members deliberately not exposed go to `gen/skip.txt` (a method
  by `<Ns>.<Type>.<method>`, a GObject property's `@property` tag by
  `<Ns>.<Type>.property:<name>`, an interface by `<Ns>.<Type>.implements:<Ns>.<Iface>`); every
  skip is listed in `gen/report.md`. `src/core/` holds no `ZEND_METHOD`s. Registration lives in
  `src/gtk4.cpp` MINIT (hand-written classes) and the generated `src/gen_minit.inc` — a
  conditional namespace registers from its own `register_<feature>_classes()` in
  `src/gen_minit_defs.inc` instead, taking the class entries it inherits from the always-built
  block as parameters, so MINIT stays one readable function — in four
  shapes:
  `register_class("GTypeName", register_class_Gtk4_X(parent_ce), G_TYPE_X)` for GObject handles,
  parents first — `register_class()` installs `create_object` (inherited by subclasses registered
  afterwards) and records the GType → class mapping `wrap()` uses — pass the `*_TYPE_*` macro, never
  look types up by name at MINIT (GTK registers GTypes lazily); `register_enum/flags(GTK_TYPE_X,
  register_class_Gtk4_X())` for enums; `register_interface("GTypeName", ce, G_TYPE_X)` for
  interfaces (registry entries, no `create_object`); and `register_X(register_class_Gtk4_X())` for
  everything else (boxed, fundamental, own object layout), declared in `src/classes.h` and defined
  next to the class — **that last shape is the fourth place for hand code** besides `gen/overrides/`,
  `gen/skip.txt` and `handwritten.txt`: a non-GObject type is registered by hand in `src/classes.h`
  and `src/gtk4.cpp`, the generator only emits `gen_minit.inc` for GObject classes, interfaces and
  enums. Interfaces (`GAction`, `GActionMap`, `GActionGroup`, `GListModel`) come from
  `implements` in the stub (gen_stub emits `zend_class_implements`); when an interface's methods
  have one C implementation, write it **once** as `ZEND_METHOD(Gtk4_<Interface>, m)`
  (`src/Gio/GListModel.cpp`, prototypes in `src/gen_prototypes.h`) and tag every implementing class's method
  in the stub with `/** @implementation-alias Gtk4\<Interface>::m */` — gen_stub emits a
  `ZEND_MALIAS`, no per-class C++. `G_TYPE_POINTER` is unsupported on purpose.
- `gen/` — `gir.php` (the GIR generator: per-namespace stubs, `src/<Ns>/<Class>.cpp`, `gen_minit.inc`,
  `gen_minit_defs.inc`, `gen_prototypes.h`, vfunc thunks, smoke tests, example skeletons,
  `report.md`, the map's status column via `map-status.php`) with its inputs `allowlist.txt`,
  `handwritten.txt`, `skip.txt`, `ctor-props.txt`, `smoke-skip.txt` and `overrides/`;
  `gen_stub.php` (vendored from php-src), `ide-stub.php`, `method-comments.php`.
  `gen/README.md` has the flow diagram.
- **Everything PHP-visible is in the `Gtk4\` namespace**; PHP class name = `Gtk4\<GTypeName>`, and
  `wrap()` walks the GType parent chain to the nearest registered class. Constants:
  `Gtk4\VERSION`, `BUILD_INFO`, `FEATURES`. One `Object` struct serves every class (all per-class
  state lives in the GObject).
- Exception rule (from php-gtk3, keep it): a PHP throwable must never unwind through GLib frames,
  and Zend refuses to run PHP while one is pending. Every trampoline ends with
  `phpgtk::report_pending_exception(origin)`, which implements `Gtk4\ExceptionMode`: `Log`
  (handler, else an `E_WARNING` through `core/diagnostics`; GTK continues) or `Rethrow` (handler,
  then the Throwable stays pending, `quit_running_loops()` stops
  `GMainLoop::run`/`GtkApplication::run`, and it propagates to PHP).
  A vfunc thunk *parks* a Throwable that is already pending (`zend_exception_save()` around the
  call, restored after): Zend refuses to run PHP while one is in flight, and answering GTK with
  the slot's default instead is a lie about the object — a `GListModel` that says "0 items"
  because PHP happens to be unwinding leaves `GtkListView`'s item manager holding rows GTK
  believes gone, which a GTK built with assertions aborts on.
  Non-signal callbacks go through `src/core/callback.*` with the installing method as origin;
  typed C callbacks (`GtkDrawingAreaDrawFunc`, `GtkCustomFilterFunc`, `GCompareDataFunc`) follow
  the trampoline rule in gen/README.md ("Typed C callbacks") — `gen/overrides/Gtk.DrawingArea.cpp`,
  `Gtk.CustomFilter.cpp`, `Gtk.CustomSorter.cpp` are the templates.
- Main loop: `GtkApplication::run()` (preferred) or `GMainLoop` + `GLib::idle_add/timeout_add`;
  `GLib::main_context_iteration()` pumps one iteration without handing over control;
  `GLib::io_add_watch($stream, GIOCondition::IN, fn)` puts a socket on the loop (a `GIOChannel`
  watch over PHP's own descriptor - PHP keeps and closes it). There is no
  `Gtk::main()`. Rethrow mode leaves the Throwable pending only when the next return lands in
  PHP; inside an *unregistered* nested loop (`g_main_depth()` deeper than the innermost
  registered `run()`) it is **parked** (`core/error.cpp`), handlers keep running, and the next
  `run()`/`main_context_iteration()` returning to PHP rethrows it — never drop it.
  `connect()` takes exactly `(string $signal, callable $handler)` — no user data, closures capture
  with `use`. `emit()` emits with converted arguments (use it in tests instead of `activate()`,
  whose `clicked` needs a realized widget).
- Actions: `GSimpleAction` + `GtkApplication::add_action()`; GVariant parameters/states are plain
  PHP values. `has_action/list_actions/activate_action` only work once the app is registered
  (from `startup` on); `add/remove/lookup_action` always. Errors raised *to* PHP from methods use the PHP 8
  vocabulary, by what went wrong. Two argument checks are shared and must not be skipped
  (`src/php_gtk4.h`, emitted by the generator, applied by `core/marshal` for property writes and
  signal arguments, and by `core/variant` for GVariant strings): `check_utf8()` — a GLib string
  ends at the first NUL and has to be valid UTF-8, a PHP string is neither, and both used to fail
  silently (`GBytes` parameters are binary and are *not* validated); `check_range<T>()` — a
  `zend_long` is 64-bit and signed, most C parameters are not, and a negative value used to reach
  GTK as a huge unsigned one. `core/variant` also caps nesting at 64 and refuses an array that
  contains itself: both used to be a SIGSEGV (`ArgumentGuardTest`). The vocabulary:
  bad *argument* → `zend_argument_value_error(pos, …)` /
  `zend_argument_type_error` / `zend_argument_count_error` (`ValueError`/`TypeError`/
  `ArgumentCountError`) — always the positional form when the value came in as a parameter, so
  the message names it (`connect(): Argument #1 ($signal) …`); a dead or *disposed* handle (GTK
  ran dispose under a live handle — `Object::disposed`, set by a weak notify) is an `Error` as
  `$this` and as an argument alike (`unwrap()`: only a wrong *class* is the `TypeError`); the wrapped object
  is in the wrong *state* for the call (running loop, stateless action, a native `vfunc_*()` outside
  `parent::` chaining) → `spl_ce_LogicException`; the *handle itself* cannot do it — dead `$this`,
  `new`/`clone` of a C-created handle, `unset()` of a GObject property, a GTK precondition failure
  (NULL result, no `GError`) → plain `\Error` via `zend_throw_error(nullptr, …)`, as the engine
  does for readonly/uncloneable. Shared
  helpers: `PHPGTK_RETURN_STRING_OR_NULL(expr)` for nullable C strings (`php_gtk4.h`),
  the generated `?GtkWidget` parameter handling for everything else.
- **Naming is snake_case, final** (decided 2026-08-25, docs/DESIGN.md): methods mirror the GTK C API
  with the type prefix stripped (`gtk_window_set_title` → `set_title`), properties keep GTK's names
  with underscores (`$win->default_width`). Never add camelCase aliases. Two deliberate exceptions:
  `GError::getDomain()` sits next to the inherited `getCode()`/`getMessage()`, and enum *cases* are
  CamelCase (`GtkAlign::Center`, PHP enum convention). phpcs' camelCaps rule is switched off for
  `tests/` and `examples/` because PHP subclasses override GTK slots as `vfunc_<name>()`; the stub
  itself is not linted by phpcs at all.
- **C++ file conventions**: `src/core/*.cpp` define inside `namespace phpgtk {}`; class files
  (`src/<Ns>/*.cpp`, generated or hand-written, and the `gen/overrides/` preludes) `using namespace
  phpgtk;`. File-local helpers (trampolines, thunks) go in an anonymous namespace everywhere;
  `static` only where a GLib macro declares it (`G_DEFINE_TYPE` in `core/phpvalue.cpp`). Includes:
  own header first; keep the existing grouping of a file (`SortIncludes` is off — clang-format keeps
  what you write). `NOLINTNEXTLINE(check) reason` — with the reason — is allowed for findings inside
  GLib/Zend macro expansions and for GLib API signatures we cannot change (`gconstpointer` items,
  `gint8` chars); multi-line macros use `NOLINTBEGIN`/`NOLINTEND`. The magic-number and enum-size
  checks are off in `.clang-tidy` (deny-list rationale there).
