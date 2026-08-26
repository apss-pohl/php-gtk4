# php-gtk4

[![C++ lint](https://github.com/apss-pohl/php-gtk4/actions/workflows/cpp-lint.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/cpp-lint.yml)
[![PHP QA](https://github.com/apss-pohl/php-gtk4/actions/workflows/php-qa.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/php-qa.yml)
[![Build & tests](https://github.com/apss-pohl/php-gtk4/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/tests.yml)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![GTK 4](https://img.shields.io/badge/GTK-4.14%2B-4A86CF?logo=gtk&logoColor=white)](https://www.gtk.org/)
[![C++20](https://img.shields.io/badge/C%2B%2B-20-00599C?logo=cplusplus&logoColor=white)](https://en.cppreference.com/w/cpp/20)

PHP extension binding GTK 4, written in C++20 against the native Zend API (standard `phpize`
build). See `docs/PLAN.md` for the design.

## Usage

```php
<?php
use Gtk4\{Gtk, GtkApplication, GtkWindow};

$app = new GtkApplication('org.example.Hello');
$app->connect('activate', function (GtkApplication $app): void {
    $win = new GtkWindow($app);
    $win->title = 'php-gtk4';            // GObject properties are PHP properties
    $win->set_default_size(300, 200);
    $win->connect('close-request', fn() => false);
    $win->present();
});
exit($app->run($argv));
```

Actions: `new GSimpleAction('quit')` + `$app->add_action()` — reachable as `app.quit` from widgets
(`$button->activate_action('app.quit')`); GVariant parameters/states are plain PHP values.
Exceptions thrown in handlers are logged by default; `Gtk::set_exception_mode(ExceptionMode::Rethrow)`
makes them propagate out of `run()` instead. `Gtk4\GMainLoop` + `Gtk4\GLib::timeout_add()` cover
scripts without windows.

## Build & install

Requires **PHP 8.4+** (8.4 and 8.5 are tested) with the dev package (`php8.4-dev`: phpize,
php-config) and `libgtk-4-dev` (GTK ≥ 4.14).

```sh
phpize8.4 && ./configure --with-php-config=/usr/bin/php-config8.4 && make -j"$(nproc)"
sudo make install                           # or: ./buildall.sh (builds + installs every enabled PHP version)
./ci.sh                                     # stubs, cpp-lint, md-lint, php-qa, build, load, test, phpt
./ci.sh --only=test --filter SignalTest    # single stage / single test class; --fix applies all auto-fixes
./ci.sh --only=phpt                        # php-src run-tests.php over tests/phpt (stderr, fatals, RSHUTDOWN)
bin/php-gtk4 examples/demo.php            # the demo app: every class, one page at a time
bin/php-gtk4 examples/GtkButton.php       # the same source, just that class (examples/README.md)
```

After cloning: `composer install` and `git config core.hooksPath .githooks` (pre-commit = fast QA,
pre-push = full `./ci.sh`).

Configure options: `--enable-gtk4-webkit`, `--enable-gtk4-sanitize`, `--enable-gtk4-coverage`.
The API is declared in `src/gtk4.stub.php`; `src/gtk4_arginfo.h` and the IDE stub `stubs/gtk4.php`
are generated from it (`./ci.sh --only=stubs --fix`).

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
including where php-gtk4 departs from it (`docs/PLAN.md`, `docs/GTK3-MAP.md`).

Thanks also to the [GTK](https://www.gtk.org/) and [GObject](https://docs.gtk.org/gobject/) teams,
and to the [PHP](https://www.php.net/) project, whose `build/gen_stub.php` this repository vendors.

php-gtk3 is LGPL-3.0; php-gtk4 is a from-scratch reimplementation against the native Zend API and
shares no code with it.

## License

[MIT](LICENSE). `gen/gen_stub.php` is vendored from
[php-src](https://github.com/php/php-src/blob/master/build/gen_stub.php) (PHP License 3.01).
