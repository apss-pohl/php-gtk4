--TEST--
gtk4.diagnostics=fatal: a GLib CRITICAL is a PHP fatal at the offending line
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--INI--
gtk4.diagnostics=fatal
--FILE--
<?php
use Gtk4\Gtk;
use Gtk4\GtkEntry;

Gtk::init();
$entry = new GtkEntry();
$entry->set_text('hello');
// GTK refuses end < start with a g_return_if_fail(). The report is raised at the VM's next
// safe point, which is this line - not wherever inside GTK the precondition tripped.
$entry->get_chars(1, 0);
echo "never reached\n";
?>
--EXPECTF--
Fatal error: Gtk: gtk_editable_get_chars: assertion 'end_pos == -1 || end_pos >= start_pos' failed in %sdiagnostics-fatal.php on line 10
