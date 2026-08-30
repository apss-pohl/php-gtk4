<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GMenu;
use Gtk4\GMenuModel;
use Gtk4\GSimpleAction;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkPopoverMenuBar;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GMenu - the writable menu model.
 *
 * A GMenu is populated by hand: append(), prepend(), insert() take a label and a
 * detailed action name, append_section() and append_submenu() nest other models,
 * remove() and remove_all() take items out, freeze() makes it read-only. The menu
 * bar at the top of the page renders the model as it grows, the readout dumps it,
 * and the button walks through the calls one at a time.
 *
 *   bin/php-gtk4 examples/demo.php GMenu
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GMenu',
    'the writable menu model',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $chosen = 'nothing yet';

        // One parameterised action; every item points at it with a different target.
        $pick = new GSimpleAction('gmenu-pick', 's');
        $app->add_action($pick);

        $file = new GMenu();
        $bar = GtkPopoverMenuBar::new_from_model(null);
        $root = new GMenu();
        $root->append_submenu('File', $file);
        $bar->set_menu_model($root);

        $dump = function (GMenuModel $model, int $depth = 0) use (&$dump): string {
            $out = '';
            for ($i = 0; $i < $model->get_n_items(); $i++) {
                $label = $model->get_item_attribute_value($i, 'label', 's');
                $text = is_string($label) ? $label : '(unlabelled)';
                $out .= sprintf("%s%d: %s\n", str_repeat('   ', $depth), $i, $text);
                foreach (['section', 'submenu'] as $link) {
                    $child = $model->get_item_link($i, $link);
                    if ($child instanceof GMenuModel) {
                        $out .= sprintf("%s   [%s]\n", str_repeat('   ', $depth), $link) . $dump($child, $depth + 1);
                    }
                }
            }
            return $out;
        };

        $note = 'a submenu "File" holding an empty GMenu';
        $render = function () use ($readout, $file, $dump, &$note, &$chosen): void {
            $readout->set_markup(sprintf(
                "<b>GMenu</b> · File has %d item(s)\n\n<tt>%s</tt>\nlast call: <i>%s</i>\nchosen: <b>%s</b>\n\n"
                . '<small>open "File" in the bar above; click the button for the next call</small>',
                $file->get_n_items(),
                htmlspecialchars(rtrim($dump($file)) ?: '(empty)'),
                htmlspecialchars($note),
                htmlspecialchars($chosen),
            ));
        };
        $pick->connect('activate', function (GSimpleAction $self, mixed $what) use (&$chosen, $render): void {
            $chosen = is_string($what) ? $what : '?';
            $render();
        });

        $recent = new GMenu();
        $recent->append('yesterday.txt', 'app.gmenu-pick::yesterday');
        $clipboard = new GMenu();
        $clipboard->append('Copy', 'app.gmenu-pick::copy');
        $clipboard->append('Paste', 'app.gmenu-pick::paste');

        /** @var list<array{string, callable(): void}> $steps */
        $steps = [
            ["append('Open', 'app.gmenu-pick::open')", static fn() => $file->append('Open', 'app.gmenu-pick::open')],
            ["prepend('New', 'app.gmenu-pick::new')", static fn() => $file->prepend('New', 'app.gmenu-pick::new')],
            ["insert(1, 'Save', ...)", static fn() => $file->insert(1, 'Save', 'app.gmenu-pick::save')],
            ["append_submenu('Recent', \$recent)", static fn() => $file->append_submenu('Recent', $recent)],
            ["append_section('Clipboard', \$clipboard)", static fn() => $file->append_section('Clipboard', $clipboard)],
            ['insert_section(0, null, ...)', static fn() => $file->insert_section(0, null, $clipboard)],
            ['remove(0)', static fn() => $file->remove(0)],
            ['remove_all()', static fn() => $file->remove_all()],
        ];

        $step = 0;
        $next = GtkButton::new_with_label('next call');
        $next->connect('clicked', function () use ($steps, &$step, &$note, $render): void {
            [$note, $apply] = $steps[$step++ % count($steps)];
            $apply();
            $render();
        });

        $render();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($bar);
        $page->append($next);
        $page->append($readout);
        return $page;
    },
    560,
    440,
);
