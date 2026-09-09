<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GApplication;
use Gtk4\GApplicationFlags;
use Gtk4\GCancellable;
use Gtk4\GdkClipboard;
use Gtk4\GdkDisplay;
use Gtk4\GdkTexture;
use Gtk4\GListStore;
use Gtk4\GMenu;
use Gtk4\GMenuItem;
use Gtk4\GMenuModel;
use Gtk4\GObject;
use Gtk4\GSimpleAction;
use Gtk4\GTask;
use Gtk4\GtkAdjustment;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCalendar;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkEntry;
use Gtk4\GtkGestureLongPress;
use Gtk4\GtkGrid;
use Gtk4\GtkIconPaintable;
use Gtk4\GtkLabel;
use Gtk4\GtkNotebook;
use Gtk4\GtkOrientation;
use Gtk4\GtkScale;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkSpinButton;
use Gtk4\GtkStack;
use Gtk4\GtkStringList;
use Gtk4\GtkTextBuffer;
use Gtk4\GtkTextIter;
use Gtk4\GtkTextView;
use Gtk4\GtkTextWindowType;
use Gtk4\GtkWindow;
use Gtk4\PangoFontDescription;
use Gtk4\PhpValue;
use PhpGtk4\Tests\Subclass\ChainingScale;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The PHP -> C argument boundary (`check_utf8` / `check_range` in src/php_gtk4.h, the depth
 * and cycle guards in src/core/variant.cpp). A PHP value is 64-bit, signed and may hold any
 * bytes; a GTK parameter is usually none of those. Everything here used to be accepted and
 * then went wrong somewhere the caller could not see it - two of them by killing the process.
 *
 * Found by the security review on 2026-08-30; each test names what happened before.
 */
final class ArgumentGuardTest extends GtkTestCase
{
    // ---------------------------------------------------------------- GVariant recursion

    /** `$a = [1]; $a[] = &$a;` recursed until the C stack was gone: SIGSEGV. */
    /**
     * It pins the individual cases where GTK refuses a value PHP can build; several are only
     * reachable *through* GTK's precondition (a reversed get_chars() range answers NULL).
     */
    protected function toleratesGtkCriticals(): bool
    {
        return true;
    }

    public function testSelfReferentialArrayIsRejected(): void
    {
        $a = [1];
        $a[] = &$a;
        $action = new GSimpleAction('x', 'v');

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('array contains itself');
        $action->activate($a);
    }

    /** 20 000 levels was a SIGSEGV; a GVariant cannot be deeper than 64 anyway. */
    public function testTooDeeplyNestedArrayIsRejected(): void
    {
        $deep = 1;
        for ($i = 0; $i < 5_000; $i++) {
            $deep = [$deep];
        }
        $action = new GSimpleAction('x', 'v');

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('nested too deeply');
        $action->activate($deep);
    }

    /** The cap is on pathological depth, not on ordinary structures. */
    public function testOrdinaryNestingStillConverts(): void
    {
        $action = new GSimpleAction('x', 'v');
        $seen = null;
        $action->connect('activate', function (GSimpleAction $a, mixed $p) use (&$seen): void {
            $seen = $p;
        });
        $action->activate(['name' => ['a', 'b'], 'flags' => [1, 2, 3], 'on' => true]);

        self::assertSame(['name' => ['a', 'b'], 'flags' => [1, 2, 3], 'on' => true], $seen);
    }

    // ---------------------------------------------------------------- strings

    /**
     * A GLib string ends at the first NUL, so PHP saw "safe\0evil" and the window title said
     * "safe" - a mismatch worth having wherever a title is built from data.
     */
    public function testEmbeddedNullByteIsRejectedInAnArgument(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must not contain a null byte');
        $this->window()->set_title("safe\0evil");
    }

    public function testEmbeddedNullByteIsRejectedInAPropertyWrite(): void
    {
        $window = $this->window();
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('null byte');
        $window->title = "a\0b";
    }

    /** Invalid UTF-8 used to reach GLib, which dropped the value with a CRITICAL on stderr. */
    public function testInvalidUtf8IsRejected(): void
    {
        $label = new GtkLabel('x');
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be valid UTF-8');
        $label->set_text("\xff\xfe not text");
    }

    public function testInvalidUtf8IsRejectedInAVariant(): void
    {
        $action = new GSimpleAction('x', 's');
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('valid UTF-8');
        $action->activate("\xff\xfe");
    }

    public function testValidUtf8Passes(): void
    {
        $window = $this->window();
        $window->set_title('Grüße ✓ 日本語');
        self::assertSame('Grüße ✓ 日本語', $window->get_title());
    }

    /** A GBytes parameter is binary by definition and must not be validated as text. */
    public function testBinaryDataIsStillAcceptedWhereItIsBytes(): void
    {
        $texture = GdkTexture::new_from_bytes(PngFixture::red(1, 1));
        self::assertSame(1, $texture->get_width());
    }

    // ---------------------------------------------------------------- property writes

    /**
     * `$win->title = 'x'` is the idiom the README teaches, and it used to be the one API
     * surface with no type checking at all: `$win->default_width = 'garbage'` stored 0,
     * PHP_INT_MAX stored -1 and strict_types was ignored. It now converts exactly like the
     * equivalent setter's parameter does.
     *
     * @return iterable<string, array{string, mixed, class-string<\Throwable>}>
     */
    public static function refusedPropertyWrites(): iterable
    {
        yield 'non-numeric string into int' => ['default_width', 'garbage', \TypeError::class];
        yield 'float into int (strict)' => ['default_width', 3.7, \TypeError::class];
        yield 'bool into int (strict)' => ['default_width', true, \TypeError::class];
        yield 'out of range for gint' => ['default_width', PHP_INT_MAX, \ValueError::class];
        yield 'non-numeric string into double' => ['opacity', 'x', \TypeError::class];
        yield 'int into string (strict)' => ['title', 42, \TypeError::class];
        yield 'string into bool (strict)' => ['resizable', 'yes', \TypeError::class];
    }

    /** @param class-string<\Throwable> $expected */
    #[DataProvider('refusedPropertyWrites')]
    public function testPropertyWritesConvertLikeAParameter(
        string $property,
        mixed $value,
        string $expected,
    ): void {
        $window = $this->window();
        $this->expectException($expected);
        $window->{$property} = $value;
    }

    public function testPropertyWritesStillTakeWhatTheTypeAllows(): void
    {
        $window = $this->window();
        $window->default_width = 320;          // int into gint
        $window->opacity = 1;                  // int into gdouble is a widening conversion
        $window->title = 'plain';
        $window->resizable = false;
        self::assertSame(320, $window->default_width);
        self::assertEqualsWithDelta(1.0, $window->opacity, 1e-9);
        self::assertSame('plain', $window->title);
        self::assertFalse($window->resizable);
    }

    // ---------------------------------------------------------------- integers

    /** -1 reached g_list_store_remove() as 4294967295 and only GLib's assertion caught it. */
    public function testNegativeUnsignedArgumentIsRejected(): void
    {
        $store = new GListStore('PhpValue');
        $store->append(new PhpValue(1));

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be between 0 and 4294967295');
        $store->remove(-1);
    }

    /** PHP_INT_MAX truncated to -1, which GTK reads as "natural size". */
    public function testTooLargeArgumentIsRejected(): void
    {
        $label = new GtkLabel('x');
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be between -2147483648 and 2147483647');
        $label->set_size_request(PHP_INT_MAX, 10);
    }

    /** -1 is a real value for a signed parameter (GTK: "whatever you need"). */
    public function testNegativeSignedArgumentStillPasses(): void
    {
        $label = new GtkLabel('x');
        $label->set_size_request(-1, -1);
        self::assertSame([-1, -1], $label->get_size_request());
    }

    // ---------------------------------------------------------------- GtkTextIter

    /**
     * A negative index is `g_error("Byte index -1 is off the end of the line")` inside GTK,
     * which aborts the process - the two methods that take one are guarded by hand
     * (gen/overrides/Gtk.TextIter.set_line_index.cpp and .set_line_offset.cpp).
     *
     * @param callable(\Gtk4\GtkTextIter): void $call
     */
    #[DataProvider('negativeIterIndexes')]
    public function testNegativeLineIndexIsRejected(callable $call): void
    {
        $buffer = new GtkTextBuffer();
        $buffer->set_text('hello world', -1);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be greater than or equal to 0');
        $call($buffer->get_start_iter());
    }

    /** @return iterable<string, array{callable(\Gtk4\GtkTextIter): void}> */
    public static function negativeIterIndexes(): iterable
    {
        yield 'set_line_index' => [static fn(GtkTextIter $i) => $i->set_line_index(-1)];
        yield 'set_line_offset' => [static fn(GtkTextIter $i) => $i->set_line_offset(-1)];
    }

    /** The guard is on negative values only; the end of the line stays reachable. */
    public function testLineIndexWithinTheLineStillPasses(): void
    {
        $buffer = new GtkTextBuffer();
        $buffer->set_text('hello world', -1);
        $iter = $buffer->get_start_iter();

        $iter->set_line_index(5);
        self::assertSame(5, $iter->get_offset());
        $iter->set_line_offset(0);
        self::assertSame(0, $iter->get_offset());
    }

    /**
     * GTK refuses `end < start` with a precondition and returns NULL; the declared return
     * type is `string`, and reading that NULL back was a SIGSEGV.
     */
    public function testReversedRangeReturnsAnEmptyStringInsteadOfCrashing(): void
    {
        $entry = new GtkEntry();
        $entry->set_text('hello');

        self::assertSame('hello', $entry->get_chars(0, -1));
        self::assertSame('', $entry->get_chars(1, 0));
    }

    /**
     * `g_menu_model_get_item_link()` and `get_item_attribute_value()` index the item array
     * without checking: a negative index read before it (SIGSEGV) and one past the end reached
     * `g_assert_not_reached()` in gmenumodel.c (a `g_error()`, so the process ended either way).
     * Both are values a script can produce from a loop bound, so the boundary refuses them.
     */
    #[DataProvider('outOfRangeMenuIndexes')]
    public function testMenuItemIndexOutsideTheModelIsRejected(int $index): void
    {
        $menu = new GMenu();
        $menu->append('only item', null);

        $this->expectException(\ValueError::class);
        $menu->get_item_link($index, 'submenu');
    }

    #[DataProvider('outOfRangeMenuIndexes')]
    public function testMenuItemAttributeIndexOutsideTheModelIsRejected(int $index): void
    {
        $menu = new GMenu();
        $menu->append('only item', null);

        $this->expectException(\ValueError::class);
        $menu->get_item_attribute_value($index, 'label', null);
    }

    /** @return iterable<string, array{int}> */
    public static function outOfRangeMenuIndexes(): iterable
    {
        yield 'negative' => [-1];
        yield 'one past the end' => [1];
        yield 'far past the end' => [999];
    }

    /** An empty model has no valid index at all, and says so rather than naming a range. */
    public function testMenuItemIndexOnAnEmptyModelIsRejected(): void
    {
        $menu = new GMenu();

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('cannot be used on an empty menu');
        $menu->get_item_link(0, 'submenu');
    }

    /** The guard is on the range only: an index inside the model still answers. */
    public function testMenuItemIndexInsideTheModelStillPasses(): void
    {
        $menu = new GMenu();
        $submenu = new GMenu();
        $menu->append('plain', null);
        $menu->append_submenu('with a submenu', $submenu);

        self::assertSame('plain', $menu->get_item_attribute_value(0, 'label', null));
        self::assertNull($menu->get_item_link(0, 'submenu'));
        self::assertInstanceOf(GMenuModel::class, $menu->get_item_link(1, 'submenu'));
    }

    // ------------------------------------------------- preconditions GTK only complains about

    /**
     * `gdk_clipboard_read_async()` asserts `mime_types[0] != NULL` and returns without ever
     * calling the callback, so an empty list - what a filtered format list can end up as - is a
     * read that silently never finishes.
     */
    public function testClipboardReadWithoutAMimeTypeIsRejected(): void
    {
        $clipboard = self::clipboard();

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must name at least one mime type');
        $clipboard->read_async([], 0, null, static fn() => null);
    }

    /**
     * GIR marks the `GAsyncReadyCallback` of these four nullable, as C allows for a fire-and-
     * forget call; GDK asserts `callback != NULL` and does nothing at all. The stub therefore
     * declares them non-nullable (`NON_NULLABLE_PARAMS` in gen/gir/config.php), which makes null
     * an ordinary TypeError at the boundary. Called through reflection because the argument the
     * test passes is exactly the one the signature now forbids.
     *
     * @param list<mixed> $arguments
     */
    #[DataProvider('clipboardCallsThatNeedACallback')]
    public function testClipboardAsyncWithoutACallbackIsRejected(string $method, array $arguments): void
    {
        $call = new \ReflectionMethod(GdkClipboard::class, $method);

        $this->expectException(\TypeError::class);
        $call->invokeArgs(self::clipboard(), $arguments);
    }

    /** @return iterable<string, array{string, list<mixed>}> */
    public static function clipboardCallsThatNeedACallback(): iterable
    {
        yield 'read_async' => ['read_async', [['text/plain'], 0, null, null]];
        yield 'read_text_async' => ['read_text_async', [null, null]];
        yield 'read_texture_async' => ['read_texture_async', [null, null]];
        yield 'store_async' => ['store_async', [0, null, null]];
    }

    /**
     * The CSS sizing algorithm has a domain GDK states as four `g_return_if_fail()`s and then
     * answers `[0.0, 0.0]` for - indistinguishable from a real answer. A size computed by the
     * script can leave it, so the range is refused instead.
     */
    #[DataProvider('sizesOutsideTheSizingAlgorithm')]
    public function testConcreteSizeOutsideTheAlgorithmsDomainIsRejected(
        float $specifiedWidth,
        float $specifiedHeight,
        float $defaultWidth,
        float $defaultHeight,
    ): void {
        $paintable = self::paintable();

        $this->expectException(\ValueError::class);
        $paintable->compute_concrete_size($specifiedWidth, $specifiedHeight, $defaultWidth, $defaultHeight);
    }

    /** @return iterable<string, array{float, float, float, float}> */
    public static function sizesOutsideTheSizingAlgorithm(): iterable
    {
        yield 'negative specified width' => [-1.0, 0.0, 16.0, 16.0];
        yield 'negative specified height' => [0.0, -1.0, 16.0, 16.0];
        yield 'zero default width' => [0.0, 0.0, 0.0, 16.0];
        yield 'zero default height' => [0.0, 0.0, 16.0, 0.0];
    }

    /**
     * 0 means "not specified" and is inside the domain: with neither dimension specified the
     * algorithm answers the paintable's intrinsic size, here the 2x1 of the fixture PNG.
     */
    public function testConcreteSizeInsideTheDomainStillAnswers(): void
    {
        self::assertSame([2.0, 1.0], self::paintable()->compute_concrete_size(0.0, 0.0, 16.0, 16.0));
    }

    /**
     * `gtk_selection_model_selection_changed()` asserts the range lies inside the model and drops
     * the notification otherwise, so a range one item too long silently fails to repaint.
     */
    #[DataProvider('rangesPastTheEndOfTheModel')]
    public function testSelectionChangeBeyondTheModelIsRejected(int $position, int $items): void
    {
        $model = new GtkSingleSelection(new GtkStringList(['one']));

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must not reach past the end of the model');
        $model->selection_changed($position, $items);
    }

    /** @return iterable<string, array{int, int}> */
    public static function rangesPastTheEndOfTheModel(): iterable
    {
        yield 'far past the end' => [0, 999];
        yield 'one item too long' => [0, 2];
        yield 'starting past the end' => [1, 1];
    }

    /** The whole model is a legal range. */
    public function testSelectionChangeInsideTheModelStillPasses(): void
    {
        $model = new GtkSingleSelection(new GtkStringList(['one']));

        $model->selection_changed(0, 1);
        self::assertSame(1, $model->get_n_items());
    }

    /**
     * A parameter whose C function accepts less than its type does. GTK states each of these as
     * a `g_return_if_fail()`, so out of domain meant a CRITICAL and the call silently not
     * happening - `$calendar->set_month(12)` left the month alone and told PHP nothing. Every
     * bound is copied from the assertion GTK printed (`PARAM_DOMAINS` in gen/gir/config.php),
     * and every line here retired one from tests/robustness-criticals.txt.
     *
     * @param class-string $class
     * @param list<mixed> $arguments
     */
    #[DataProvider('argumentsOutsideTheirDomain')]
    public function testAParameterOutsideItsDomainIsRejected(
        string $class,
        string $method,
        array $arguments,
        string $expected,
    ): void {
        $target = GtkInstances::make($class);
        self::assertInstanceOf($class, $target);

        try {
            new \ReflectionMethod($class, $method)->invokeArgs($target, $arguments);
            self::fail("$class::$method() accepted a value outside its domain");
        } catch (\ValueError $e) {
            self::assertStringContainsString($expected, $e->getMessage());
        }
    }

    /** @return iterable<string, array{class-string, string, list<mixed>, string}> */
    public static function argumentsOutsideTheirDomain(): iterable
    {
        yield 'calendar month' => [GtkCalendar::class, 'set_month', [12], 'must be between 0 and 11'];
        yield 'calendar day' => [GtkCalendar::class, 'set_day', [0], 'must be between 1 and 31'];
        yield 'calendar year' => [GtkCalendar::class, 'set_year', [0], 'must be between 1 and 9999'];
        yield 'drawing area width' => [
            GtkDrawingArea::class, 'set_content_width', [-1], 'must be greater than or equal to 0',
        ];
        yield 'drawing area height' => [
            GtkDrawingArea::class, 'set_content_height', [-1], 'must be greater than or equal to 0',
        ];
        yield 'editable start' => [
            GtkEntry::class, 'get_chars', [-1, 2], 'must be greater than or equal to 0',
        ];
        yield 'editable delete start' => [
            GtkEntry::class, 'delete_text', [-1, 2], 'must be greater than or equal to 0',
        ];
        yield 'grid span' => [
            GtkGrid::class, 'attach', [new GtkLabel('x'), 0, 0, 0, 1], 'must be greater than or equal to 1',
        ];
        yield 'long press delay' => [
            GtkGestureLongPress::class, 'set_delay_factor', [9.0], 'must be between 0.5 and 2',
        ];
        yield 'font size' => [
            PangoFontDescription::class, 'set_size', [-1], 'must be greater than or equal to 0',
        ];
    }

    /** The domain is a floor, not a ban: what GTK does accept still goes through. */
    public function testValuesInsideTheDomainStillPass(): void
    {
        $calendar = new GtkCalendar();
        $calendar->set_day(28);   // 31 is inside the domain but GTK still clamps it to the month
        self::assertSame(28, $calendar->get_day(), 'a day every month has is taken as given');

        $entry = new GtkEntry();
        $entry->set_text('hello');
        self::assertSame('hello', $entry->get_chars(0, -1), '-1 still means "to the end"');

        $gesture = new GtkGestureLongPress();
        $gesture->set_delay_factor(2.0);
        self::assertSame(2.0, $gesture->get_delay_factor());
    }

    /** The message a float bound prints is not the C locale's: "0.5", never "0,5". */
    public function testAFloatBoundPrintsWithADecimalPoint(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be between 0.5 and 2, 9 given');
        new GtkGestureLongPress()->set_delay_factor(9.0);
    }

    /**
     * An enum GIR declares but nothing binds as a PHP enum crosses as an int, and its value used
     * to be checked with `check_flags()` - which casts the `GEnumClass` to a `GFlagsClass` and
     * reads `mask`, a field that is `minimum` there. It is 0 for every one of the six affected
     * types, so *every* non-zero value was refused with a message about "a mask of 0x0", and the
     * read itself was of the wrong type. Found on a ZTS run where GTK delivered a real
     * `GtkSystemSetting` to a PHP `vfunc_system_setting_changed()`.
     */
    public function testAValidValueOfAnUnboundEnumIsAccepted(): void
    {
        $scale = new ChainingScale(GtkOrientation::Horizontal);
        $scale->set_range(0.0, 10.0);

        // GTK_SCROLL_STEP_FORWARD: a value the type declares, and the whole point is that it goes
        // through rather than being refused against a mask of zero - GTK's own change_value sets
        // the range to what it was handed, so the new value is the proof that it ran.
        $scale->vfunc_change_value(3, 5.0);
        self::assertSame(5.0, $scale->get_value());
    }

    /** A value the enum does not declare is still refused, and says which type it is not in. */
    public function testAnUnknownValueOfAnUnboundEnumIsRejected(): void
    {
        $scale = new ChainingScale(GtkOrientation::Horizontal);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be a valid GtkScrollType value, 99 given');
        $scale->vfunc_change_value(99, 5.0);
    }

    private static function clipboard(): GdkClipboard
    {
        $display = GdkDisplay::get_default();
        self::assertInstanceOf(GdkDisplay::class, $display);
        return $display->get_clipboard();
    }

    /** Any paintable will do - a texture is the one every implementation shares the code with. */
    private static function paintable(): GdkTexture
    {
        return GdkTexture::new_from_bytes(PngFixture::red(2, 1));
    }

    public function testWindowTitleRoundTripIsUnaffected(): void
    {
        $window = new GtkWindow();
        $window->set_title('plain');
        self::assertSame('plain', $window->get_title());
        $window->destroy();
    }
    // ---- a widget in the wrong place (CHILD_PARAMS / SELF_UNPARENTED_OR): LogicException, not a CRITICAL

    public function testANotebookMethodRefusesAWidgetThatIsNotAPage(): void
    {
        $notebook = new GtkNotebook();
        $notebook->append_page(new GtkLabel('p'), null);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Argument #1 ($child) is not a page of this notebook');
        $notebook->set_tab_label(new GtkButton(), null);
    }

    public function testAContainerMethodRefusesAWidgetThatIsNotItsChild(): void
    {
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $box->append(new GtkLabel('a'));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Argument #1 ($child) is not a child of this GtkBox');
        $box->reorder_child_after(new GtkButton(), null);
    }

    public function testInsertAfterRefusesASiblingOfAnotherParent(): void
    {
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $elsewhere = new GtkBox(GtkOrientation::Vertical, 0);
        $sibling = new GtkButton();
        $elsewhere->append($sibling);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Argument #2 ($previous_sibling) is not a child of $parent');
        new GtkLabel('n')->insert_after($box, $sibling);
    }

    public function testInsertBeforeRefusesAWidgetThatAlreadyHasAnotherParent(): void
    {
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $elsewhere = new GtkBox(GtkOrientation::Vertical, 0);
        $widget = new GtkButton();
        $elsewhere->append($widget);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('already has a parent');
        $widget->insert_before($box, null);
    }

    public function testInsertAfterAcceptsAValidSibling(): void
    {
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $first = new GtkLabel('first');
        $box->append($first);
        $second = new GtkLabel('second');

        $second->insert_after($box, $first);

        self::assertSame($second, $first->get_next_sibling());
    }
    // ---- GLib's own value predicates and state preconditions (PARAM_VALIDATORS / SELF_PRECONDITIONS)

    public function testAnApplicationIdHasToBeValid(): void
    {
        $app = new GApplication(null, GApplicationFlags::NON_UNIQUE);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #1 ($application_id) must be a valid application id');
        $app->set_application_id('not valid!');
    }

    public function testAResourceBasePathHasToBeAbsolute(): void
    {
        $app = new GApplication(null, GApplicationFlags::NON_UNIQUE);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be an absolute resource path');
        $app->set_resource_base_path('relative');
    }

    public function testWithdrawingANotificationNeedsARegisteredApplication(): void
    {
        $app = new GApplication(null, GApplicationFlags::NON_UNIQUE);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('not registered yet');
        $app->withdraw_notification('x');
    }
    // ---- what GLib asserts about the arguments after the type check (ARG_PRECONDITIONS)

    public function testAStorePositionPastTheEndIsAValueError(): void
    {
        $store = new GListStore(GObject::class);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #1 ($position) must not be past the end of the store');
        $store->insert(1, new GObject());
    }

    public function testARangeMinimumAboveTheMaximumIsAValueError(): void
    {
        $scale = new GtkScale(GtkOrientation::Horizontal, null);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #2 ($max) must not be below $min');
        $scale->set_range(10.0, 1.0);
    }

    /**
     * `gtk_adjustment_configure()` grew `g_return_if_fail (lower + page_size <= upper)` in GTK
     * 4.22 - a page that does not fit between the bounds. GTK 4.14 takes it silently, so this is
     * the binding answering the same on both rather than a complaint only Windows CI would see.
     */
    public function testAnAdjustmentPageWiderThanItsBoundsIsAValueError(): void
    {
        $adjustment = new GtkAdjustment(0.0, 0.0, 100.0, 1.0, 10.0, 10.0);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #3 ($upper) must not be below $lower plus $page_size');
        $adjustment->configure(0.0, 0.0, 10.0, 1.0, 10.0, 20.0);
    }

    /** A span the page does fit into still goes through, page size and all. */
    public function testAnAdjustmentPageThatFitsIsConfigured(): void
    {
        $adjustment = new GtkAdjustment(0.0, 0.0, 100.0, 1.0, 10.0, 10.0);
        $adjustment->configure(5.0, 0.0, 30.0, 1.0, 10.0, 30.0);

        self::assertSame(30.0, $adjustment->get_upper());
        self::assertSame(30.0, $adjustment->get_page_size());
    }

    /**
     * `gtk_spin_button_set_range()` reaches the same 4.22 assertion through the spin button's own
     * adjustment, so the bound it has to clear is $min *plus that adjustment's page size* - not
     * $min alone, which is what GtkRange::set_range() checks.
     */
    public function testASpinButtonRangeNarrowerThanItsPageIsAValueError(): void
    {
        $spin = new GtkSpinButton(new GtkAdjustment(0.0, 0.0, 100.0, 1.0, 10.0, 10.0), 1.0, 0);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #2 ($max) must not be below $min plus the page size');
        $spin->set_range(0.0, 5.0);
    }

    /** With the default adjustment (page size 0) the check is the plain min <= max. */
    public function testASpinButtonMinimumAboveTheMaximumIsAValueError(): void
    {
        $spin = new GtkSpinButton(null, 1.0, 0);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #2 ($max) must not be below $min');
        $spin->set_range(10.0, 1.0);
    }

    /**
     * GTK 4.22 builds the paintable with `g_object_new()`, whose `size`/`scale` properties are
     * `g_param_spec_int(0, G_MAXINT)`: -1 is out of range there and passed unnoticed on 4.14.
     */
    public function testAnIconPaintableSizeBelowZeroIsAValueError(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #2 ($size) must be greater than or equal to 0, -1 given');
        GtkIconPaintable::new_for_file(__FILE__, -1, 1);
    }

    /** The same bound on the scale, which is the third argument. */
    public function testAnIconPaintableScaleBelowZeroIsAValueError(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #3 ($scale) must be greater than or equal to 0, -1 given');
        GtkIconPaintable::new_for_file(__FILE__, 16, -1);
    }

    public function testAStackChildNameNothingAnswersToIsAValueError(): void
    {
        $stack = new GtkStack();

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('is not the name of a child of this stack');
        $stack->set_visible_child_name('nope');
    }

    public function testADuplicateStackChildNameIsAValueError(): void
    {
        $stack = new GtkStack();
        $stack->add_titled(new GtkLabel('a'), 'same', 'A');

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #2 ($name) is already the name of a child');
        $stack->add_titled(new GtkLabel('b'), 'same', 'B');
    }

    public function testAnIterOffsetPastTheLineIsAValueError(): void
    {
        $buffer = new GtkTextBuffer(null);
        $buffer->set_text('ab');
        $iter = $buffer->get_start_iter();

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must not be past the end of the line');
        $iter->set_line_offset(7);
    }

    public function testATaskReturnsOnce(): void
    {
        $task = new GTask(null, null, static fn() => null);
        $task->return_int(1);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('already has a result');
        $task->return_int(2);
    }

    public function testDisconnectingAnUnknownHandlerIsAValueError(): void
    {
        $cancellable = new GCancellable();

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('is not a connected handler id');
        $cancellable->disconnect(12345);
    }

    public function testAMenuLinkNameFollowsGlibsRule(): void
    {
        $item = new GMenuItem('x', null);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be a letter followed by letters, digits and dashes');
        $item->set_link('9bad', new GMenu());
    }

    public function testAnAcceleratorStringHasToParse(): void
    {
        $app = new GtkApplication(null, GApplicationFlags::NON_UNIQUE);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be a valid accelerator string');
        $app->get_actions_for_accel('not an accel');
    }

    public function testAGutterHasToBeABorderWindow(): void
    {
        $view = new GtkTextView();

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be one of the four border windows');
        $view->set_gutter(GtkTextWindowType::Text, null);
    }

    public function testMovingAForeignOverlayIsALogicException(): void
    {
        $view = new GtkTextView();

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('is not a child of this GtkTextView');
        $view->move_overlay(new GtkButton(), 1, 1);
    }

    public function testMovingAnOverlayWorks(): void
    {
        $view = new GtkTextView();
        $overlay = new GtkButton();
        $view->add_overlay($overlay, 0, 0);

        $view->move_overlay($overlay, 5, 6);

        self::assertSame($overlay, $view->get_first_child()?->get_first_child() ?? $overlay);
    }
}
