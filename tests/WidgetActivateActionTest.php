<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GApplicationFlags;
use Gtk4\GListStore;
use Gtk4\GSimpleAction;
use Gtk4\GtkApplication;
use Gtk4\GtkApplicationWindow;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkListView;
use Gtk4\GtkOrientation;
use Gtk4\GtkSingleSelection;
use Gtk4\PhpValue;

/**
 * GtkWidget::activate_action() converts its argument to the type the action declares, found
 * the way GTK's muxer finds the action: class actions up the widget chain, groups inserted
 * under the prefix, the application window and the application. Inference alone gave an
 * `int` the type `i`, which a `u` action refused with a CRITICAL.
 */
final class WidgetActivateActionTest extends GtkTestCase
{
    private static int $apps = 0;

    private function app(): GtkApplication
    {
        $id = sprintf('org.phpgtk4.activate.p%d.n%d', getmypid(), self::$apps++);
        $app = new GtkApplication($id, GApplicationFlags::NON_UNIQUE);
        $app->register(null);

        return $app;
    }

    /** @return array{GtkListView, GtkSingleSelection} five items, single selection */
    private function listView(): array
    {
        $store = new GListStore(PhpValue::class);
        for ($i = 0; $i < 5; $i++) {
            $store->append(new PhpValue($i));
        }
        $selection = new GtkSingleSelection($store);

        return [new GtkListView($selection, null), $selection];
    }

    /** A `u` action with a latch on the value the handler receives. */
    private function counting(string $name, mixed &$received): GSimpleAction
    {
        $action = new GSimpleAction($name, 'u');
        $action->connect('activate', function (GSimpleAction $a, mixed $parameter) use (&$received): void {
            $received = $parameter;
        });

        return $action;
    }

    public function testAClassActionParameterIsTypedByTheClass(): void
    {
        [$view] = $this->listView();
        $position = null;
        $view->connect('activate', function (GtkListView $v, int $p) use (&$position): void {
            $position = $p;
        });

        self::assertTrue($view->activate_action('list.activate-item', 3));

        self::assertSame(3, $position);
    }

    public function testATupleParameterComesFromAList(): void
    {
        [$view, $selection] = $this->listView();

        self::assertTrue($view->activate_action('list.select-item', [2, false, false]));

        self::assertSame(2, $selection->get_selected());
    }

    public function testAValueThatDoesNotFitTheTypeIsATypeError(): void
    {
        [$view] = $this->listView();

        $this->expectException(\TypeError::class);
        $view->activate_action('list.activate-item', ['not a position']);
    }

    public function testAMissingRequiredParameterIsAValueError(): void
    {
        [$view] = $this->listView();

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('takes a parameter of type u');
        $view->activate_action('list.activate-item');
    }

    public function testAnInsertedGroupTypesTheParameter(): void
    {
        $app = $this->app();
        $received = null;
        $app->add_action($this->counting('count', $received));
        $button = new GtkButton();
        $button->insert_action_group('x', $app);

        self::assertTrue($button->activate_action('x.count', 5));

        self::assertSame(5, $received);
    }

    public function testAGroupOnAnAncestorIsFound(): void
    {
        $app = $this->app();
        $received = null;
        $app->add_action($this->counting('count', $received));
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $button = new GtkButton();
        $box->append($button);
        $box->insert_action_group('x', $app);

        self::assertTrue($button->activate_action('x.count', 6));

        self::assertSame(6, $received);
    }

    public function testARemovedGroupIsForgotten(): void
    {
        $app = $this->app();
        $received = null;
        $app->add_action($this->counting('count', $received));
        $button = new GtkButton();
        $button->insert_action_group('x', $app);
        $button->insert_action_group('x', null);

        self::assertFalse($button->activate_action('x.count', 5));

        self::assertNull($received);
    }

    public function testTheApplicationPrefix(): void
    {
        $app = $this->app();
        $received = null;
        $app->add_action($this->counting('count', $received));
        $window = $this->window();
        $window->set_application($app);
        $button = new GtkButton();
        $window->set_child($button);

        self::assertTrue($button->activate_action('app.count', 7));

        self::assertSame(7, $received);
    }

    public function testTheApplicationWindowPrefix(): void
    {
        $app = $this->app();
        $received = null;
        $window = new GtkApplicationWindow($app);
        $window->add_action($this->counting('count', $received));
        $button = new GtkButton();
        $window->set_child($button);

        self::assertTrue($button->activate_action('win.count', 8));

        self::assertSame(8, $received);
        $window->destroy();
    }

    /** No action answers to the name: the value is inferred as before and GTK says no. */
    public function testAnUnknownActionIsFalseNotAnError(): void
    {
        $button = new GtkButton();

        self::assertFalse($button->activate_action('nope.count', 1));
        self::assertFalse($button->activate_action('nope.count'));
    }
}
