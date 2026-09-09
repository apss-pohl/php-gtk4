# php-gtk4/stubs

IDE and static-analysis stubs for the [php-gtk4](https://github.com/apss-pohl/php-gtk4) extension:
every class, method, enum, constant and `@property` of the `Gtk4\` namespace, with the same
signatures and docblocks the extension registers, and dummy bodies so PhpStorm, Intelephense and
PHPStan can read them.

```sh
composer require --dev php-gtk4/stubs
```

The package version equals the extension version it describes: `php-gtk4/stubs` `0.3.0` is the
API of php-gtk4 `0.3.0`.

**Never load this file.** The extension defines the classes at runtime and the stub would
redeclare every one of them, so the package deliberately has no `autoload` section. It is read
by tools, not by PHP.

## Licence

MIT, like the extension (`LICENSE`). One thing in this package is not: the docblocks are GTK's, GLib's,
Pango's and WebKitGTK's own documentation, copied out of their GObject-Introspection data by the generator
and reflowed — their authors' text under their licences (LGPL, MIT for graphene). `THIRD-PARTY-NOTICES.md`
next to this file is the accounting.

## PHPStan

With [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer) nothing
else is needed: `extension.neon` registers `gtk4.php` under `scanFiles`, so `Gtk4\GtkButton` resolves
whether or not the extension is loaded in the PHP that runs PHPStan. Without the installer:

```neon
includes:
    - vendor/php-gtk4/stubs/extension.neon
```

## IDEs

PhpStorm and Intelephense index `vendor/` on their own, so `composer require` is all it takes.
For a project that does not use Composer, point the IDE at the directory instead:

- **VS Code (Intelephense)**: `"intelephense.environment.includePaths": ["/path/to/stubs"]`
  in `settings.json`.
- **PhpStorm**: Settings → PHP → Include Path → add the directory.

## For php-gtk4 developers

`gtk4.php` is **generated** from the hand-written `src/gtk4.stub.php` and every generated
`src/<Ns>/<Ns>.stub.php` by `gen/ide-stub.php` (same declarations with dummy bodies, plus
`__get/__set/__isset` on `GObject` so `@property` tags are honoured). Do not edit;
`./ci.sh --only=stubs --fix` regenerates it together with `src/gtk4_arginfo.h`, and
`tests/StubsTest.php` fails if it is stale or disagrees with `ReflectionExtension('gtk4')`.

This directory *is* the package: `release.yml` copies it into the
[php-gtk4-stubs](https://github.com/apss-pohl/php-gtk4-stubs) repository and tags it with the
extension's version on every real release (`docs/RELEASING.md` "The stubs package"). Nothing in
that repository is edited by hand.
