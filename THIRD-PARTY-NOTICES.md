# Third-party notices

php-gtk4 is [MIT](LICENSE). It also redistributes one file that is not, carries documentation text that
belongs to the libraries it binds, and links those libraries at runtime. This file is that accounting. It
travels with every distribution of php-gtk4 — the source tarball, the release archives and the
`php-gtk4/stubs` package.

## PHP, in `gen/gen_stub.php`

`gen/gen_stub.php` is vendored from php-src (`build/gen_stub.php`), Copyright (c) The PHP Group, licensed
under the PHP License, version 3.01 — full text in [LICENSES/PHP-3.01.txt](LICENSES/PHP-3.01.txt), not
covered by this repository's MIT licence. It turns `src/gtk4.stub.php` and the generated
`src/<Ns>/<Ns>.stub.php` into arginfo headers at build time; no part of it ends up in `gtk4.so` or
`php_gtk4.dll`.

This product includes PHP software, freely available from <http://www.php.net/software/>.

## Documentation text from GTK and the libraries around it

The class bindings are generated from the GObject-Introspection data those libraries install
(`/usr/share/gir-1.0/*.gir`). The generator copies each member's documentation along with its signature, so
the docblocks in `stubs/gtk4.php` and `src/<Ns>/<Ns>.stub.php`, the comment blocks in `src/<Ns>/*.cpp` and
the summary line of a generated page in `examples/` are **the libraries' own sentences** — reflowed, with
the markup rewritten to PHP's vocabulary (`@width` → `$width`, `%TRUE` → `true`, `#GtkWidget` →
`` `GtkWidget` ``).

Those sentences live in the libraries' source files and carry those files' licence. They belong to their
authors and are *not* covered by this repository's MIT licence:

| Namespaces bound here | Library | Licence, as the project declares it |
| --- | --- | --- |
| `GLib`, `GObject`, `Gio` | GLib | LGPL-2.1-or-later |
| `Gdk`, `Gsk`, `Gtk` | GTK 4 | LGPL-2.1-or-later |
| `GdkPixbuf` | gdk-pixbuf | LGPL-2.1-or-later |
| `Pango` | Pango | LGPL-2.0-or-later |
| `Graphene` | graphene | MIT |
| `Soup` | libsoup | LGPL-2.0-or-later |
| `WebKit`, `JavaScriptCore` | WebKitGTK | LGPL-2.1-or-later, with BSD-2-Clause for parts of JavaScriptCore |

Sources: <https://gitlab.gnome.org/GNOME/gtk>, <https://gitlab.gnome.org/GNOME/glib>,
<https://gitlab.gnome.org/GNOME/pango>, <https://gitlab.gnome.org/GNOME/gdk-pixbuf>,
<https://github.com/ebassi/graphene>, <https://gitlab.gnome.org/GNOME/libsoup>,
<https://github.com/WebKit/WebKit>.

The signatures themselves are the libraries' interface, not their expression, and the code that implements
them here is this project's own — the notice above is about the prose.

## Libraries the built extension links

`gtk4.so` and `php_gtk4.dll` link the GTK 4 stack **dynamically** (`pkg-config` at configure time). No
library code is copied into the extension, and a release archive holds the extension binary alone: GTK is
[installed separately](https://github.com/apss-pohl/php-gtk4/blob/main/docs/INSTALL.md). GTK, GLib, Pango,
gdk-pixbuf, cairo and libsoup are LGPL, and the shared-library mechanism is what satisfies the LGPL's
relinking condition — the copy of GTK on the machine is the one that runs, and replacing it replaces it
for php-gtk4 too.

Linked at runtime: GTK 4, GLib/GObject/Gio, Pango (LGPL-2.0-or-later), gdk-pixbuf, cairo (LGPL-2.1 or
MPL-1.1), graphene (MIT), HarfBuzz (MIT), and — only in a build with `--enable-gtk4-webkit` — WebKitGTK,
JavaScriptCore and libsoup.

## Not redistributed

Downloaded or installed by the build and never part of a distribution: php-src's `run-tests.php` (PHP
License 3.01, written by `phpize`), nikic/PHP-Parser (BSD-3-Clause, fetched by `gen/gen_stub.php` for the
stub step), the Composer dev dependencies in `vendor/` (MIT and BSD-3-Clause) and `markdownlint-cli2`
(MIT). The GIR files the generator reads are part of the libraries above and are read, never copied.
