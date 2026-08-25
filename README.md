# php-gtk4

[![C++ lint](https://github.com/apss-pohl/php-gtk4/actions/workflows/cpp-lint.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/cpp-lint.yml)
[![PHP QA](https://github.com/apss-pohl/php-gtk4/actions/workflows/php-qa.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/php-qa.yml)
[![Build & tests](https://github.com/apss-pohl/php-gtk4/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/tests.yml)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![GTK 4](https://img.shields.io/badge/GTK-4.14%2B-4A86CF?logo=gtk&logoColor=white)](https://www.gtk.org/)
[![C++20](https://img.shields.io/badge/C%2B%2B-20-00599C?logo=cplusplus&logoColor=white)](https://en.cppreference.com/w/cpp/20)

PHP extension binding GTK 4, built on [PHP-CPP](https://github.com/apss-pohl/PHP-CPP) (fork with the const-heap fix). See `PLAN.md` for the design.

## Usage

```php
<?php
use Gtk4\{Gtk, GtkWindow};

Gtk::init();
$win = new GtkWindow();
$win->set_title('php-gtk4');
$win->connect('close-request', function () { Gtk::main_quit(); return false; });
$win->present();
Gtk::main();
```

## Build & install

Requires **PHP 8.4+** (8.4 and 8.5 are tested; older php-configs are rejected by the Makefile). Prerequisites: `libgtk-4-dev`, `php8.4-dev`, and a PHP-CPP static lib built with the same
`php-config` (`PHP-CPP/build-dist.sh` puts it in `PHP-CPP/dist/php8.4/`).

```sh
./buildall.sh                               # build + install for every enabled PHP version (uses sudo)
ONLY=8.4 ./buildall.sh                      # a single version from the table in buildall.sh
PHPCPP_BASE=/path/to/PHP-CPP/dist ./buildall.sh

# manual, without installing
make PHP_CONFIG=/usr/bin/php-config8.4 PHPCPP_STATIC=/path/libphpcpp.a.2.4.16 -j"$(nproc)"
php8.4 /usr/local/bin/composer install     # once, for PHPUnit
./ci.sh                                     # cpp-lint, php-qa, build, load, test - same as GitHub Actions
./ci.sh --only=test --filter SignalTest    # single stage / single test class; --fix applies all auto-fixes
make test                                   # PHPUnit under xvfb-run against ./gtk4.so (./tests/run.sh --filter X for one)
bin/php-gtk4 examples/example.php          # canonical showcase of every element
```

## Contributing

A new element (class/method/constant) is only done when it comes with tests in `tests/`, a typed
entry in `stubs/gtk4.php`, and a use in `examples/example.php`; `make test` enforces the last two.

## Coexisting with php-gtk3

All PHP-visible names live in the **`Gtk4` namespace** (`use Gtk4\{Gtk, GtkWindow};`,
`Gtk4\PHPGTK_VERSION`), so gtk3 and gtk4 can be *installed* side by side and never hijack each
other's class names.

They still cannot be *used* in one process: libgtk-3 and libgtk-4 export the same C symbols, and
the dynamic linker binds every `gtk_*` call to whichever library loaded first — with gtk3 loaded,
gtk4 calls land in GTK 3 and fail. gtk4 prints a warning at startup when it sees gtk3, and
`buildall.sh` installs `gtk4.ini` without enabling it globally. Pick one per process:

```sh
bin/php-gtk4 script.php               # gtk4 only; keeps all other extensions (filters gtk3 via PHP_INI_SCAN_DIR)
GTK4_SO=./gtk4.so bin/php-gtk4 t.php  # same, with a fresh uninstalled build
php8.4 -n -dextension=gtk4 script.php # minimal: no ini files at all
sudo phpdismod gtk3 && sudo phpenmod gtk4   # switch the system default (Debian/Ubuntu)
```

Editing the version table in `buildall.sh` (`version:php-config:ini-dir:with_webkit:enabled`) is
how a PHP version is added.
