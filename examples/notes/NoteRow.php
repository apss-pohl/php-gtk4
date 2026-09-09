<?php

declare(strict_types=1);

namespace PhpGtk4\Examples\Notes;

use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\PangoEllipsizeMode;

/**
 * The widget of one list row: title and date on the first line, a preview on the second.
 *
 * GtkListView recycles rows, so build() runs once per row on screen and show() every
 * time a row is bound to a (different) note - the factory in NotesWindow calls them from
 * its `setup` and `bind` handlers.
 */
final class NoteRow
{
    public static function build(): GtkBox
    {
        $title = new GtkLabel();
        $title->add_css_class('note-row-title');
        $title->set_xalign(0.0);
        $title->set_hexpand(true);
        $title->set_ellipsize(PangoEllipsizeMode::End);

        $when = new GtkLabel();
        $when->add_css_class('dim-label');
        $when->add_css_class('caption');

        $head = new GtkBox(GtkOrientation::Horizontal, 8);
        $head->append($title);
        $head->append($when);

        $preview = new GtkLabel();
        $preview->add_css_class('dim-label');
        $preview->set_xalign(0.0);
        $preview->set_ellipsize(PangoEllipsizeMode::End);

        $row = new GtkBox(GtkOrientation::Vertical, 2);
        $row->set_margin_top(6);
        $row->set_margin_bottom(6);
        $row->set_margin_start(6);
        $row->set_margin_end(6);
        $row->append($head);
        $row->append($preview);
        return $row;
    }

    public static function show(GtkBox $row, Note $note): void
    {
        $head = $row->get_first_child();
        $preview = $row->get_last_child();
        $title = $head?->get_first_child();
        $when = $head?->get_last_child();
        if ($title instanceof GtkLabel) {
            $title->set_text($note->displayTitle());
        }
        if ($when instanceof GtkLabel) {
            $when->set_text(Note::when($note->modified));
        }
        if ($preview instanceof GtkLabel) {
            $preview->set_text($note->preview());
        }
    }
}
