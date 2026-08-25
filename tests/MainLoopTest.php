<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\Gtk;

final class MainLoopTest extends GtkTestCase
{
    public function testQuitBeforeMainReturnsImmediately(): void
    {
        $this->expectNotToPerformAssertions();
        Gtk::main_quit();
        Gtk::main();  // must return, not block
    }

    public function testQuitFromHandlerDuringMain(): void
    {
        $w = $this->window();
        $ran = false;
        $w->connect('notify::title', function () use (&$ran): void {
            $ran = true;
            Gtk::main_quit();
        });
        // Emitted synchronously before main(); main_quit() is remembered.
        $w->set_title('x');
        Gtk::main();
        self::assertTrue($ran);
    }
}
