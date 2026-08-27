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

## Constructor ownership rule (for the generator)

Which `attach*()` a generated constructor emits is decided by the return type, not by GIR's
`transfer` (which is `none` for both `gtk_window_new()` and `gtk_button_new()`):

| constructor returns                              | emit          | why                                  |
|--------------------------------------------------|---------------|--------------------------------------|
| a `GInitiallyUnowned` subclass (floating)        | `attach_new()` | sinks the floating ref, handle owns it |
| a `GtkRoot` implementor (`GtkWindow`, …)         | `attach()`     | GTK's toplevel list owns the initial ref |
| a plain `GObject`, `transfer="full"`             | `attach_new()` | adopts the returned ref              |
| anything else                                    | error          | hand-write it in `overrides/`        |

`GtkRoot` is read from GIR (`implements`), never from a name list. Wave 0 must diff its choice
against the 27 hand-written constructors before anything else is generated; `attach_new()` also
catches a root at runtime with a `g_critical`.

Run everything via `./ci.sh --only=stubs [--fix]`.
