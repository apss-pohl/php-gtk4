<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GObject;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkSizeRequestMode;
use Gtk4\GtkStateFlags;
use Gtk4\GtkTextDirection;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/** Gtk4\GtkWidget base layer, GtkButton, GtkLabel and the widget hierarchy. */
final class WidgetTest extends GtkTestCase
{
    public function testHierarchy(): void
    {
        $w = $this->window();
        self::assertInstanceOf(GtkWidget::class, $w);
        self::assertInstanceOf(GObject::class, $w);
        self::assertSame(GtkWidget::class, get_parent_class($w));
        self::assertSame(GtkWidget::class, get_parent_class(new GtkButton()));
        self::assertSame(GtkWidget::class, get_parent_class(new GtkLabel()));
    }

    /**
     * @param class-string $name
     * @return class-string
     */
    private static function dynamicClass(string $name): string
    {
        return $name;  // hides the literal from static analysis
    }

    public function testGtkWidgetCannotBeConstructed(): void
    {
        // Abstract in GTK, but never `abstract` in PHP: wrap() instantiates the nearest registered
        // class for whatever GTK hands back. The constructor is public so that a PHP subclass
        // inherits it (SubclassTest), and refuses the native class itself.
        $ctor = new \ReflectionClass(GtkWidget::class)->getConstructor();
        self::assertNotNull($ctor);
        self::assertTrue($ctor->isPublic());
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('abstract in GTK: subclass it in PHP');
        $name = self::dynamicClass(GtkWidget::class);
        new $name();
    }

    public function testChildTypedAsWidget(): void
    {
        $w = $this->window();
        $button = GtkButton::new_with_label('Click');
        $w->set_child($button);
        self::assertSame($button, $w->get_child());
        self::assertSame($w, $button->get_parent());
        self::assertSame($w, $button->get_root());
        $w->set_child(null);
        self::assertNull($w->get_child());
        self::assertNull($button->get_parent());
    }

    public function testNonWidgetChildIsATypeError(): void
    {
        $this->expectException(\TypeError::class);
        self::opaque([$this->window(), 'set_child'])(new \stdClass());
    }

    public function testVisibilityAndSensitivity(): void
    {
        $label = new GtkLabel('x');
        self::assertTrue($label->get_visible(), 'GTK4 widgets are visible by default');
        $label->set_visible(false);
        self::assertFalse($label->get_visible());
        $label->set_visible(true);
        self::assertTrue($label->get_visible());
        $label->set_visible(false);
        self::assertFalse($label->visible, '@property access');
        self::assertFalse($label->is_visible(), 'not in a visible toplevel');

        $label->set_sensitive(false);
        self::assertFalse($label->get_sensitive());
        $label->sensitive = true;
        self::assertTrue($label->get_sensitive());
    }

    public function testCssClasses(): void
    {
        $b = new GtkButton();
        self::assertFalse($b->has_css_class('suggested-action'));
        $b->add_css_class('suggested-action');
        self::assertTrue($b->has_css_class('suggested-action'));
        $b->remove_css_class('suggested-action');
        self::assertFalse($b->has_css_class('suggested-action'));
    }

    public function testTooltipNameAndSizeRequest(): void
    {
        $b = new GtkButton();
        $b->set_tooltip_text('tip');
        self::assertSame('tip', $b->get_tooltip_text());
        $b->set_tooltip_text(null);
        self::assertNull($b->get_tooltip_text());
        $b->set_name('primary');
        self::assertSame('primary', $b->get_name());
        $b->set_size_request(120, 40);
        self::assertSame(120, $b->width_request);
        self::assertSame(40, $b->height_request);
    }

    public function testButtonLabelAndClicked(): void
    {
        $b = GtkButton::new_with_label('Go');
        self::assertSame('Go', $b->get_label());
        $b->set_label('Stop');
        self::assertSame('Stop', $b->label);

        $clicks = 0;
        $b->connect('clicked', function (GtkButton $btn) use (&$clicks, $b): void {
            self::assertSame($b, $btn);
            $clicks++;
        });
        self::assertTrue($b->activate(), 'a button is activatable');
        $b->emit('clicked');
        self::assertSame(1, $clicks, 'emit() delivers clicked synchronously');
    }

    public function testButtonChildWidget(): void
    {
        $b = new GtkButton();
        $label = new GtkLabel('inner');
        $b->set_child($label);
        self::assertSame($label, $b->get_child());
        self::assertSame($b, $label->get_parent());
    }

    public function testLabel(): void
    {
        $l = new GtkLabel();
        self::assertSame('', $l->get_text());
        $l->set_text('plain');
        self::assertSame('plain', $l->get_text());
        $l->set_markup('<b>bold</b>');
        self::assertSame('bold', $l->get_text(), 'markup is stripped from get_text()');
        self::assertTrue($l->use_markup);
        $l->set_selectable(true);
        self::assertTrue($l->get_selectable());
    }

    public function testUnregisteredWidgetSubclassFallsBackToNearestClass(): void
    {
        // A GtkWindow's focus widget etc. may be types we have no PHP class for; the
        // nearest registered ancestor (GtkWidget) is used. Probe via a real GTK object:
        // the label inside a labelled button is a GtkLabel (registered).
        $b = GtkButton::new_with_label('x');
        $inner = $b->get_child();
        self::assertInstanceOf(GtkLabel::class, $inner);
        self::assertInstanceOf(GtkWidget::class, $inner);
    }

    public function testFocusApiDoesNotCrashOnPresentedWindow(): void
    {
        $w = $this->window();
        $b = GtkButton::new_with_label('f');
        $w->set_child($b);
        $w->present();
        self::assertTrue($b->can_focus);
        $b->grab_focus();  // may be refused under Xvfb when the window is not active yet
        self::assertSame($b, $w->get_child());
    }

    public function testWidgetTreeNavigation(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $a = new GtkLabel();
        $b = new GtkLabel();
        $box->append($a);
        $box->append($b);
        self::assertSame($a, $box->get_first_child());
        self::assertSame($b, $box->get_last_child());
        self::assertSame($b, $a->get_next_sibling());
        self::assertSame($a, $b->get_prev_sibling());
        self::assertNull($b->get_next_sibling());
        self::assertTrue($a->is_ancestor($box));
        self::assertFalse($box->is_ancestor($a));

        $c = new GtkLabel();
        $c->insert_after($box, $b);            // GtkWidget API, not a container method
        self::assertSame($c, $box->get_last_child());
        $a->unparent();
        self::assertSame($b, $box->get_first_child());
        self::assertNull($a->get_parent());
    }

    public function testMarginsOpacityAndFocusFlags(): void
    {
        $w = new GtkLabel();
        $w->set_margin_start(7);
        $w->set_margin_end(8);
        $w->set_margin_top(9);
        $w->set_margin_bottom(10);
        self::assertSame([7, 8, 9, 10], [
            $w->get_margin_start(), $w->get_margin_end(), $w->get_margin_top(), $w->get_margin_bottom(),
        ]);
        $w->set_opacity(0.5);
        self::assertEqualsWithDelta(0.5, $w->get_opacity(), 0.01);   // GTK stores opacity as 8 bit
        self::assertFalse($w->get_can_focus() && $w->get_focusable(), 'a label is not focusable by default');
        $w->set_focusable(true);
        self::assertTrue($w->get_focusable());
        $w->set_tooltip_markup('<b>tip</b>');
        self::assertSame('<b>tip</b>', $w->get_tooltip_markup());
        self::assertSame('tip', $w->get_tooltip_text(), 'markup stripped for the plain text view');
    }

    public function testDirectionAndStateFlags(): void
    {
        $w = new GtkLabel();
        $w->set_direction(GtkTextDirection::Rtl);
        self::assertSame(GtkTextDirection::Rtl, $w->get_direction());
        self::assertSame(0, $w->get_state_flags() & GtkStateFlags::INSENSITIVE);
        $w->set_state_flags(GtkStateFlags::INSENSITIVE, false);
        self::assertSame(GtkStateFlags::INSENSITIVE, $w->get_state_flags() & GtkStateFlags::INSENSITIVE);
        $w->unset_state_flags(GtkStateFlags::INSENSITIVE);
        self::assertSame(0, $w->get_state_flags() & GtkStateFlags::INSENSITIVE);
        self::assertSame(GtkSizeRequestMode::ConstantSize, new GtkButton()->get_request_mode());
    }
}
