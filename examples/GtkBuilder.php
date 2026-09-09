<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GError;
use Gtk4\GtkBox;
use Gtk4\GtkBuilder;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkBuilder - widgets described in XML, wired to PHP callables.
 *
 * The whole panel below the source is one .ui document: GtkBuilder instantiates it,
 * get_object() hands the widgets back as their own classes, and set_handlers() is what makes
 * <signal handler="on_bump"/> reach a PHP closure - GTK 4 replaced connect_signals() with
 * GtkBuilderScope, so the handler map has to be given to the builder *before* the document is
 * parsed. A deliberately broken document below shows that a parse failure is a Gtk4\GError, not
 * an aborted process.
 *
 *   bin/php-gtk4 examples/demo.php GtkBuilder
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkBuilder',
    'widgets described in XML, wired to PHP callables',
    function (GtkWindow $win): GtkWidget {
        $ui = <<<'XML'
            <interface>
              <object class="GtkBox" id="panel">
                <property name="orientation">vertical</property>
                <property name="spacing">8</property>
                <child><object class="GtkLabel" id="counter">
                  <property name="label">0 bumps</property>
                </object></child>
                <child><object class="GtkButton" id="bump">
                  <property name="label">Bump the counter</property>
                  <signal name="clicked" handler="on_bump"/>
                </object></child>
              </object>
            </interface>
            XML;

        $bumps = 0;
        $builder = new GtkBuilder();
        // Given to the builder first: GTK connects the signals while it parses.
        $builder->set_handlers([
            'on_bump' => function (GtkButton $button) use (&$bumps, $builder): void {
                $bumps++;
                $counter = $builder->get_object('counter');
                if ($counter instanceof GtkLabel) {
                    $counter->set_label($bumps . ' bump' . ($bumps === 1 ? '' : 's'));
                }
                Demo::status('on_bump ran ' . $bumps . 'x - the handler is a PHP closure');
            },
        ]);
        $builder->add_from_string($ui);

        // A document GTK cannot build reports a GError; GtkBuilder::new_from_string() would have
        // killed the process instead, which is why it is not bound.
        $failure = 'none';
        try {
            new GtkBuilder()->add_from_string('<interface><object class="NoSuchWidget" id="x"/></interface>');
        } catch (GError $e) {
            $failure = $e->getMessage();
        }

        $page = new GtkBox(GtkOrientation::Vertical, 12);

        $source = new GtkLabel();
        $source->set_markup('<tt>' . htmlspecialchars(trim($ui)) . '</tt>');
        $source->set_xalign(0.0);
        $page->append($source);

        $panel = $builder->get_object('panel');
        if ($panel instanceof GtkWidget) {
            $page->append($panel);
        }

        $note = new GtkLabel();
        $note->set_markup('<small>a document GTK cannot build: <tt>'
            . htmlspecialchars($failure) . '</tt></small>');
        $note->set_wrap(true);
        $note->set_xalign(0.0);
        $page->append($note);

        Demo::status('set_handlers() before add_from_string() - GTK connects while parsing');

        return $page;
    },
);
