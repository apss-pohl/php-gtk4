<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkTextureDownloader - The GdkTextureDownloader is used to download the contents of a Texture.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkTextureDownloader doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkTextureDownloader
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkTextureDownloader',
    'The GdkTextureDownloader is used to download the contents of a Texture.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'copy',
            'download_bytes',
            'download_into',
            'free',
            'get_format',
            'get_texture',
            'set_format',
            'set_texture',
        ]);
        $label->set_markup("<b>GdkTextureDownloader</b>\n9 generated methods\n<small>$names</small>");
        return $label;
    },
);
