# Changelog

All notable changes to php-gtk4. Format: [Keep a Changelog](https://keepachangelog.com/),
versions follow [SemVer](https://semver.org/). The version lives in `VERSION` at the repo root and is
mirrored into `src/php_gtk4.h`, `src/gtk4.stub.php` and the built module by `./ci.sh --only=version`
— see docs/RELEASING.md.

## [Unreleased]

### Added

- Native Zend API runtime (`GObject` handles with property access, `GValue`/`GVariant`/boxed
  marshalling, GClosure-based signals, `emit()`, exception boundary with `ExceptionMode`).
- Classes: `GObject`, `GParamSpec`, `GtkWidget`, `GtkWindow`, `GtkButton`, `GtkLabel`,
  `GtkApplication`, `GMainLoop`, `GLib` (sources), `GSimpleAction` + `GAction`/`GActionMap`/
  `GActionGroup`, `GdkRGBA`, `GdkRectangle`, `Gtk`, `ExceptionMode`, `GError`, `GdkTexture`,
  `PhpValue`, `GListStore` + `GListModel`, `GtkDrawingArea` + `CairoContext`, `GtkFilter`/
  `GtkCustomFilter`/`GtkFilterListModel`, `GtkSorter`/`GtkCustomSorter`/`GtkSortListModel`,
  enums `GtkAlign`, `GtkOrientation`, `GtkFilterChange`, `GtkSorterChange`, flags `GApplicationFlags`.
- Tooling: `ci.sh` stages, sanitizer/valgrind/coverage runs, stub-driven arginfo, IDE stub and
  method comments, git hooks, Dependabot.

## Release checklist

Full details in docs/RELEASING.md. `VERSION` is the only trigger — there is no tag to push.

1. `VERSION`: drop the `-dev` suffix (`0.2.0-dev` → `0.2.0`).
2. `./ci.sh --only=version,stubs --fix` — propagates into `src/php_gtk4.h` and `src/gtk4.stub.php`.
3. Move the Unreleased entries under `## [0.2.0] - YYYY-MM-DD`. The release workflow copies that
   section into the GitHub release body and fails if it is missing.
4. `./ci.sh --with=asan,coverage,valgrind` green.
5. Merge as a `release: 0.2.0` PR — `.github/workflows/release.yml` tags, builds and publishes.
6. Follow-up PR: `VERSION` → `0.3.0-dev`, `./ci.sh --only=version,stubs --fix`, fresh `## [Unreleased]`.

Between releases every merge into `main` publishes a `vX.Y.Z-dev.<run>` pre-release instead; the
newest five stay downloadable.
