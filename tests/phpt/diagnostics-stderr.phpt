--TEST--
gtk4.diagnostics=stderr: GLib keeps its own writer and PHP is never told
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--INI--
gtk4.diagnostics=stderr
--FILE--
<?php
use Gtk4\Gtk;
use Gtk4\GtkEntry;

Gtk::init();
$entry = new GtkEntry();
$entry->set_text('hello');

var_dump(ini_get('gtk4.diagnostics'));

// stderr: the message goes out through g_log_writer_default in GLib's own format,
// so no PHP error is raised at all - the handler below is never reached.
set_error_handler(function (int $severity, string $message): bool {
    echo 'handler: ', $message, "\n";
    return true;
});
var_dump($entry->get_chars(1, 0));

// PHP_INI_ALL, so a script can change its mind: the next one is a PHP error again.
var_dump(ini_set('gtk4.diagnostics', 'warning'));
var_dump($entry->get_chars(1, 0));
restore_error_handler();
?>
--EXPECTF--
string(6) "stderr"

(%s:%d): Gtk-CRITICAL **: %s: gtk_editable_get_chars: assertion 'end_pos == -1 || end_pos >= start_pos' failed
string(0) ""
string(6) "stderr"
handler: Gtk: gtk_editable_get_chars: assertion 'end_pos == -1 || end_pos >= start_pos' failed
string(0) ""
