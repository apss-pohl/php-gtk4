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

and revert the branch. Keys are `<class>::<method>#arguments` or `#values` for the two method
sweeps and `<class>::$<property>#properties` for the property-write sweep.

Recording is deliberately *not* a committed switch: regenerating
wholesale is how a real leak gets pinned by accident, so it should cost a patch and a diff you
have to read.
