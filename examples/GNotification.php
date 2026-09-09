<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GApplication;
use Gtk4\GNotification;
use Gtk4\GNotificationPriority;
use Gtk4\GThemedIcon;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GNotification - a message the desktop shows, not the application.
 *
 * GTK 4 has no tray icon; this is what took over that job. The application builds a message -
 * title, body, an icon by name, buttons wired to its own actions - and hands it to
 * GApplication::send_notification() with an id. The desktop decides how and whether to show it,
 * and the id is what lets the application replace or withdraw the same notification later.
 *
 * The buttons activate actions of the *application*, so they work even after the notification
 * outlives the window it came from. Sending needs a registered application, which is why this
 * page asks the one running the demo.
 *
 *   bin/php-gtk4 examples/demo.php GNotification
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GNotification',
    'a message the desktop shows, with buttons that run your actions',
    function (GtkWindow $win, GApplication $app): GtkWidget {
        $column = new GtkBox(GtkOrientation::Vertical, 12);
        $column->append(Demo::label(
            "<span size=\"x-large\"><b>send a notification</b></span>\n"
            . '<small>whether it appears is the desktop\'s decision, not the application\'s</small>',
        ));

        $send = new GtkButton();
        $send->set_child(Demo::label('Send one'));
        $send->connect('clicked', static function () use ($app): void {
            $notification = new GNotification('php-gtk4');
            $notification->set_body('Sent from the demo, with an icon and a button.');
            $notification->set_icon(new GThemedIcon('dialog-information'));
            $notification->set_priority(GNotificationPriority::Normal);
            // The button activates an action of the application; "app.quit" exists in the demo.
            $notification->add_button('Quit the demo', 'app.quit');

            if (!$app->get_is_registered()) {
                Demo::status('the application is not registered yet - nothing to send to');
                return;
            }
            // The id is the handle: sending the same one again replaces the message.
            $app->send_notification('php-gtk4-demo', $notification);
            Demo::status('sent as "php-gtk4-demo" - send again to replace it, or withdraw it');
        });

        $withdraw = new GtkButton();
        $withdraw->set_child(Demo::label('Withdraw it'));
        $withdraw->connect('clicked', static function () use ($app): void {
            if (!$app->get_is_registered()) {
                Demo::status('the application is not registered yet');
                return;
            }
            $app->withdraw_notification('php-gtk4-demo');
            Demo::status('withdrawn - the desktop takes it away if it was still showing');
        });

        $column->append($send);
        $column->append($withdraw);
        Demo::status('click to send; the desktop decides what to do with it');
        return $column;
    },
    460,
    260,
);
