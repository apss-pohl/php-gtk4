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

    // Not bound: Gtk.Snapshot is not in the generator's closure, so nothing installs a thunk.
    public function vfunc_snapshot(mixed $snapshot): void {}

    // A typo: neither GTK nor php-gtk4 has a slot by that name.
    public function vfunc_size_alocate(int $width, int $height, int $baseline): void {}
}

// Reported once, when the class' GType is created - not again for a subclass that inherits them.
class Deeper extends Overrides {}

new Overrides(GtkOrientation::Vertical, 0);
new Deeper(GtkOrientation::Vertical, 0);
echo "constructed\n";
?>
--EXPECTF--
%aphp-gtk4: Overrides::vfunc_snapshot() overrides no slot php-gtk4 binds on Php__Overrides - it will never be called
%aphp-gtk4: Overrides::vfunc_size_alocate() overrides no slot php-gtk4 binds on Php__Overrides - it will never be called
constructed
