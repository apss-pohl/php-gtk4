--TEST--
var_dump() of a boxed handle shows its fields via the get_debug_info handler
--EXTENSIONS--
gtk4
--FILE--
<?php
var_dump(new Gtk4\GdkRGBA('red'));
var_dump(new Gtk4\GdkRectangle(1, 2, 3, 4));
?>
--EXPECT--
object(Gtk4\GdkRGBA)#1 (4) {
  ["red"]=>
  float(1)
  ["green"]=>
  float(0)
  ["blue"]=>
  float(0)
  ["alpha"]=>
  float(1)
}
object(Gtk4\GdkRectangle)#1 (4) {
  ["x"]=>
  int(1)
  ["y"]=>
  int(2)
  ["width"]=>
  int(3)
  ["height"]=>
  int(4)
}
