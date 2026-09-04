# Building php-gtk4

php-gtk4 is a standard `phpize` extension: `config.m4` generates `configure`, `make` produces
`gtk4.so`. There is no other build system. `ci.sh` and `buildall.sh` are wrappers around exactly
that flow.

## Requirements

| What | Linux (primary target; Windows below) |
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
./ci.sh                                   # every default stage: version, gen, stubs, lint, QA, build, load, test, phpt
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
once in MINIT and read-only afterwards, so they are shared. CI builds and tests NTS and ZTS for
every supported PHP on Linux and Windows (`tests.yml`, `windows.yml`); the release binaries stay NTS.

### Testing a ZTS build locally

Distributions ship NTS only, so a ZTS interpreter has to be built once — into a prefix of your
own, so nothing system-wide changes and no `sudo` is needed:

```sh
cd /path/to/php-src            # a PHP >= 8.4 source tree
./configure --prefix="$HOME/.local/php-8.4-zts" --enable-zts \
    --disable-cgi --without-pear --with-zlib \
    --enable-mbstring --enable-tokenizer --with-libxml --enable-dom \
    --enable-xmlwriter --enable-xmlreader --enable-simplexml --enable-phar
make clean && make -j"$(nproc)" && make install
```

Neither the extension list nor `make clean` is optional. `tests/run.sh` runs PHPUnit through
`bin/php-gtk4` rather than `php -n` (see "Running the tests"), so dom/mbstring/tokenizer/xmlwriter
have to be in the interpreter, and the PNG fixtures the tests build need `zlib` — CI's images
bundle it, a source build does not enable it by default. `make clean` matters when the tree was
configured NTS before: ZTS turns on `ZEND_MAX_EXECUTION_TIMERS` and stale objects link against
symbols that were not compiled (`undefined reference to zend_max_execution_timer_init`).

A source build loads no `php.ini` at all, and PHP's *built-in* defaults are not the ones a
distribution ships — `zend.exception_ignore_args` is `0` rather than `1`, so an exception's stack
trace keeps its arguments alive and tests that assert an object is freed will fail. Install one:

```sh
cp php.ini-production "$HOME/.local/php-8.4-zts/lib/php.ini"
```

Then point `ci.sh` at it — `PHPIZE` is derived from `PHP_CONFIG`:

```sh
PHP="$HOME/.local/php-8.4-zts/bin/php" PHP_CONFIG="$HOME/.local/php-8.4-zts/bin/php-config" \
    ./ci.sh --only=build,load,test
```

Worth doing before pushing anything that touches `src/core/globals.h` or that GLib/GTK can call
back on a thread of its own: `GTK4_G()` resolves through per-thread storage under ZTS, so a
callback that runs on one of GTK's worker threads reads globals that do not exist there. A
`GLogWriterFunc` that recorded into the module globals segfaulted exactly this way — NTS has a
single globals block and never noticed, and the sanitizer and valgrind stages did not either.
`phpgtk::on_gui_thread()` (`src/core/mainloop.h`) is the guard for that case.

**Using GTK from more than one thread** does not work, and that is GTK's rule, not ours: every
`gtk_*` call must come from the thread that owns the default `GMainContext` — the one that ran
`Gtk::init()`. The extension records that thread and `Gtk::init()`, `GtkApplication::run()`,
`GMainLoop::run()` and `GLib::main_context_iteration()` throw `Error` when called from another
one. Consequences:

- *Several threads each opening windows* — not possible. No lock on our side can make it correct;
  the same holds for PyGObject, gtk-rs and every other binding. (php-gtk4 is a **CLI** extension:
  desktop applications run with `php script.php`; web SAPIs are not a target.)
- *One GUI thread plus PHP worker threads* — the useful shape, and the standard GTK pattern: workers
  never touch widgets and post results to the GUI thread with `GLib::idle_add()`. What is still
  missing for that is a thread-safe way to hand a callable from one PHP thread to the GUI thread's
  context (a `parallel`-style channel or a `GLib::invoke_on_main()`); tracked in `docs/TODO.md`.

## Windows

**Status: builds through PHP's own Windows build system** — `config.w32` is the counterpart of
`config.m4`, driven by the PHP SDK (`phpize.bat` → `configure` → `nmake`), with GTK 4 from
[gvsbuild](https://github.com/wingtk/gvsbuild). The output is `php_gtk4.dll`. There is no IDE
project and there will be none (php-gtk3 had one; see `docs/PLAN.md` milestone 6). CI builds and
tests it on `windows-2022` for PHP 8.4 and 8.5, NTS and ZTS (`.github/workflows/windows.yml` — the
same matrix as Linux), and every release ships one
`php_gtk4-<ver>-php<X.Y>-nts-vs17-x64.dll` per supported PHP — TS builds from source, as on Linux.

### Why gvsbuild

Windows PHP is built with MSVC (VS17 for 8.4/8.5, see
<https://windows.php.net/downloads/php-sdk/deps/series/>). gvsbuild builds GTK with the same
compiler, so the extension, PHP and GTK share one CRT and one `malloc` — no MinGW/MSVC boundary to
crash at. It also ships `pkgconf` and the `.pc` files, which is what `config.w32` reads the include
and library lists from, so the flag list never has to be maintained by hand.

### Requirements

| What | Windows |
| --- | --- |
| Visual Studio | 2022 (Community is fine), workload *Desktop development with C++*. The series must match the PHP build: VS17 for PHP 8.4 and 8.5. |
| PHP | `php.exe` 8.4+ from <https://windows.php.net/download/> (NTS or TS) **and** the matching *Development package* (`php-devel-pack-<ver>[-nts]-Win32-vs17-x64.zip`): headers, `php8.lib`, `phpize.bat`. |
| PHP SDK | [php-sdk-binary-tools](https://github.com/php/php-sdk-binary-tools) (`git clone`); it provides the `phpsdk-vs17-x64.bat` shell in which everything below runs. |
| GTK 4 | A gvsbuild tree: `<root>\include\gtk-4.0`, `<root>\lib\*.lib`, `<root>\lib\pkgconfig`, `<root>\bin\*.dll`. |
| Composer | for the test suite only. |

Getting the GTK tree, either way gives the same layout:

```powershell
# (a) prebuilt: the GTK4_Gvsbuild_<ver>_x64.zip asset of a gvsbuild release (~300 MB), unpacked to C:\gtk
Expand-Archive GTK4_Gvsbuild_2026.8.0_x64.zip -DestinationPath C:\gtk

# (b) from source (an hour or more; needs Python + the tools gvsbuild's README lists)
py -m pip install --user gvsbuild
gvsbuild build gtk4          # -> C:\gtk-build\gtk\x64\release
```

### Build

In a `phpsdk-vs17-x64.bat` shell, with the devel pack's directory (the one containing
`phpize.bat`) on `PATH`, from the repo root:

```bat
phpize
configure --with-gtk4=C:\gtk        # or set GTK4_ROOT=C:\gtk; default C:\gtk-build\gtk\x64\release
nmake                               # -> x64\Release\php_gtk4.dll (Release_TS for a TS PHP)
```

`config.w32` does what `config.m4` does: refuses PHP < 8.4, checks `src/php_gtk4.h` against
`VERSION`, bakes git hash/date into `Gtk4\BUILD_INFO`, compiles every `src/**/*.cpp` with
`/std:c++20 /EHsc`, and takes the include/library flags from `<root>\bin\pkgconf.exe`
(`--define-prefix`, so the tree may live anywhere) — falling back to the known gvsbuild layout when
`pkgconf` is missing. `--enable-gtk4-webkit` is refused (WebKitGTK is Linux-only; WebView2 is
the plan).

### Running what you built

`php_gtk4.dll` links against `gtk-4-1.dll` and friends, so `<root>\bin` must be on `PATH` (or its
DLLs next to `php.exe`); GTK finds its `share\` tree (schemas, icons) relative to them.

```bat
bin\php-gtk4 examples\demo.php           :: PATH=<GTK4_ROOT>\bin + -dextension=x64\Release\php_gtk4.dll
set GTK4_ROOT=C:\gtk                     :: where the DLLs are (default C:\gtk-build\gtk\x64\release)
set GTK4_DLL=x64\Release_TS\php_gtk4.dll :: which build (default: whatever is under x64\)
tests\run --filter SignalTest            :: PHPUnit on the real desktop (no Xvfb on Windows)
```

`bin/php-gtk4.cmd` and `tests/run.cmd` are the Windows counterparts of `bin/php-gtk4` and
`tests/run.sh`. There is no php-gtk3 to filter out and no display to fake, so they are only
`PATH` + `-dextension`; `tests/run.cmd` sets `GSK_RENDERER=cairo`, `GTK_A11Y=none` and
`XDEBUG_MODE=off` like the Linux runner does.

### The pinned GTK version (`GVSBUILD_VERSION`)

Linux CI takes GTK from apt; Windows CI has no package manager and downloads gvsbuild's prebuilt
`GTK4_Gvsbuild_<ver>_x64.zip` instead, pinned to one gvsbuild release by `GVSBUILD_VERSION` in
`.github/workflows/windows-build.yml` — the one reusable Windows recipe that both `windows.yml` (CI)
and the `build-windows` job of `release.yml` call (the pin is also the cache key, so the 300 MB zip is
fetched once per version). The Windows build
stays on that GTK until the number is bumped — nothing does it automatically: Dependabot only tracks
package ecosystems and `uses:` lines, not an `env:` value.

The archive is pinned twice: `GVSBUILD_VERSION` names it and `GVSBUILD_SHA256` says what it must
contain. A GitHub release asset can be replaced by its owner and the workflow caches the unpacked
tree, so the hash is verified before unpacking and the cached tree carries a stamp that is checked
again on a cache hit. Read the hash for a version from the API:

```sh
curl -s https://api.github.com/repos/wingtk/gvsbuild/releases/tags/2026.8.0 \
  | jq -r '.assets[] | select(.name | test("GTK4")) | .digest'
```

`./update-deps.sh --only=gvsbuild` does that lookup for you and prints the current pin next to
gvsbuild's newest release and its digest; `--gvsbuild` writes both values into the recipe.

To move to a newer GTK on Windows: pick a release from <https://github.com/wingtk/gvsbuild/releases>,
set it in `windows-build.yml` (`WorkflowsTest` fails if a caller carries its own pin), push, and let
`windows.yml` prove it. Do it when a newer GTK is needed or when a release is cut (it is on the
checklist in `docs/RELEASING.md`), not on a schedule; an old pin is not a failure. Never below the
4.14 floor — `config.w32` checks `gtk4 >= 4.14` (through pkgconf, or `gtkversion.h` on the
fallback path).

### What is Linux-only

- `ci.sh` and all of its stages (clang-tidy, sanitizers, coverage, valgrind, `run-tests.php` over
  `tests/phpt`). Lint on Linux/WSL, build on Windows. `tests/phpt` asserts stderr text from GLib
  and needs Xvfb; it is not run on Windows.
- `--enable-gtk4-webkit`.
- `bin/php-gtk4`'s php-gtk3 filtering — not needed, there is no php-gtk3 for Windows PHP 8.

### Platform-specific code

Exactly one place in `src/` is platform-specific: `pin_gtk_library()` in `src/gtk4.cpp`, which
keeps libgtk-4 mapped after PHP unloads the extension — `dlopen(RTLD_NODELETE)` on Linux,
`GetModuleHandleEx(GET_MODULE_HANDLE_EX_FLAG_PIN)` on Windows. Keep it that way: GTK's API is
identical on both, so new bindings never need an `#ifdef`. WebView on Windows will follow
php-gtk3's `_Unix.cpp` / `_Windows.cpp` split when it arrives.

WSL2 with Ubuntu 24.04 remains the way to run the full `ci.sh` on a Windows machine: it is exactly
the CI environment, and WSLg provides the display.

## Releases

Bumping `VERSION` on `main` is the only release trigger — `release.yml` builds one `.so` per
supported PHP and publishes it. Never tag by hand. Details in `docs/RELEASING.md`.
