# Changelog

All notable changes to php-gtk4. Format: [Keep a Changelog](https://keepachangelog.com/),
versions follow [SemVer](https://semver.org/); `Gtk4\VERSION` and the module version are one value
(`PHP_GTK4_VERSION` in `src/php_gtk4.h`, mirrored in `src/gtk4.stub.php` — `ExtensionTest` checks).

## [Unreleased]

### Added
- Native Zend API runtime (`GObject` handles with property access, `GValue`/`GVariant`/boxed
  marshalling, GClosure-based signals, `emit()`, exception boundary with `ExceptionMode`).
- Classes: `GObject`, `GParamSpec`, `GtkWidget`, `GtkWindow`, `GtkButton`, `GtkLabel`,
  `GtkApplication`, `GMainLoop`, `GLib` (sources), `GSimpleAction` + `GAction`/`GActionMap`/
  `GActionGroup`, `GdkRGBA`, `GdkRectangle`, `Gtk`, `ExceptionMode`.
- Tooling: `ci.sh` stages, sanitizer/valgrind/coverage runs, stub-driven arginfo, IDE stub and
  method comments, git hooks, Dependabot.

## Release checklist
1. Bump `PHP_GTK4_VERSION` (`src/php_gtk4.h`) and `VERSION` (`src/gtk4.stub.php`), regenerate
   (`./ci.sh --only=stubs --fix`), move Unreleased entries under the new version + date.
2. `./ci.sh --with=asan,coverage,valgrind` green; CI green on `main`.
3. `git tag -s vX.Y.Z && git push --tags`; `pie`/PECL packaging follows the standard phpize layout.
