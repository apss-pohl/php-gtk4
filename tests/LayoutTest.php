<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GLib;
use Gtk4\GObject;
use Gtk4\GtkAdjustment;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkFixed;
use Gtk4\GtkFrame;
use Gtk4\GtkGrid;
use Gtk4\GtkLabel;
use Gtk4\GtkNotebook;
use Gtk4\GtkNotebookPage;
use Gtk4\GtkOrientation;
use Gtk4\GtkOverlay;
use Gtk4\GtkPackType;
use Gtk4\GtkPaned;
use Gtk4\GtkPolicyType;
use Gtk4\GtkPositionType;
use Gtk4\GtkRevealer;
use Gtk4\GtkScrollable;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkSeparator;
use Gtk4\GtkSizeGroup;
use Gtk4\GtkSizeGroupMode;
use Gtk4\GtkStack;
use Gtk4\GtkStackPage;
use Gtk4\GtkStackSidebar;
use Gtk4\GtkStackSwitcher;
use Gtk4\GtkStackTransitionType;
use Gtk4\GtkViewport;

/**
 * Wave 1 (layout containers): what the generated smoke tests cannot see - children going in
 * and coming out, the page objects GTK creates, out-parameter lists, and the constructors
 * the binding refuses.
 */
final class LayoutTest extends GtkTestCase
{
    public function testGridAttachAndQuery(): void
    {
        $grid = new GtkGrid();
        $a = new GtkLabel('a');
        $b = new GtkLabel('b');
        $grid->attach($a, 0, 0, 1, 1);
        $grid->attach($b, 1, 0, 2, 1);
        $grid->attach_next_to(new GtkLabel('c'), $a, GtkPositionType::Bottom, 1, 1);
        self::assertSame($b, $grid->get_child_at(1, 0));
        self::assertSame($b, $grid->get_child_at(2, 0), 'width 2 spans the next column');
        self::assertNull($grid->get_child_at(5, 5));
        self::assertSame([1, 0, 2, 1], $grid->query_child($b), 'query_child() -> [column, row, width, height]');
        $grid->insert_row(0);
        self::assertSame([0, 1, 1, 1], $grid->query_child($a), 'inserting a row above moves everything down');
        $grid->remove($a);
        self::assertNull($grid->get_child_at(0, 1));
        $grid->set_row_spacing(4);
        $grid->set_column_homogeneous(true);
        self::assertSame(4, $grid->get_row_spacing());
        self::assertTrue($grid->get_column_homogeneous());
    }

    public function testStackPagesComeFromTheStack(): void
    {
        $stack = new GtkStack();
        $one = new GtkLabel('one');
        $two = new GtkLabel('two');
        $page = $stack->add_titled($one, 'one', 'One');
        self::assertInstanceOf(GtkStackPage::class, $page);
        self::assertSame($one, $page->get_child());
        self::assertSame('one', $page->get_name());
        self::assertSame('One', $page->get_title());
        self::assertSame($page, $stack->get_page($one), 'get_page() returns the same handle');
        $stack->add_named($two, 'two');
        self::assertSame($one, $stack->get_visible_child(), 'the first child added is visible');
        $stack->set_visible_child_full('two', GtkStackTransitionType::None);
        self::assertSame('two', $stack->get_visible_child_name());
        self::assertSame($two, $stack->get_child_by_name('two'));
        self::assertNull($stack->get_child_by_name('nope'));
        $page->set_needs_attention(true);
        self::assertTrue($page->get_needs_attention());
        $stack->remove($two);
        // Unmapped, GTK does not pick a replacement for a removed visible child.
        self::assertNotSame($two, $stack->get_visible_child());

        $switcher = new GtkStackSwitcher();
        $switcher->set_stack($stack);
        self::assertSame($stack, $switcher->get_stack());
        $sidebar = new GtkStackSidebar();
        $sidebar->set_stack($stack);
        self::assertSame($stack, $sidebar->get_stack());
    }

    public function testStackPageCannotBeConstructed(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessageMatches('/private/');
        $class = self::opaque(static fn(): string => GtkStackPage::class)();
        new $class();
    }

    public function testNotebookPagesAndTabs(): void
    {
        $nb = new GtkNotebook();
        $first = new GtkLabel('first');
        $second = new GtkLabel('second');
        self::assertSame(0, $nb->append_page($first, new GtkLabel('Tab 1')));
        self::assertSame(1, $nb->append_page($second, null));
        self::assertSame(2, $nb->get_n_pages());
        self::assertSame(1, $nb->page_num($second));
        self::assertSame(-1, $nb->page_num(new GtkLabel('stranger')));
        self::assertSame('Tab 1', $nb->get_tab_label_text($first));
        $nb->set_tab_label_text($second, 'Tab 2');
        self::assertSame('Tab 2', $nb->get_tab_label_text($second));
        self::assertSame($second, $nb->get_nth_page(1));
        self::assertNull($nb->get_nth_page(7));

        $page = $nb->get_page($second);
        self::assertInstanceOf(GtkNotebookPage::class, $page);
        self::assertSame($second, $page->get_child());
        // get_pages() is a GListModel, but its concrete class (GtkNotebookPages) is GTK-private:
        // wrap() has no registered class for it and falls back to GObject (docs/TODO.md §7, the
        // list-model wave needs an interface fallback). The handle itself is fine.
        self::assertInstanceOf(GObject::class, $nb->get_pages());
        self::assertSame($nb->get_pages(), $nb->get_pages());

        $nb->set_tab_pos(GtkPositionType::Left);
        self::assertSame(GtkPositionType::Left, $nb->get_tab_pos());
        $action = new GtkButton();
        $nb->set_action_widget($action, GtkPackType::End);
        self::assertSame($action, $nb->get_action_widget(GtkPackType::End));
        self::assertNull($nb->get_action_widget(GtkPackType::Start));
        $nb->remove_page(0);
        self::assertSame(1, $nb->get_n_pages());
    }

    public function testNotebookPageCannotBeConstructed(): void
    {
        $this->expectException(\Error::class);
        $class = self::opaque(static fn(): string => GtkNotebookPage::class)();
        new $class();
    }

    public function testPanedChildren(): void
    {
        $paned = new GtkPaned(GtkOrientation::Horizontal);
        $start = new GtkLabel('start');
        $end = new GtkLabel('end');
        $paned->set_start_child($start);
        $paned->set_end_child($end);
        self::assertSame($start, $paned->get_start_child());
        self::assertSame($end, $paned->get_end_child());
        $paned->set_position(120);
        self::assertSame(120, $paned->get_position());
        $paned->set_end_child(null);
        self::assertNull($paned->get_end_child());
        self::assertSame(GtkOrientation::Horizontal, $paned->get_orientation());
    }

    public function testFrameLabelAndChild(): void
    {
        $frame = new GtkFrame('Title');
        self::assertSame('Title', $frame->get_label());
        $child = new GtkLabel('body');
        $frame->set_child($child);
        self::assertSame($child, $frame->get_child());
        $custom = new GtkButton();
        $frame->set_label_widget($custom);
        self::assertSame($custom, $frame->get_label_widget());
        self::assertNull($frame->get_label(), 'a label widget replaces the text label');
        $frame->set_label(null);
        self::assertNull($frame->get_label_widget());
    }

    public function testOverlayAddAndRemove(): void
    {
        $overlay = new GtkOverlay();
        $base = new GtkLabel('base');
        $badge = new GtkLabel('badge');
        $overlay->set_child($base);
        $overlay->add_overlay($badge);
        self::assertSame($base, $overlay->get_child());
        self::assertSame($overlay, $badge->get_parent());
        $overlay->set_clip_overlay($badge, true);
        self::assertTrue($overlay->get_clip_overlay($badge));
        $overlay->set_measure_overlay($badge, true);
        self::assertTrue($overlay->get_measure_overlay($badge));
        $overlay->remove_overlay($badge);
        self::assertNull($badge->get_parent());
    }

    public function testRevealerRevealsItsChild(): void
    {
        $revealer = new GtkRevealer();
        $revealer->set_transition_duration(0);
        $revealer->set_child(new GtkLabel('hidden'));
        self::assertFalse($revealer->get_reveal_child());
        $revealer->set_reveal_child(true);
        self::assertTrue($revealer->get_reveal_child());
        self::assertTrue($revealer->get_child_revealed(), 'zero duration: revealed at once');
    }

    public function testFixedPositions(): void
    {
        $fixed = new GtkFixed();
        $dot = new GtkLabel('.');
        $fixed->put($dot, 10.0, 20.0);
        self::assertSame([0.0, 0.0], $fixed->get_child_position($dot), 'positions exist once allocated');
        $fixed->move($dot, 30.5, 5.0);
        $win = $this->window();
        $win->set_child($fixed);
        $win->present();
        self::settle();
        self::assertSame([30.5, 5.0], $fixed->get_child_position($dot), 'get_child_position() -> [x, y]');
        $fixed->remove($dot);
        self::assertNull($dot->get_parent());
    }

    public function testSizeGroupMembership(): void
    {
        $group = new GtkSizeGroup(GtkSizeGroupMode::Horizontal);
        $a = new GtkButton();
        $b = new GtkButton();
        $group->add_widget($a);
        $group->add_widget($b);
        self::assertSame([$b, $a], $group->get_widgets(), 'get_widgets() is the members as handles (GTK prepends)');
        $group->set_mode(GtkSizeGroupMode::Both);
        self::assertSame(GtkSizeGroupMode::Both, $group->get_mode());
        $group->remove_widget($a);
        self::assertSame([$b], $group->get_widgets());
    }

    public function testScrolledWindowPolicyChildAndAdjustments(): void
    {
        $sw = new GtkScrolledWindow();
        $content = new GtkBox(GtkOrientation::Vertical, 0);
        $sw->set_child($content);
        // A non-scrollable child is wrapped in a GtkViewport, and get_child() returns that.
        $viewport = $sw->get_child();
        self::assertInstanceOf(GtkViewport::class, $viewport);
        self::assertSame($content, $viewport->get_child());
        $sw->set_policy(GtkPolicyType::Never, GtkPolicyType::Always);
        self::assertSame([GtkPolicyType::Never, GtkPolicyType::Always], $sw->get_policy(), 'get_policy() -> [h, v]');
        $vadj = $sw->get_vadjustment();
        self::assertInstanceOf(GtkAdjustment::class, $vadj);
        self::assertSame($vadj, $sw->get_vadjustment(), 'one handle per adjustment');
        $own = new GtkAdjustment(0.0, 0.0, 100.0, 1.0, 10.0, 10.0);
        $sw->set_vadjustment($own);
        self::assertSame($own, $sw->get_vadjustment());
    }

    public function testViewportIsScrollable(): void
    {
        $h = new GtkAdjustment(0.0, 0.0, 10.0, 1.0, 1.0, 1.0);
        $vp = new GtkViewport($h, null);
        self::assertInstanceOf(GtkScrollable::class, $vp);
        self::assertSame($h, $vp->get_hadjustment());
        self::assertInstanceOf(GtkAdjustment::class, $vp->get_vadjustment(), 'GTK creates the missing one');
        $vp->set_scroll_to_focus(false);
        self::assertFalse($vp->get_scroll_to_focus());
    }

    public function testAdjustmentSignalsAndClamping(): void
    {
        $adj = new GtkAdjustment(5.0, 0.0, 100.0, 1.0, 10.0, 20.0);
        $seen = [];
        $adj->connect('value-changed', function (GtkAdjustment $a) use (&$seen): void {
            $seen[] = $a->get_value();
        });
        $adj->set_value(50.0);
        $adj->set_value(500.0);
        self::assertSame([50.0, 80.0], $seen, 'value is clamped to upper - page_size');
        self::assertSame(1.0, $adj->get_minimum_increment());
        $adj->configure(1.0, 0.0, 10.0, 0.5, 2.0, 0.0);
        self::assertSame(10.0, $adj->get_upper());
        $adj->clamp_page(8.0, 12.0);
        self::assertSame(8.0, $adj->get_value(), 'clamp_page() scrolls so the range is visible');
    }

    public function testSeparatorOrientation(): void
    {
        $sep = new GtkSeparator(GtkOrientation::Vertical);
        self::assertSame(GtkOrientation::Vertical, $sep->get_orientation());
        $sep->set_orientation(GtkOrientation::Horizontal);
        self::assertSame(GtkOrientation::Horizontal, $sep->orientation);
    }

    /** Pump the main context so GTK allocates what was just presented. */
    private static function settle(): void
    {
        for ($i = 0; $i < 20; $i++) {
            GLib::main_context_iteration(false);
        }
    }
}
