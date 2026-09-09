<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkRectangle;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkMenuButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkPopover;
use Gtk4\GtkPositionType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPopover - a bubble anchored to a widget.
 *
 * A popover holds one child (set_child) and points at a spot on its parent: by
 * default the parent's whole allocation, or a GdkRectangle given to
 * set_pointing_to(). set_position() picks the side, set_has_arrow() the tip,
 * set_autohide() whether a click elsewhere closes it, set_offset() nudges it.
 * A GtkMenuButton is the parent here (it adopts the popover through set_popover);
 * the other button pops it up and cycles the settings, and the readout shows
 * them plus the closed signal.
 *
 *   bin/php-gtk4 examples/demo.php GtkPopover
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPopover',
    'a bubble anchored to a widget',
    function (GtkWindow $win): GtkWidget {
        $readout = Demo::label();
        $closed = 0;

        $popover = new GtkPopover();
        $popover->set_child(Demo::label("<b>a GtkPopover</b>\nclick outside to close"));
        $popover->connect('closed', function () use (&$closed): void {
            $closed++;
        });

        $anchor = new GtkMenuButton();
        $anchor->set_label('anchor (a GtkMenuButton)');
        $anchor->set_popover($popover);
        $anchor->set_size_request(260, 80);
        $anchor->set_halign(GtkAlign::Center);

        $render = function () use ($readout, $popover, &$closed): void {
            $rect = $popover->get_pointing_to();
            [$dx, $dy] = $popover->get_offset();
            $readout->set_markup(sprintf(
                "<tt>position     %s\npointing_to  %s\nhas_arrow    %s\nautohide     %s\n"
                . "offset       %d, %d\nclosed       %d times</tt>",
                $popover->get_position()->name,
                $rect === null
                    ? 'null (whole parent)'
                    : sprintf('%d,%d %dx%d', $rect->x, $rect->y, $rect->width, $rect->height),
                $popover->get_has_arrow() ? 'true' : 'false',
                $popover->get_autohide() ? 'true' : 'false',
                $dx,
                $dy,
                $closed,
            ));
        };
        $popover->connect('closed', function () use ($render): void {
            $render();
        });

        // Every click changes one thing and pops the popover up so the change is visible.
        $step = 0;
        $next = GtkButton::new_with_label('popup() with the next setting');
        $next->connect('clicked', function () use ($popover, &$step, $render): void {
            match ($step++ % 6) {
                0 => $popover->set_position(GtkPositionType::Right),
                1 => $popover->set_pointing_to(new GdkRectangle(10, 10, 20, 20)),
                2 => $popover->set_has_arrow(false),
                3 => $popover->set_offset(40, 0),
                4 => $popover->set_autohide(false),
                default => (function () use ($popover): void {
                    $popover->set_position(GtkPositionType::Bottom);
                    $popover->set_pointing_to(null);
                    $popover->set_has_arrow(true);
                    $popover->set_offset(0, 0);
                    $popover->set_autohide(true);
                })(),
            };
            $popover->popup();
            $render();
        });
        $hide = GtkButton::new_with_label('popdown()');
        $hide->connect('clicked', function () use ($popover): void {
            $popover->popdown();
        });

        $render();
        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->set_halign(GtkAlign::Center);
        $buttons->append($next);
        $buttons->append($hide);
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($anchor);
        $page->append($buttons);
        $page->append($readout);
        return $page;
    },
    520,
    420,
);
