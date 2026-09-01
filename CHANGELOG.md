# Changelog

All notable changes to php-gtk4. Format: [Keep a Changelog](https://keepachangelog.com/),
versions follow [SemVer](https://semver.org/). The version lives in `VERSION` at the repo root and is
mirrored into `src/php_gtk4.h`, `src/gtk4.stub.php` and the built module by `./ci.sh --only=version`
— see docs/RELEASING.md.

## [Unreleased]

### Security

- Two more ways a well-typed PHP value ended the process, both found by widening
  `RobustnessTest` (see *Added*) to sweep the classes it used to skip:
  - **`GtkEditable::get_chars()` with `end < start`** - and every other method returning an
    owned string GIR marks non-nullable - read GTK's NULL result as a PHP string: SIGSEGV.
    The generator emits the same NULL guard the borrowed-string path already had, so a failed
    GTK precondition yields `''`. `GtkEntry`, `GtkText`, `GtkTextBuffer`, `GtkTextIter`,
    `GtkCssProvider` and `PangoFontDescription` were all reachable this way.
  - **`GtkTextIter::set_line_index(-1)` / `set_line_offset(-1)`** is a `g_error()` inside GTK
    ("Byte index -1 is off the end of the line"), which aborts. Both are guarded by hand
    (`gen/overrides/Gtk.TextIter.set_line_*.cpp`) and refuse a negative index with a
    `ValueError`; one past the end of the line is GTK's own business and still clamps.

- Type review of 2026-08-30, the second half of the argument-boundary work:
  - **Property writes are typed.** `$win->title = 'x'` was the one API surface with no type
    checking at all: `$win->default_width = 'garbage'` stored 0, `PHP_INT_MAX` stored -1, and
    `declare(strict_types=1)` had no effect. `core/marshal` now converts exactly like the
    equivalent setter's parameter - strict where the assignment is written, weak coercion
    otherwise - and applies the C type's range. `$win->set_default_size('garbage', 1)` and
    `$win->default_width = 'garbage'` finally agree.
  - **`GtkPopover::popup()` on an unparented popover** asked GDK for a popup surface with a
    NULL parent and segfaulted; it is a `LogicException` now (`gen/overrides`).
    `GCancellable::release_fd()` aborted the process unless `get_fd()` was called first and the
    refcount is not observable from PHP, so it is in `gen/skip.txt`.
  - **Flags arguments are checked against their own mask** (`check_flags`): `new
    GtkApplication($id, 1 << 30)` was accepted, printed a GLib `CRITICAL` and ran with default
    flags. The property path already did this.
  - **`GtkEntry`/`GtkPasswordEntry`/`GtkSpinButton::get_delegate()` broke their own signature**:
    they declare `?GtkEditable` and returned a plain `GtkWidget`, because `GtkText` was unbound
    and `wrap()` stops at the nearest registered *class*. PHP does not verify the return types
    of internal functions, so nothing caught it. `Gtk.Text` is bound now and
    `TypeDeclarationTest` calls every arg-less getter and compares what came back with what was
    declared.

- The PHP -> C argument boundary is checked instead of trusted (security review, 2026-08-30;
  `check_utf8`/`check_range` in `src/php_gtk4.h`, emitted by the generator into every binding and
  applied in `core/marshal` for property writes and signal arguments):
  - **A self-referential or very deeply nested array no longer crashes the process.**
    `$a = [1]; $a[] = &$a;` passed to an action recursed until the C stack was gone (SIGSEGV), as
    did 20 000 levels of nesting. `core/variant` now refuses both with a `ValueError` - a cycle
    guard like Zend's own array walkers use, and a depth cap of 64 (a GVariant cannot be deeper).
  - **Strings with an embedded NUL or invalid UTF-8 are rejected.** A GLib string ends at the
    first NUL, so `set_title("safe\0evil")` stored `safe` while PHP still held the whole string;
    invalid UTF-8 tripped an assertion inside GLib that dropped the value with only a `CRITICAL`
    on stderr. Both are `ValueError` now. `GBytes` parameters stay binary and are not validated.
  - **Integer arguments are range-checked.** `$store->remove(-1)` reached
    `g_list_store_remove()` as 4294967295 and `set_size_request(PHP_INT_MAX, …)` truncated to -1
    ("natural size"); both are `ValueError`. Signed parameters still take -1.
- The gvsbuild GTK archive every Windows binary links against is pinned by content
  (`GVSBUILD_SHA256` in `windows-build.yml`, verified before unpacking and again when the Actions
  cache serves the tree) - a release asset is mutable by its owner, and the cache made one bad
  download persistent.

### Added

- Three `tests/phpt` cases for runtime paths the PHPUnit suite structurally cannot reach: the
  origin a throw inside an **async callback** is blamed on (stderr only), and what request
  shutdown does with a **live PHP subclass** in a widget tree and with an **armed I/O watch**
  (including one whose stream PHP closed under it) - both crash shapes that would take the
  PHPUnit runner down with them rather than failing a test.

- **`RobustnessTest` sweeps the whole surface, not the handful of classes it could name.** It
  used to skip any class its hand-written instance map did not list - 3262 of its 4174 cases,
  including all of `GtkTextIter`, `GtkSpinButton`, `GtkBitset` and `GApplication`. It now builds
  an instance of anything with a no-argument constructor, takes the GTK-owned handles from their
  owner (a `GtkTextIter` from its buffer, a `GtkStackPage` from its stack) and fills the
  arguments it is not attacking with a real value of the declared type, so the method bodies run.
  That is 6.5 points of C++ line coverage and the two crashes above.

- Wave 7 — **list models and views** (docs/PLAN.md §3): `GtkListView`, `GtkGridView`,
  `GtkColumnView` (+ `GtkColumnViewColumn`), the factories that build their rows
  (`GtkListItemFactory`, `GtkSignalListItemFactory`, `GtkListItem`), the selection models
  (`GtkSelectionModel` + `GtkSingleSelection`, `GtkMultiSelection`, `GtkNoSelection`) with
  `GtkBitset` as the set of selected positions, and the tree stack (`GtkTreeListModel`,
  `GtkTreeListRow`, `GtkTreeExpander`) — GTK 4's replacement for `GtkTreeView` and
  `GtkCellRenderer`. `GtkScrollInfo` came with them and unlocked `scroll_to()` on the views and
  on `GtkViewport`.
- **A tree unfolds through a PHP callable.** `new GtkTreeListModel($root, $passthrough,
  $autoexpand, $createFunc)` (`gen/overrides/Gtk.TreeListModel.*`) asks
  `function (GObject $item): ?GListModel` for an item's children, null for a leaf; anything else
  is a `TypeError` through the exception boundary and the row stays a leaf. The model has no API
  to take the callable back, so it also keeps it as qdata and request teardown releases it there.
- **A transfer-full boxed argument is copied, not handed over.** `scroll_to($pos, $flags, $info)`
  passed the handle's own `GtkScrollInfo` to GTK, which unreffed it — the next use of the handle
  read freed memory and the process aborted at shutdown. The generator now emits a
  `g_boxed_copy()` for every boxed parameter GIR marks `transfer full`.
- **`clone` and `==` on an opaque boxed record mean something.** A refcounted record registers
  `ref()` as its boxed copy, so `clone $bitset` used to alias the original; such a type now clones
  through its own `copy()` (`BoxedClass::copy`). Comparison goes through the type's own equality
  where it has one (`BoxedClass::equal`: `GtkBitset::equals`, `GtkTextIter::equal`,
  `PangoFontDescription::equal`) — two iterators at different offsets used to compare equal
  because neither has public fields — and by identity for a record with neither.
- The generated fallback class for an interface (`wrap()`'s answer for a GTK-private
  implementation) now also implements the methods the interface *inherits*: without them
  `GtkSelectionModelObject` was abstract and `GtkNotebook::get_pages()` could not be wrapped at all.

- Wave 6 — **text** (docs/PLAN.md §3): `GtkTextView`, `GtkTextBuffer`, `GtkTextIter`,
  `GtkTextMark`, `GtkTextTag`, `GtkTextTagTable` and `GtkWrapMode` (+ the search-flags and
  window-type enums the signatures name). `GtkTextIter` is the first boxed record with a real
  method surface, and it **holds its buffer**: a boxed handle may now own a reference to the object
  it points into (`BOXED_OWNERS` in `gen/gir/config.php`), because an iter kept after the script
  dropped the buffer read freed memory. The insert family (`insert()`, `insert_at_cursor()`,
  `insert_markup()`, `set_text()` and the interactive pair) takes a **byte count that must lie
  inside the string and end on a UTF-8 character boundary** — GTK reads exactly `len` bytes and
  trusts them — defaulting to -1, the whole string, wherever the count is the trailing parameter.
  `GtkTextView::get_extra_menu()` is declared `?GMenuModel`: the 4.14 GIR omits the nullable its
  own documentation states.
- Wave 5 — **dialogs** (docs/PLAN.md §3): `GtkFileDialog`, `GtkFileFilter`, `GtkColorDialog`,
  `GtkFontDialog` and `GtkAboutDialog`, with `PangoFontDescription` (a boxed value: clone copies,
  `equal()` compares), `GtkLicense` and the Pango enums a description uses. GTK 4.10's dialogs are
  async — `open()`/`choose_rgba()`/`choose_font()` return at once and `*_finish()` turns the
  `GAsyncResult` into the answer or throws the `GError`.
- **A `GFile` is a path string.** `Gio.File` parameters take a path or a URI
  (`g_file_new_for_commandline_arg()`) and returns answer with the local path, falling back to the
  URI — a GFile carries nothing else a PHP program can use while the GIO file stack is unbound.
  It also unlocked `GtkPicture::set_file()`/`get_file()`.

- GitHub issue forms (`.github/ISSUE_TEMPLATE/`): a bug form that requires the versions
  (`Gtk4\VERSION`/`BUILD_INFO`/`FEATURES`, `php -v`, `pkg-config --modversion gtk4`), the OS, the
  display server, how the extension was installed, a single-file reproduction script, the expected
  behaviour and the output; a feature form that requires the GTK symbol, the use case and a
  documentation link. Blank issues are disabled and `IssueTemplateTest` guards the required fields.

- Debugging the examples with Xdebug: `.vscode/launch.json` (F5 on an example file debugs its page
  through `examples/demo.php`, plus configurations for the whole demo, an arbitrary script, one
  filtered PHPUnit test and a listen-only session), `.vscode/tasks.json` (build, run, `ci.sh`) and
  `bin/php-gtk4-debug` for the same from a terminal. Breakpoints inside signal handlers and GLib
  callbacks work; `docs/CONTRIBUTING.md` "Debugging" has the caveats.

- Conventional Commits are mandatory (`docs/CONTRIBUTING.md` "Commit messages") and become the
  release notes. `bin/commit-lint` enforces the format from three places — the `commit-msg` hook,
  `./ci.sh --only=commits` (the branch's unpushed commits, or `COMMIT_LINT_RANGE`) and a `commits`
  job in `php-qa.yml` that also checks the PR title, because a squash merge turns the title into the
  commit. `bin/release-notes` groups the commits since the previous release by type and
  `release.yml` appends that to every release body, after the `CHANGELOG.md` section.

- `GLib::io_add_watch($stream, $condition, $callback)` + `GIOCondition`: a socket stream on the
  default main context - the callback runs with the stream and the condition bits when it is
  readable/writable/hung up, returning true keeps the watch; `source_remove()` ends it. PHP keeps
  the descriptor. ext-sockets `\Socket`s join through `socket_export_stream()`.
- Wave 4 (menus/actions, docs/PLAN.md §3): `GMenuModel`, `GMenu`, `GMenuItem`, `GtkPopover`,
  `GtkPopoverMenu`, `GtkPopoverMenuBar`, `GtkMenuButton` (`set_create_popup_func(callable)`),
  `GtkHeaderBar`, `GtkApplicationWindow`, `GtkApplication::set_menubar()`, `GtkArrowType`,
  `GtkPopoverMenuFlags` — generated, `MenuTest`. A menu is a `GMenu` of detailed action names
  (`app.quit`, `win.save`) shown by a popover menu bar, a menu button or a popover; the actions
  are `GSimpleAction`s on the application or the application window; `set_menubar()` works once
  the application is registered (from `startup` on), like the action queries.
- `wrap()` falls back to a generated `<Interface>Object` class (`GListModelObject`, ...) for a
  GTK-private class whose only registered face is an interface: `GtkNotebook::get_pages()` is a
  usable list instead of a bare `GObject`. Fundamental handles keep
  identity (`===` for the same `GdkEvent`/`GParamSpec` while PHP holds it), and `GdkEventSequence`
  is bound as an opaque identity, which unlocks every per-sequence `GtkGesture` method.
- Wave 3 (event controllers, docs/PLAN.md §3): `GtkEventController`, `GtkEventControllerKey`,
  `GtkEventControllerMotion`, `GtkEventControllerScroll`, `GtkEventControllerFocus`,
  `GtkEventControllerLegacy`, `GtkGesture`, `GtkGestureSingle`, `GtkGestureClick`, `GtkGestureDrag`,
  `GtkGestureLongPress`, `GtkGestureSwipe`, `GtkGesturePan`, `GtkGestureZoom`, `GtkGestureRotate`
  with `GtkPropagationPhase`, `GtkPropagationLimit`, `GtkEventControllerScrollFlags`,
  `GtkPanDirection`, `GtkEventSequenceState`, `GdkScrollUnit` — generated — and the `GdkEvent`
  family hand-written on the fundamental registry: `GdkEvent` (type, time, modifier state,
  position, display, context-menu test) with `GdkKeyEvent`, `GdkButtonEvent`, `GdkScrollEvent`,
  `GdkCrossingEvent`, `GdkFocusEvent`, `GdkTouchEvent`, `GdkTouchpadEvent`, `GdkPadEvent`,
  `GdkGrabBrokenEvent` and the enums `GdkEventType`, `GdkScrollDirection`, `GdkCrossingMode`,
  `GdkNotifyType`, `GdkTouchpadGesturePhase`, `GdkKeyMatch`. Events come out of
  `GtkEventController::get_current_event()` and the legacy controller's `event` signal as their
  real subclass; there is no `new`. `EventControllerTest` drives real X input into the Xvfb
  display (`tests/XInput.php`, ext-ffi + libXtst; skipped where unavailable).
- Wave 2 (controls, docs/PLAN.md §3): `GtkEntry`, `GtkEditable`, `GtkEntryBuffer`,
  `GtkPasswordEntry`, `GtkCheckButton`, `GtkToggleButton`, `GtkSpinButton`, `GtkRange`, `GtkScale`
  (`set_format_value_func(callable)`), `GtkProgressBar`, `GtkImage`, `GtkPicture`, `GdkPaintable`,
  `GtkSpinner`, `GtkCalendar`, `GtkDropDown`, `GtkStringList`, `GtkStringObject`, with
  `GtkEntryIconPosition`, `GtkInputPurpose`, `GtkInputHints`, `GtkImageType`, `GtkIconSize`,
  `GtkContentFit`, `GtkSpinType`, `GtkSpinButtonUpdatePolicy`, `GtkStringFilterMatchMode`,
  `GdkPaintableFlags`, `GdkDragAction`, `GtkAccessiblePlatformState` — generated, one example page
  each, `ControlsTest`. Generator: nullable `GStrv` parameters are `?array`
  (`new GtkStringList(null)`).
- Wave 1 (layout, docs/PLAN.md §3): `GtkScrolledWindow`, `GtkViewport`, `GtkScrollable`,
  `GtkAdjustment`, `GtkGrid`, `GtkPaned`, `GtkFrame`, `GtkStack` + `GtkStackPage`/`GtkStackSwitcher`/
  `GtkStackSidebar`, `GtkNotebook` + `GtkNotebookPage`, `GtkOverlay`, `GtkRevealer`, `GtkFixed`,
  `GtkSeparator`, `GtkSizeGroup`, with `GtkPolicyType`, `GtkCornerType`, `GtkScrollablePolicy`,
  `GtkPositionType`, `GtkPackType`, `GtkStackTransitionType`, `GtkRevealerTransitionType`,
  `GtkSizeGroupMode` — generated, one example page each, `LayoutTest` for what round-trips cannot
  show. The demo's sidebar scrolls. Page objects come from their container (`new GtkStackPage()`
  is refused).
- Boxed records generated from GIR (`GtkRequisition` first): fields as properties, value
  semantics, generated registration. Out parameters of string/object/enum/record kind, including
  caller-allocated structs (`GtkWidget::get_color(): GdkRGBA`, `get_preferred_size()`).
- Async API: callback parameters with GIR scope `async`/`call` get a generated trampoline;
  `GtkAlertDialog` (`choose()` + `choose_finish()`), `GCancellable`, `GAsyncResult`.
- GTK interfaces implemented from PHP: `class M extends GObject implements GListModel` is a real
  `GListModel` (`SubclassTest`); `GObject::__construct()`.
- CSS (docs/PLAN.md milestone 8): `GtkCssProvider` (`load_from_string`/`_path`/`_bytes`/`_resource`,
  `load_named`, `to_string`) plus `Gtk::add_provider_for_display()` /
  `remove_provider_for_display()` and the `GtkStyleProviderPriority` constants attach a stylesheet
  to every widget on a display; `GtkStyleProvider` is the interface they take. Parse errors come
  back through the `parsing-error` signal as a `GtkCssSection` (`to_string()`, `get_parent()`,
  `get_start_location()`/`get_end_location()`) and a `GError` — CSS is parsed leniently and the
  loaders never throw, so connecting that signal is the only way to see a typo. `GdkDisplay` is
  bound with it (`get_default()`, `open()`, `get_monitors()`, …), and `GtkWidget::get_display()`,
  `GtkWindow::get_display()`/`set_display()` and `GtkRoot::get_display()` come along.

- PHP subclasses of GObject classes are real GTypes (`src/core/subtype`, docs/PLAN.md §2.6):
  `class MyWidget extends GtkWidget` gets its own GType at the first `new`, constructor arguments
  become construct properties, and `vfunc_<name>()` methods override the GTK class-struct slots
  (`measure`, `size_allocate`, `get_request_mode`, `match`/`get_strictness` on `GtkFilter`,
  `compare`/`get_order` on `GtkSorter`, `clicked`, `activate`/`startup`/`shutdown` on
  applications, …) with `parent::vfunc_<name>()` chaining down to GTK's implementation. Abstract
  GTK classes (`GtkWidget`, `GtkFilter`, `GtkSorter`, …) now have a public constructor that
  refuses the native class and works on a subclass.
- Windows build: `config.w32` (PHP SDK `phpize.bat` + `configure --with-gtk4=<root>` + `nmake`,
  GTK 4 from [gvsbuild](https://github.com/wingtk/gvsbuild)), `pin_gtk_library()` Win32
  counterpart (`GetModuleHandleEx` + `GET_MODULE_HANDLE_EX_FLAG_PIN`), `bin/php-gtk4.cmd`,
  `tests/run.cmd`, the `windows.yml` workflow (build + load + PHPUnit on PHP 8.4/8.5) and a
  `php_gtk4.dll` asset per supported PHP in every release.

- ZTS builds: per-request runtime state moved into module globals (`src/core/globals.h`);
  `config.m4` and `php_gtk4.h` no longer refuse thread-safe PHP, CI tests one ZTS variant.
  GTK itself stays single-threaded — `Gtk::init()`, `GtkApplication::run()`, `GMainLoop::run()`
  and `GLib::main_context_iteration()` throw `Error` from any thread other than the one that
  initialised GTK.
- `docs/BUILD.md` (build process, variants, Windows status and route, threads) and
  `docs/CONTRIBUTING.md`; README links to them.
- CI: the asan stage preloads `tests/asan-dlopen-shim.c` so PHP builds that `dlopen()` extensions
  with `RTLD_DEEPBIND` (setup-php) can load the sanitized module; phpt runs with `GTK_A11Y=none`.
- libgtk-4 is pinned in the process at MINIT (`RTLD_NODELETE`): PHP unloads extensions at
  MSHUTDOWN, and unmapping GTK under its fontconfig warm-up thread crashed at exit after a failed
  `Gtk::init()` on machines with a cold fontconfig cache (CI runners).

- Native Zend API runtime (`GObject` handles with property access, `GValue`/`GVariant`/boxed
  marshalling, GClosure-based signals, `emit()`, exception boundary with `ExceptionMode`).
- `GtkBox`, the first layout container: GTK 4 has no `GtkContainer`, so this is what lets a window
  hold more than one widget. With `GtkWidget::set_hexpand()`/`set_vexpand()` it turns
  `examples/demo.php` into a real application (header, sidebar, content) instead of a slideshow.
- Classes: `GObject`, `GParamSpec`, `GtkWidget`, `GtkWindow`, `GtkButton`, `GtkLabel`,
  `GtkApplication`, `GMainLoop`, `GLib` (sources), `GSimpleAction` + `GAction`/`GActionMap`/
  `GActionGroup`, `GdkRGBA`, `GdkRectangle`, `Gtk`, `ExceptionMode`, `GError`, `GdkTexture`,
  `PhpValue`, `GListStore` + `GListModel`, `GtkDrawingArea` + `CairoContext`, `GtkFilter`/
  `GtkCustomFilter`/`GtkFilterListModel`, `GtkSorter`/`GtkCustomSorter`/`GtkSortListModel`,
  enums `GtkAlign`, `GtkOrientation`, `GtkFilterChange`, `GtkSorterChange`, flags `GApplicationFlags`.
- Tooling: `ci.sh` stages, sanitizer/valgrind/coverage runs, stub-driven arginfo, IDE stub and
  method comments, git hooks, Dependabot.
- `gen/gir.php`, the GIR generator (milestone 3): `--install` writes per-namespace stubs
  (`src/<Ns>/<Ns>.stub.php` + arginfo), one `.cpp` per class, `src/gen_minit.inc`, example
  skeletons and `gen/report.md`; inputs `allowlist.txt`, `handwritten.txt`, `skip.txt`,
  `overrides/` (method bodies and class preludes). `./ci.sh --only=gen` fails on a stale tree.
  Wave 0: `GtkWidget`, `GtkWindow`, `GtkButton`, `GtkLabel`, `GtkBox`, `GtkDrawingArea`, the
  filter/sorter/list-model classes, `GtkApplication` (+ its real parent `GApplication`),
  `GdkTexture`, `GListStore`/`GListModel`, the `GAction*` interfaces and `GSimpleAction` are now
  generated with GTK's full 4.14 API (e.g. +93 methods on `GtkWidget`), plus every enum/flags type
  their signatures use.

- Generated smoke tests: `tests/Generated/<Class>SmokeTest.php` per generated class (construction,
  every setter/getter pair and writable property round trip; `gen/smoke-skip.txt` excludes what
  GTK legitimately does not honour). Hand-written `LabelTest`, `ButtonTest` and new cases in
  `WidgetTest`, `BoxTest`, `ApplicationTest`, `FilterSortTest`, `TextureTest`, `ActionTest`
  cover the semantically interesting new API; every generated class and enum has a visual
  `examples/` page.
- Constructors that GTK refuses (`g_return_val_if_fail`, e.g. an invalid application id) throw
  `Error` instead of leaving a dead handle.

### Changed (wave 0 - the API follows GTK's shape)

- Constructors mirror GTK: `new GtkWindow()` + `set_application()` (was `new GtkWindow($app)`),
  `GtkButton::new_with_label()` (was `new GtkButton($label)`), `GSimpleAction::new_stateful()`
  (was a third constructor argument), `new GtkBox($orientation, $spacing)` with no defaults,
  `GtkFilter/GtkSorter::changed($change)` requires the enum, `GtkApplication::__construct($id, $flags)`.
- `GtkWidget::show()/hide()` (deprecated in GTK 4.10) are not exposed; use `set_visible()`.
- `GdkTexture::save_to_png()` returns `bool` like GTK instead of throwing.
- PHP-side validations GTK does not make were dropped (`GtkBox` spacing/child checks, `GListStore`
  bounds and item type, application-id and action-name validity): GTK reports them as criticals.
- `GtkWidget` and other GIR-abstract classes are no longer `abstract` in PHP (`wrap()` needs to
  instantiate them for GTK-created objects); `new` is refused by a private constructor.
- `--enable-gtk4-testing` (`FEATURES` gains `testing=yes|no`): compiles `Gtk::testing_*` hooks
  declared in `#if defined(PHPGTK_TESTING)` stub blocks; never in the shipped `.so`, used by the
  `asan`/`coverage` variants. First hook: `Gtk::testing_iterate_nested(int $iterations)` — a
  C-driven nested main loop, which lets the suite cover parked-exception `previous` chaining and
  the enclosing `run()` rethrow (`RethrowModeTest`, `tests/scripts/stress.php`).
- Constructor ownership rule for the generator (docs/PLAN.md "Ownership", gen/README.md):
  floating → `attach_new()`, `GtkRoot` implementor → `attach()`, plain transfer-full GObject →
  `attach_new()`, else a generator error; `attach_new()` now detects a `GtkRoot` at runtime
  (`g_critical`, then `attach()` semantics).
- `GLib::main_context_iteration(bool $may_block = false): bool` — one iteration of the default
  context without handing control to `run()`.
- `ExceptionMode::Rethrow` inside an *unregistered* nested main loop (GTK iterating the context
  itself, or `main_context_iteration()`): the Throwable is parked instead of left pending across
  C frames, handlers keep running so the inner loop can finish, and the next `run()` /
  `main_context_iteration()` returning to PHP rethrows it. A second Throwable meanwhile is chained
  as `previous`; anything still parked at request shutdown goes to the handler / `g_critical`.
- `get_property_ptr_ptr`/`unset_property` handlers on GObject and boxed handles: `$w->width++`,
  `$w->title .= 'x'`, `$rgba->red += 0.1` now write through (they used to create a shadow dynamic
  property and drop the write); `unset($w->title)` throws `Error`. `StubsTest` gates snake_case
  method and parameter names in the stub (`GError::getDomain()` is the one allowed exception).

### Changed

- `gen/gir.php` is six files: the loader, the model, the type map, the writer, and the emitters
  with the CLI that stay in `gir.php` (docs/TODO.md §10). Each step of the split was verified by
  regenerating - every one of the 178 generated files stayed byte-identical - and the type map is
  now reachable on its own, which `GeneratorTypeMapTest` uses.

- `./ci.sh` no longer does pointless work: `gen/gir.php` writes a generated file only when its
  content changed (rewriting identical files bumped their mtimes and cost ~50 s of recompiling on
  every run), and clang-tidy remembers what it linted at which content in `.ci/tidy-ok`
  (`GTK4_LINT_ALL=1` forces a full pass; any header change still relints everything). A run that
  touched no C++ went from ~5 min to ~35 s, so `pre-push` keeps running the full gate —
  `GTK4_PREPUSH=fast git push` drops `build,load,test,phpt` when you want it anyway.

- Interfaces with vfuncs declare only their vfunc-backed methods in PHP (`GListModel`:
  `get_item_type`, `get_n_items`, `get_item`); the utility methods remain on the implementing
  classes. Native `vfunc_*()` callable only from a PHP subclass; `emit()` arity errors are
  `ArgumentCountError`.
- The generator throws on an unresolvable MINIT parent (was a silent comment), skips
  caller-allocates out parameters (never a by-reference PHP parameter), emits `interface X
  extends Y` from GIR prerequisites, puts thunks in an anonymous namespace, and seeds the MINIT
  order with the hand-written classes. `gen/gir.php` is analysed by PHPStan against a baseline.
- Handles keep a *toggle* reference on their GObject: while GTK holds the object (a parented
  widget, a `GListStore` item, a window in the toplevel list) the PHP object stays alive with it,
  so a PHP subclass appended without keeping a reference is returned from `get_first_child()` as
  that subclass with its state, not as a fresh base-class wrapper. Released when GTK lets go and
  at request shutdown.
- Source layout follows the GIR namespaces: `src/GLib/` (`GLib`, `GMainLoop`, `GError`),
  `src/GObject/` (`GObject`, `GParamSpec`, `PhpValue`), `src/Gio/`, `src/Gdk/`, `src/Gtk/`,
  `src/Cairo/`; `src/core/` contains no `ZEND_METHOD` any more. The `GListModel` interface methods
  are implemented once (`src/Gio/GListModel.cpp`) and aliased into `GListStore`,
  `GtkFilterListModel` and `GtkSortListModel` via `@implementation-alias` in the stub. The MINIT
  block has three registration shapes only (`src/classes.h` declares the non-GObject hooks);
  `config.m4` compiles every `src/**/*.cpp` it finds instead of a hand-kept list.
- `ci.sh` rejects unknown `--options` and stages (exit 2) instead of forwarding them to phpunit,
  has `--help`, and falls back to the unversioned `clang-tidy`/`clang-format` when no
  `/usr/bin/clang-*-N` exists. `tests/run.sh`/`buildall.sh` run with `set -euo pipefail`.
- `.clang-tidy` enforces naming (`readability-identifier-naming`: snake_case functions/variables,
  CamelCase types, UPPER_CASE macros; `ce_<GTypeName>` class entries allowed). Parameter classes
  are resolved with `class_for_gtype(G_TYPE_X)` instead of by name string. `markdownlint-cli2` is
  pinned in `package.json` so Dependabot tracks it. `tests/TestPng.php` → `tests/PngFixture.php`.
- CI: the PR workflows declare a read-only token; `release.yml` pins its actions by commit SHA.
- Error vocabulary settled (CLAUDE.md): argument errors → `ValueError`/`TypeError`, wrong object
  state → `LogicException` (`GSimpleAction::set_state()` on a stateless action now throws
  `LogicException` instead of `Error`), handle-level impossibilities → `Error`. Shared helpers
  `PHPGTK_RETURN_STRING_OR_NULL` and `src/Gtk/children.h` (`?GtkWidget` arguments, unparented /
  child-of checks; `GtkBox` messages now say "this GtkBox" / "its parent").
- Parameter names in the public API are snake_case (`$handler_id`, `$interval_ms`, `$source_id`,
  `$item_type`, `$parameter_type`, `$application_id`, `$css_class`, `$draw_func`, `$match_func`);
  named-argument callers using the old camelCase spellings break.

### Fixed

- Generated methods handed a transfer-full string parameter (`GtkStringList::take()`) Zend's own
  buffer, which GTK then `g_free()`d (`free(): invalid pointer`); the generator passes a
  `g_strdup()` copy for transfer-full strings now.
- Generated `throws` methods with a scalar return (`GtkAlertDialog::choose_finish()`,
  `GTask::propagate_int()`) checked the `GError` for nothing: a dismissed dialog came back as
  `-1` and the error leaked. They throw the `GError` now (`GTaskTest`).
- A `GError` *parameter* (`GTask::return_error()`) was generated as a boxed handle and
  dereferenced NULL in argument parsing: the generator builds a `GError` from the `Gtk4\GError`
  exception now (`gerror_from_php()`, domain `php-gtk4-error-quark` when the PHP side set none).
- A boxed record the GIR gives no constructor (`GtkTextIter` will be the first) refuses `new`
  like the fundamental handles do, and a boxed method on a handle without data throws an `Error`
  instead of dereferencing NULL (`PHPGTK_BOXED_SELF` is a statement pair now, like `PHPGTK_SELF`).
- Handles know when GTK *disposed* their object while PHP still held it (a weak notify set in
  `arm()`): methods and argument passing throw an `Error` from then on instead of driving a gutted
  widget. GTK 4's `gtk_window_destroy()` does not dispose a window PHP holds (it only drops GTK's
  reference — `WrapTest`), so this guards C-owned disposal; `Gtk::testing_run_dispose()` triggers
  it in test builds.
- `callback_invoke()` reports a Throwable that is already pending when a non-signal callback is
  entered in `Log` mode (it can only come from the trampoline's own argument conversion) instead of
  leaving it to unwind through the GLib frame.
- `throw_gerror()` on a NULL result without a `GError` (a failed GTK precondition) threw a plain
  `Error` instead of dereferencing NULL; `GtkFilter`'s `gptrarray_to_php`/`strv_to_php` no longer
  double-free (transfer full with a free func) or leak (transfer container).
- A PHP `__destruct` running inside a GTK frame (a handle GTK let go of in a main-loop dispatch)
  now goes through the exception boundary; a Throwable pending at request shutdown is reported.
- Generated vfunc thunks skip the PHP method while an exception is already pending (Rethrow
  mode reported one Throwable once per remaining thunk); native `vfunc_*()` empty slots are
  no-ops instead of errors.
- Two PHP classes whose names map to the same GType name (`App\Foo` / `App__Foo`, and *every*
  anonymous class — their names carry a NUL) no longer share one GType; the second one throws.
- `subtype` `instance_init` binds only the instance being constructed; `gui_thread` and the
  enum-verification flag are atomic (ZTS); the fundamental registry walks GType parents (a
  `GdkKeyEvent` handle is a `GdkEvent`); `emit()` with the wrong argument count throws
  `ArgumentCountError`, unknown signals/properties/actions name the argument.
- `.clang-tidy`'s header filter never matched (absolute include paths): `src/**/*.h` are linted
  now (`--header-filter` from `ci.sh`, gen_stub arginfo excluded), findings fixed; `--fix` runs
  clang-tidy one TU at a time so shared headers are patched once.
- Build: `config.m4` and `config.w32` derive the source directories from the tree (a new
  namespace directory such as `src/Pango` needs no edit); `ci.sh`'s `gen` gate covers
  `gen/report.md`, the map and new example skeletons and runs on pull requests (`cpp-lint.yml`)
  and in the pre-commit hook; `PHPT_TESTS` documented; `--only=tidy` rejected with a hint;
  compiler-warning gate limited to our sources.
- RSHUTDOWN teardown walks the live registries instead of a snapshot, so a disconnect that
  finalizes another tracked object can no longer leave a dangling pointer for the next iteration.
- `ci.sh --only=cpp-lint` on a fresh checkout failed with `'config.h' file not found` (the stage
  runs before `build`); it now runs `phpize && ./configure` itself when `config.h` is missing.
  This is what broke every `release.yml` verify job. The job also pins clang 20 now, like
  `cpp-lint.yml`.
- `config.m4` lacked the `src/Cairo` build directory (out-of-tree builds failed).
- `cpp-lint.yml` never ran clang-tidy/clang-format (it gated on a step that did not exist); it now
  installs clang 20 and runs `./ci.sh --only=cpp-lint` like the pre-commit hook.

## Release checklist

Full details in docs/RELEASING.md. `VERSION` is the only trigger — there is no tag to push.

1. `VERSION`: drop the `-dev` suffix (`0.2.0-dev` → `0.2.0`).
2. `./ci.sh --only=version,stubs --fix` — propagates into `src/php_gtk4.h` and `src/gtk4.stub.php`.
3. Move the Unreleased entries under `## [0.2.0] - YYYY-MM-DD`. The release workflow copies that
   section into the GitHub release body and fails if it is missing.
4. `GVSBUILD_VERSION` in `windows.yml` and `release.yml`: still the GTK you want the `.dll` built
   against? (nothing bumps it automatically — `docs/BUILD.md` "The pinned GTK version").
5. `./ci.sh --with=asan,coverage,valgrind` green.
6. Merge as a `release: 0.2.0` PR — `.github/workflows/release.yml` tags, builds and publishes.
7. Follow-up PR: `VERSION` → `0.3.0-dev`, `./ci.sh --only=version,stubs --fix`, fresh `## [Unreleased]`.

Between releases every merge into `main` publishes a `vX.Y.Z-dev.<run>` pre-release instead; the
newest five stay downloadable.
