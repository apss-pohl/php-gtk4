--TEST--
gtk4.diagnostics=off from the ini: PHP's scanner makes it "", and the mode is still Off
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--INI--
gtk4.diagnostics=off
--FILE--
<?php
use Gtk4\Gtk;
use Gtk4\GtkEntry;

Gtk::init();

// `off` in a php.ini (or a -d) never reaches the extension as the word: the ini scanner reads it
// as the boolean false and stores the empty string. What matters is that the mode took, not what
// ini_get() echoes back.
var_dump(ini_get('gtk4.diagnostics'));

$entry = new GtkEntry();
$entry->get_chars(1, 0);   // a GLib CRITICAL - dropped in this mode
echo "still running\n";

// Switching back at runtime uses the word, and reports again.
var_dump(ini_set('gtk4.diagnostics', 'warning'));
$entry->get_chars(1, 0);
echo "done\n";
?>
--EXPECTF--
string(0) ""
still running
string(0) ""

Warning: Gtk: gtk_editable_get_chars: assertion 'end_pos == -1 || end_pos >= start_pos' failed in %sdiagnostics-off-ini.php on line %d
done
