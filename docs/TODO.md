# TODO

What is still open. Decisions live in README.md "Design", what shipped in `CHANGELOG.md` and the git
history; an item leaves this file when it is done or decided against, it is not ticked.

## Open work

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
- **Branch protection for `main`**: unblocked - the repository is public since 2026-09-09, so the
  ruleset in `.github/ruleset-main.json` can go up:
  `gh api -X POST repos/apss-pohl/php-gtk4/rulesets --input .github/ruleset-main.json`
  (drop `required_approving_review_count` to 0 while there is a single maintainer). Still open with
  it: the free security features a public repository gets - private vulnerability reporting (the
  channel `SECURITY.md` sends people to), secret scanning and its push protection - are all off.

- **What the generator still cannot shape**, in the order of how many members each blocks
  (`gen/report.md`; the count moves as classes are bound). None of these is a missing *type* -
  the one-class-away list is empty - they are shapes the emitters do not map yet:
  - *a C array as an input parameter*, for the element types that are not scalars or strings:
    `GApplication::open()` wants an array of `GFile` (unbound), `GActionMap::add_action_entries()`
    an array of C structs. The scalar and string cases are mapped.
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
- `GtkFontDialogButton::set_font_desc()` logs a GLib CRITICAL on GTK < 4.18 when the description
  names a family the font map does not list: `update_font_data()` walks
  `g_list_model_get_n_items(self->font_family)` with `font_family` still NULL
  (`gtk/gtkfontdialogbutton.c`; upstream added the NULL check in 4.18, so 4.14 and 4.16 - the
  floor and one above it - complain). Nothing to guard here, any family string is legitimate;
  `examples/GtkFontDialogButton.php` uses "Sans", which Pango always lists. Pin it if a sweep ever
  reaches it.
- `GskTextNode` (needs a Pango font and glyph string) and `GskColorMatrixNode` (a graphene matrix
  and vec4) have no constructor until those types are bound; `GdkPixbufAnimationIter` needs a
  `GTimeVal`, which is not.

## Keep (verified good, do not "clean up")

Namespace `Gtk4\`; PHP class == GType name + registry; toggle-ref hold + qdata identity;
GValue-array GClosure marshaller; single GValue bridge; catch → leave scope → report; `ci.sh`
stages; ASan/UBSan/LSan + valgrind + gcov; `EveryClassTest`/`ExampleTest`; PHP 8.4/8.5 × NTS/ZTS
on GTK 4.14 (Linux) and the pinned gvsbuild GTK (Windows); C++20.
