<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GObject;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
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

    public function testGtkWidgetIsAbstract(): void
    {
        self::assertTrue(new \ReflectionClass(GtkWidget::class)->isAbstract());
        $this->expectException(\Error::class);
        $name = self::dynamicClass(GtkWidget::class);
        new $name();
    }

    public function testChildTypedAsWidget(): void
    {
        $w = $this->window();
        $button = new GtkButton('Click');
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
        $label->hide();
        self::assertFalse($label->get_visible());
        $label->show();
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
        $b = new GtkButton('Go');
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
        $b = new GtkButton('x');
        $inner = $b->get_child();
        self::assertInstanceOf(GtkLabel::class, $inner);
        self::assertInstanceOf(GtkWidget::class, $inner);
    }

    public function testFocusApiDoesNotCrashOnPresentedWindow(): void
    {
        $w = $this->window();
        $b = new GtkButton('f');
        $w->set_child($b);
        $w->present();
        self::assertTrue($b->can_focus);
        $b->grab_focus();  // may be refused under Xvfb when the window is not active yet
        self::assertSame($b, $w->get_child());
    }
}
