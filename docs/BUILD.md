# Building php-gtk4

php-gtk4 is a standard `phpize` extension: `config.m4` generates `configure`, `make` produces
`gtk4.so`. There is no other build system. `ci.sh` and `buildall.sh` are wrappers around exactly
that flow.

## Requirements

| What | Linux (primary target) |
| --- | --- |
| PHP | **8.4 or newer**, NTS or ZTS. `php8.4-dev` for `phpize`/`php-config`. `config.m4` refuses < 8.4. |
| GTK | **4.14 or newer** — `libgtk-4-dev` (pulls GLib ≥ 2.76, cairo-gobject). Ubuntu 24.04 is the CI floor. |
| Compiler | GCC 11+ or Clang 14+ (`-std=c++20`). |
| Optional | `libwebkitgtk-6.0-dev` (`--enable-gtk4-webkit`), `xvfb`, `valgrind`, `gcovr`, clang-tidy/format 20 for the QA stages. |

Debian/Ubuntu in one line:

```sh
sudo apt install php8.4-dev libgtk-4-dev build-essential pkg-config xvfb
```

## Build

```sh
phpize8.4
./configure --with-php-config=/usr/bin/php-config8.4
make -j"$(nproc)"                         # -> modules/gtk4.so
sudo make install                         # copies it into PHP's extension dir
```

Or let the scripts do it:

```sh
./ci.sh --only=build     # phpize + configure + make, then copies modules/gtk4.so to ./gtk4.so
./buildall.sh            # build + install for every enabled version in its table (sudo for the install)
ONLY=8.5 ./buildall.sh   # one specific version, even if disabled in the table
WITH_WEBKIT=1 ./buildall.sh
```

`./gtk4.so` in the repo root is what `bin/php-gtk4`, `tests/run.sh` and every `ci.sh` stage use.
Build metadata (git hash, date, enabled features) is baked in at configure time and readable as
`Gtk4\BUILD_INFO` / `Gtk4\FEATURES`.

### Configure options

| Option | Effect |
| --- | --- |
| `--enable-gtk4-webkit` | Adds `webkitgtk-6.0` to the pkg-config set and builds the WebKit classes. |
| `--enable-gtk4-sanitize` | ASan + UBSan (+ LSan) instrumented build; `ci.sh --only=asan` builds it as `gtk4-asan.so`. |
| `--enable-gtk4-coverage` | gcov instrumentation; `ci.sh --only=coverage` builds `gtk4-cov.so` and renders HTML with gcovr. |

Variants rebuild in place; `ci.sh` stashes the finished `.so` files around `make clean`.

### Rules that bite

- **Editing `config.m4` requires re-running `phpize`** — `configure` is generated from it. `ci.sh`
  does that on every build.
- **Never run `phpize --clean`**: it deletes `tests/*.php` (php-src assumes `.phpt` tests live
  there). Use `make clean`, and know that it removes every `*.so` under the tree.
- **`VERSION` is the single source of truth.** `src/php_gtk4.h` and `src/gtk4.stub.php` are
  mirrors written by `./ci.sh --only=version --fix`; `configure` aborts on a mismatch.
- **Generated files are committed and checked**: `src/gtk4_arginfo.h` and `stubs/gtk4.php` come
  from `src/gtk4.stub.php` via `./ci.sh --only=stubs --fix`. Never edit them by hand.
- `bear -- make` produces `compile_commands.json` for clangd / clang-tidy.

## Running what you built

```sh
bin/php-gtk4 script.php                    # prefers ./gtk4.so, filters php-gtk3 out of the ini scan dir
GTK4_SO=gtk4 bin/php-gtk4 script.php       # force the installed extension
php8.4 -n -dextension=./gtk4.so script.php # no ini at all
bin/php-gtk4 examples/demo.php             # the demo application (every class, one page each)
```

php-gtk3 and php-gtk4 must never be loaded in one process — libgtk-3 and libgtk-4 export the same C
symbols and the first library loaded wins. `buildall.sh` therefore installs `gtk4.ini` without
enabling it. See "Coexisting with php-gtk3" in the README.

## Verifying the build

```sh
./ci.sh                                   # every default stage: version, stubs, lint, QA, build, load, test, phpt
./ci.sh --only=load                       # does the .so load, does it report the right version
./ci.sh --only=test --filter SignalTest   # PHPUnit under Xvfb (tests/run.sh)
./ci.sh --with=asan,coverage,valgrind     # the extra stages CI runs on top
```

`ci.sh --help` lists every stage and flag; the full description is the "Lint, QA, build, test"
section of `CLAUDE.md`. Tests need a display: `tests/run.sh` wraps `xvfb-run` and pins
`GDK_BACKEND=x11` (GTK 4.14's Wayland backend corrupts the heap under the suite's window churn).

## Threads and ZTS

Two separate questions hide behind "thread safe":

**Building against a ZTS PHP** works. Every piece of per-request state (exception handler and mode,
the parked Throwable, running-loop stack, tracked closures/sources/callables, live `PhpValue`
instances) lives in the module globals struct in `src/core/globals.h`, accessed through `GTK4_G()`
— one copy per thread under ZTS, one in total under NTS. The GType ↔ class registries are filled
once in MINIT and read-only afterwards, so they are shared. CI builds and tests one ZTS variant
(`tests.yml`, `phpts: ts`); the release binaries stay NTS.

**Using GTK from more than one thread** does not work, and that is GTK's rule, not ours: every
`gtk_*` call must come from the thread that owns the default `GMainContext` — the one that ran
`Gtk::init()`. The extension records that thread and `Gtk::init()`, `GtkApplication::run()`,
`GMainLoop::run()` and `GLib::main_context_iteration()` throw `Error` when called from another
one. Consequences:

- *Several request threads each opening windows* (FPM/Apache-worker style) — not possible. No lock
  on our side can make it correct; the same holds for PyGObject, gtk-rs and every other binding.
- *One GUI thread plus PHP worker threads* — the useful shape, and the standard GTK pattern: workers
  never touch widgets and post results to the GUI thread with `GLib::idle_add()`. What is still
  missing for that is a thread-safe way to hand a callable from one PHP thread to the GUI thread's
  context (a `parallel`-style channel or a `GLib::invoke_on_main()`); tracked in `docs/TODO.md`.

## Windows

**Status: not supported yet.** Windows is milestone 6 in `docs/PLAN.md`; nothing in `src/` is
Windows-specific today and there is no `config.w32`. This section records the intended route so
that the port lands the same way the Linux build works — through PHP's own build system — rather
than as a hand-maintained IDE project like php-gtk3's.

### Intended route: php-sdk + `config.w32`

1. **Toolchain.** Install Visual Studio (Community is fine) with the "Desktop development with
   C++" workload. The compiler *series* must match the PHP build you target — see
   <https://windows.php.net/downloads/php-sdk/deps/series/> (PHP 8.4 = VS17). Install the
   [php-sdk-binary-tools](https://github.com/php/php-sdk-binary-tools) and open a
   `phpsdk-vs17-x64.bat` shell.
2. **PHP source + deps.** In the SDK shell: `phpsdk_buildtree phpdev`, unpack the matching
   php-src release into `phpdev\vs17\x64\php-8.4.x-src`, run `phpsdk_deps --update --branch master`.
   Build PHP once (`buildconf`, `configure --disable-all --enable-cli --disable-zts`, `nmake`) —
   php-gtk4 is NTS-only on every platform.
3. **GTK 4.** Two options:
   - **gvsbuild** (<https://github.com/wingtk/gvsbuild>) — MSVC-built GTK 4, the toolchain PHP
     itself uses, so no CRT mixing: `gvsbuild build gtk4`, then point the build at its
     `include`/`lib` directories.
   - **MSYS2** — `pacman -S mingw-w64-x86_64-gtk4`. Faster to obtain, but MinGW-built libraries
     linked into an MSVC-built PHP work only because GTK's ABI is plain C; keep the two CRTs in
     mind when something crashes at a boundary.
4. **`config.w32`.** The Windows counterpart of `config.m4`: `ARG_ENABLE("gtk4", ...)`, the include
   and library paths from step 3, `EXTENSION("gtk4", <list of every src/**/*.cpp>)`,
   `ADD_FLAG("CFLAGS_GTK4", "/std:c++20 /EHsc")`. Drop it into `ext\gtk4` of the php-src tree
   (or use `--with-extra-dirs`), re-run `buildconf --force`, `configure --enable-gtk4=shared`,
   `nmake`. The output is `php_gtk4.dll`.
5. **Runtime.** The DLL needs every GTK DLL on `PATH` (or next to `php.exe`) plus GTK's `share\`
   tree (schemas, icons) relative to them. WebView on Windows is planned as WebView2, following
   php-gtk3's `_Unix.cpp` / `_Windows.cpp` split for the platform-specific half.

### Things that will need work

- `config.m4`'s `PHPGTK_BUILD_INFO` (git hash/date) has no `config.w32` equivalent yet.
- `bin/php-gtk4`, `tests/run.sh` and every `ci.sh` stage are bash + Xvfb; on Windows the suite
  runs against the real display with `php vendor\bin\phpunit`.
- `pin_gtk_library()` in `src/gtk4.cpp` uses `dlopen(RTLD_NODELETE)` to keep libgtk-4 mapped after
  PHP unloads the extension; it is `#ifndef _WIN32` and needs a Windows counterpart
  (`GetModuleHandleEx` with `GET_MODULE_HANDLE_EX_FLAG_PIN`). Nothing else in `src/` is POSIX-only —
  keep it that way.

Until the above exists, the practical way to develop on a Windows machine is WSL2 with Ubuntu
24.04, which is exactly the CI environment: install the packages from "Requirements" and follow the
Linux steps; WSLg provides the display.

## Releases

Bumping `VERSION` on `main` is the only release trigger — `release.yml` builds one `.so` per
supported PHP and publishes it. Never tag by hand. Details in `docs/RELEASING.md`.
