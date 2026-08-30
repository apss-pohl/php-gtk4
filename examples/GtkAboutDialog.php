<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAboutDialog;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLicense;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkAboutDialog - the one dialog in wave 5 that is still a widget (a GtkWindow), not a
 * 4.10-style async object: fill it in, give it a transient parent, present() it. The licence
 * is an enum, the credits are string lists.
 *
 *   bin/php-gtk4 examples/demo.php GtkAboutDialog
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkAboutDialog',
    'the about box: program, version, licence and credits',
    function (GtkWindow $win): GtkWidget {
        $facts = Demo::label();

        $build = static function (): GtkAboutDialog {
            $about = new GtkAboutDialog();
            $about->set_program_name('php-gtk4');
            $about->set_version(\Gtk4\VERSION);
            $about->set_comments('GTK 4 for PHP, generated from GObject-Introspection.');
            $about->set_website('https://github.com/apss-pohl/php-gtk4');
            $about->set_website_label('php-gtk4 on GitHub');
            $about->set_license_type(GtkLicense::MitX11);
            $about->set_authors(['Sven Pohl']);
            $about->set_artists(['the GNOME icon designers']);
            $about->add_credit_section('Built on', ['GTK', 'GLib', 'PHP']);
            $about->set_logo_icon_name('applications-development');
            return $about;
        };

        $about = $build();
        $facts->set_markup(sprintf(
            "<tt>program  %s %s\nlicence  %s\nauthors  %s\nwebsite  %s</tt>",
            htmlspecialchars((string) $about->get_program_name()),
            htmlspecialchars((string) $about->get_version()),
            $about->get_license_type()->name,
            htmlspecialchars(implode(', ', $about->get_authors())),
            htmlspecialchars((string) $about->get_website()),
        ));
        $about->destroy();

        // The dialog is a window of its own: it gets the demo's window as its transient parent
        // and closes itself, so the page never touches the application's window.
        $show = GtkButton::new_with_label('present the about box');
        $show->add_css_class('suggested-action');
        $show->connect('clicked', function () use ($build, $win): void {
            $about = $build();
            $about->set_transient_for($win);
            $about->set_modal(true);
            $about->present();
            Demo::status('about box shown');
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($facts);
        $page->append($show);
        return $page;
    },
);
