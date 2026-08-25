# stubs/

`gtk4.php` declares every class, method, parameter/return type and constant the extension
provides, for IDE completion and diagnostics. It is never executed.

- **VS Code (Intelephense)**: any `.php` in the workspace is indexed, so a project that vendors or
  symlinks this directory needs nothing else. For a project outside this repo add it to
  `intelephense.environment.includePaths` in `settings.json`:
  ```json
  { "intelephense.environment.includePaths": ["/mnt/share/dev/code/php-gtk4/stubs"] }
  ```
- **PhpStorm**: Settings → PHP → Include Path → add this directory.

`tests/StubsTest.php` compares the stub's classes and methods against `ReflectionExtension('gtk4')` of
the built extension, so drift fails `make test`.
