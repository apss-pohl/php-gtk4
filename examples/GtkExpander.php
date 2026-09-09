<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkExpander;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkExpander - a disclosure triangle over a child.
 *
 * The child is built once and stays in the widget tree; expanding only shows it. The label can
 * be plain text, markup (use_markup), or a widget of its own - the third expander here has a
 * label widget rather than a string, which is why get_label() reads its text back.
 *
 *   bin/php-gtk4 examples/demo.php GtkExpander
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkExpander',
    'a disclosure triangle over a child',
    function (GtkWindow $win): GtkWidget {
        $column = new GtkBox(GtkOrientation::Vertical, 12);

        $plain = new GtkExpander('Plain label');
        $plain->set_child(Demo::label('The child is here all along - expanding shows it.'));

        $markup = new GtkExpander('<b>Markup</b> label');
        $markup->set_use_markup(true);
        $markup->set_child(Demo::label(
            'set_use_markup(true) makes the label Pango markup, like any GtkLabel.',
        ));

        $widget = new GtkExpander(null);
        $widget->set_label_widget(Demo::label(
            '<span foreground="' . Demo::ACCENT . '">a label widget</span>',
        ));
        $widget->set_child(Demo::label('Any widget can be the label; this one is a GtkLabel.'));
        $widget->set_expanded(true);

        foreach ([$plain, $markup, $widget] as $expander) {
            $column->append($expander);
            $expander->connect('notify::expanded', static function () use ($plain, $markup, $widget): void {
                Demo::status(sprintf(
                    'expanded: plain %s · markup %s · widget %s',
                    $plain->get_expanded() ? 'yes' : 'no',
                    $markup->get_expanded() ? 'yes' : 'no',
                    $widget->get_expanded() ? 'yes' : 'no',
                ));
            });
        }
        Demo::status('click the triangles');
        return $column;
    },
    460,
    300,
);
