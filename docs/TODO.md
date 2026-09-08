# TODO

What is still open. Decisions live in README.md "Design", what shipped in `CHANGELOG.md` and the git
history; an item leaves this file when it is done or decided against, it is not ticked.

## Open work

- **The segfault behind the 8.5 ZTS CI job is worked around, not understood.** The event log
  named it twice in a row:
  `RobustnessTest::testWrongArgumentsThrowInsteadOfCrashing#Gtk4\GtkFontDialog::choose_font`,
  prepared and never finished (runs 34115054496 and 34116287179; an earlier one, 34085363767,
  died at the same ~46% mark). The async choosers are off the sweep now - every argument may
  legitimately be null, so the sweep was *opening* a dialog that outlives the test, which a
  headless argument sweep has no business doing - and that is what makes the job green, not a
  fix. Only that one matrix cell ever dies: 8.5 NTS and 8.4 ZTS pass the same commit.
  What is known: it reproduces on no configuration that can be built here, and the PHP build is
  no longer the unknown. The *exact* binary the job runs - the `php_8.5-zts+ubuntu24.04.tar.zst`
  that `shivammathur/php-builder` publishes and setup-php unpacks, PHP 8.5.10 ZTS - runs the
  whole suite with the async choosers put back into the sweep, on Ubuntu 24.04 with GTK 4.14.5,
  and passes: twice over, plain and under `dbus-run-session`. So it is neither ZTS by itself nor
  an artefact of a hand-built PHP (a hand-built one and 8.4 NTS pass too). What is left is the
  runner: 4 cores against 16, its font set, and whatever else `choose_font` reaches for when it
  opens a dialog nothing will close.
  To try again without CI: extract that tarball into a scratch prefix (never `/`), point copies
  of `phpize`/`php-config` at it, build, and run PHPUnit with `-n -d extension_dir=...` plus
  dom/mbstring/tokenizer/xml/xmlwriter - `-n` alone leaves the ide-stub subprocess without
  tokenizer and fails `StubsTest` for reasons that have nothing to do with the crash.
- **What the Windows job still reports that GTK 4.14 never shows** (gvsbuild ships 4.22, built
  with the debug and consistency checks a distro release build compiles out). Two open groups:
  - *Two pin lines to write, and they need different mechanisms.* `GtkWidget::allocate`'s
    complaints in `gtkwidget.c` sit behind `G_ENABLE_DEBUG` / `G_ENABLE_CONSISTENCY_CHECKS` -
    that follows how GTK was *built*, which no `gtk>=` condition can express, so they belong in
    the environmental filter in `gateAgainstPinnedList()` next to the portal message. The
    `measure` one, `"Trying to measure %s %p for %s of %d, but it needs at least %d"`
    (`gtksizerequest.c`), is unguarded and simply absent in 4.14, so it takes a condition in the
    pin file. Neither is written: the run's logs are gone and the sweep suffix
    (`#arguments`/`#values`) and exact wording are guesses without them. Take both off the next
    Windows run.
  - *Windows-only behaviour, not yet understood*: `DragDropTest` finds a `GtkTextBuffer` entry
    in the content formats the serialisation test expects to be `['string']`, and
    `PixbufTest::testEncodingTakesOptionsAsAMap` gets the same size back for both compression
    levels. `DragDropTest::testStoringTheClipboardIsAnAnswerEitherWay` ("the async callback
    ran") failed one run and passed the next, so that one is intermittent.
  Reproduce without Windows: Arch's `gtk4` package is 4.22.4, and a
  `meson --buildtype=debugoptimized` build of GTK turns the assertions and the consistency checks
  back on. Reading GTK's own sources per version gets a long way without either -
  `https://gitlab.gnome.org/GNOME/gtk/-/raw/<tag>/gtk/<file>.c`.
- **`gtk_entry_set_extra_menu(entry, NULL)` is a GTK bug worth reporting upstream.** 4.20 rewrote
  it to `g_object_ref()` the model without the NULL check its `(nullable)` annotation promises
  (`g_object_ref: assertion 'G_IS_OBJECT (object)' failed`); 4.14/4.16/4.18 delegate to
  `gtk_text_set_extra_menu()` and are quiet, and `main` still has it. The end state is right, so
  it is only a spurious complaint - pinned for `gtk>=4.20`, nothing to fix here.
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

- **What the generator still cannot shape**, in the order of how many members each blocks
  (`gen/report.md`; the count moves as classes are bound). None of these is a missing *type* -
  the one-class-away list is empty - they are shapes the emitters do not map yet:
  - *a C array with its length in an out parameter* (~8 members): `GKeyFile::get_groups()`,
    `get_keys()`, the four `get_*_list()` and `to_data()`. GIR marks the array `length="N"`
    pointing at that out parameter, so the PHP return is just the list and the length is
    `count()`. Needs `strv_to_php()` to take a length, and `retMapping()` to recognise the out
    as the array's length rather than a value of its own.
  - *a C array as an input parameter* (~8): `set_*_list()`, `GtkBuilder`'s and
    `GApplication::open()`'s, `GActionMap::add_action_entries()`. A PHP list has to become an
    array plus its length, per element type.
  - *a caller-allocated buffer out* (5): `GInputStream::read()` and friends. `read_bytes()`
    already answers with a string, so these may be better skipped than bound.
  - *`GType` as a value* (12), *`GObject.Value`* (7), *`GLib.HashTable`* (6), *`GLib.List`* and
    *`GLib.PtrArray`* where the element type is unbound, *`gpointer`* (4, unsupported by design).
  - Pango's own leaves, now that the cluster is bound: `Pango.Font` and `Pango.FontFamily`
    (abstract, backend-owned - the same shape as `PangoFontMap`), `Pango.Language` and
    `Pango.Rectangle` (~19 members between them).

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
- `GskTextNode` (needs a Pango font and glyph string) and `GskColorMatrixNode` (a graphene matrix
  and vec4) have no constructor until those types are bound; `GdkPixbufAnimationIter` needs a
  `GTimeVal`, which is not.

## Keep (verified good, do not "clean up")

Namespace `Gtk4\`; PHP class == GType name + registry; toggle-ref hold + qdata identity;
GValue-array GClosure marshaller; single GValue bridge; catch → leave scope → report; `ci.sh`
stages; ASan/UBSan/LSan + valgrind + gcov; `EveryClassTest`/`ExampleTest`; PHP 8.4/8.5 × NTS/ZTS
on GTK 4.14 (Linux) and the pinned gvsbuild GTK (Windows); C++20.
