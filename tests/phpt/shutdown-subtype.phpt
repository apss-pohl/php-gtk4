--TEST--
RSHUTDOWN with a live PHP subclass: GTK holds the widget, the vfunc thunks are gone first
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
// A `class X extends GtkWidget` gets its own GType at the first `new` and installs thunks
// into the class struct (src/core/subtype). If one of its instances is still in a widget
// tree when the request ends, nothing may call back into PHP after Zend has torn down -
// a regression here is a crash at shutdown, which PHPUnit reports as "the runner died".
use Gtk4\Gtk;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

class Square extends GtkWidget
{
    public function vfunc_measure(GtkOrientation $orientation, int $for_size): array
    {
        return [40, 40, -1, -1];
    }
}

Gtk::init();
$square = new Square();
$square->connect('destroy', function (): void {
    echo "destroy handler ran while PHP was still there\n";
});

$box = new GtkBox(GtkOrientation::Horizontal, 0);
$box->append($square);
$window = new GtkWindow();
$window->set_child($box);
$window->present();

echo 'measure: ', implode(',', $square->measure(GtkOrientation::Horizontal, -1)), "\n";
echo "the window, the box and the subclass instance all outlive this script\n";
?>
--EXPECT--
measure: 40,40,-1,-1
the window, the box and the subclass instance all outlive this script
