--TEST--
A vfunc_*() the PHP class declares that no bound slot answers is reported, not silently ignored
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
use Gtk4\Gtk;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;

Gtk::init();

class Overrides extends GtkBox
{
    // Bound: GTK routes WidgetClass.size_allocate through it (on a widget without a layout
    // manager - see the method's docblock), so no warning.
    public function vfunc_size_allocate(int $width, int $height, int $baseline): void
    {
        parent::vfunc_size_allocate($width, $height, $baseline);
    }

    // A typo, and a slot that never existed. Naming a real member the generator happens to skip
    // would date the test - this one named vfunc_snapshot() until GtkSnapshot was bound.
    public function vfunc_size_alocate(int $width, int $height, int $baseline): void {}

    public function vfunc_no_such_gtk_slot(): void {}
}

// Reported once, when the class' GType is created - not again for a subclass that inherits them.
class Deeper extends Overrides {}

new Overrides(GtkOrientation::Vertical, 0);
new Deeper(GtkOrientation::Vertical, 0);
echo "constructed\n";
?>
--EXPECTF--
%aphp-gtk4: Overrides::vfunc_size_alocate() overrides no slot php-gtk4 binds on Php__Overrides - it will never be called
%aphp-gtk4: Overrides::vfunc_no_such_gtk_slot() overrides no slot php-gtk4 binds on Php__Overrides - it will never be called
constructed
