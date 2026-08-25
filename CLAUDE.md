# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

php-gtk4 is a PHP extension written in C++20 against the **native Zend API** (no PHP-CPP — see
TODO.md §1 / PLAN.md for why it was dropped), built with the standard `phpize`/`config.m4` flow.
It is the successor to `/mnt/share/dev/code/php-gtk3`; `PLAN.md` records the design and what is
deliberately *not* carried over. Read `PLAN.md` and `TODO.md` before changing anything under
`src/core/`.

Module name is **`gtk4`** everywhere: Makefile `NAME`, `Php::Extension("gtk4")` in `main.cpp`,
`extension=gtk4` in `gtk4.ini`. Keep them in sync.

Hard constraints:

- **PHP 8.4+ only** — 8.4 and 8.5 in the CI matrix; **locally only 8.4 is built/tested** (`ci.sh`
  defaults, `buildall.sh` has 8.5 disabled — `ONLY=8.5 ./buildall.sh` to force). The Makefile (`php-config --vernum >= 80400`) and `main.cpp`
  (`#error` on `PHP_VERSION_ID < 80400`) both refuse older PHP. Don't add compatibility shims for
  older PHP; do use PHP 8.4 features in stubs/tests/examples (property hooks, `#[\Deprecated]`,
  `new X()->m()`, typed constants).
- **C++20** (`-std=c++20`, GCC 11+/Clang 14+). `ci.sh` (clang-tidy) and `.clang-format` use the same standard.
- **GTK 4.14+** (`pkg-config gtk4`); CI compiles and tests on Ubuntu 24.04 (GTK 4.14, the floor) and
  26.04 (GTK 4.22, current) — guard anything newer than 4.14 with `GTK_CHECK_VERSION`. Never use GTK 3 APIs (`gtk_main`, `GdkEvent` unions,
  `GtkContainer`, …).
- Linux is the primary target; Windows/WebKit follow later (see PLAN.md milestones).

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
  assumes `.phpt` tests there). Use `make clean`. Variants (sanitize/coverage) rebuild in place after a clean and copy
  `modules/gtk4.so` to `gtk4-asan.so` / `gtk4-cov.so`.
- **API declaration = `stubs/gtk4.stub.php`.** `gen/gen_stub.php` (vendored from php-src) generates
  `src/gtk4_arginfo.h` (class entries, method tables, typed arginfo); `gen/ide-stub.php` generates
  `stubs/gtk4.php` for IDEs. Both generated files are committed; `./ci.sh --only=stubs` (and CI)
  fail if they are stale. **Never edit the generated files.** Adding a method = declare it in the
  stub, regenerate, implement the `ZEND_METHOD(Gtk4_Class, name)`.
- GObject properties exposed as PHP properties are declared with `@property` tags on the class in
  the stub (the generator will emit them from GIR); the IDE stub adds `__get/__set/__isset` to
  `GObject` so PHPStan/IDEs honour them (`StubsTest` ignores those three).
- `src/gtk4_arginfo.h` is included by exactly one TU (`src/gtk4.cpp`, which owns MINIT and class
  registration); method files only define `ZEND_METHOD`s.

