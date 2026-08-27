--TEST--
gtk4.build_info/features are PHP_INI_SYSTEM; the Gtk4\ constants stay authoritative
--EXTENSIONS--
gtk4
--INI--
gtk4.build_info=spoofed
gtk4.features=spoofed
--FILE--
<?php
// PHP_INI_SYSTEM: php.ini (and -d) may override the directive...
var_dump(ini_get('gtk4.build_info'), ini_get('gtk4.features'));
// ...but the constants are baked in at configure time and cannot be.
var_dump(Gtk4\BUILD_INFO === 'spoofed', Gtk4\FEATURES === 'spoofed');
var_dump(str_contains(Gtk4\BUILD_INFO, 'git'));
var_dump((bool) preg_match('/^webkit=(yes|no) testing=(yes|no)$/', Gtk4\FEATURES));
// PHP_INI_SYSTEM is not runtime-writable.
var_dump(@ini_set('gtk4.features', 'webkit=yes'));
?>
--EXPECT--
string(7) "spoofed"
string(7) "spoofed"
bool(false)
bool(false)
bool(true)
bool(true)
bool(false)
