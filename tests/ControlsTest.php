<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkDragAction;
use Gtk4\GdkPaintable;
use Gtk4\GdkPaintableFlags;
use Gtk4\GdkTexture;
use Gtk4\GtkAccessiblePlatformState;
use Gtk4\GtkAdjustment;
use Gtk4\GtkCalendar;
use Gtk4\GtkCheckButton;
use Gtk4\GtkContentFit;
use Gtk4\GtkDropDown;
use Gtk4\GtkEditable;
use Gtk4\GtkEntry;
use Gtk4\GtkEntryBuffer;
use Gtk4\GtkEntryIconPosition;
use Gtk4\GtkIconSize;
use Gtk4\GtkImage;
use Gtk4\GtkImageType;
use Gtk4\GtkInputHints;
use Gtk4\GtkInputPurpose;
use Gtk4\GtkOrientation;
use Gtk4\GtkPasswordEntry;
use Gtk4\GtkPicture;
use Gtk4\GtkPositionType;
use Gtk4\GtkProgressBar;
use Gtk4\GtkRange;
use Gtk4\GtkScale;
use Gtk4\GtkSpinButton;
use Gtk4\GtkSpinButtonUpdatePolicy;
use Gtk4\GtkSpinner;
use Gtk4\GtkSpinType;
use Gtk4\GtkStringFilterMatchMode;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkToggleButton;

/**
 * Wave 2 (controls): behaviour the generated smoke tests cannot see - text flowing through
 * GtkEditable and the buffer behind it, radio groups, spin/scale arithmetic, the scale's
 * format callback (an override), images and pictures over a paintable, and the string list
 * model behind GtkDropDown.
 */
final class ControlsTest extends GtkTestCase
{
    public function testEntryTextGoesThroughEditableAndBuffer(): void
    {
        $entry = new GtkEntry();
        self::assertInstanceOf(GtkEditable::class, $entry);
        $changes = 0;
        $entry->connect('changed', function () use (&$changes): void {
            $changes++;
        });
        $entry->set_text('hello');
        self::assertSame('hello', $entry->get_text());
        self::assertSame(5, $entry->get_text_length());
        self::assertSame(1, $changes);
        $entry->select_region(1, 3);
        self::assertSame([1, 3], $entry->get_selection_bounds(), 'get_selection_bounds() -> [start, end]');
        self::assertSame('el', $entry->get_chars(1, 3));
        $entry->delete_selection();
        self::assertSame('hlo', $entry->get_text());
        $entry->delete_text(0, 1);
        self::assertSame('lo', $entry->get_text());
        $entry->set_position(1);
        self::assertSame(1, $entry->get_position());
        $entry->select_region(0, 0);
        self::assertNull($entry->get_selection_bounds(), 'no selection -> null');

        $buffer = $entry->get_buffer();
        self::assertInstanceOf(GtkEntryBuffer::class, $buffer);
        self::assertSame('lo', $buffer->get_text());
        $other = new GtkEntryBuffer('shared', -1);
        $entry->set_buffer($other);
        self::assertSame('shared', $entry->get_text());
        self::assertSame($other, $entry->get_buffer());
        $twin = GtkEntry::new_with_buffer($other);
        $twin->set_text('both');
        self::assertSame('both', $entry->get_text(), 'two entries on one buffer see the same text');
    }

    public function testEntryBufferEditsAndSignals(): void
    {
        $buffer = new GtkEntryBuffer('abc', -1);
        $inserted = [];
        $log = function (GtkEntryBuffer $b, int $pos, string $chars, int $n) use (&$inserted): void {
            $inserted[] = [$pos, $chars, $n];
        };
        $buffer->connect('inserted-text', $log);
        self::assertSame(2, $buffer->insert_text(1, 'xy', -1), 'characters inserted');
        self::assertSame('axybc', $buffer->get_text());
        self::assertSame([[1, 'xy', 2]], $inserted);
        self::assertSame(2, $buffer->delete_text(0, 2));
        self::assertSame('ybc', $buffer->get_text());
        self::assertSame(3, $buffer->get_length());
        self::assertSame(3, $buffer->get_bytes());
        $buffer->set_max_length(2);
        $buffer->set_text('longer', -1);
        self::assertSame('lo', $buffer->get_text(), 'max_length truncates');
        $buffer->set_text('ü', -1);
        self::assertSame(1, $buffer->get_length());
        self::assertSame(2, $buffer->get_bytes(), 'length counts characters, bytes count UTF-8');
    }

    public function testEntryIconsAndHints(): void
    {
        $entry = new GtkEntry();
        $entry->set_icon_from_icon_name(GtkEntryIconPosition::Secondary, 'edit-clear-symbolic');
        self::assertSame('edit-clear-symbolic', $entry->get_icon_name(GtkEntryIconPosition::Secondary));
        self::assertSame(GtkImageType::IconName, $entry->get_icon_storage_type(GtkEntryIconPosition::Secondary));
        self::assertSame(GtkImageType::Empty, $entry->get_icon_storage_type(GtkEntryIconPosition::Primary));
        $entry->set_icon_tooltip_text(GtkEntryIconPosition::Secondary, 'clear');
        self::assertSame('clear', $entry->get_icon_tooltip_text(GtkEntryIconPosition::Secondary));
        $entry->set_icon_sensitive(GtkEntryIconPosition::Secondary, false);
        self::assertFalse($entry->get_icon_sensitive(GtkEntryIconPosition::Secondary));
        $entry->set_icon_from_icon_name(GtkEntryIconPosition::Secondary, null);
        self::assertNull($entry->get_icon_name(GtkEntryIconPosition::Secondary));

        $entry->set_input_purpose(GtkInputPurpose::Digits);
        self::assertSame(GtkInputPurpose::Digits, $entry->get_input_purpose());
        $entry->set_placeholder_text('type here');
        self::assertSame('type here', $entry->get_placeholder_text());
        $entry->set_invisible_char(0x2022);
        $entry->set_visibility(false);
        self::assertSame(0x2022, $entry->get_invisible_char());
        self::assertFalse($entry->get_visibility());
        $entry->unset_invisible_char();
        $entry->set_progress_fraction(0.5);
        self::assertSame(0.5, $entry->get_progress_fraction());
        $entry->progress_pulse();
    }

    public function testPasswordEntryIsEditableToo(): void
    {
        $pw = new GtkPasswordEntry();
        self::assertInstanceOf(GtkEditable::class, $pw);
        $pw->set_text('secret');
        self::assertSame('secret', $pw->get_text());
        $pw->set_show_peek_icon(true);
        self::assertTrue($pw->get_show_peek_icon());
    }

    public function testCheckButtonsFormARadioGroup(): void
    {
        $a = GtkCheckButton::new_with_label('a');
        $b = GtkCheckButton::new_with_mnemonic('_b');
        $b->set_group($a);
        $toggles = 0;
        $b->connect('toggled', function () use (&$toggles): void {
            $toggles++;
        });
        $a->set_active(true);
        self::assertTrue($a->get_active());
        $b->set_active(true);
        self::assertFalse($a->get_active(), 'one active button per group');
        self::assertTrue($b->get_active());
        self::assertSame(1, $toggles);
        self::assertSame('_b', $b->get_label());
        self::assertTrue($b->get_use_underline());
        $a->set_inconsistent(true);
        self::assertTrue($a->get_inconsistent());
        $b->set_group(null);
        $a->set_active(true);
        self::assertTrue($b->get_active(), 'ungrouped: both can be active');

        $t1 = GtkToggleButton::new_with_label('t1');
        $t2 = GtkToggleButton::new_with_mnemonic('_t2');
        $t2->set_group($t1);
        $t1->set_active(true);
        $t2->set_active(true);
        self::assertFalse($t1->get_active());
    }

    public function testSpinButtonArithmetic(): void
    {
        $spin = GtkSpinButton::new_with_range(0.0, 10.0, 0.5);
        self::assertSame([0.0, 10.0], $spin->get_range(), 'get_range() -> [min, max]');
        self::assertSame([0.5, 5.0], $spin->get_increments(), 'get_increments() -> [step, page]');
        $spin->set_value(2.5);
        $spin->spin(GtkSpinType::StepForward, 1.0);
        self::assertSame(3.5, $spin->get_value());
        self::assertSame(4, $spin->get_value_as_int(), 'rounded');
        $spin->set_wrap(true);
        $spin->set_value(10.0);
        $spin->spin(GtkSpinType::StepForward, 0.5);
        self::assertSame(0.0, $spin->get_value(), 'wrap goes back to min');
        $spin->set_digits(2);
        $spin->set_value(1.234);
        $spin->update();
        self::assertContains($spin->get_text(), ['1.23', '1,23'], 'formatted with the locale decimal separator');
        $spin->set_text('7');  // an integer: the same in every locale
        $spin->update();
        self::assertSame(7.0, $spin->get_value(), 'typed text is parsed on update()');
        $adj = $spin->get_adjustment();
        self::assertInstanceOf(GtkAdjustment::class, $adj);
        $own = new GtkAdjustment(1.0, 0.0, 3.0, 1.0, 1.0, 0.0);
        $spin->configure($own, 2.0, 0);
        self::assertSame($own, $spin->get_adjustment());
        self::assertSame(2.0, $spin->get_climb_rate());
        self::assertSame(0, $spin->get_digits());
        self::assertSame([0.0, 3.0], $spin->get_range());
    }

    public function testScaleMarksAndFormatFunc(): void
    {
        $scale = GtkScale::new_with_range(GtkOrientation::Horizontal, 0.0, 100.0, 1.0);
        $scale->set_value(42.0);
        self::assertSame(42.0, $scale->get_value());
        $scale->add_mark(50.0, GtkPositionType::Bottom, '<b>half</b>');
        $scale->clear_marks();
        $scale->set_digits(1);
        $scale->set_draw_value(true);
        $scale->set_value_pos(GtkPositionType::Right);
        self::assertSame(GtkPositionType::Right, $scale->get_value_pos());
        $scale->set_has_origin(false);
        self::assertFalse($scale->get_has_origin());
        self::assertSame([0.0, 100.0], [$scale->get_adjustment()->get_lower(), $scale->get_adjustment()->get_upper()]);
        self::assertCount(2, $scale->get_layout_offsets(), 'get_layout_offsets() -> [x, y]');

        // The override: GTK asks the callable for the value label whenever it draws one.
        $seen = [];
        $scale->set_format_value_func(function (GtkScale $s, float $value) use ($scale, &$seen): string {
            self::assertSame($scale, $s);
            $seen[] = $value;
            return sprintf('%d%%', (int) $value);
        });
        $win = $this->window();
        $win->set_child($scale);
        $win->present();
        self::settle();
        self::assertContains(42.0, $seen, 'the format func ran for the drawn value');
        $scale->set_format_value_func(null);
        $scale->set_value(7.0);
        self::settle();
        self::assertNotContains(7.0, $seen, 'null removed the callable');

        $scale->set_range(0.0, 10.0);
        $scale->set_increments(0.5, 2.0);
        $scale->set_fill_level(4.0);
        $scale->set_show_fill_level(true);
        $scale->set_restrict_to_fill_level(true);
        $scale->set_value(9.0);
        self::assertSame(4.0, $scale->get_value(), 'restricted to the fill level');
        self::assertCount(2, $scale->get_slider_range(), 'get_slider_range() -> [start, end]');
        $scale->set_inverted(true);
        self::assertTrue($scale->get_inverted());
    }

    public function testFormatFuncExceptionIsReportedNotUnwound(): void
    {
        $scale = GtkScale::new_with_range(GtkOrientation::Vertical, 0.0, 1.0, 0.1);
        $scale->set_draw_value(true);
        $win = $this->window();
        $win->set_child($scale);
        // GTK formats as soon as the func is installed, so the handler is in place before that.
        $seen = $this->captureHandlerException(static function () use ($scale, $win): void {
            $scale->set_format_value_func(static function (): string {
                throw new \RuntimeException('format boom');
            });
            $scale->set_value(0.5);
            $win->present();
            self::settle();
        });
        self::assertNotNull($seen, 'the Throwable went through the boundary');
        self::assertSame(['format boom', 'GtkScale::set_format_value_func'], [$seen[0], $seen[1]]);
    }

    public function testProgressBarAndSpinner(): void
    {
        $bar = new GtkProgressBar();
        $bar->set_fraction(0.25);
        $bar->set_text('a quarter');
        $bar->set_show_text(true);
        $bar->set_pulse_step(0.2);
        $bar->pulse();
        self::assertSame(0.25, $bar->get_fraction());
        self::assertSame('a quarter', $bar->get_text());
        self::assertTrue($bar->get_show_text());
        self::assertSame(0.2, $bar->get_pulse_step());
        $bar->set_inverted(true);
        self::assertTrue($bar->get_inverted());

        $spinner = new GtkSpinner();
        $spinner->start();
        self::assertTrue($spinner->get_spinning());
        $spinner->stop();
        self::assertFalse($spinner->get_spinning());
    }

    public function testImageAndPictureOverAPaintable(): void
    {
        $image = GtkImage::new_from_icon_name('emblem-ok-symbolic');
        self::assertSame(GtkImageType::IconName, $image->get_storage_type());
        self::assertSame('emblem-ok-symbolic', $image->get_icon_name());
        $image->set_icon_size(GtkIconSize::Large);
        self::assertSame(GtkIconSize::Large, $image->get_icon_size());
        $image->set_pixel_size(48);
        self::assertSame(48, $image->get_pixel_size());
        $image->clear();
        self::assertSame(GtkImageType::Empty, $image->get_storage_type());
        self::assertNull($image->get_paintable());

        // A 1x1 red PNG, so the test needs no fixture file.
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8DwHwAFBQIAX8jx0gAAAABJRU5ErkJggg==',
            true,
        );
        self::assertIsString($png);
        $texture = GdkTexture::new_from_bytes($png);
        self::assertInstanceOf(GdkPaintable::class, $texture);
        $image->set_from_paintable($texture);
        self::assertSame(GtkImageType::Paintable, $image->get_storage_type());
        self::assertSame($texture, $image->get_paintable());

        $picture = GtkPicture::new_for_paintable($texture);
        self::assertSame($texture, $picture->get_paintable());
        $picture->set_content_fit(GtkContentFit::Cover);
        $picture->set_can_shrink(false);
        $picture->set_alternative_text('logo');
        self::assertSame(GtkContentFit::Cover, $picture->get_content_fit());
        self::assertFalse($picture->get_can_shrink());
        self::assertSame('logo', $picture->get_alternative_text());
        self::assertSame($texture->get_width(), $texture->get_intrinsic_width(), 'a texture is its own paintable');
        self::assertSame($texture, $texture->get_current_image());
        $picture->set_paintable(null);
        self::assertNull($picture->get_paintable());
    }

    public function testCalendarMarks(): void
    {
        $cal = new GtkCalendar();
        $cal->set_year(2026);
        $cal->set_month(7);
        $cal->set_day(29);
        self::assertSame([2026, 7, 29], [$cal->get_year(), $cal->get_month(), $cal->get_day()]);
        $cal->mark_day(3);
        self::assertTrue($cal->get_day_is_marked(3));
        self::assertFalse($cal->get_day_is_marked(4));
        $cal->unmark_day(3);
        self::assertFalse($cal->get_day_is_marked(3));
        $cal->mark_day(5);
        $cal->clear_marks();
        self::assertFalse($cal->get_day_is_marked(5));
        $selected = $this->latch();
        $cal->connect('day-selected', $selected);
        $cal->set_day(1);
        self::assertTrue($this->latched());
    }

    public function testStringListAndDropDown(): void
    {
        $list = new GtkStringList(['a', 'b']);
        self::assertSame(2, $list->get_n_items());
        self::assertSame('GObject', $list->get_item_type(), 'GTK declares the generic item type');
        $list->append('c');
        $list->take('d');
        self::assertSame('d', $list->get_string(3));
        $item = $list->get_item(0);
        self::assertInstanceOf(GtkStringObject::class, $item);
        self::assertSame('a', $item->get_string());
        self::assertSame($item, $list->get_item(0), 'GListModel contract: the same object per position');
        $list->splice(1, 2, ['x']);
        self::assertSame(['a', 'x', 'd'], [$list->get_string(0), $list->get_string(1), $list->get_string(2)]);
        $list->remove(0);
        self::assertSame('x', $list->get_string(0));
        self::assertNull($list->get_string(9));
        $empty = new GtkStringList();
        self::assertSame(0, $empty->get_n_items());

        $obj = new GtkStringObject('solo');
        self::assertSame('solo', $obj->get_string());
        self::assertSame('solo', $obj->string);

        $dd = GtkDropDown::new_from_strings(['one', 'two', 'three']);
        $selections = [];
        $dd->connect('notify::selected', function (GtkDropDown $d) use (&$selections): void {
            $selections[] = $d->get_selected();
        });
        $dd->set_selected(2);
        self::assertSame(2, $dd->get_selected());
        self::assertSame([2], $selections);
        $selected = $dd->get_selected_item();
        self::assertInstanceOf(GtkStringObject::class, $selected);
        self::assertSame('three', $selected->get_string());
        self::assertInstanceOf(GtkStringList::class, $dd->get_model());
        $dd->set_model($list);
        self::assertSame($list, $dd->get_model());
        $dd->set_enable_search(true);
        $dd->set_search_match_mode(GtkStringFilterMatchMode::Prefix);
        self::assertSame(GtkStringFilterMatchMode::Prefix, $dd->get_search_match_mode());
        $dd->set_model(null);
        self::assertNull($dd->get_selected_item());
    }

    public function testEnumsAndFlagsOfTheWave(): void
    {
        $spin = GtkSpinButton::new_with_range(0.0, 1.0, 1.0);
        $scale = GtkScale::new_with_range(GtkOrientation::Horizontal, 0.0, 1.0, 1.0);
        self::assertInstanceOf(GtkRange::class, $scale, 'GtkScale is the GtkRange we bind');
        $spin->set_update_policy(GtkSpinButtonUpdatePolicy::IfValid);
        self::assertSame(GtkSpinButtonUpdatePolicy::IfValid, $spin->get_update_policy());

        $entry = new GtkEntry();
        $entry->set_input_hints(GtkInputHints::SPELLCHECK | GtkInputHints::UPPERCASE_WORDS);
        self::assertSame(GtkInputHints::SPELLCHECK | GtkInputHints::UPPERCASE_WORDS, $entry->get_input_hints());
        self::assertSame(-1, $entry->get_current_icon_drag_source(), 'no icon is being dragged');
        self::assertSame(1, GdkDragAction::COPY, 'flags are the C values');
        self::assertFalse($entry->delegate_get_accessible_platform_state(GtkAccessiblePlatformState::Focused));
        self::assertTrue($entry->delegate_get_accessible_platform_state(GtkAccessiblePlatformState::Focusable));

        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8DwHwAFBQIAX8jx0gAAAABJRU5ErkJggg==',
            true,
        );
        self::assertIsString($png);
        $texture = GdkTexture::new_from_bytes($png);
        self::assertSame(
            GdkPaintableFlags::SIZE | GdkPaintableFlags::CONTENTS,
            $texture->get_flags(),
            'a texture is static in size and contents',
        );
    }

    /** Pump the main context so GTK draws what was just presented. */
    private static function settle(): void
    {
        for ($i = 0; $i < 30; $i++) {
            \Gtk4\GLib::main_context_iteration(false);
        }
    }
}
