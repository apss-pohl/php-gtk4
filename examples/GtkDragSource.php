<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkContentProvider;
use Gtk4\GdkDragAction;
use Gtk4\GtkBox;
use Gtk4\GtkDragSource;
use Gtk4\GtkDropTarget;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkDragSource - dragging a PHP value from one widget to another.
 *
 * A drag carries a *value*, not a string of bytes: GtkDragSource hands GTK a
 * GdkContentProvider built from an ordinary PHP value, and the GtkDropTarget on the other side
 * declares which types it accepts and gets that value back. GIR spells both sides with a GValue
 * and a GType, neither of which PHP has a word for, so the payload is the PHP value itself and
 * the type is named the way a list store names its item type - "string" here.
 *
 *   bin/php-gtk4 examples/demo.php GtkDragSource
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkDragSource',
    'dragging a PHP value from one widget to another',
    function (GtkWindow $win): GtkWidget {
        $page = new GtkBox(GtkOrientation::Vertical, 12);

        $source = new GtkLabel('Drag me');
        $target = new GtkLabel('… and drop here');

        // The source side: what is offered, and under which actions.
        $drag = new GtkDragSource();
        $drag->set_actions(GdkDragAction::COPY);
        $drag->set_content(GdkContentProvider::new_for_value('a value from PHP'));
        $source->add_controller($drag);

        // The drop side: which types are accepted. `drop` hands back the PHP value.
        $drop = new GtkDropTarget('string', GdkDragAction::COPY);
        $drop->connect('drop', function (GtkDropTarget $self, mixed $value, float $x, float $y) use ($target): bool {
            $text = is_string($value) ? $value : get_debug_type($value);
            $target->set_label(sprintf('got "%s" at %.0f, %.0f', $text, $x, $y));
            Demo::status('dropped: ' . $text);
            return true;
        });
        $target->add_controller($drop);

        $page->append($source);
        $page->append($target);

        $note = new GtkLabel();
        $note->set_markup(
            '<small>the payload is a PHP value, typed as <tt>'
            . implode(', ', $drop->get_gtypes())
            . '</tt>; GdkContentProvider::new_for_value() is what wraps it</small>',
        );
        $note->set_wrap(true);
        $page->append($note);

        Demo::status('drag the first label onto the second');

        return $page;
    },
);
