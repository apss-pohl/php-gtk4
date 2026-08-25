# gen/

- `gen_stub.php` — vendored from php-src (`/usr/lib/php/<api>/build/gen_stub.php`, PHP 8.4). Turns
  `src/gtk4.stub.php` into `src/gtk4_arginfo.h`. It downloads PHP-Parser 5.0.0 next to itself on
  first run (`gen/PHP-Parser-5.0.0/`, gitignored).
- `ide-stub.php` — derives `stubs/gtk4.php` (dummy bodies) from the stub source.
- `method-comments.php` — writes the comment block above every `ZEND_METHOD` from the stub's
  signature/docblock; `--check` also reports any other C++ function without a comment.
- The GIR generator (docs/PLAN.md milestone 3) will live here and emit `src/gtk4.stub.php`
  sections, `ZEND_METHOD` skeletons and the MINIT block; hand-written overrides get a directory
  when it exists.

Run everything via `./ci.sh --only=stubs [--fix]`.
