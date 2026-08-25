# php-gtk4

PHP extension binding GTK 4, built on PHP-CPP. See `PLAN.md` for the design.

## Build & install

Requires **PHP 8.4+** (older php-configs are rejected by the Makefile). Prerequisites: `libgtk-4-dev`, `php8.4-dev`, and a PHP-CPP static lib built with the same
`php-config` (`PHP-CPP/build-dist.sh` puts it in `PHP-CPP/dist/php8.4/`).

```sh
./buildall.sh                               # build + install for every enabled PHP version (uses sudo)
ONLY=8.4 ./buildall.sh                      # a single version from the table in buildall.sh
PHPCPP_BASE=/path/to/PHP-CPP/dist ./buildall.sh

# manual, without installing
make PHP_CONFIG=/usr/bin/php-config8.4 PHPCPP_STATIC=/path/libphpcpp.a.2.4.16 -j"$(nproc)"
make test                                   # tests/*.php under xvfb-run against ./gtk4.so
php8.4 -n -dextension=./gtk4.so examples/hello.php
```

Editing the version table in `buildall.sh` (`version:php-config:ini-dir:with_webkit:enabled`) is
how a PHP version is added. `build-*.sh` are gitignored personal wrappers.
