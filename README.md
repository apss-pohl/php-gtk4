# php-gtk4

[![Release](https://github.com/apss-pohl/php-gtk4/actions/workflows/release.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/release.yml)
[![C++ lint](https://github.com/apss-pohl/php-gtk4/actions/workflows/cpp-lint.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/cpp-lint.yml)
[![coverage](https://img.shields.io/endpoint?url=https://raw.githubusercontent.com/apss-pohl/php-gtk4/badges/coverage.json)](https://github.com/apss-pohl/php-gtk4/actions/workflows/release.yml)
[![latest release](https://img.shields.io/github/v/release/apss-pohl/php-gtk4?include_prereleases&sort=semver&label=release&color=4A86CF)](https://github.com/apss-pohl/php-gtk4/releases)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![GTK 4](https://img.shields.io/badge/GTK-4.14%2B-4A86CF?logo=gtk&logoColor=white)](https://www.gtk.org/)
[![C++20](https://img.shields.io/badge/C%2B%2B-20-00599C?logo=cplusplus&logoColor=white)](https://en.cppreference.com/w/cpp/20)

PHP extension binding GTK 4, written in C++20 against the native Zend API (standard `phpize`
build). The design decisions are in [docs/DESIGN.md](docs/DESIGN.md); what is still open in
[docs/TODO.md](docs/TODO.md). Every class the extension registers, with a link to its upstream
documentation, is in [docs/INVENTORY.md](docs/INVENTORY.md).

## Usage

```php
<?php
use Gtk4\{GApplicationFlags, GtkApplication, GtkWindow};

$app = new GtkApplication('org.example.Hello', GApplicationFlags::DEFAULT_FLAGS);
$app->connect('activate', function (GtkApplication $app): void {
    $win = new GtkWindow();
    $win->set_application($app);         // constructors mirror GTK: no extra arguments
    $win->title = 'php-gtk4';            // GObject properties are PHP properties
    $win->set_default_size(300, 200);
    $win->connect('close-request', fn() => false);
    $win->present();
});
exit($app->run($argv));
```

Styling is CSS: a `GtkCssProvider` holds the stylesheet, `Gtk::add_provider_for_display($win->get_display(),
$provider, GtkStyleProviderPriority::APPLICATION)` attaches it to every widget on the display, and
`$widget->add_css_class('card')` is what a selector matches. Loading new CSS into an attached
provider restyles what is on screen; parse errors arrive through the provider's `parsing-error`
signal as a `GtkCssSection` and a `GError` (the loaders themselves never throw).

Actions: `new GSimpleAction('quit')` + `$app->add_action()` — reachable as `app.quit` from widgets
(`$button->activate_action('app.quit')`); GVariant parameters/states are plain PHP values.
Exceptions thrown in handlers are logged by default; `Gtk::set_exception_mode(ExceptionMode::Rethrow)`
makes them propagate out of `run()` instead. `Gtk4\GMainLoop` + `Gtk4\GLib::timeout_add()` cover
scripts without windows.

**Diagnostics.** GTK refuses a value it does not accept by logging, and php-gtk4 turns those into
PHP errors *at the line that caused them* — catchable with `set_error_handler()`, and subject to
`error_reporting` and `@` like any other. `gtk4.diagnostics` chooses between a warning, a fatal,
GLib's own stderr and silence:
[docs/INSTALL.md § Runtime settings](docs/INSTALL.md#runtime-settings).

## Examples

Every registered class has a page of its own under [`examples/`](examples/README.md), and one
application mounts them all:

```sh
bin/php-gtk4 examples/demo.php              # every class, one page at a time, in a sidebar
bin/php-gtk4 examples/demo.php GtkListView  # or a single page on its own
bin/php-gtk4 examples/notes/notes.php       # Notes: a real application, not a page
```

`notes/` is the one to read to see how a program is put together rather than what a class does — a
`GtkApplicationWindow` subclass, a header bar and a primary menu, list models over a `GListStore`
of PHP objects, and autosave to JSON.

## Install

Requires **PHP 8.4+** (NTS or ZTS) and GTK ≥ 4.14. With [PIE](https://github.com/php/pie), the PHP
Foundation's extension installer:

```sh
sudo apt install php8.4-dev libgtk-4-dev build-essential pkg-config   # PIE builds from source
pie install php-gtk4/php-gtk4
composer require --dev php-gtk4/stubs     # IDE and PHPStan stubs for the Gtk4\ namespace
```

On Windows PIE takes the prebuilt DLL from the release instead of building. Or the standard
`phpize` build, which is what PIE runs for you:

```sh
phpize8.4 && ./configure --with-php-config=/usr/bin/php-config8.4 && make -j"$(nproc)" && sudo make install
```

Step-by-step installation for **Linux and Windows** — dependencies, prebuilt release binaries,
enabling the extension, troubleshooting — is in [docs/INSTALL.md](docs/INSTALL.md).

Everything else — `ci.sh`/`buildall.sh`, configure options, the sanitizer/coverage variants, and
the **Windows** build (PHP SDK + `config.w32`, GTK 4 from gvsbuild) — is in [docs/BUILD.md](docs/BUILD.md).

## Design

Why the binding is shaped the way it is — the native Zend API instead of a framework, classes
generated from GObject-Introspection, GTK's own names in snake_case, one PHP handle per GObject with
identity preserved, a `Throwable` that never crosses a GLib frame, values rather than GLib shapes at
the PHP boundary, a single GUI thread, and what is deliberately not bound and why — is in
[docs/DESIGN.md](docs/DESIGN.md).

## Contributing

Setup, the git hooks, the definition of done for a new class (implementation + tests + stub +
example) and the code rules are in [docs/CONTRIBUTING.md](docs/CONTRIBUTING.md).

## Coexisting with php-gtk3

All PHP-visible names live in the **`Gtk4` namespace** (`use Gtk4\{Gtk, GtkWindow};`,
`Gtk4\VERSION`), so gtk3 and gtk4 can be *installed* side by side and never hijack each
other's class names.

They still cannot be *used* in one process: libgtk-3 and libgtk-4 export the same C symbols, and
the dynamic linker binds every `gtk_*` call to whichever library loaded first — with gtk3 loaded,
gtk4 calls land in GTK 3 and fail. gtk4 prints a warning at startup when it sees gtk3, and
`buildall.sh` installs `gtk4.ini` without enabling it globally. Pick one per process:

```sh
bin/php-gtk4 script.php               # gtk4 only; keeps all other extensions (filters gtk3 via PHP_INI_SCAN_DIR);
                                      # uses the repo's freshly built ./gtk4.so if present, else the installed one
GTK4_SO=gtk4 bin/php-gtk4 script.php  # force the installed extension
php8.4 -n -dextension=gtk4 script.php # minimal: no ini files at all
sudo phpdismod gtk3 && sudo phpenmod gtk4   # switch the system default (Debian/Ubuntu)
```

Editing the version table in `buildall.sh` (`version:enabled`, one line per PHP version) is how a
PHP version is added.

## Credits & inspiration

Thanks to [**php-gtk3**](https://github.com/scorninpc/php-gtk3) by
[Bruno Pitteli Gonçalves](https://github.com/scorninpc) and its contributors, which kept PHP
desktop development alive and set the API shape this project starts from —
including where php-gtk4 departs from it ([docs/DESIGN.md](docs/DESIGN.md), `docs/GTK3-MAP.md`, and
[docs/PORTING.md](docs/PORTING.md) for what a port actually involves).

Thanks also to the [GTK](https://www.gtk.org/) and [GObject](https://docs.gtk.org/gobject/) teams,
and to the [PHP](https://www.php.net/) project, whose `build/gen_stub.php` this repository vendors.

php-gtk3 is LGPL-3.0; php-gtk4 is a from-scratch reimplementation against the native Zend API and
shares no code with it.

## License and warranty

[MIT](LICENSE) — the code here, including everything the generator writes.

Two things in a distribution are somebody else's and keep their own terms, both accounted for in
[THIRD-PARTY-NOTICES.md](THIRD-PARTY-NOTICES.md): `gen/gen_stub.php` is vendored from
[php-src](https://github.com/php/php-src/blob/master/build/gen_stub.php) (PHP License 3.01, full text in
[LICENSES/PHP-3.01.txt](LICENSES/PHP-3.01.txt)), and the documentation in the generated docblocks and
comment blocks is GTK's, GLib's, Pango's and WebKitGTK's own prose under their licences (LGPL, MIT for
graphene). The built extension links the GTK stack dynamically and bundles none of it.

This product includes PHP software, freely available from <http://www.php.net/software/>.

**This software comes with no warranty of any kind.** It is provided *as is*, without warranty
express or implied, including but not limited to merchantability, fitness for a particular purpose
and non-infringement; the authors and copyright holders are not liable for any claim, damages or
other liability arising from it or its use (see [LICENSE](LICENSE) for the binding wording). It
drives a native toolkit in your process: a bug here can take the whole application down. Version
0.x means the API can still change between releases — pin a version, run your own tests, and use
it at your own risk.
