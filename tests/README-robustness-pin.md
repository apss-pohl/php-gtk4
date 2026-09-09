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

A line may carry a **version condition** in a second column, `gtk>=<major>.<minor>`: the same
commit meets two GTKs (Linux CI is on the 4.14 floor, the Windows job runs whatever gvsbuild
ships), and preconditions — plus the occasional regression, which is what
`GtkEntry::set_extra_menu` is — arrive between releases. A line whose condition does not hold is
*dropped*, not tolerated: the older GTK is expected to stay quiet, so a complaint from it still
fails the sweep as unlisted. Nothing else is understood in that column, and a typo fails the gate
rather than silently disabling the line (`RobustnessTest::conditionHolds()`,
`testThePinFileIsWellFormed()`).

The other token that column takes is **`optional`**: the complaint follows the *environment*
rather than the value, so silence is not staleness and the line survives both. It is for the
cases no `gtk>=` can express — a GTK *built* with `G_ENABLE_DEBUG` or
`G_ENABLE_CONSISTENCY_CHECKS` (gvsbuild's is; a distro release build is not) says things about a
bad size that no version condition covers, and a relative path GLib resolves against the process
cwd is silent under ZTS, where PHP's `chdir()` moves only its own. The strict half of the gate is
unchanged for those lines: a complaint from a method with **no** line at all still fails, so
`optional` widens nothing but the "has gone quiet" direction.

Prefer a real fix to either token. A complaint the binding could have refused at the boundary is
a boundary to close, not a line to add — that is what the first paragraph of this file says, and
`optional` does not change it.

Messages that are about the machine and not about any value at all — no notification daemon, a
portal too old — are not pinned here: they are filtered in `GtkTestCase::ENVIRONMENTAL` (the
whole suite) and in `gateAgainstPinnedList()` (the sweeps).

Recording is deliberately *not* a committed switch: regenerating
wholesale is how a real leak gets pinned by accident, so it should cost a patch and a diff you
have to read.

## What stays pinned, and why

The remaining method lines are GTK reporting about the *data* it was handed, or
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
