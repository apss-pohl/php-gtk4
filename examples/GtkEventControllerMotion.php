<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerMotion;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEventControllerMotion - where the pointer is, without any button.
 *
 * `enter` and `leave` bracket the pointer's visit to the widget, `motion`
 * reports every move in between with widget-relative coordinates. The
 * controller also answers `contains_pointer()` (the pointer is over the widget
 * or a descendant) and `is_pointer()` (over the widget itself) at any time.
 * Move the mouse over the pad to see the coordinates run.
 *
 *   bin/php-gtk4 examples/demo.php GtkEventControllerMotion
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEventControllerMotion',
    'pointer enter / motion / leave with widget-relative coordinates',
    function (GtkWindow $win): GtkWidget {
        $pad = new GtkLabel('move the pointer over me');
        $pad->add_css_class('card');
        $pad->set_size_request(420, 180);

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);

        $controller = new GtkEventControllerMotion();
        $pad->add_controller($controller);

        $state = ['event' => '-', 'x' => 0.0, 'y' => 0.0, 'moves' => 0];
        $show = function () use ($readout, $controller, &$state): void {
            $readout->set_markup(sprintf(
                "<tt>last signal       %s\nposition          %6.1f  %6.1f\nmotion count      %d\n"
                . "contains_pointer  %s\nis_pointer        %s</tt>",
                $state['event'],
                $state['x'],
                $state['y'],
                $state['moves'],
                $controller->contains_pointer() ? 'true' : 'false',
                $controller->is_pointer() ? 'true' : 'false',
            ));
        };

        $onEnter = function (GtkEventControllerMotion $c, float $x, float $y) use ($pad, $show, &$state): void {
            $state['event'] = 'enter';
            $state['x'] = $x;
            $state['y'] = $y;
            $pad->set_text('welcome');
            Demo::status('pointer entered');
            $show();
        };
        $controller->connect('enter', $onEnter);
        $onMotion = function (GtkEventControllerMotion $c, float $x, float $y) use ($pad, $show, &$state): void {
            $state['event'] = 'motion';
            $state['x'] = $x;
            $state['y'] = $y;
            $state['moves']++;
            $pad->set_text(sprintf('%.0f, %.0f', $x, $y));
            $show();
        };
        $controller->connect('motion', $onMotion);
        $controller->connect('leave', function (GtkEventControllerMotion $c) use ($pad, $show, &$state): void {
            $state['event'] = 'leave';
            $pad->set_text('come back');
            Demo::status('pointer left');
            $show();
        });

        $show();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($readout);
        $page->append($pad);
        return $page;
    },
    600,
    380,
);
