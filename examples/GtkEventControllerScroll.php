<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerScroll;
use Gtk4\GtkEventControllerScrollFlags;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEventControllerScroll - the wheel and the touchpad, as deltas.
 *
 * Constructed with GtkEventControllerScrollFlags: which axes to report, whether
 * to quantise to wheel clicks (DISCRETE) and whether to emit `decelerate` with a
 * velocity when a kinetic touchpad scroll lets go (KINETIC). `scroll` delivers
 * dx/dy and returns true to stop propagation; `scroll-begin`/`scroll-end`
 * bracket a smooth sequence, `get_unit()` says whether the deltas are wheel
 * clicks or surface pixels. Scroll over the pad and watch the running sum.
 *
 *   bin/php-gtk4 examples/demo.php GtkEventControllerScroll
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEventControllerScroll',
    'wheel and touchpad scrolling as dx/dy deltas, with kinetic deceleration',
    function (GtkWindow $win): GtkWidget {
        $pad = new GtkLabel('scroll over me');
        $pad->add_css_class('card');
        $pad->set_size_request(420, 160);

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);

        $controller = new GtkEventControllerScroll(
            GtkEventControllerScrollFlags::BOTH_AXES | GtkEventControllerScrollFlags::KINETIC,
        );
        $pad->add_controller($controller);

        $state = ['event' => '-', 'dx' => 0.0, 'dy' => 0.0, 'sumx' => 0.0, 'sumy' => 0.0, 'velocity' => '-'];
        $show = function () use ($readout, $controller, &$state): void {
            $readout->set_markup(sprintf(
                "<tt>flags       %d (BOTH_AXES|KINETIC)\nunit        %s\nlast        %s\n"
                . "delta       %7.2f  %7.2f\nsum         %7.2f  %7.2f\ndecelerate  %s</tt>",
                $controller->get_flags(),
                $controller->get_unit()->name,
                $state['event'],
                $state['dx'],
                $state['dy'],
                $state['sumx'],
                $state['sumy'],
                $state['velocity'],
            ));
        };

        $onScroll = function (GtkEventControllerScroll $c, float $dx, float $dy) use ($pad, $show, &$state): bool {
            $state['event'] = 'scroll';
            $state['dx'] = $dx;
            $state['dy'] = $dy;
            $state['sumx'] += $dx;
            $state['sumy'] += $dy;
            $pad->set_text(sprintf('%+.2f / %+.2f (%s)', $dx, $dy, $c->get_unit()->name));
            Demo::status('scroll ' . $c->get_unit()->name);
            $show();
            return true;    // consumed - the page behind the pad must not scroll too
        };
        $controller->connect('scroll', $onScroll);
        $controller->connect('scroll-begin', function (GtkEventControllerScroll $c) use ($show, &$state): void {
            $state['event'] = 'scroll-begin';
            $show();
        });
        $controller->connect('scroll-end', function (GtkEventControllerScroll $c) use ($show, &$state): void {
            $state['event'] = 'scroll-end';
            $show();
        });
        $onDecelerate = function (GtkEventControllerScroll $c, float $vx, float $vy) use ($show, &$state): void {
            $state['event'] = 'decelerate';
            $state['velocity'] = sprintf('%.1f  %.1f px/s', $vx, $vy);
            $show();
        };
        $controller->connect('decelerate', $onDecelerate);

        $show();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($readout);
        $page->append($pad);
        return $page;
    },
    600,
    380,
);
