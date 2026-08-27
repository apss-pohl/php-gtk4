--TEST--
run-tests.php loads gtk4 alone: php-gtk3 is filtered out of the generated ini
--EXTENSIONS--
gtk4
--FILE--
<?php
var_dump(extension_loaded('gtk4'), extension_loaded('php-gtk3'), extension_loaded('gtk3'));
var_dump(str_starts_with(Gtk4\VERSION, '0.'));
?>
--EXPECT--
bool(true)
bool(false)
bool(false)
bool(true)
