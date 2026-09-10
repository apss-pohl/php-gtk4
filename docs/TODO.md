# TODO

What is still open. Decisions live in docs/DESIGN.md, what shipped in `CHANGELOG.md` and the git
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
- **Two Windows-only behaviours nobody has explained**, both made portable rather than
  understood: `DragDropTest` finds a `GtkTextBuffer` among the content formats where Linux finds
  `['string']` (the test asserts containment), and gvsbuild's gdk-pixbuf ships exactly five loaders
  where a count assertion wanted more (it names `png` and `jpeg`). The sweep complaints that used
  to sit here are pinned as `optional` lines and need no further work. Reproduce without Windows:
  Arch's `gtk4` is 4.22.4, and `meson --buildtype=debugoptimized` turns the assertions and
  consistency checks back on.

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
- **What the generator still cannot shape**, in the order of how many members each blocks
  (`gen/report.md`; the count moves as classes are bound). None of these is a missing *type* -
  the one-class-away list is empty - they are shapes the emitters do not map yet:
  - *a C array as an input parameter*, for the element types that are not scalars or strings:
    `GApplication::open()` wants an array of `GFile` (unbound), `GActionMap::add_action_entries()`
    an array of C structs. The scalar and string cases are mapped.
  - *a caller-allocated buffer out*: `GInputStream::read()` and friends. `read_bytes()` already
    answers with a string, so these may be better skipped than bound.
  - *`GType` as a value* (9), *`GObject.Value`* (6), *`GLib.HashTable`* (6), *`GLib.List`* (6) and
    *`GLib.PtrArray`* where the element type is unbound, *`gpointer`* (7, unsupported by design).
    Counted from `gen/report.md` on 2026-09-10; the array-parameter case above is the big one at
    49 members.
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
  excuse - and one did: `GskColorMatrixNode` was excused as needing types that had been bound for
  waves, until it was built by hand. A static check that every excuse is still true would have
  caught it.
- `GtkFontDialogButton::set_font_desc()` logs a GLib CRITICAL on GTK < 4.18 when the description
  names a family the font map does not list: `update_font_data()` walks
  `g_list_model_get_n_items(self->font_family)` with `font_family` still NULL
  (`gtk/gtkfontdialogbutton.c`; upstream added the NULL check in 4.18, so 4.14 and 4.16 - the
  floor and one above it - complain). Nothing to guard here, any family string is legitimate;
  `examples/GtkFontDialogButton.php` uses "Sans", which Pango always lists. Pin it if a sweep ever
  reaches it.
- `GskTextNode` has no constructor until `Pango.Font` is bound (`get_font` needs it too, and
  `get_glyphs` returns an array plus out parameters); `GdkPixbufAnimationIter::advance()` needs a
  `GLib.TimeVal`, which is deprecated in GLib and will not arrive.

## Keep (verified good, do not "clean up")

Namespace `Gtk4\`; PHP class == GType name + registry; toggle-ref hold + qdata identity;
GValue-array GClosure marshaller; single GValue bridge; catch → leave scope → report; `ci.sh`
stages; ASan/UBSan/LSan + valgrind + gcov; `EveryClassTest`/`ExampleTest`; PHP 8.4/8.5 × NTS/ZTS
on GTK 4.14 (Linux) and the pinned gvsbuild GTK (Windows); C++20.
