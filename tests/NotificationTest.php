<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GApplication;
use Gtk4\GApplicationFlags;
use Gtk4\GNotification;
use Gtk4\GNotificationPriority;
use Gtk4\GSimpleAction;
use Gtk4\GThemedIcon;

/**
 * The desktop notification a background application sends - what replaced the tray icon's
 * "tell the user something" job, GTK 4 having no tray API at all.
 *
 * A notification is a message the *desktop* draws, so nothing here can assert what it looks
 * like; what a test can hold is that the message is built and accepted, and that sending one
 * without a registered application is refused rather than silently dropped.
 */
final class NotificationTest extends GtkTestCase
{
    private function application(): GApplication
    {
        // Non-unique: several of these exist over a suite run, and none of them owns the name.
        return new GApplication('test.php.gtk4.notify', GApplicationFlags::NON_UNIQUE);
    }

    public function testANotificationIsBuiltFromItsParts(): void
    {
        $notification = new GNotification('Build finished');
        $notification->set_body('42 tests, no failures.');
        $notification->set_priority(GNotificationPriority::High);
        $notification->set_category('device');

        // GNotification is write-only - the desktop reads it, PHP cannot - so being able to
        // build one without a complaint from GLib is the assertion.
        self::assertInstanceOf(GNotification::class, $notification);
    }

    /** The icon is a GIcon, which is why it is named rather than drawn. */
    public function testTheIconIsAGicon(): void
    {
        $notification = new GNotification('Download ready');
        $notification->set_icon(new GThemedIcon('folder-download'));

        self::assertInstanceOf(GNotification::class, $notification);
    }

    /** Buttons carry an action of the application's own, with an optional target. */
    public function testButtonsCarryApplicationActions(): void
    {
        $notification = new GNotification('A file arrived');
        $notification->add_button('Open', 'app.open');
        $notification->add_button_with_target('Open folder', 'app.open-folder', '/tmp');
        $notification->set_default_action('app.show');
        $notification->set_default_action_and_target('app.show-file', 'report.pdf');

        self::assertInstanceOf(GNotification::class, $notification);
    }

    /**
     * Sending needs a registered application: g_application_send_notification() asserts on one
     * that has not been registered, so the binding refuses it first.
     */
    public function testSendingFromAnUnregisteredApplicationIsRefused(): void
    {
        $app = $this->application();

        $this->expectException(\LogicException::class);
        $app->send_notification('build', new GNotification('Build finished'));
    }

    /** A registered application takes the notification, and can take it back again. */
    public function testARegisteredApplicationSendsAndWithdraws(): void
    {
        $app = $this->application();
        $app->register(null);
        $app->add_action(new GSimpleAction('show'));

        $notification = new GNotification('Build finished');
        $notification->set_body('42 tests, no failures.');
        $notification->set_default_action('app.show');

        // Whether a notification daemon is listening is the desktop's business; that GIO
        // accepted the message is ours.
        $app->send_notification('build', $notification);
        $app->withdraw_notification('build');
        self::assertTrue($app->get_is_registered());
    }
}
