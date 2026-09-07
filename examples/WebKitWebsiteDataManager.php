<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitWebsiteDataManager - Manages data stored locally by web sites.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitWebsiteDataManager doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitWebsiteDataManager
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitWebsiteDataManager',
    'Manages data stored locally by web sites.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'clear',
            'clear_finish',
            'fetch',
            'fetch_finish',
            'get_base_cache_directory',
            'get_base_data_directory',
            'get_favicon_database',
            'get_favicons_enabled',
            'get_itp_summary',
            'get_itp_summary_finish',
            'is_ephemeral',
            'remove',
            '…',
        ]);
        $label->set_markup("<b>WebKitWebsiteDataManager</b>\n14 generated methods\n<small>$names</small>");
        return $label;
    },
);
