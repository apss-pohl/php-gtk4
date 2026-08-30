<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GApplicationFlags;
use Gtk4\GdkRectangle;
use Gtk4\GMenu;
use Gtk4\GMenuItem;
use Gtk4\GMenuModel;
use Gtk4\GSimpleAction;
use Gtk4\GtkApplication;
use Gtk4\GtkApplicationWindow;
use Gtk4\GtkArrowType;
use Gtk4\GtkButton;
use Gtk4\GtkHeaderBar;
use Gtk4\GtkLabel;
use Gtk4\GtkMenuButton;
use Gtk4\GtkPopover;
use Gtk4\GtkPopoverMenu;
use Gtk4\GtkPopoverMenuBar;
use Gtk4\GtkPopoverMenuFlags;
use Gtk4\GtkPositionType;

/**
 * Wave 4 (menus/actions): the GMenuModel stack that replaces GtkMenu - models, the popovers
 * and buttons that show them, the header bar, and GtkApplicationWindow as an action map.
 */
final class MenuTest extends GtkTestCase
{
    public function testMenuModelBuildsAndReads(): void
    {
        $menu = new GMenu();
        self::assertInstanceOf(GMenuModel::class, $menu);
        self::assertTrue($menu->is_mutable());
        $changes = [];
        $log = function (GMenuModel $m, int $pos, int $removed, int $added) use (&$changes): void {
            $changes[] = [$pos, $removed, $added];
        };
        $menu->connect('items-changed', $log);
        $menu->append('_Open', 'app.open');
        $menu->prepend('New', 'app.new');
        $menu->insert(1, 'Recent', null);
        self::assertSame(3, $menu->get_n_items());
        self::assertSame([[0, 0, 1], [0, 0, 1], [1, 0, 1]], $changes);
        self::assertSame('New', $menu->get_item_attribute_value(0, 'label', 's'));
        self::assertSame('app.open', $menu->get_item_attribute_value(2, 'action', 's'));
        self::assertNull($menu->get_item_attribute_value(1, 'action', 's'), 'no action on that item');
        self::assertNull($menu->get_item_attribute_value(0, 'nope', null));

        $section = new GMenu();
        $section->append('Quit', 'app.quit');
        $menu->append_section('File', $section);
        $sub = new GMenu();
        $sub->append('Zoom in', 'app.zoom-in');
        $menu->append_submenu('View', $sub);
        self::assertSame(5, $menu->get_n_items());
        self::assertSame($section, $menu->get_item_link(3, 'section'), 'links come back as the same handle');
        self::assertSame($sub, $menu->get_item_link(4, 'submenu'));
        self::assertNull($menu->get_item_link(0, 'submenu'));
        $menu->remove(1);
        self::assertSame(4, $menu->get_n_items());
        $menu->remove_all();
        self::assertSame(0, $menu->get_n_items());
        $menu->freeze();
        self::assertFalse($menu->is_mutable());
    }

    public function testMenuItemAttributesAndTargets(): void
    {
        $item = new GMenuItem('Say _hi', 'app.hello');
        self::assertSame('Say _hi', $item->get_attribute_value('label', 's'));
        $item->set_label('Renamed');
        $item->set_detailed_action('app.greet::loud');
        self::assertSame('app.greet', $item->get_attribute_value('action', 's'));
        self::assertSame('loud', $item->get_attribute_value('target', 's'));
        $item->set_action_and_target_value('app.count', 7);
        self::assertSame(7, $item->get_attribute_value('target', 'i'));
        $item->set_attribute_value('icon', 'edit-copy-symbolic');
        self::assertSame('edit-copy-symbolic', $item->get_attribute_value('icon', 's'));
        $item->set_attribute_value('icon', null);
        self::assertNull($item->get_attribute_value('icon', null), 'null unsets');

        $sub = new GMenu();
        $sub->append('Child', 'app.child');
        $item->set_submenu($sub);
        self::assertSame($sub, $item->get_link('submenu'));
        $item->set_submenu(null);
        self::assertNull($item->get_link('submenu'));
        $item->set_section($sub);
        self::assertSame($sub, $item->get_link('section'));
        $item->set_link('custom', $sub);
        self::assertSame($sub, $item->get_link('custom'));

        $menu = new GMenu();
        $menu->append_item($item);
        $menu->prepend_item(GMenuItem::new_section('Top', $sub));
        $menu->insert_item(1, GMenuItem::new_submenu('More', $sub));
        self::assertSame(3, $menu->get_n_items());
        $copy = GMenuItem::new_from_model($menu, 2);
        self::assertSame('Renamed', $copy->get_attribute_value('label', 's'));
    }

    public function testPopoverMenusTakeAModel(): void
    {
        $menu = new GMenu();
        $menu->append('One', 'app.one');
        $sub = new GMenu();
        $sub->append('Deep', 'app.deep');
        $menu->append_submenu('More', $sub);

        $popover = GtkPopoverMenu::new_from_model($menu);
        self::assertInstanceOf(GtkPopover::class, $popover);
        self::assertSame($menu, $popover->get_menu_model());
        $popover->set_menu_model(null);
        self::assertNull($popover->get_menu_model());
        $nested = GtkPopoverMenu::new_from_model_full($menu, GtkPopoverMenuFlags::NESTED);
        self::assertSame(GtkPopoverMenuFlags::NESTED, $nested->get_flags());
        $nested->set_flags(GtkPopoverMenuFlags::SLIDING);
        self::assertSame(GtkPopoverMenuFlags::SLIDING, $nested->get_flags());
        $custom = new GtkLabel('custom');
        self::assertFalse($nested->add_child($custom, 'nope'), 'no <item> with that custom id in the model');
        $slot = new GMenuItem(null, null);
        $slot->set_attribute_value('custom', 'slot');
        $menu->append_item($slot);
        $slotted = GtkPopoverMenu::new_from_model($menu);
        self::assertTrue($slotted->add_child($custom, 'slot'), 'a custom item takes a widget');
        self::assertTrue($slotted->remove_child($custom));

        $bar = GtkPopoverMenuBar::new_from_model($menu);
        self::assertSame($menu, $bar->get_menu_model());
        $bar->set_menu_model(null);
        self::assertNull($bar->get_menu_model());
        $bar->set_menu_model($menu);
        self::assertFalse($bar->remove_child($custom), 'never added');
    }

    public function testPopoverOnAButton(): void
    {
        $button = GtkButton::new_with_label('anchor');
        $win = $this->window();
        $win->set_child($button);
        $popover = new GtkPopover();
        $popover->set_child(new GtkLabel('inside'));
        $popover->set_parent($button);
        $popover->set_position(GtkPositionType::Bottom);
        $popover->set_autohide(false);
        $popover->set_has_arrow(false);
        $popover->set_offset(4, 8);
        $popover->set_pointing_to(new GdkRectangle(1, 2, 3, 4));
        $popover->set_cascade_popdown(true);
        $popover->set_mnemonics_visible(true);
        self::assertSame(GtkPositionType::Bottom, $popover->get_position());
        self::assertFalse($popover->get_autohide());
        self::assertFalse($popover->get_has_arrow());
        self::assertSame([4, 8], $popover->get_offset(), 'get_offset() -> [x, y]');
        $rect = $popover->get_pointing_to();
        self::assertInstanceOf(GdkRectangle::class, $rect);
        self::assertSame(3, $rect->width);
        self::assertTrue($popover->get_cascade_popdown());
        self::assertTrue($popover->get_mnemonics_visible());
        $default = new GtkButton();
        $popover->set_default_widget($default);
        $closed = $this->latch();
        $popover->connect('closed', $closed);
        $win->present();
        $popover->present();
        $popover->popup();
        self::assertTrue($popover->get_visible());
        $popover->popdown();
        self::assertTrue($this->latched(), 'popdown emits closed');
        $popover->unparent();
    }

    public function testMenuButtonShowsAModelOrAPopover(): void
    {
        $mb = new GtkMenuButton();
        $mb->set_label('_Menu');
        $mb->set_use_underline(true);
        $mb->set_always_show_arrow(true);
        $mb->set_has_frame(false);
        $mb->set_can_shrink(true);
        $mb->set_primary(true);
        self::assertSame('_Menu', $mb->get_label());
        self::assertTrue($mb->get_use_underline());
        self::assertTrue($mb->get_always_show_arrow());
        self::assertFalse($mb->get_has_frame());
        self::assertTrue($mb->get_can_shrink());
        self::assertTrue($mb->get_primary());
        $mb->direction = GtkArrowType::Left;  // the methods clash with GtkWidget's text direction
        self::assertSame(GtkArrowType::Left, $mb->direction);
        $mb->set_icon_name('open-menu-symbolic');
        self::assertSame('open-menu-symbolic', $mb->get_icon_name());
        self::assertNull($mb->get_label(), 'an icon replaces the label');

        $menu = new GMenu();
        $menu->append('Item', 'app.item');
        $mb->set_menu_model($menu);
        self::assertSame($menu, $mb->get_menu_model());
        self::assertInstanceOf(GtkPopoverMenu::class, $mb->get_popover(), 'a model gets a popover menu');
        $popover = new GtkPopover();
        $mb->set_popover($popover);
        self::assertSame($popover, $mb->get_popover());
        self::assertNull($mb->get_menu_model(), 'a plain popover replaces the model');

        $calls = 0;
        $mb->set_create_popup_func(function (GtkMenuButton $b) use (&$calls, $mb): void {
            self::assertSame($mb, $b);
            $calls++;
        });
        $win = $this->window();
        $win->set_child($mb);
        $win->present();
        $mb->popup();
        self::assertSame(1, $calls, 'the create-popup func ran before showing');
        self::assertTrue($mb->get_active());
        $mb->popdown();
        self::assertFalse($mb->get_active());
        $mb->set_create_popup_func(null);
        $mb->popup();
        $mb->popdown();
        self::assertSame(1, $calls, 'removed');
        $mb->set_popover(null);
        self::assertNull($mb->get_popover());
    }

    public function testCreatePopupFuncExceptionGoesThroughTheBoundary(): void
    {
        $mb = new GtkMenuButton();
        $mb->set_popover(new GtkPopover());
        $mb->set_create_popup_func(static function (): void {
            throw new \RuntimeException('popup boom');
        });
        $win = $this->window();
        $win->set_child($mb);
        $win->present();
        $seen = $this->captureHandlerException(static function () use ($mb): void {
            $mb->popup();
        });
        self::assertNotNull($seen);
        self::assertSame(['popup boom', 'GtkMenuButton::set_create_popup_func'], [$seen[0], $seen[1]]);
        $mb->popdown();
    }

    public function testHeaderBarPacksAroundATitle(): void
    {
        $bar = new GtkHeaderBar();
        $left = new GtkButton();
        $right = new GtkButton();
        $title = new GtkLabel('Title');
        $bar->pack_start($left);
        $bar->pack_end($right);
        $bar->set_title_widget($title);
        $bar->set_show_title_buttons(false);
        $bar->set_decoration_layout('close:');
        self::assertSame($title, $bar->get_title_widget());
        self::assertFalse($bar->get_show_title_buttons());
        self::assertSame('close:', $bar->get_decoration_layout());
        self::assertNotNull($left->get_parent(), 'packed into the bar (GTK nests its own boxes in between)');
        self::assertSame($bar, $left->get_root() ?? $bar, 'unrooted: no GtkRoot above a bare header bar');
        $bar->remove($left);
        self::assertNull($left->get_parent());
        $bar->set_title_widget(null);
        self::assertNull($bar->get_title_widget());
    }

    public function testApplicationWindowIsAnActionMap(): void
    {
        $app = new GtkApplication('org.php.gtk4.MenuTest', GApplicationFlags::NON_UNIQUE);
        $win = new GtkApplicationWindow($app);
        self::assertSame($app, $win->get_application());
        self::assertSame(0, $win->get_id(), 'ids are handed out once the application is registered');
        $win->set_show_menubar(true);
        self::assertTrue($win->get_show_menubar());
        self::assertInstanceOf(\Gtk4\GdkDisplay::class, $win->get_display());
        $child = new GtkButton();
        $win->set_child($child);
        $win->set_focus($child);
        self::assertSame($child, $win->get_focus());

        $fired = $this->latch();
        $action = new GSimpleAction('greet');
        $action->connect('activate', $fired);
        $win->add_action($action);
        self::assertSame($action, $win->lookup_action('greet'));
        self::assertTrue($win->activate_action('win.greet'), 'the widget method finds the window\'s own actions');
        self::assertTrue($this->latched());
        $win->remove_action('greet');
        self::assertNull($win->lookup_action('greet'));

        $menu = new GMenu();
        $menu->append('Greet', 'win.greet');
        $app->register(null);  // set_menubar() needs a registered application (from `startup` on)
        $app->set_menubar($menu);
        self::assertSame($menu, $app->get_menubar());
        $win->destroy();
    }
}
