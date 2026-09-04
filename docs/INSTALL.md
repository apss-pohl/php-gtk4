# Installing php-gtk4

php-gtk4 is a PHP extension that binds GTK 4. It is a **CLI** extension: desktop applications are
started with `php script.php` — web SAPIs (FPM, Apache, CGI) are not a target.

There are two ways to get it, on both platforms:

| Route | When |
| --- | --- |
| **Prebuilt binary** from the [releases page](https://github.com/apss-pohl/php-gtk4/releases) | Your PHP matches one of the published builds exactly. Fastest path. |
| **Build from source** | Everything else — a different distribution, a ZTS PHP, a newer GTK, or you want to hack on the extension. |

The prebuilt binaries are **not portable**: each one is bound to a PHP version, NTS, and (on Linux)
the C++ ABI, glibc and GTK 4 soname it was built against — the filename spells all of it out. When
in doubt, build from source; it is one `phpize && ./configure && make` away and always correct.

This document covers installing and enabling the extension. The full build reference — configure
options, the sanitizer/coverage variants, the CI stages — is [BUILD.md](BUILD.md).

## Requirements

| What | Linux | Windows |
| --- | --- | --- |
| PHP | 8.4 or newer, CLI, NTS or ZTS | 8.4 or newer, CLI, x64, NTS or TS |
| GTK | 4.14 or newer (runtime: `libgtk-4-1`) | 4.14 or newer from [gvsbuild](https://github.com/wingtk/gvsbuild) |
| Display | X11 or Wayland session | the desktop |
| To build | `php8.4-dev`, `libgtk-4-dev`, GCC 11+ / Clang 14+, `pkg-config` | VS 2022 (*Desktop development with C++*), PHP devel pack, PHP SDK |

php-gtk4 refuses to build or load on PHP < 8.4 and GTK < 4.14.

## Linux

### Dependencies

Debian / Ubuntu (24.04 or newer; PHP 8.4 comes from the
[ondrej/php](https://launchpad.net/~ondrej/+archive/ubuntu/php) PPA on older releases):

```sh
sudo apt install php8.4-cli libgtk-4-1                       # to run a prebuilt .so
sudo apt install php8.4-dev libgtk-4-dev build-essential pkg-config   # to build from source
```

Fedora:

```sh
sudo dnf install php-cli gtk4                                # runtime
sudo dnf install php-devel gtk4-devel gcc-c++ pkgconf        # build
```

Arch:

```sh
sudo pacman -S php gtk4 base-devel
```

### Option A — a prebuilt `.so`

Pick the asset whose name matches your PHP and distribution, download it together with
`SHA256SUMS`, verify it, and drop it into PHP's extension directory:

```sh
sha256sum -c SHA256SUMS --ignore-missing
ext=$(php-config8.4 --extension-dir)
sudo install -m 644 gtk4-<ver>-php8.4-nts-x86_64-linux-gnu-<dist>.so "$ext/gtk4.so"
```

The assets carry build provenance; `gh attestation verify <file> --repo apss-pohl/php-gtk4` checks it.

If the file does not match your PHP (wrong version, ZTS, another distribution), it will refuse to
load — that is the naming doing its job. Build from source instead.

### Option B — from source

```sh
tar xf php-gtk4-<ver>.tar.gz && cd php-gtk4-<ver>      # or: git clone + cd php-gtk4
phpize8.4
./configure --with-php-config=/usr/bin/php-config8.4
make -j"$(nproc)"                                      # -> modules/gtk4.so
sudo make install                                      # -> PHP's extension dir
```

On a clone, `./buildall.sh` does build + install for every PHP version in its table, and
`./ci.sh --only=build` builds `./gtk4.so` in the repo root without installing anything.

### Enabling it

The extension is loaded like any other, but **php-gtk3 must not be loaded in the same process**:
libgtk-3 and libgtk-4 export the same C symbols and whichever library loads first wins, so a gtk4
call would land in GTK 3. If this machine has php-gtk3 enabled, do not enable gtk4 globally — use
the launcher instead (see below).

System-wide, on a machine without php-gtk3 (Debian/Ubuntu):

```sh
echo 'extension=gtk4' | sudo tee /etc/php/8.4/mods-available/gtk4.ini
sudo phpenmod -v 8.4 -s cli gtk4          # CLI only; php-gtk4 has no business in FPM
```

Other distributions: put `extension=gtk4` into an ini file in the scanned directory that
`php --ini` reports (Fedora `/etc/php.d/`, Arch `/etc/php/conf.d/`).

Per script, no ini changes at all:

```sh
php8.4 -d extension=gtk4 script.php       # from the extension dir
php8.4 -d extension=/path/to/gtk4.so script.php
bin/php-gtk4 script.php                   # from a clone: filters php-gtk3 out, keeps every other extension
```

### Verifying

```sh
php8.4 -d extension=gtk4 -r 'echo Gtk4\VERSION, " ", Gtk4\BUILD_INFO, PHP_EOL;'
php8.4 -d extension=gtk4 -m | grep gtk4
```

## Windows

### Dependencies

1. **PHP 8.4+ for Windows**, x64, from <https://windows.php.net/download/> — the *NTS* build unless
   you specifically need TS. Prebuilt DLLs here are NTS/VS17/x64.
2. **GTK 4** built with MSVC, from gvsbuild — php-gtk4, PHP and GTK must share one CRT, so a MinGW
   or MSYS2 GTK will not do. Unpack the prebuilt release asset:

   ```powershell
   # GTK4_Gvsbuild_<ver>_x64.zip from https://github.com/wingtk/gvsbuild/releases (~300 MB)
   Expand-Archive GTK4_Gvsbuild_<ver>_x64.zip -DestinationPath C:\gtk
   ```

   The tree must contain `C:\gtk\bin`, `C:\gtk\lib` and `C:\gtk\share`.
3. `C:\gtk\bin` on `PATH` — that is where `gtk-4-1.dll` and its dependencies live, and GTK finds its
   `share\` tree (icons, schemas) relative to them:

   ```powershell
   [Environment]::SetEnvironmentVariable('Path', $env:Path + ';C:\gtk\bin', 'User')
   ```

Building additionally needs Visual Studio 2022 with *Desktop development with C++*, the PHP
*development package* matching your `php.exe`, and
[php-sdk-binary-tools](https://github.com/php/php-sdk-binary-tools).

### Option A — a prebuilt `.dll`

Download `php_gtk4-<ver>-php8.4-nts-vs17-x64.dll` from the releases page, verify it against
`SHA256SUMS`, and copy it into PHP's `ext\` directory as `php_gtk4.dll`:

```powershell
Get-FileHash php_gtk4-<ver>-php8.4-nts-vs17-x64.dll -Algorithm SHA256
Copy-Item php_gtk4-<ver>-php8.4-nts-vs17-x64.dll C:\php\ext\php_gtk4.dll
```

PHP version, threading (NTS) and compiler series (VS17) in the name must all match your `php.exe`
— `php -i` prints them. Anything else: build from source.

### Option B — from source

In a `phpsdk-vs17-x64.bat` shell, with the devel pack's `phpize.bat` on `PATH`, from the repo root:

```bat
phpize
configure --with-gtk4=C:\gtk
nmake                                :: -> x64\Release\php_gtk4.dll (Release_TS for a TS PHP)
```

The whole Windows toolchain — why gvsbuild, what `config.w32` does, the pinned GTK version — is in
[BUILD.md § Windows](BUILD.md#windows).

### Enabling it

Add to `php.ini` (the file `php --ini` reports):

```ini
extension_dir = "C:\php\ext"
extension = php_gtk4
```

Or per script, without touching `php.ini`:

```bat
php -d extension=C:\php\ext\php_gtk4.dll script.php
bin\php-gtk4 script.php              :: from a clone: puts %GTK4_ROOT%\bin on PATH, loads the built DLL
```

`bin\php-gtk4.cmd` defaults to `GTK4_ROOT=C:\gtk-build\gtk\x64\release`; set `GTK4_ROOT` to your GTK
tree and `GTK4_DLL` to a specific build if the defaults do not fit.

### Verifying

```bat
php -d extension=php_gtk4 -r "echo Gtk4\VERSION, PHP_EOL;"
```

## Hello, window

Same script on both platforms — with the extension enabled, `php hello.php` opens a window:

```php
<?php
use Gtk4\{GApplicationFlags, GtkApplication, GtkLabel, GtkWindow};

$app = new GtkApplication('org.example.Hello', GApplicationFlags::DEFAULT_FLAGS);
$app->connect('activate', function (GtkApplication $app): void {
    $win = new GtkWindow();
    $win->set_application($app);
    $win->title = 'php-gtk4';
    $win->set_default_size(320, 200);
    $win->set_child(new GtkLabel('It works.'));
    $win->present();
});
exit($app->run($argv));
```

From a clone, `bin/php-gtk4 examples/demo.php` (`bin\php-gtk4 examples\demo.php` on Windows) opens
the demo application: every bound class with its own page.

## Troubleshooting

| Symptom | Cause and fix |
| --- | --- |
| `Unable to load dynamic library 'gtk4'` | Wrong `extension_dir`, or the file is not there. `php --ini`, `php-config --extension-dir`. |
| `undefined symbol: ...` at load | The `.so` was built for another PHP or another GTK. Build from source. |
| `Cannot load module 'gtk4' ... module compiled with module API=...` | PHP version mismatch (NTS vs ZTS counts too). Build against the PHP you run. |
| `configure: error: PHP 8.4 or newer required` | Older `phpize`/`php-config`. Pass `--with-php-config=/usr/bin/php-config8.4`. |
| `configure: error: gtk4 >= 4.14 required` | `libgtk-4-dev` too old or absent; check `pkg-config --modversion gtk4`. |
| `php-gtk3 is loaded` warning at startup | Both bindings in one process. Use `bin/php-gtk4`, or `php -n -d extension=gtk4`, or disable gtk3. |
| `cannot open display` / no window | No X11/Wayland session. Over SSH use X forwarding, or `xvfb-run` for headless runs. |
| Windows: *The specified module could not be found* | `C:\gtk\bin` not on `PATH`, or a 32-bit/TS mismatch between `php.exe` and the DLL. |
| Windows: window opens without icons or themes | GTK cannot find its `share\` tree — keep the DLLs in the unpacked gvsbuild tree instead of copying them out. |
| `PHP Warning: Gtk: ... assertion '...' failed` | GTK refused a value your script passed; the line named is the call. Fix the call, or see `gtk4.diagnostics` below. |

## Runtime settings

| directive | default | what it does |
| --- | --- | --- |
| `gtk4.diagnostics` | `warning` | What GTK's own complaints (`g_critical` / `g_warning`) become. `warning`: PHP `E_WARNING`s at the line that caused them, catchable with `set_error_handler()`. `fatal`: a `CRITICAL` becomes `E_ERROR` — useful in development and CI, where an unguarded call should stop the run. `stderr`: GLib's raw output. `off`: dropped. |

`gtk4.build_info` and `gtk4.features` are informational (`phpinfo()`); the `Gtk4\BUILD_INFO` and
`Gtk4\FEATURES` constants are baked in at build time and are the authoritative copies.

## Uninstalling

Remove the ini entry (`sudo phpdismod -v 8.4 gtk4`, or delete the `extension=` line) and delete
`gtk4.so` / `php_gtk4.dll` from the extension directory. Nothing else is written outside it.

## See also

- [BUILD.md](BUILD.md) — the full build reference, configure options, Windows toolchain, ZTS/threads.
- [CONTRIBUTING.md](CONTRIBUTING.md) — working on the extension itself.
- [../README.md](../README.md) — usage, coexisting with php-gtk3.
