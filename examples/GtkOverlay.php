<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkOverlay;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkOverlay - widgets stacked on top of one child.
 *
 * The main child gets the whole allocation; every add_overlay() widget floats
 * above it, placed by its own halign/valign. The page puts a badge on a card,
 * the buttons add/remove it and toggle measure_overlay (does the badge count
 * for the size request?) and clip_overlay (is it cut at the child's edge?).
 *
 *   bin/php-gtk4 examples/demo.php GtkOverlay
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkOverlay',
    'widgets stacked on top of one child - badges, toasts, corner labels',
    function (GtkWindow $win): GtkWidget {
        $overlay = new GtkOverlay();
        $overlay->set_vexpand(true);

        $card = Demo::label("<b>the child</b>\n<small>gets the whole allocation</small>");
        $card->add_css_class('card');
        $card->set_size_request(200, 120);
        $card->set_halign(GtkAlign::Center);
        $card->set_valign(GtkAlign::Center);
        $overlay->set_child($card);

        // The badge is bigger than the corner it sits in; clip_overlay decides what shows.
        $badge = Demo::label('<b> 42 </b>');
        $badge->add_css_class('suggested-action');
        $badge->set_halign(GtkAlign::End);
        $badge->set_valign(GtkAlign::Start);
        $badge->set_margin_top(4);
        $badge->set_margin_end(4);
        $overlay->add_overlay($badge);

        $status = Demo::label();
        // An overlay widget has the GtkOverlay as parent; removed, it has none.
        $describe = function () use ($overlay, $badge, $status): void {
            $shown = $badge->get_parent() !== null;
            $status->set_markup(sprintf(
                "<tt>overlay added     %s\nmeasure_overlay   %s\nclip_overlay      %s</tt>",
                $shown ? 'true' : 'false',
                $shown && $overlay->get_measure_overlay($badge) ? 'true' : 'false',
                $shown && $overlay->get_clip_overlay($badge) ? 'true' : 'false',
            ));
        };

        $toggle = GtkButton::new_with_label('add / remove overlay');
        $toggle->connect('clicked', function () use ($overlay, $badge, $describe): void {
            if ($badge->get_parent() !== null) {
                $overlay->remove_overlay($badge);
                Demo::status('remove_overlay()');
            } else {
                $overlay->add_overlay($badge);
                Demo::status('add_overlay()');
            }
            $describe();
        });
        $measure = GtkButton::new_with_label('measure_overlay');
        $measure->connect('clicked', function () use ($overlay, $badge, $describe): void {
            if ($badge->get_parent() !== null) {
                $overlay->set_measure_overlay($badge, !$overlay->get_measure_overlay($badge));
            }
            $describe();
        });
        $clip = GtkButton::new_with_label('clip_overlay');
        $clip->connect('clicked', function () use ($overlay, $badge, $describe): void {
            if ($badge->get_parent() !== null) {
                $overlay->set_clip_overlay($badge, !$overlay->get_clip_overlay($badge));
            }
            $describe();
        });

        $describe();

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->append($toggle);
        $buttons->append($measure);
        $buttons->append($clip);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($buttons);
        $page->append($overlay);
        return $page;
    },
    520,
    380,
);
