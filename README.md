# php-gtk4

[![C++ lint](https://github.com/apss-pohl/php-gtk4/actions/workflows/cpp-lint.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/cpp-lint.yml)
[![PHP QA](https://github.com/apss-pohl/php-gtk4/actions/workflows/php-qa.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/php-qa.yml)
[![Build & tests](https://github.com/apss-pohl/php-gtk4/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/apss-pohl/php-gtk4/actions/workflows/tests.yml)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![GTK 4](https://img.shields.io/badge/GTK-4.14%2B-4A86CF?logo=gtk&logoColor=white)](https://www.gtk.org/)
[![C++20](https://img.shields.io/badge/C%2B%2B-20-00599C?logo=cplusplus&logoColor=white)](https://en.cppreference.com/w/cpp/20)

PHP extension binding GTK 4, written in C++20 against the native Zend API (standard `phpize`
build). The design decisions are in [Design](#design) below; what is still open in
[docs/TODO.md](docs/TODO.md).

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

**Diagnostics.** GTK refuses a value it does not accept by logging — `g_return_if_fail()` — which
means your script did something wrong. php-gtk4 turns those into PHP errors at the line that caused
them, rather than leaving them on stderr with no file, no line and no `error_log`:

```text
PHP Warning:  Gtk: gtk_editable_get_chars: assertion 'end_pos == -1 || end_pos >= start_pos'
              failed in /home/you/app.php on line 12
```

`set_error_handler()` catches them like any other PHP error, and `error_reporting`, `error_log`
and `@` all apply. The `gtk4.diagnostics` ini directive picks what happens (changeable at runtime
with `ini_set()`):

| value                | GLib `CRITICAL` | GLib `WARNING` |
| -------------------- | --------------- | -------------- |
| `warning` *(default)*| `E_WARNING`     | `E_WARNING`    |
| `fatal`              | `E_ERROR`       | `E_WARNING`    |
| `stderr`             | GLib's own output, unchanged     ||
| `off`                | dropped                          ||

GTK returns and carries on after a `CRITICAL`, so the default reports without ending your
application. `fatal` is for development and CI, where an unguarded boundary should stop the run.

## Build & install

Requires **PHP 8.4+** (NTS or ZTS) with `php8.4-dev` and `libgtk-4-dev` (GTK ≥ 4.14). Standard `phpize` build:

```sh
phpize8.4 && ./configure --with-php-config=/usr/bin/php-config8.4 && make -j"$(nproc)" && sudo make install
bin/php-gtk4 examples/demo.php            # the demo app: every class, one page at a time
```

Step-by-step installation for **Linux and Windows** — dependencies, prebuilt release binaries,
enabling the extension, troubleshooting — is in [docs/INSTALL.md](docs/INSTALL.md).

Everything else — `ci.sh`/`buildall.sh`, configure options, the sanitizer/coverage variants, and
the **Windows** build (PHP SDK + `config.w32`, GTK 4 from gvsbuild) — is in [docs/BUILD.md](docs/BUILD.md).

## Design

The decisions the extension is built on. `CLAUDE.md` has the working rules that follow from them,
`gen/README.md` the generator's flow, `CHANGELOG.md` what shipped when.

- **Native Zend API, no framework.** PHP-CPP was dropped after a day: it could not express typed
  signatures, enums, attributes or object handlers and forced a non-standard build. The API is
  declared once in a php-src style stub (`src/gtk4.stub.php` and the generated per-namespace
  stubs), `gen_stub.php` derives the arginfo, `phpize`/`config.m4` build it (PIE-installable), and
  the same sources build on Windows through `config.w32`. PHP 8.4+, C++20, GTK 4.14+, CLI only.
- **Most of the code is generated, and the rest was written with an AI.** The class bindings
  (`src/<Ns>/`, the stubs, the smoke tests, the example skeletons, the class map's status) come
  out of `gen/gir.php`; the runtime core, the generator itself, the tests and the documentation
  were written with Claude Code, reviewed and driven by the maintainer. That is why the gates are
  strict and generic: every method is swept with hostile values, every getter checked against its
  declared type, every GLib critical fails a test, clang-tidy treats every finding as an error.
  Read the code with that in mind, and hold changes to the same gates.
- **Generated from GObject-Introspection, hand-written runtime.** `gen/gir.php` emits one `.cpp`
  per class from the installed `.gir` files for an allow-list with its transitive closure; a type
  outside the closure means the member is skipped and reported, never a placeholder. Hand work lives
  in exactly four places: `gen/overrides/` (a method body or a class prelude), `gen/skip.txt` (what
  is deliberately not exposed, each line with its reason), `gen/handwritten.txt` (a class owned by
  hand from then on) and the MINIT of the non-GObject types in `src/gtk4.cpp`. Generated files
  are never edited; CI fails when a fresh run differs.
- **Modern GTK 4 only.** Everything GIR marks deprecated is skipped in favour of its replacement;
  nothing carries `#[\Deprecated]`. `GtkTreeView`, `GtkDialog` and the pixbuf-to-texture calls are
  not bound; the list widgets, the async dialogs and byte-based texture bridges are.
- **The C API's names, in snake_case, one spelling.** `gtk_window_set_title` is `set_title`,
  properties keep GTK's names with underscores (`$win->default_width`), enum cases are CamelCase
  PHP enums, flags are constant classes. No camelCase aliases.
- **One handle per object, identity preserved.** A PHP handle holds a toggle reference on its
  GObject; while GTK holds other references the GObject holds the `zend_object`, so a PHP subclass
  and its state survive `$box->append(new MyButton())` and come back as the same object from
  `get_first_child()`. A qdata back-pointer makes `===` hold. The ownership rule at construction is
  fixed, not per class: floating references are sunk, `transfer full` results adopted, a `GtkRoot`
  belongs to GTK's toplevel list. Boxed structs are value handles (cloneable, compared by value,
  in-place C operations bound as copies); refcounted non-GObject types (`GdkEvent`, cairo,
  `GskRenderNode`) are handles on a registry with the type's ref/unref pair.
- **PHP subclasses are real GTypes.** `class MyWidget extends GtkWidget` registers a GType at its
  first `new`; `vfunc_<name>()` methods override class-struct slots through generated thunks and
  `parent::vfunc_<name>()` chains down to GTK; `implements GListModel` adds the GTK interface to the
  type. Abstract GTK classes are constructible only through a PHP subclass.
- **A Throwable never crosses GLib.** Every trampoline ends by reporting a pending exception to
  `Gtk::set_exception_handler()`; `ExceptionMode::Log` lets GTK continue, `Rethrow` quits the
  running loops and propagates it. GLib's own criticals and warnings are PHP errors
  (`gtk4.diagnostics`), and a critical on an ordinary path is a missing guard, so the test suite
  fails on it.
- **Convert at the boundary, never coerce.** A value GTK would only assert on is refused first with
  the PHP 8 vocabulary: a bad argument is a `ValueError`/`TypeError` naming the parameter, a wrong
  state a `LogicException`, a dead handle an `Error`. The generator emits those checks from tables
  that mirror GTK's own assertions; a value PHP can build must not end the process. A declared
  return type is a promise the engine does not verify for an internal class, so every getter
  returns what it declares and generic sweeps check every method and getter.
- **Values, not GLib shapes, at the PHP side.** Out parameters are return values (several become a
  list; a boolean-plus-outs returns the outs or null); `GError **` throws `Gtk4\GError`; `GBytes` is
  a string, `GVariant` a PHP value, a `GFile` a path, a `GType` a class name, C arrays are lists;
  signals take exactly `(string $signal, callable $handler)` and closures capture their context.
- **Single GUI thread.** ZTS builds keep per-request state in module globals, but GTK stays
  single-threaded and the loop-driving methods assert the thread that ran `Gtk::init()`.
- **WebKitGTK is an optional feature, generated like the rest.** `--enable-gtk4-webkit` compiles
  the `WebKit*` classes (the web view, its settings, session, user content, policy decisions,
  permission requests) and JavaScriptCore's `JSCContext`/`JSCValue` - `evaluate_javascript()`
  answers with a value, a script message arrives as one - from `WebKit-6.0.gir` into
  `src/WebKit/` and `src/JavaScriptCore/`, the two namespaces `CONDITIONAL_NAMESPACES` gates:
  left out of the source glob without the flag, registered under `#ifdef` with it, their tests
  skipped where the build lacks them (`tests/Features.php`), `Gtk4\FEATURES` saying which.
  Linux only; Windows gets WebView2 later.
- **Not carried over from php-gtk3**: varargs trampolines, a fresh wrapper per return, `GdkEvent`
  field copies, `Gtk::main()`, `connect()` user data, raw pointers stored in user data, a
  hand-maintained module table.

## Threads

The extension builds for ZTS PHP and keeps all per-request state per thread, but **GTK is
single-threaded**: only the thread that ran `Gtk::init()` may drive the main loop (the loop-driving
methods throw `Error` from any other thread). Worker threads must never touch widgets; hand results
to the GUI thread via `GLib::idle_add()`. Serving GUI windows from several request threads at once
is not possible with GTK on any language binding. Details and what is still planned:
[docs/BUILD.md § Threads](docs/BUILD.md#threads-and-zts).

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
including where php-gtk4 departs from it ([Design](#design), `docs/GTK3-MAP.md`).

Thanks also to the [GTK](https://www.gtk.org/) and [GObject](https://docs.gtk.org/gobject/) teams,
and to the [PHP](https://www.php.net/) project, whose `build/gen_stub.php` this repository vendors.

php-gtk3 is LGPL-3.0; php-gtk4 is a from-scratch reimplementation against the native Zend API and
shares no code with it.

## License and warranty

[MIT](LICENSE). `gen/gen_stub.php` is vendored from
[php-src](https://github.com/php/php-src/blob/master/build/gen_stub.php) (PHP License 3.01).

**This software comes with no warranty of any kind.** It is provided *as is*, without warranty
express or implied, including but not limited to merchantability, fitness for a particular purpose
and non-infringement; the authors and copyright holders are not liable for any claim, damages or
other liability arising from it or its use (see [LICENSE](LICENSE) for the binding wording). It
drives a native toolkit in your process: a bug here can take the whole application down. Version
0.x means the API can still change between releases — pin a version, run your own tests, and use
it at your own risk.
