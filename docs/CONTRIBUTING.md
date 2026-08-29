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

The hooks are the local gate: `commit-msg` checks the commit message (see below), `pre-commit` runs
version/stub checks, PHP style (without phpstan),
clang-format and markdownlint in about ten seconds and remembers which tree passed; `pre-push` runs
the rest of `ci.sh` (clang-tidy, phpstan, build, load, tests, phpt) and only repeats the pre-commit
steps if the tree being pushed was never checked (a `--no-verify` commit). Skip once with
`--no-verify` when you know why.

## Hard constraints

- **PHP 8.4+ only**, NTS or ZTS. No compatibility shims for older PHP; use 8.4 features freely.
- **C++20**, **GTK 4.14+** — guard anything newer with `GTK_CHECK_VERSION`. Never GTK 3 APIs.
- **Naming is snake_case, final.** Methods mirror the C API minus the type prefix
  (`gtk_window_set_title` → `set_title`); properties keep GTK's names with underscores. No
  camelCase aliases.
- **Everything PHP-visible lives in the `Gtk4\` namespace**; PHP class name = `Gtk4\<GTypeName>`.
- Zero clang-tidy findings, zero phpstan (level max) findings, no exceptions — there is no
  backlog and CI fails on the first one.

## Definition of done for a new class / method / constant

All four in the same change, or CI rejects it — the detail (what each of these means, the
generator's part in it) is in CLAUDE.md "Definition of done", which is the authority:

1. **Implementation** — generated from GIR for a GObject class (`gen/allowlist.txt`, overrides in
   `gen/overrides/`), hand-written `ZEND_METHOD`s + `src/gtk4.cpp` MINIT registration otherwise.
2. **Tests** — the generated smoke test, plus `tests/<Class>Test.php` for everything beyond it.
3. **Declaration** — the stub (hand-written `src/gtk4.stub.php` or the generated per-namespace one),
   then `./ci.sh --only=gen,stubs --fix`. Never edit generated files.
4. **Example** — `examples/<Class>.php` returning `Demo::page(...)`, listed in `Demo::SECTIONS`.

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

## Debugging

Xdebug works on the PHP side of an example, breakpoints inside signal handlers and GLib callbacks
included — the call stack then shows your closure above `GtkApplication::run()`. Install
`php8.4-xdebug` and the [PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug)
extension (`.vscode/extensions.json` recommends it); how `xdebug.mode` is set in `php.ini` does not
matter, the launch configuration decides.

`.vscode/launch.json` has five ready configurations:

| Configuration | Runs |
| ------------- | ---- |
| **Example: the open file's page** | `examples/demo.php <the open file's class>` — F5 in `examples/GtkCssProvider.php` debugs that page |
| **Example: pick a class** | the same, asking for the class name |
| **Example: the whole demo application** | `examples/demo.php` |
| **Script: the open PHP file** | `${file}` — a scratch script, `tests/scripts/stress.php` |
| **PHPUnit: one filtered test** | `vendor/bin/phpunit --filter <what you type>` |
| **Listen for Xdebug** | nothing; waits for a process you start yourself |

An example file *describes* a page and runs nothing, so debugging one means running `demo.php` with
its class name — which is what those configurations do for you.

From a terminal, with the "Listen for Xdebug" configuration started:

```sh
bin/php-gtk4-debug examples/demo.php GtkBox     # bin/php-gtk4 + Xdebug pointed at port 9003
XDEBUG_PORT=9004 bin/php-gtk4-debug script.php  # when something else owns 9003
```

Two things to know:

- Nothing rebuilds the extension for you — run the **php-gtk4: build extension** task
  (Ctrl+Shift+B) after touching `src/`, or you are stepping through PHP that calls a stale
  `./gtk4.so`.
- Debug a *filtered* test, never the whole suite: under Xdebug the process segfaults at request
  shutdown after `ReflectionMethod::invoke()` on internal methods (`RobustnessTest`,
  `EveryClassTest`), after the results are printed. That is why `tests/run.sh` sets
  `XDEBUG_MODE=off`; it is not our bug and not worth chasing.

## Commit messages

**[Conventional Commits](https://www.conventionalcommits.org) are mandatory**, because they *are*
the release notes: `bin/release-notes` groups them by type into the body of every release
(`docs/RELEASING.md`). A wrong type is a wrong section in the next release.

```text
<type>[(scope)][!]: <subject>

<body>
```

```sh
git commit -m 'feat(css): GtkCssProvider and the display it attaches to'
git commit -m 'fix: GdkTexture keeps a private constructor'
git commit -m 'refactor(core)!: wrap() returns the nearest registered class'   # ! = breaking
```

| Type | For | Release section |
| ---- | --- | --------------- |
| `feat` | a new class, method, constant — anything a user can call | Features |
| `fix` | wrong behaviour, a crash, a leak | Fixes |
| `perf` | same behaviour, less time or memory | Performance |
| `refactor` | internal shape only | Refactoring |
| `docs` | `*.md`, docblocks, `examples/` prose | Documentation |
| `test` | `tests/`, `tests/phpt/`, the stress script | Tests |
| `build` | `config.m4`, `config.w32`, `composer.json`, dependencies | Build & CI |
| `ci` | `ci.sh`, `.github/`, the hooks | Build & CI |
| `chore` | releases, housekeeping (`chore(release): 0.2.0`) | Chores |
| `style` | formatting only, no behaviour | Chores |
| `revert` | undoing a commit | Chores |

The scope is optional and lower-case — a class (`css`, `core`, `gen`) or a file. A breaking change
is `!` before the colon, or a `BREAKING CHANGE:` footer; either one lifts the commit into the
release's first section. The subject is not capitalisation-checked (it usually starts with an
identifier), must not end in a period, and the whole header stays under 100 characters.

Three places enforce it, all running the same `bin/commit-lint`:

```sh
git commit -m 'wip'                  # rejected by .githooks/commit-msg, before you leave the editor
./ci.sh --only=commits               # the commits this branch has not pushed yet
COMMIT_LINT_RANGE=main..HEAD ./ci.sh --only=commits    # any range
bin/release-notes                    # what the next release body will say
```

CI checks the **PR title** as well, because `main` takes squash merges and the title becomes the
commit message then. `git commit --fixup` is fine locally; autosquash it away (`git rebase -i
--autosquash`) before pushing, or CI rejects the branch.

## Pull requests

- Branch from `main`; one topic per PR. `pre-push` has already run `./ci.sh` when you push.
- The PR title is a Conventional Commit too — a squash merge makes it the commit on `main`.
- Add a line under `## [Unreleased]` in `CHANGELOG.md` for anything user-visible.
- Don't bump `VERSION` in a feature PR — that is the release trigger (`docs/RELEASING.md`).
- Dependabot handles composer and actions updates; don't bundle those.
- Windows: the build is `config.w32` (PHP SDK + gvsbuild GTK, see the Windows section of
  `docs/BUILD.md`). Nothing under `src/` may become platform-specific except `pin_gtk_library()`;
  anything that touches `config.m4` (sources, defines, features) needs the same change in `config.w32`.
  WebKit: milestone 6 in `docs/PLAN.md` first.
