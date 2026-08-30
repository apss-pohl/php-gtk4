--TEST--
A self-referential or too deeply nested array is a ValueError, not a SIGSEGV
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
// Both of these used to recurse through php_to_variant until the C stack was gone
// (security review, 2026-08-30). They live here rather than in PHPUnit because the
// failure mode being guarded is a crash: run-tests.php names the test and carries on,
// PHPUnit would simply die mid-run and blame whatever came next.
use Gtk4\GSimpleAction;
use Gtk4\Gtk;

Gtk::init();
$action = new GSimpleAction('x', 'v');

$cyclic = [1];
$cyclic[] = &$cyclic;
try {
    $action->activate($cyclic);
    echo "cyclic: NOT REJECTED\n";
} catch (ValueError $e) {
    echo "cyclic: ", $e->getMessage(), "\n";
}

$deep = 1;
for ($i = 0; $i < 100_000; $i++) {
    $deep = [$deep];
}
try {
    $action->activate($deep);
    echo "deep: NOT REJECTED\n";
} catch (ValueError $e) {
    echo "deep: ", $e->getMessage(), "\n";
}

// Depth the guard must not object to.
$ok = 'leaf';
for ($i = 0; $i < 30; $i++) {
    $ok = [$ok];
}
$action->activate($ok);
echo "30 levels: converted\n";
echo "still alive\n";
?>
--EXPECT--
cyclic: array contains itself and cannot be converted to a GVariant
deep: array is nested too deeply for a GVariant (limit 64)
30 levels: converted
still alive
