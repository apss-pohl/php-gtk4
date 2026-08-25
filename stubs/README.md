# stubs/

`gtk4.php` — IDE stub, **generated** from the API declaration `src/gtk4.stub.php` by
`gen/ide-stub.php` (same declarations with dummy bodies so Intelephense/PhpStorm stay quiet,
plus `__get/__set/__isset` on `GObject` so `@property` tags are honoured). Do not edit;
`./ci.sh --only=stubs --fix` regenerates it together with `src/gtk4_arginfo.h`.

**VS Code (Intelephense)**: any `.php` in the workspace is indexed. For a project outside this
repo add to `settings.json`:
```json
{ "intelephense.environment.includePaths": ["/mnt/share/dev/code/php-gtk4/stubs"] }
```
**PhpStorm**: Settings → PHP → Include Path → add this directory.

`tests/StubsTest.php` fails if `gtk4.php` is stale or disagrees with `ReflectionExtension('gtk4')`.
