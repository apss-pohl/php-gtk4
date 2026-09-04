--TEST--
gtk4.diagnostics: the default warning is catchable, off drops, an unknown value is refused
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
use Gtk4\Gtk;
use Gtk4\GtkEntry;

Gtk::init();
$entry = new GtkEntry();
$entry->set_text('hello');

var_dump(ini_get('gtk4.diagnostics'));

// warning (the default): catchable like any other E_WARNING, and the script survives it.
set_error_handler(function (int $severity, string $message): bool {
    echo 'handler: ', $message, "\n";
    return true;
}, E_WARNING);
var_dump($entry->get_chars(1, 0));
restore_error_handler();

// PHP_INI_ALL, so a script can change its mind; off drops the message entirely.
var_dump(ini_set('gtk4.diagnostics', 'off'));
var_dump($entry->get_chars(1, 0));

// An unknown mode is refused rather than silently defaulting.
var_dump(@ini_set('gtk4.diagnostics', 'loud'));
var_dump(ini_get('gtk4.diagnostics'));
?>
--EXPECT--
string(7) "warning"
handler: Gtk: gtk_editable_get_chars: assertion 'end_pos == -1 || end_pos >= start_pos' failed
string(0) ""
string(7) "warning"
string(0) ""
bool(false)
string(3) "off"
