# TODO

What is still open. Decisions live in README.md "Design", what shipped in `CHANGELOG.md` and the git
history; an item leaves this file when it is done or decided against, it is not ticked.

## Open work

- **An intermittent segfault in the PHP 8.5 ZTS suite.** One CI run (2026-09-07, run
  34085363767) died with `Segmentation fault (core dumped)` about 46% into the PHPUnit suite;
  the same job re-run on the same commit passed, and 8.4 NTS/ZTS, 8.5 NTS, ASan and valgrind
  were green. So it is real but rare, and nothing named the test: PHPUnit dies with the process
  and writes no JUnit file. `tests.yml` now streams `--log-events-text` and prints its tail on a
  failed job, so the next occurrence names the test that was running. Until then there is
  nothing to reproduce.
- **The Windows list-view abort.** The four Windows CI jobs build and load the DLL and then die in
  PHPUnit at `PhpSelectionModelTest` with `gtk_list_item_manager_clear_model: assertion failed:
  (gtk_rb_tree_get_root (self->items) == NULL)`, exit `0xC0000409`. The assertion exists in the
  newer GTK gvsbuild ships, not in the 4.14 Linux CI runs, so Linux may be leaking silently where
  Windows aborts. Hypothesis: a PHP-implemented `GtkSelectionModel` slot answers its default (zero
  items) once the handle is unavailable, leaving GTK's item manager with items it believes gone.
  Needs a run against that GTK; a workflow run with the test class excluded would also show the
  failures the abort hides.
- **WebKitGTK, what the first wave left out** (`gen/report.md`, sections `WebKit*`/`JSC*`): the
  URI scheme handler (`WebKitWebContext::register_uri_scheme()` and the request/response pair need
  `GInputStream`), the Soup types (`WebKitCookieManager::add_cookie()`, the HTTP headers of a
  request or response), `WebKitWebsiteDataManager::clear()` (`GTimeSpan` is a GIR alias the type
  map does not resolve), TLS certificates, `WebKitWebExtension` (2.52 API) and the web-process
  extension side (`WebKitWebProcessExtension-6.0.gir`, a separate library loaded into the web
  process - a PHP extension cannot live there). Windows stays without a web view (WebView2 is the
  plan, docs/BUILD.md).
- **A PHP-driven print preview.** `GtkPrintOperation` does not implement
  `GtkPrintOperationPreview` in PHP: its slots (`render_page`, `end_preview`, `is_selected`) are only
  valid inside the `preview` signal, where GTK keeps the state private, and dereference NULL
  outside it. Binding them needs the signal to hand PHP an object that carries that state.
- **Cross-thread hand-off** for the "one GUI thread + workers" shape: a thread-safe
  `GLib::invoke_on_main(callable)` (serialise the callable or require a `parallel`-style channel;
  `g_main_context_invoke` on the GUI context, the callable released on that thread). Needs a
  concrete consumer (`ext-parallel` or PHP-native threads) before designing the API.
- **Branch protection for `main`**: blocked, private repository on a Free plan (the GitHub API
  answers 403 "Upgrade to GitHub Pro or make this repository public"). The ruleset is ready in
  `.github/ruleset-main.json`; once public or Pro:
  `gh api -X POST repos/apss-pohl/php-gtk4/rulesets --input .github/ruleset-main.json`
  (drop `required_approving_review_count` to 0 while there is a single maintainer).
- **Robustness pin residue** (`tests/robustness-criticals.txt`, `tests/README-robustness-pin.md`).
  The remaining method lines are GTK reporting about the data it was handed or about state it keeps
  private. In the order worth attacking: *state preconditions* (`parent != NULL` on
  `gtk_layout_manager_get_layout_child()`, `!task->ever_returned`, `is_registered` on the
  `GApplication` methods, `center_child != NULL` on `GtkTextView::move_overlay()`) are
  `LogicException` candidates; *lookup misses* ("Child name not found in GtkStack", "no mark named")
  are a question of whether a miss should raise at all; *parse diagnostics* (theme parser errors,
  "invalid accelerator string", the `goption.c` warnings) are correctly pinned.
- **Performance**, all single-digit percent: the signal marshaller allocates the argument array per
  emission, `subtype_vfunc()` hashes the method name per call, the PHP-GType snapshots are O(N²) in
  PHP subclasses and never freed.
- **Comments and lint style**: declarations in `src/core/*.h` carry no comment (the gate covers
  definitions only); `.clang-tidy` explains its macro exclusions but not the style choices.
- **A porting guide** for php-gtk3 programs (`docs/GTK3-MAP.md` is the class map; the prose about
  the model differences - no `Gtk::main()`, no containers, signals without user data, async
  dialogs, list views instead of tree views, `GdkTexture` instead of pixbufs in widgets - is not
  written).

## Smaller notes

- `gdk_drop_read_async()` very likely asserts `callback != NULL` like its four `GdkClipboard`
  siblings, but a `GdkDrop` needs a real drag from another client and no test can reach one
  (`DragDropTest`), so `NON_NULLABLE_PARAMS` deliberately has no line for it: every entry there is
  a precondition that was *observed* firing. Revisit when a drag test exists.
- `RETURNS_HOLD_SELF` can only name `$this`, so the sibling walk is outside it -
  `get_next_sibling()`'s result belongs to the shared parent. Measured, not assumed: reaching a
  sibling through the eight composite widgets that have one, dropping everything that holds the
  parent and calling every arg-less getter on the orphan is clean under ASan+UBSan. Re-measure
  before adding the owner-expression form `BOXED_OWNERS` has.
- `GtkInstances::UNREACHABLE` is guarded dynamically (`TypeDeclarationTest` fails if any getter
  hands out a class it calls unbuildable), but only through arg-less getters on classes the
  factory can build. A class reachable *only* through a method with arguments would keep a stale
  excuse.
- Per-namespace stub naming (`src/Gtk/Gtk.stub.php` next to the hand-written `Gtk.cpp`) stays as
  documented in `gen/README.md`; renaming would touch every tool's path list for no behaviour.
- `GskTextNode` (needs a Pango font and glyph string) and `GskColorMatrixNode` (a graphene matrix
  and vec4) have no constructor until those types are bound; `GdkPixbufAnimationIter` needs a
  `GTimeVal`, which is not.

## What the class map still marks unported

`docs/GTK3-MAP.md`'s "Recommended port order" has landed in full; what it still marks ❌ needs a
decision rather than a wave:

- *deprecated in 4.10* - `GtkDialog`, `GtkInfoBar`, `GtkStatusbar`, `GtkEntryCompletion`,
  `GtkColorButton`/`GtkFontButton`, `GtkAppChooser*`. The binding exposes the modern API only and
  every replacement is bound; port none of them unless a real port asks.
- *current widgets, no wave needed* - `GtkListBox`(+`Row`), `GtkFlowBox`(+`Child`), `GtkExpander`,
  `GtkActionBar`, `GtkAspectFrame`, `GtkCenterBox`. Mechanical: add to `gen/allowlist.txt` and
  generate.
- *small gaps in bound classes* - `GIcon`/`GThemedIcon` (what `GtkImage::set_from_gicon()` wants),
  the runtime GTK version triple (`gtk_get_major_version` ..., next to `Gtk4\VERSION`), and
  `GtkUriLauncher` as the replacement for the deprecated `gtk_show_uri`.
- *out of scope by design* - `GtkPrinter`/`GtkPrintJob`/`GtkPrintUnixDialog` (a separate library,
  gtk4-unix-print, on the deprecated `GtkDialog`), `GskGLShader` (deprecated in 4.16), the
  Broadway/NGL/Vulkan renderer classes, GIO streams.

## Keep (verified good, do not "clean up")

Namespace `Gtk4\`; PHP class == GType name + registry; toggle-ref hold + qdata identity;
GValue-array GClosure marshaller; single GValue bridge; catch → leave scope → report; `ci.sh`
stages; ASan/UBSan/LSan + valgrind + gcov; `EveryClassTest`/`ExampleTest`; PHP 8.4/8.5 × NTS/ZTS
on GTK 4.14 (Linux) and the pinned gvsbuild GTK (Windows); C++20.
