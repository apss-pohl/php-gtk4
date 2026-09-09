<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GObject;
use Gtk4\GParamSpec;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkToggleButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkToggleButton - a button that stays pressed.
 *
 * A GtkButton with one bit of state: `active`. It is what a tool palette or a
 * "bold / italic" bar is made of, and like GtkCheckButton it turns into a radio
 * group through set_group(). The page has a free toggle wired to `toggled` and a
 * three-way group wired to `notify::active`, both writing into the label.
 *
 *   bin/php-gtk4 examples/demo.php GtkToggleButton
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkToggleButton',
    'a button that stays pressed; set_group() makes a radio bar',
    function (GtkWindow $win): GtkWidget {
        $status = Demo::label();

        // One free toggle, tracking its own state through the `toggled` signal.
        $bold = GtkToggleButton::new_with_label('Bold');
        $bold->set_halign(GtkAlign::Center);

        // A group: pressing one releases the others. Exactly one is always down.
        $bar = new GtkBox(GtkOrientation::Horizontal, 0);
        $bar->add_css_class('linked');
        $bar->set_halign(GtkAlign::Center);
        $aligns = [];
        $first = null;
        foreach (['Left', 'Center', 'Right'] as $name) {
            $toggle = GtkToggleButton::new_with_mnemonic('_' . $name);
            $toggle->set_group($first);
            $first ??= $toggle;
            $aligns[$name] = $toggle;
            $bar->append($toggle);
        }
        $aligns['Center']->set_active(true);

        $describe = function () use ($status, $bold, $aligns): void {
            $down = array_keys(array_filter(
                $aligns,
                static fn(GtkToggleButton $t): bool => $t->get_active(),
            ));
            $status->set_markup(sprintf(
                "<span weight='%s'>The quick brown fox</span>\n\n<tt>bold   %s\ngroup  %s</tt>",
                $bold->get_active() ? 'bold' : 'normal',
                $bold->get_active() ? 'true' : 'false',
                $down === [] ? '(none)' : $down[0],
            ));
        };

        // `toggled` is the widget's own signal ...
        $bold->connect('toggled', function (GtkToggleButton $self) use ($describe): void {
            Demo::status('toggled -> ' . ($self->get_active() ? 'active' : 'inactive'));
            $describe();
        });
        // ... and `notify::active` the generic GObject one; both see every change.
        foreach ($aligns as $name => $toggle) {
            $toggle->connect('notify::active', function (GObject $obj, GParamSpec $spec) use ($name, $describe): void {
                if ($obj instanceof GtkToggleButton && $obj->get_active()) {
                    Demo::status(sprintf('notify::%s -> %s', $spec->get_name(), $name));
                }
                $describe();
            });
        }

        // set_active() from the script emits the same signals as a click.
        $step = 0;
        GLib::timeout_add(1200, function () use ($bold, $aligns, &$step): bool {
            $names = array_keys($aligns);
            $bold->set_active($step % 2 === 0);
            $aligns[$names[$step % 3]]->set_active(true);
            $step++;
            return true;
        });

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($bold);
        $page->append($bar);
        $page->append($status);
        return $page;
    },
    480,
    300,
);
