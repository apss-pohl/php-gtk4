--TEST--
Gtk::init() returns false instead of aborting when the backend cannot connect
--EXTENSIONS--
gtk4
--ENV--
GDK_BACKEND=x11
DISPLAY=
WAYLAND_DISPLAY=
--FILE--
<?php
var_dump(Gtk4\Gtk::init());
echo "still alive\n";
?>
--EXPECT--
bool(false)
still alive
