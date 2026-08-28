<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkSizeRequestMode;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSizeRequestMode - how a widget wants to be measured: height-for-width, width-for-height or constant.
 *
 * get_request_mode() is the answer the layout machinery asks every widget for. A
 * wrapping label trades width for height, a button reports the constant size of its
 * child, an empty box is constant too.
 *
 *   bin/php-gtk4 examples/demo.php GtkSizeRequestMode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSizeRequestMode',
    'how a widget wants to be measured',
    function (GtkWindow $win): GtkWidget {
        $wrapping = new GtkLabel();
        $wrapping->set_text('a wrapping label wants height for its width');
        $wrapping->set_wrap(true);
        $button = GtkButton::new_with_label('a button');
        $empty = new GtkBox(GtkOrientation::Vertical, 0);

        $rows = [];
        foreach (['wrapping label' => $wrapping, 'button' => $button, 'empty box' => $empty] as $name => $widget) {
            $mode = $widget->get_request_mode();
            $rows[] = sprintf('%s: <b>GtkSizeRequestMode::%s</b> (%d)', $name, $mode->name, $mode->value);
        }
        $column = new GtkBox(GtkOrientation::Vertical, 8);
        $column->append($wrapping);
        $column->append($button);
        $column->append(Demo::label(implode("\n", $rows)));
        Demo::status(implode(', ', array_map(
            static fn(GtkSizeRequestMode $c): string => $c->name,
            GtkSizeRequestMode::cases(),
        )));
        return $column;
    },
    480,
    340,
);
