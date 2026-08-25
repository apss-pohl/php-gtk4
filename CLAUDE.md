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
- **C++20** (`-std=c++20`, GCC 11+/Clang 14+). lint.sh and `.clang-format` use the same standard.
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
- `build-*.sh` are gitignored personal wrappers.

## Lint

```sh
./lint.sh              # clang-tidy + clang-format --dry-run over src/**, main.cpp, main.h, version.cpp
./lint.sh --fix        # apply fixes
./lint.sh --no-tidy    # format only (fast)
CLANG_TIDY=clang-tidy-17 CLANG_FORMAT=clang-format-17 ./lint.sh
```

`.clang-tidy` has a documented deny-list (GLib macros, PHP-CPP slicing); everything else is
enforced and **CI fails on any finding** — there is no backlog, keep it that way. `SortIncludes` is
off; don't reorder includes. Use `// NOLINT(check)` only for findings inside GLib macro expansions.

## Tests

`tests/run.sh` runs each `tests/*.php` with `xvfb-run -a php -n -dextension=./gtk4.so`; a test
exits non-zero on failure (plain `check()` asserts, no framework). `-n` is mandatory — without it an
installed gtk4 is loaded a second time and segfaults. Run a single test the same way:
`xvfb-run -a php8.4 -n -dextension=./gtk4.so tests/window.php`.

## CI

`.github/workflows/codechecks.yml`: (1) static analysis — setup-php 8.4, GTK4 headers, PHP-CPP
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
- PHP class names equal GType names (`GtkWindow`, `GdkTexture`); `wrap()` depends on it.
- Exception rule (from php-gtk3, keep it): a PHP throwable must never unwind through GLib frames.
  Trampolines catch `Php::Throwable` around their whole body, copy message/code, leave the catch
  scope, then call `phpgtk::report_callback_exception()`.
