# gen/

- `gen_stub.php` — vendored from php-src (`/usr/lib/php/<api>/build/gen_stub.php`, PHP 8.4). Turns
  `stubs/gtk4.stub.php` into `src/gtk4_arginfo.h`. It downloads PHP-Parser 5.0.0 next to itself on
  first run (`gen/PHP-Parser-5.0.0/`, gitignored).
- `ide-stub.php` — derives `stubs/gtk4.php` (dummy bodies) from the stub source.
- `overrides/` — reserved for the GIR generator (PLAN.md milestone 3): hand-written method bodies
  that replace generated ones. The GIR generator will emit `stubs/gtk4.stub.php` sections and the
  matching `ZEND_METHOD` skeletons.

Run everything via `./ci.sh --only=stubs [--fix]`.
