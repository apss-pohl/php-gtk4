# TODO

Findings of the 2026-08-25 review ("best practice or php-gtk3 ballast?"). Ordered by impact.
Tick items off here; design rationale lives in docs/PLAN.md.

## 1. Runtime foundation — replace PHP-CPP with the native Zend API  ✅ done 2026-08-25

Inherited from php-gtk3 without re-examination. PHP-CPP's 2018 rationale (Zend API churn) no
longer holds for a PHP 8.4+ target, and it blocks idiomatic PHP:

- [x] `struct { GObject *obj; zend_object std; }` objects with custom handlers
      (`free_obj`, no clone, `get_debug_info`, `read_property`/`write_property` → GObject props)
- [x] `src/gtk4.stub.php` as the **single source**: `gen_stub.php` → `gtk4_arginfo.h`
      (typed arginfo, return types, nullable class types, enums, `#[\Deprecated]`); IDE stub is
      derived, not hand-synced → delete the sync half of `StubsTest`
- [x] `phpize` / `config.m4` build (PIE/PECL-installable, Windows via php-sdk); drop the
      PHP-CPP template Makefile, `PHPCPP_STATIC`/`PHPCPP_BASE` pairing logic, the PHP-CPP fork
- [x] signals: `zend_fcall_info` + cache resolved once at `connect()`, `zend_call_function`
      directly (no `call_user_func_array` round trip)
- [x] arity/type violations = `ArgumentCountError`/`TypeError` (PHP 8 semantics), not `E_WARNING`
- [x] exception boundary: capture the real `Throwable` (`EG(exception)`), hand the object to
      `Gtk::set_exception_handler` (done); rethrow mode (done, §2)
- [x] `var_dump($obj)` shows GObject properties (`get_debug_info`)

## 2. GTK3 mental-model ballast in the PHP API  ✅ done 2026-08-25

- [x] `Gtk::main()` / `Gtk::main_quit()` (+ the `quit_pending` hack) → `GtkApplication::run()`
      as the documented path, `Gtk4\GMainLoop` (+ `GLib::idle_add/timeout_add/source_remove`) for
      scripts that need a bare loop; `Gtk::init()` kept
- [x] `connect($signal, $handler, ...$userData)` → `connect(string $signal, callable $handler): int`;
      PHP closures capture context with `use`
- [x] `Gtk4\PHPGTK_VERSION` → `Gtk4\VERSION` etc. (double prefix)
- [x] exception model: `Gtk4\ExceptionMode::Log` (default) / `::Rethrow` via
      `Gtk::set_exception_mode()`; Rethrow stops running loops and propagates the Throwable

## 3. Decisions to write down  ✅ done 2026-08-25

- [x] **snake_case methods** (`set_title`) — kept, final. 1:1 mapping to docs.gtk.org and every
      other binding (PyGObject, gjs, gtk-rs, Vala); recorded in docs/PLAN.md; single spelling, no
      camelCase aliases; phpcs exclusion for the stub is intentional
- [x] property access: `get_property()/set_property()` *and* `$obj->prop` via handlers

- [ ] **Branch protection for `main`** — blocked: private repo on a Free plan (GitHub API returns
      403 "Upgrade to GitHub Pro or make this repository public"). Ruleset is ready in
      `.github/ruleset-main.json`; once public/Pro:
      `gh api -X POST repos/apss-pohl/php-gtk4/rulesets --input .github/ruleset-main.json`
      (drop `required_approving_review_count` to 0 while there is a single maintainer)
- [x] **License file** — MIT, `LICENSE` added 2026-08-25 (matches composer.json);
      keep php-src header on `gen/gen_stub.php` (PHP License 3.01, MIT-compatible)

## 4. Milestone 2 leftovers  ✅ done 2026-08-25

- [x] boxed core (`src/core/boxed.*`: value handles, clone/compare by value, field properties) +
      `GdkRGBA`, `GdkRectangle`, `GStrv` ↔ `list<string>`; graphene/GBytes/GError when an API needs them
- [x] `G_TYPE_VARIANT` ↔ PHP values (`src/core/variant.*`), real `GParamSpec` class;
      `G_TYPE_POINTER` deliberately unsupported (no meaningful PHP value)
- [x] interface mechanism (`zend_class_implements` from the stub's `implements`): `GAction`,
      `GActionMap`, `GActionGroup`; the rest come with the generator
- [x] one callback abstraction for non-signal callbacks (`src/core/callback.*`, used by GLib
      idle/timeout; reuse for sorters, draw funcs, factories)
- [x] `GtkWidget` layer (+ `GtkButton`, `GtkLabel`), `GObject::emit()`
- [x] closure/source teardown in RSHUTDOWN (`src/core/teardown.*`, `tests/scripts/shutdown.php`)

## 7. Milestone 2b — foundation the generator will emit against (decided 2026-08-26)

Do before milestone 3; each changes what generated code looks like.

- [x] **Enums and flags as PHP types** (2026-08-26) — `src/core/enums.*`; `GtkAlign`, `GtkOrientation`
      as PHP enums (cases literal in the stub, verified against the C enum at RINIT), `GApplicationFlags`
      as a constant class (values literal, verified at RINIT); properties return cases and accept
      cases or ints, typed methods
      take the enum only; unregistered enum types stay ints.
- [x] **Collection helpers** (2026-08-26) — `src/core/collections.*`: `GList`/`GSList`/`GPtrArray`/`char**`
      → PHP list by element GType with `Transfer::{None,Container,Full}`; `strv_from_php`. Users:
      `GtkApplication::get_windows()` (none), `GtkWidget::list_mnemonic_labels()` (container),
      CSS classes (`GStrv`). `GPtrArray` helper exists but has no bound user yet.
- [x] **Out parameters** (2026-08-26) — convention recorded in PLAN.md: outs become the return value
      (one → value, several → list in declaration order); a `bool` C return with outs → the outs or
      `null`. Users: `get_size_request()`, `GtkWindow::get_default_size()`, `GtkLabel::get_selection_bounds()`.
- [x] **Fundamental handle mechanism** (2026-08-26) — `src/core/fundamental.*`: registry of
      GType → {class, ref, unref}; `GParamSpec` migrated onto it. `GdkEvent`/`GskRenderNode`/
      `GtkExpression` register the same way when their APIs arrive (GdkEvent with controllers).
- [x] **Typed C callbacks from GIR** (2026-08-26) — hand-written trampolines as the generator
      template (PLAN.md "Typed C callbacks"): `GtkDrawingArea::set_draw_func()`
      (`GtkDrawingAreaDrawFunc`, with a minimal `CairoContext` handle on the fundamental registry),
      `GtkCustomFilter` (`GtkCustomFilterFunc`) + `GtkFilterListModel`, `GtkCustomSorter`
      (`GCompareDataFunc`) + `GtkSortListModel`; notified scope tracked by `teardown.*`, callable
      release deferred to `callback_drain()` (destroy notifies run inside GTK frames).
- [x] **`GError` → exception, `GBytes` ↔ string** (2026-08-26) — `Gtk4\GError extends RuntimeException`
      (`getDomain()`, GLib code in `getCode()`), `throw_gerror()` for `GError **` APIs, `G_TYPE_ERROR`
      values become exception objects; `GBytes` ↔ string in marshal and method signatures.
      First user: `GdkTexture` (`new_from_filename/new_from_bytes/save_to_png_bytes/save_to_png`).

## 6. GTK4 feature surface (what the binding still has to expose to deliver GTK4's benefits)

Inherited for free (nothing to do): GSK/GPU rendering, the flat widget hierarchy (no
GtkContainer), Wayland/HiDPI/platform backends, the cleaned-up API (the stub is generated from
GTK4 GIR only).

- [ ] **Event controllers** — `GtkGestureClick`, `GtkEventControllerKey/Motion/Scroll/Focus`,
      `GtkWidget::add_controller()/remove_controller()`; signals already marshal (ints/doubles/flags).
      Pure generator output (milestone 3).
- [ ] **List models** — `GListModel` interface, `GtkStringList`, `GListStore`, selection models,
      `GtkListView`/`GtkColumnView`/`GtkGridView`, `GtkSignalListItemFactory` (`setup`/`bind`),
      `GtkListItem`. Core prework done 2026-08-26: `Gtk4\PhpValue` (GType `PhpValue`, a GObject
      carrying a zval) + `GListModel` interface + `GListStore`. The views are generator output.
- [ ] **Drag and drop** — `GtkDragSource`, `GtkDropTarget`, `GdkContentProvider` (GValue payloads:
      boxed/variant support exists). Generator output.
- [ ] **CSS** — `GtkCssProvider` + `gtk_style_context_add_provider_for_display`; custom properties.
      Two small classes.
- [ ] **Concrete layouts** — `GtkBox`, `GtkGrid`, `GtkCenterBox`, `GtkStack`, `GtkPaned`,
      `GtkScrolledWindow` (+ `GtkOrientable` interface). Generator output.
- [ ] **Custom `GtkLayoutManager` / GObject subclassing from PHP** — vfunc overriding; separate
      design, after milestone 4.
- [ ] **Rendering from PHP** — `GtkSnapshot`, `GdkTexture`, `GdkPaintable`, `GtkDrawingArea::set_draw_func`
      (cairo interop). Milestone 4.
- [x] **GL renderer smoke test** — the test infrastructure forces `GSK_RENDERER=cairo` +
      `GDK_DISABLE=gl` (Xvfb). Verified manually on a real Wayland session with an AMD GPU on
      2026-08-26 (see PLAN.md §6 "GL"); repeat before a release, no automation possible on CI runners.

## 5. Keep (verified good, do not "clean up")

Namespace `Gtk4\`; PHP class == GType name + registry; owned refs + qdata identity + weak ref;
GValue-array GClosure marshaller; single GValue bridge; catch → leave scope → report; `ci.sh`
stages; ASan/UBSan/LSan + valgrind + gcov; `EveryClassTest`/`ExampleTest`; PHP 8.4/8.5 ×
GTK 4.14/4.22 matrix; C++20.
