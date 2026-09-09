<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListItemFactory;
use Gtk4\GtkListView;
use Gtk4\GtkOrientation;
use Gtk4\GtkPolicyType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkListItemFactory - the type every list widget asks for its rows.
 *
 * The base class of the factories; php-gtk4 binds the signal-driven one
 * (GtkSignalListItemFactory), and that is what every `set_factory()` takes.
 * Swapping the factory of a live GtkListView rebuilds every row, which is what
 * the button below does: the same model, drawn plain, then as a bar chart, then
 * as one line of markup.
 *
 *   bin/php-gtk4 examples/demo.php GtkListItemFactory
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkListItemFactory',
    'the type every list widget asks for its rows',
    function (GtkWindow $win): GtkWidget {
        $sizes = ['Mercury 4879', 'Venus 12104', 'Earth 12742', 'Mars 6779',
            'Jupiter 139820', 'Saturn 116460', 'Uranus 50724', 'Neptune 49244'];
        $model = new GtkSingleSelection(new GtkStringList($sizes));

        /** @param callable(string): string $render */
        $factoryFor = function (callable $render): GtkSignalListItemFactory {
            $factory = new GtkSignalListItemFactory();
            $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
                $label = new GtkLabel();
                $label->set_halign(GtkAlign::Start);
                $item->set_child($label);
            });
            $factory->connect('bind', function (
                GtkSignalListItemFactory $f,
                GtkListItem $item,
            ) use ($render): void {
                $label = $item->get_child();
                $object = $item->get_item();
                if ($label instanceof GtkLabel && $object instanceof GtkStringObject) {
                    $markup = $render($object->get_string());
                    $label->set_markup(is_string($markup) ? $markup : '');
                }
            });
            return $factory;
        };

        $plain = $factoryFor(static fn(string $s): string => htmlspecialchars($s));
        $bars = $factoryFor(static function (string $s): string {
            [$name, $km] = explode(' ', $s);
            return sprintf(
                '<tt>%-8s %s</tt>',
                htmlspecialchars($name),
                str_repeat('█', max(1, (int) ((int) $km / 12000))),
            );
        });
        $fancy = $factoryFor(static function (string $s): string {
            [$name, $km] = explode(' ', $s);
            return sprintf(
                '<b>%s</b>  <span foreground="%s">%s km</span>',
                htmlspecialchars($name),
                Demo::ACCENT,
                number_format((int) $km),
            );
        });

        $view = new GtkListView($model, $plain);
        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
        $scroller->set_child($view);
        $scroller->set_vexpand(true);

        /** @var list<array{string, GtkListItemFactory}> $factories */
        $factories = [['plain labels', $plain], ['bar chart', $bars], ['markup', $fancy]];
        $step = 0;
        $button = GtkButton::new_with_label('next factory');
        $button->connect('clicked', function () use ($view, $factories, &$step): void {
            [$name, $factory] = $factories[++$step % count($factories)];
            $view->set_factory($factory);          // every visible row is built again
            Demo::status($name . ' - set_factory() rebuilt the rows');
        });

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append(Demo::label(
            "<b>one model, three factories</b>\n<small>the widget asks the factory, never the model</small>",
        ));
        $page->append($scroller);
        $page->append($button);
        return $page;
    },
    460,
    420,
);
