# TODO

What is still open. Decisions live in docs/DESIGN.md, what shipped in `CHANGELOG.md` and the git
history; an item leaves this file when it is done or decided against, it is not ticked.

## Open work

- **File the `gtk_entry_set_extra_menu(entry, NULL)` report upstream.** Written and verified
  against GTK `main`; filing it needs an account on gitlab.gnome.org. Nothing to fix here - the
  complaint is spurious and the whole trail (the commit that lost the NULL check, why, and the
  sibling setters that kept it) is in the pin comment in `tests/robustness-criticals.txt`.
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
  - *a C array as an input parameter* (31), for the element types that are not scalars or strings:
    `GApplication::open()` wants an array of `GFile` (unbound), `GActionMap::add_action_entries()`
    an array of C structs. The scalar and string cases are mapped.
  - *`gpointer`* (25, unsupported by design), *`GType` as a value* (19), *`GObject.Value`* (9),
    *`GLib.HashTable`* (6), *`GLib.List`* (6) and *`GLib.PtrArray`* where the element type is
    unbound (5). Counted on 2026-09-10 over the member lines of `gen/report.md` - methods, vfuncs
    and properties together - so the same grep reproduces them.

## Keep (verified good, do not "clean up")

Namespace `Gtk4\`; PHP class == GType name + registry; toggle-ref hold + qdata identity;
GValue-array GClosure marshaller; single GValue bridge; catch → leave scope → report; `ci.sh`
stages; ASan/UBSan/LSan + valgrind + gcov; `EveryClassTest`/`ExampleTest`; PHP 8.4/8.5 × NTS/ZTS
on GTK 4.14 (Linux) and the pinned gvsbuild GTK (Windows); C++20.
