<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkActionBar;
use Gtk4\GtkAspectFrame;
use Gtk4\GtkButton;
use Gtk4\GtkCenterBox;
use Gtk4\GtkExpander;
use Gtk4\GtkLabel;

/**
 * The four single-purpose containers of the same wave as {@see ListBoxTest} - what the generated
 * smoke tests cannot see, because packing a child is not a setter with a getter beside it.
 */
final class ContainerWidgetsTest extends GtkTestCase
{
    /** A disclosure triangle: a label of its own, and a child it shows or hides. */
    public function testAnExpanderHoldsALabelAndAChild(): void
    {
        $expander = new GtkExpander('More');
        $expander->set_child(new GtkLabel('hidden until expanded'));

        self::assertSame('More', $expander->get_label());
        self::assertInstanceOf(GtkLabel::class, $expander->get_child());
        self::assertFalse($expander->get_expanded(), 'an expander starts closed');

        $expander->set_expanded(true);
        self::assertTrue($expander->get_expanded());
    }

    /**
     * The label can be a widget instead of text - and when that widget is a GtkLabel, GTK still
     * answers get_label() with its text rather than with null.
     */
    public function testAnExpanderLabelCanBeAWidget(): void
    {
        $expander = new GtkExpander(null);
        self::assertNull($expander->get_label(), 'no label yet');

        $expander->set_label_widget(new GtkLabel('rich'));
        self::assertInstanceOf(GtkLabel::class, $expander->get_label_widget());
        self::assertSame('rich', $expander->get_label(), "GTK reads the label widget's text");

        $expander->set_label_widget(new GtkButton());
        self::assertNull($expander->get_label(), 'a label widget that is not a label has no text');
    }

    /** An action bar packs to both ends and keeps a centre widget of its own. */
    public function testAnActionBarPacksToBothEndsAndTheCentre(): void
    {
        $bar = new GtkActionBar();
        $bar->pack_start(new GtkButton());
        $bar->pack_end(new GtkButton());
        $bar->set_center_widget(new GtkLabel('centre'));

        self::assertInstanceOf(GtkLabel::class, $bar->get_center_widget());
        self::assertTrue($bar->get_revealed(), 'a bar starts revealed');

        $bar->set_revealed(false);
        self::assertFalse($bar->get_revealed());
    }

    /** A packed child can be taken out again, which is what remove() is for. */
    public function testAnActionBarGivesAChildBack(): void
    {
        $bar = new GtkActionBar();
        $button = new GtkButton();
        $bar->pack_start($button);

        $bar->remove($button);
        self::assertNull($button->get_parent(), 'the button is out of the bar');
    }

    /** An aspect frame keeps its child's shape, either its own ratio or the child's. */
    public function testAnAspectFrameKeepsARatio(): void
    {
        $frame = new GtkAspectFrame(0.5, 0.5, 16 / 9, false);
        $frame->set_child(new GtkLabel('16:9'));

        self::assertEqualsWithDelta(16 / 9, $frame->get_ratio(), 0.0001);
        self::assertFalse($frame->get_obey_child());
        self::assertSame(0.5, $frame->get_xalign());

        $frame->set_obey_child(true);
        self::assertTrue($frame->get_obey_child(), 'now the child decides');
    }

    /** A centre box is three slots, and each one answers with what was put in it. */
    public function testACenterBoxHasThreeSlots(): void
    {
        $box = new GtkCenterBox();
        $start = new GtkLabel('start');
        $centre = new GtkButton();
        $end = new GtkLabel('end');
        $box->set_start_widget($start);
        $box->set_center_widget($centre);
        $box->set_end_widget($end);

        self::assertSame($start, $box->get_start_widget());
        self::assertSame($centre, $box->get_center_widget());
        self::assertSame($end, $box->get_end_widget());

        $box->set_center_widget(null);
        self::assertNull($box->get_center_widget(), 'a slot can be emptied again');
    }
}
