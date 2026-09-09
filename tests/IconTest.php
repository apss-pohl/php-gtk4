<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GIcon;
use Gtk4\GMenu;
use Gtk4\GMenuItem;
use Gtk4\GThemedIcon;
use Gtk4\GtkEntry;
use Gtk4\GtkEntryIconPosition;
use Gtk4\GtkIconPaintable;
use Gtk4\GtkIconTheme;
use Gtk4\GtkImage;
use Gtk4\GtkImageType;
use Gtk4\GtkTextDirection;

/**
 * Icons named rather than drawn: a `GThemedIcon` is a list of names to try, and `GIcon` is what
 * every widget that takes an icon is declared against - a GtkImage, an entry's two icon slots,
 * a menu item, the icon theme.
 */
final class IconTest extends GtkTestCase
{
    public function testAThemedIconIsAListOfNamesToTry(): void
    {
        $icon = new GThemedIcon('folder-music');

        // GLib adds the -symbolic variant of every name it is given, so a theme that only has
        // the symbolic one still matches.
        self::assertSame(['folder-music', 'folder-music-symbolic'], $icon->get_names());
        self::assertSame('folder-music', $icon->to_string(), 'the name it was built from');
    }

    /** The fallback constructor derives the shorter names a theme may have instead. */
    public function testDefaultFallbacksAreDerivedFromTheName(): void
    {
        $icon = GThemedIcon::new_with_default_fallbacks('folder-music-symbolic');

        self::assertContains('folder-music-symbolic', $icon->get_names());
        self::assertContains('folder', $icon->get_names(), 'the theme may only have the plain one');
    }

    public function testNamesCanBeAddedAtEitherEnd(): void
    {
        $icon = new GThemedIcon('folder');
        $icon->append_name('inode-directory');
        $icon->prepend_name('folder-open');

        // The order is what a theme is asked in; the -symbolic variants follow the real names.
        $names = array_values(array_filter(
            $icon->get_names(),
            static fn(string $name): bool => !str_ends_with($name, '-symbolic'),
        ));
        self::assertSame(['folder-open', 'folder', 'inode-directory'], $names);
    }

    /** Two icons of the same names are equal and hash alike; a different one does not. */
    public function testEqualityIsByName(): void
    {
        $one = new GThemedIcon('folder');
        $same = new GThemedIcon('folder');
        $other = new GThemedIcon('file');

        self::assertTrue($one->equal($same));
        self::assertSame($one->hash(), $same->hash());
        self::assertFalse($one->equal($other));
        self::assertFalse($one->equal(null), 'nothing equals no icon');
    }

    /** serialize() answers with the GVariant GIO would store, as a plain PHP value. */
    public function testSerializeAnswersAValue(): void
    {
        self::assertNotNull(new GThemedIcon('folder')->serialize());
    }

    /** What binding GIcon was for: a GtkImage that shows an icon by name. */
    public function testAnImageTakesAGicon(): void
    {
        $icon = new GThemedIcon('folder');
        $image = new GtkImage();
        $image->set_from_gicon($icon);

        self::assertSame(GtkImageType::Gicon, $image->get_storage_type());
        $back = $image->get_gicon();
        self::assertInstanceOf(GIcon::class, $back);
        self::assertTrue($back->equal($icon), 'the image kept the icon it was given');
    }

    public function testAnImageCanBeBuiltFromAGicon(): void
    {
        $image = GtkImage::new_from_gicon(new GThemedIcon('folder'));

        self::assertSame(GtkImageType::Gicon, $image->get_storage_type());
        self::assertInstanceOf(GIcon::class, $image->get_gicon());
    }

    /** An entry has two icon slots, and either can be an icon by name. */
    public function testAnEntryTakesAGiconOnEitherSide(): void
    {
        $entry = new GtkEntry();
        $icon = new GThemedIcon('edit-clear');
        $entry->set_icon_from_gicon(GtkEntryIconPosition::Secondary, $icon);

        $back = $entry->get_icon_gicon(GtkEntryIconPosition::Secondary);
        self::assertInstanceOf(GIcon::class, $back);
        self::assertTrue($back->equal($icon));
        self::assertNull(
            $entry->get_icon_gicon(GtkEntryIconPosition::Primary),
            'the other slot is still empty',
        );
    }

    /** A menu item's icon is a GIcon too, which is how a menu shows one. */
    public function testAMenuItemTakesAGicon(): void
    {
        $item = new GMenuItem('Open', null);
        $item->set_icon(new GThemedIcon('document-open'));

        $menu = new GMenu();
        $menu->append_item($item);
        self::assertSame(1, $menu->get_n_items());
    }

    /** The icon theme answers about a GIcon rather than about a name. */
    public function testTheIconThemeIsAskedAboutAGicon(): void
    {
        $theme = GtkIconTheme::get_for_display($this->window()->get_display());

        // Whether *this* machine's theme has "folder" is not something a test can know; that no
        // theme has this one is.
        self::assertFalse($theme->has_gicon(new GThemedIcon('php-gtk4-no-such-icon')));

        // A lookup always answers with a paintable - the theme's "missing image" when it has
        // nothing better - so a caller never has to handle null.
        self::assertInstanceOf(GtkIconPaintable::class, $theme->lookup_by_gicon(
            new GThemedIcon('folder'),
            32,
            1,
            GtkTextDirection::None,
            0,
        ));
    }
}
