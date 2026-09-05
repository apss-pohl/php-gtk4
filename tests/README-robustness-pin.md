# Regenerating `tests/robustness-criticals.txt`

`RobustnessTest` sweeps every registered method with hostile values of the right type. Some
methods can only be stopped by GTK's own precondition, so the sweep provokes a GLib `CRITICAL`
for them; `tests/robustness-criticals.txt` pins exactly which ones, and
`RobustnessTest::gateAgainstPinnedList()` fails the sweep in both directions — a method that
complains without a line, and a line whose method has gone quiet.

That means the list is a **review surface, not a suppression file**. When the gate fails:

- *"GTK complained while sweeping X, which robustness-criticals.txt does not list"* — first ask
  whether the binding should refuse the value itself. `check_utf8()` / `check_range<T>()` /
  `check_flags()` at the boundary, or a `LogicException` for a wrong-state call, is the fix
  (CLAUDE.md, "Convert at the boundary, do not coerce"). Add the line only when GTK's own
  precondition is genuinely the only thing that can catch it.
- *"lists X but GTK no longer complains about it"* — a boundary got closed, or GTK changed.
  Delete the line.

To rebuild the whole list after a deliberate, reviewed change (a new wave of classes, say),
add the two recording branches to `gateAgainstPinnedList()`:

```php
if (getenv('GTK4_PIN_CRITICALS') !== false) {
    if ($seen !== []) {
        file_put_contents((string) getenv('GTK4_PIN_CRITICALS'), $key . "\n", FILE_APPEND);
    }
    return;
}
```

right after `$isPinned` is computed, then

```sh
PHP_GTK4_ENV=GTK4_PIN_CRITICALS=/tmp/pin.txt ./tests/run.sh --filter RobustnessTest
sort -u /tmp/pin.txt        # merge into tests/robustness-criticals.txt, keeping the header
```

and revert the branch. `GTK4_PIN_REPORT=<file>` (in `PHP_GTK4_ENV` for `tests/run.sh`) appends
`key<TAB>message` for every complaint, which is how a line is classified before it is closed or
kept. Keys are `<class>::<method>#arguments` or `#values` for the two method
sweeps, `<class>::$<property>#properties` for the property-write sweep, `<class>::<signal>#emit`
for the signal-emission sweep and `<class>::vfunc_<slot>#return` for the vfunc-return sweep.

Recording is deliberately *not* a committed switch: regenerating
wholesale is how a real leak gets pinned by accident, so it should cost a patch and a diff you
have to read.

## What stays pinned, and why

The method lines left after 2026-09-05 are GTK reporting about the *data* it was handed, or
about a state it keeps private, so no boundary check can stand in for them:

- `GtkCssProvider::load_*` - the theme parser's own diagnostics about the CSS; a script that
  wants them connects to `parsing-error`.
- `GApplication::run` - "does not implement activate": GLib's advice about the script's missing
  handler, decided inside `g_application_run()` by flags and handlers together.
- `GApplication::unbind_busy_property` - "not bound": GLib keeps the binding list private.
- `GtkTextTag::set_priority` - the tag's table is private; a tag outside a table has no priority.
- `GtkSnapshot::push_*` - "too many push() calls" is GTK's notice, at finalize, of a push the
  script never popped; popping for it would hide the bug.
- `GtkWidget::insert_after` / `insert_before` - GTK's finalize warning about a widget that was
  given children it does not manage (a `GtkButton` holds one child through `set_child()`).
- `GtkTextTag::$size_points` (property sweep) - a value inside the pspec's range that overflows
  Pango's integer size; `GtkDropTarget::enter`/`motion` and `delete-text` with a negative start
  (emission sweep) - GTK's handlers asking for a drop that does not exist, or asserting a start
  the method would have refused.
