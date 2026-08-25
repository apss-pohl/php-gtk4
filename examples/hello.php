<?php
Gtk::init();

$win = new GtkWindow();
$win->set_title('php-gtk4');
$win->set_default_size(300, 200);
$win->connect('close-request', function () { Gtk::main_quit(); return false; });
$win->present();

Gtk::main();
