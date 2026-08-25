# stubs/

- `gtk4.stub.php` — **the API declaration, single source of truth** (php-src stub format: typed
  signatures, docblocks, `@generate-class-entries`). Edit this.
- `gtk4.php` — IDE stub **generated** by `gen/ide-stub.php` (same declarations with dummy bodies so
  Intelephense/PhpStorm stay quiet). Do not edit; `./ci.sh --only=stubs --fix` regenerates it and
  `src/gtk4_arginfo.h`.

**VS Code (Intelephense)**: any `.php` in the workspace is indexed. For a project outside this
repo add to `settings.json`:
```json
{ "intelephense.environment.includePaths": ["/mnt/share/dev/code/php-gtk4/stubs"] }
```
**PhpStorm**: Settings → PHP → Include Path → add this directory.

`tests/StubsTest.php` fails if `gtk4.php` is stale or disagrees with `ReflectionExtension('gtk4')`.
