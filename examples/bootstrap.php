<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\ExceptionMode;
use Gtk4\GApplicationFlags;
use Gtk4\GdkRGBA;
use Gtk4\GListModel;
use Gtk4\GListStore;
use Gtk4\GObject;
use Gtk4\GSimpleAction;
use Gtk4\Gtk;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCssProvider;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkDropDown;
use Gtk4\GtkFrame;
use Gtk4\GtkHeaderBar;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkPaned;
use Gtk4\GtkPolicyType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkSeparator;
use Gtk4\GtkStringList;
use Gtk4\GtkStyleProviderPriority;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

/**
 * Shared harness for the per-class examples next to this file.
 *
 * Every example is one class, one file, one window, and every one of them ends in
 * {@see page()} rather than running anything: run the file and it shows itself,
 * let `demo.php` require it and the page is mounted in the shared window instead.
 * One copy of each demo's source, two ways to run it.
 *
 * This file only declares - GTK is initialised lazily by run()/init(), so requiring
 * it has no side effects.
 *
 * GTK 4 has no container widget bound yet, so a window holds exactly one child. The
 * two surfaces the examples draw on are therefore a markup {@see label()} and a cairo
 * {@see canvas()}; {@see bars()} is the shared rendering of a list model used by the
 * model, filter and sorter examples.
 *
 * @phpstan-type PageBuilder callable(GtkWindow, GtkApplication): ?GtkWidget
 * @phpstan-type DemoPage array{class: string, summary: string, build: PageBuilder, width: int,
 *                             height: int, alone: (callable(): never)|null}
 */
final class Demo
{
    public const string ACCENT = '#3584e4';
    public const string INK = '#241f31';
    public const string MUTED = '#77767b';
    public const string WARN = '#e01b24';
    public const string GOOD = '#2ec27e';
    public const string PAPER = '#fdfdfd';

    /**
     * The sidebar's sections, in order. Every registered class belongs to exactly
     * one (ExampleTest checks); the sidebar shows one section at a time and scrolls
     * it (GtkScrolledWindow) when a section outgrows the window.
     *
     * @var array<string, list<string>>
     */
    public const array SECTIONS = [
        'Widgets' => [
            'GtkWidget', 'GtkWindow', 'GtkRoot', 'GtkBox', 'GtkOrientable', 'GtkButton',
            'GtkLabel', 'GtkDrawingArea', 'CairoContext', 'CairoSurface', 'GtkSnapshot',
        ],
        'Widget enums' => [
            'GtkAlign', 'GtkOrientation', 'GtkBaselinePosition', 'GtkOverflow',
            'GtkTextDirection', 'GtkDirectionType', 'GtkSizeRequestMode', 'GtkStateFlags',
            'GtkPickFlags',
        ],
        'Label text' => [
            'GtkJustification', 'PangoEllipsizeMode', 'PangoWrapMode', 'GtkNaturalWrapMode',
        ],
        'Pango' => [
            'PangoLayout', 'PangoContext', 'PangoFontMap',
            'PangoAttrList', 'PangoTabArray', 'PangoTabAlign', 'PangoDirection',
        ],
        'Application' => [
            'Gtk', 'GApplication', 'GtkApplication', 'GSimpleAction', 'GAction', 'GActionMap',
            'GActionGroup', 'GApplicationFlags', 'GtkUriLauncher', 'GNotification', 'GNotificationPriority',
        ],
        'Drag and drop' => [
            'GtkDragSource', 'GtkDropTarget', 'GdkContentProvider', 'GdkContentFormats',
        ],
        'Input devices' => [
            'GdkSeat', 'GdkDevice', 'GdkInputSource', 'GdkSeatCapabilities',
        ],
        'Objects & values' => [
            'GObject', 'GParamSpec', 'PhpValue', 'GdkRGBA', 'GdkRectangle',
            'GdkTexture', 'GdkMemoryFormat', 'GError', 'ExceptionMode',
            'GIcon', 'GThemedIcon', 'GDateTime', 'GTimeZone', 'GKeyFile',
            'GInputStream', 'GMemoryInputStream', 'GOutputStream', 'GMemoryOutputStream',
        ],
        'Lists' => [
            'GListModel', 'GListStore', 'GtkFilter', 'GtkCustomFilter',
            'GtkFilterListModel', 'GtkFilterChange', 'GtkFilterMatch', 'GtkSorter',
            'GtkCustomSorter', 'GtkSortListModel', 'GtkSorterChange', 'GtkSorterOrder',
            'GtkOrdering',
        ],
        'Styling' => [
            'GtkCssProvider', 'GtkStyleProvider', 'GtkStyleProviderPriority', 'GtkCssSection',
            'GdkDisplay', 'GtkBuilder',
        ],
        'Layout' => [
            'GtkGrid', 'GtkPaned', 'GtkFrame', 'GtkOverlay', 'GtkRevealer', 'GtkRevealerTransitionType',
            'GtkFixed', 'GtkSeparator', 'GtkSizeGroup', 'GtkSizeGroupMode', 'GtkLayoutManager',
            'GtkCenterBox', 'GtkAspectFrame', 'GtkExpander', 'GtkActionBar',
        ],
        'Boxes of children' => [
            'GtkListBox', 'GtkListBoxRow', 'GtkFlowBox', 'GtkFlowBoxChild', 'GtkSelectionMode',
        ],
        'Stacks & tabs' => [
            'GtkStack', 'GtkStackPage', 'GtkStackSwitcher', 'GtkStackSidebar', 'GtkStackTransitionType',
            'GtkNotebook', 'GtkNotebookPage', 'GtkPackType', 'GtkPositionType',
        ],
        'Scrolling' => [
            'GtkScrolledWindow', 'GtkViewport', 'GtkScrollable', 'GtkAdjustment', 'GtkPolicyType',
            'GtkCornerType', 'GtkScrollablePolicy',
        ],
        'Text input' => [
            'GtkEntry', 'GtkEditable', 'GtkEntryBuffer', 'GtkPasswordEntry', 'GtkEntryIconPosition',
            'GtkInputHints', 'GtkInputPurpose', 'GtkImageType', 'GtkAccessiblePlatformState',
        ],
        'Text view' => [
            'GtkTextView', 'GtkTextBuffer', 'GtkTextIter', 'GtkTextMark', 'GtkTextTag',
            'GtkTextChildAnchor',
            'GtkTextTagTable', 'GtkWrapMode',
        ],
        'List views' => [
            'GtkListView', 'GtkGridView', 'GtkColumnView', 'GtkColumnViewColumn', 'GtkListItem',
            'GtkListItemFactory', 'GtkSignalListItemFactory', 'GtkListScrollFlags',
            'GtkListTabBehavior', 'GtkSortType', 'GtkScrollInfo',
        ],
        'Selections & trees' => [
            'GtkSelectionModel', 'GtkSingleSelection', 'GtkMultiSelection', 'GtkNoSelection',
            'GtkBitset', 'GtkTreeListModel', 'GtkTreeListRow', 'GtkTreeExpander',
        ],
        'Buttons & ranges' => [
            'GtkCheckButton', 'GtkToggleButton', 'GtkSpinButton', 'GtkSpinButtonUpdatePolicy', 'GtkSpinType',
            'GtkRange', 'GtkScale', 'GtkProgressBar', 'GtkSpinner',
        ],
        'Images & dates' => [
            'GtkImage', 'GtkPicture', 'GdkPaintable', 'GdkPaintableFlags', 'GtkContentFit', 'GtkIconSize',
            'GtkCalendar', 'GdkDragAction',
        ],
        'Choices' => ['GtkDropDown', 'GtkStringList', 'GtkStringObject', 'GtkStringFilterMatchMode'],
        'Input controllers' => [
            'GtkEventController', 'GtkEventControllerKey', 'GtkEventControllerMotion',
            'GtkEventControllerScroll', 'GtkEventControllerFocus', 'GtkEventControllerLegacy',
            'GtkPropagationPhase', 'GtkPropagationLimit', 'GtkEventControllerScrollFlags', 'GdkScrollUnit',
        ],
        'Gestures' => [
            'GtkGesture', 'GtkGestureSingle', 'GtkGestureClick', 'GtkGestureDrag', 'GtkGestureLongPress',
            'GtkGestureSwipe', 'GtkGesturePan', 'GtkGestureZoom', 'GtkGestureRotate', 'GtkPanDirection',
            'GtkEventSequenceState',
        ],
        'Events' => [
            'GdkEvent', 'GdkKeyEvent', 'GdkButtonEvent', 'GdkScrollEvent', 'GdkCrossingEvent', 'GdkFocusEvent',
            'GdkTouchEvent', 'GdkTouchpadEvent', 'GdkPadEvent', 'GdkGrabBrokenEvent', 'GdkEventSequence',
            'GdkEventType',
            'GdkScrollDirection', 'GdkCrossingMode', 'GdkNotifyType', 'GdkTouchpadGesturePhase', 'GdkKeyMatch',
        ],
        'Menus' => [
            'GMenuModel', 'GMenu', 'GMenuItem', 'GtkPopover', 'GtkPopoverMenu', 'GtkPopoverMenuBar', 'GtkMenuButton',
            'GtkHeaderBar', 'GtkApplicationWindow', 'GtkArrowType', 'GtkPopoverMenuFlags',
        ],
        'Loop' => ['GLib', 'GMainLoop', 'GIOCondition'],
        'Geometry' => [
            'GtkRequisition', 'GrapheneMatrix', 'GraphenePoint3D',
            'GrapheneVec2', 'GrapheneVec3', 'GrapheneVec4',
        ],
        'Pixbuf' => [
            'GdkPixbuf', 'GdkPixbufLoader', 'GdkPixbufFormat', 'GdkPixbufAnimation', 'GdkPixbufAnimationIter',
            'GdkColorspace', 'GdkInterpType', 'GdkPixbufRotation', 'GdkMemoryTexture',
            'GdkTextureDownloader',
        ],
        'Scene graph' => [
            'GskRenderNode', 'GskColorNode', 'GskContainerNode', 'GskTransformNode', 'GskTransform',
            'GskRoundedRect', 'GskRoundedClipNode', 'GskBorderNode', 'GskLinearGradientNode',
            'GskShadowNode', 'GskPath', 'GskPathBuilder', 'GskStroke', 'GskStrokeNode', 'GskFillNode',
            'GskRenderer', 'GskCairoRenderer', 'GskCorner',
        ],
        'Dialogs & async' => [
            'GtkAlertDialog', 'GtkFileDialog', 'GtkFileFilter', 'GtkColorDialog', 'GtkFontDialog',
            'GtkAboutDialog', 'GtkLicense', 'PangoFontDescription',
            'GCancellable', 'GAsyncResult', 'GTask',
        ],
        // Only in a build with --enable-gtk4-webkit: pages() leaves these out otherwise.
        'Web' => [
            'WebKitWebView', 'WebKitSettings', 'WebKitUserContentManager', 'WebKitFindController',
            'JSCContext', 'JSCValue',
        ],
    ];

    /**
     * SECTIONS plus the classes the generator added but nobody placed yet
     * (examples/generated-sections.inc, written by gen/gir.php --install).
     *
     * @return array<string, list<string>>
     */
    public static function sections(): array
    {
        $file = __DIR__ . '/generated-sections.inc';
        /** @var array<string, list<string>> $generated */
        $generated = is_file($file) ? require $file : [];
        return array_filter(self::SECTIONS + $generated, static fn(array $members): bool => $members !== []);
    }

    private static bool $ready = false;
    private static string $prefix = 'php-gtk4';
    private static ?GtkWindow $window = null;

    /**
     * GTK init plus the callback exception policy every example shares.
     *
     * A Throwable must never unwind through GTK's C frames, so one thrown inside a
     * signal handler is reported here instead and GTK carries on (ExceptionMode::Log).
     * ExceptionMode.php shows the alternative.
     */
    public static function init(): void
    {
        if (self::$ready) {
            return;
        }
        if (!Gtk::init()) {
            fwrite(STDERR, "no display - run inside a desktop session or under xvfb-run\n");
            exit(1);
        }
        Gtk::set_exception_mode(ExceptionMode::Log);
        Gtk::set_exception_handler(static function (\Throwable $e, string $origin): void {
            error_log(sprintf('[%s] %s: %s', $origin, $e::class, $e->getMessage()));
        });
        self::$ready = true;
    }

    /**
     * Build a single-window application around $build and run it until the window closes.
     *
     * @param callable(GtkWindow, GtkApplication): ?GtkWidget $build
     *        Returns the window's child, or null if it set one itself.
     */
    public static function run(string $title, callable $build, int $width = 480, int $height = 340): never
    {
        self::init();
        $app = new GtkApplication('org.phpgtk4.examples', GApplicationFlags::NON_UNIQUE);
        $app->connect('activate', static function (GtkApplication $app) use ($title, $build, $width, $height): void {
            $win = new GtkWindow();
            $win->set_application($app);
            $win->set_default_size($width, $height);
            self::$window = $win;
            self::$prefix = 'php-gtk4 · ' . $title;
            self::status();
            $child = $build($win, $app);
            if ($child instanceof GtkWidget) {
                $win->set_child($child);
            }
            $win->present();
        });
        exit($app->run());
    }

    /**
     * Declare what this file demonstrates.
     *
     * Called at the end of every examples/<Class>.php, which does nothing else -
     * the file is a description, not a program. examples/demo.php collects them all
     * into the application; `examples/demo.php GtkButton` runs just this one.
     *
     * @param callable(GtkWindow, GtkApplication): ?GtkWidget $build
     * @param (callable(): never)|null $alone Replaces the window when this page is run on its own
     * @return DemoPage
     */
    public static function page(
        string $class,
        string $summary,
        callable $build,
        int $width = 480,
        int $height = 340,
        ?callable $alone = null,
    ): array {
        return [
            'class' => $class,
            'summary' => $summary,
            'build' => $build,
            'width' => $width,
            'height' => $height,
            'alone' => $alone,
        ];
    }

    /**
     * Show one page in a window of its own - what `demo.php <Class>` does.
     *
     * @param DemoPage $page
     */
    public static function single(array $page): never
    {
        if ($page['alone'] !== null) {
            ($page['alone'])();
        }
        self::run($page['class'], $page['build'], $page['width'], $page['height']);
    }

    /**
     * Every examples/<Class>.php whose class this build has, required and asked for its page.
     *
     * A page of an optional feature (`WebKitWebView` needs `--enable-gtk4-webkit`) is left out
     * when the extension does not register its class, so the sidebar only offers what can run.
     *
     * @return list<DemoPage>
     */
    public static function pages(): array
    {
        $pages = [];
        foreach (glob(__DIR__ . '/*.php') ?: [] as $file) {
            if (in_array(basename($file), ['bootstrap.php', 'demo.php'], true)) {
                continue;
            }
            $class = 'Gtk4\\' . basename($file, '.php');
            if (!class_exists($class) && !interface_exists($class)) {
                continue;   // a feature this build lacks (Gtk4\FEATURES)
            }
            $loaded = require $file;
            if (!is_array($loaded) || !isset($loaded['class'], $loaded['summary'], $loaded['build'])) {
                continue;   // not a page file
            }
            /** @var DemoPage $loaded */
            $pages[] = $loaded;
        }
        usort($pages, static fn(array $a, array $b): int => strcmp($a['class'], $b['class']));
        return $pages;
    }

    /**
     * The demo application: a header bar, a sidebar of sections, a content area.
     *
     * Every piece of the chrome is a widget the examples themselves document, which is
     * the point: {@see GtkHeaderBar} for the window controls, {@see GtkPaned} so the
     * sidebar can be resized, {@see GtkDropDown} over a {@see GtkStringList} to pick a
     * section without a column of buttons taller than the window,
     * {@see GtkScrolledWindow} for the section's classes, {@see GtkFrame} to give the
     * page an edge and {@see GtkCssProvider} for the rest of the styling.
     *
     * Pages are built the first time they are shown and then kept, so a page's
     * animations pick up where they left off. The content is swapped rather than
     * stacked (a GtkStack would build every page up front).
     *
     * @param list<DemoPage> $pages
     */
    public static function showcase(array $pages): never
    {
        self::init();
        if ($pages === []) {
            fwrite(STDERR, 'no pages found in ' . __DIR__ . "\n");
            exit(1);
        }

        /** @var array<string, DemoPage> $byClass */
        $byClass = [];
        foreach ($pages as $page) {
            $byClass[$page['class']] = $page;
        }

        $app = new GtkApplication('org.phpgtk4.examples.demo', GApplicationFlags::NON_UNIQUE);
        $app->connect('activate', static function (GtkApplication $app) use ($pages, $byClass): void {
            $win = new GtkWindow();
            $win->set_application($app);
            $win->set_default_size(1040, 720);
            self::$window = $win;
            self::chrome($win);

            /** @var array<string, GtkWidget> $built */
            $built = [];
            $section = (string) array_key_first(self::sections());
            $class = $pages[0]['class'];

            // Navigation goes through an action, so nothing here has to hold a
            // reference to anything defined below it.
            $select = new GSimpleAction('select', 's');
            $app->add_action($select);
            $jump = static function (string $to) use ($app): void {
                $app->activate_action('select', $to);
            };

            // The sections with at least one page in this build, and where each one
            // starts (pages() leaves out the classes of a feature the extension was
            // built without, which can empty a whole section).
            $sectionNames = [];
            $opensAt = [];
            foreach (self::sections() as $name => $members) {
                $first = array_values(array_filter($members, static fn(string $m): bool => isset($byClass[$m])));
                if ($first === []) {
                    continue;
                }
                $sectionNames[] = $name;
                $opensAt[$name] = $first[0];
            }

            $chooser = new GtkDropDown();
            $chooser->set_model(new GtkStringList($sectionNames));
            $chooser->set_tooltip_text('Section');

            $list = new GtkBox(GtkOrientation::Vertical, 2);
            $count = new GtkLabel();
            $count->add_css_class('dim-label');
            $count->set_halign(GtkAlign::Start);

            $heading = new GtkLabel();
            $heading->set_halign(GtkAlign::Start);
            $heading->set_hexpand(true);
            $heading->add_css_class('demo-heading');

            $content = new GtkBox(GtkOrientation::Vertical, 0);
            $content->set_hexpand(true);
            $content->set_vexpand(true);

            // The sidebar lists the current section only; the list scrolls, so a long
            // section (the layout wave has 27 entries) never pushes the window taller.
            $fillList = function () use ($list, &$section, &$class, $jump, $byClass): void {
                foreach ($list->get_children() as $old) {
                    $list->remove($old);
                }
                foreach (self::sections()[$section] as $member) {
                    if (!isset($byClass[$member])) {
                        continue;
                    }
                    // A left-aligned label rather than the button's own centred one: a
                    // column of centred words does not read as a list.
                    $text = new GtkLabel($member);
                    $text->set_xalign(0.0);
                    $button = new GtkButton();
                    $button->set_child($text);
                    $button->add_css_class($member === $class ? 'suggested-action' : 'flat');
                    $button->add_css_class('demo-item');
                    $button->connect('clicked', static function () use ($jump, $member): void {
                        $jump($member);
                    });
                    $list->append($button);
                }
            };

            $show = function (string $wanted) use (
                &$built,
                &$class,
                &$section,
                $fillList,
                $byClass,
                $pages,
                $chooser,
                $sectionNames,
                $content,
                $count,
                $heading,
                $win,
                $app
            ): void {
                if (!isset($byClass[$wanted])) {
                    return;
                }
                $page = $byClass[$wanted];
                $class = $wanted;
                foreach (self::sections() as $name => $members) {
                    if (in_array($wanted, $members, true)) {
                        $section = $name;
                    }
                }
                if (!isset($built[$wanted])) {
                    $widget = ($page['build'])($win, $app);
                    $built[$wanted] = $widget instanceof GtkWidget
                        ? $widget
                        : self::label('(this page builds no widget)');
                    // Natural size, centred in the stage: expand takes the spare room,
                    // align keeps the widget its own size inside it.
                    $built[$wanted]->set_halign(GtkAlign::Center);
                    $built[$wanted]->set_valign(GtkAlign::Center);
                    $built[$wanted]->set_hexpand(true);
                    $built[$wanted]->set_vexpand(true);
                }
                foreach ($content->get_children() as $old) {
                    $content->remove($old);   // GTK inserts, it never reparents
                }
                $content->append($built[$wanted]);
                $heading->set_markup(sprintf(
                    "<span size=\"x-large\"><b>%s</b></span>\n<span alpha=\"60%%\"><small>%s</small></span>",
                    htmlspecialchars($page['class']),
                    htmlspecialchars($page['summary']),
                ));

                // Where this page sits: which section, and how far through the whole set.
                $names = array_map(static fn(array $one): string => $one['class'], $pages);
                $at = array_search($wanted, $names, true);
                $count->set_text(sprintf(
                    '%s  ·  %d of %d',
                    $section,
                    (is_int($at) ? $at : 0) + 1,
                    count($names),
                ));

                $wantedSection = array_search($section, $sectionNames, true);
                if (is_int($wantedSection) && $chooser->get_selected() !== $wantedSection) {
                    $chooser->set_selected($wantedSection);
                }

                self::$prefix = 'php-gtk4 · ' . $page['class'];
                self::status($section);
                $fillList();
            };

            $select->connect('activate', static function (GSimpleAction $self, mixed $wanted) use ($show): void {
                if (is_string($wanted)) {
                    $show($wanted);
                }
            });

            // No guard flag against $show's own set_selected(): by the time that fires,
            // $section is already the section it moved to, so this asks for nothing.
            $chooser->connect(
                'notify::selected',
                static function (GtkDropDown $self) use (&$section, $sectionNames, $opensAt, $jump): void {
                    $name = $sectionNames[$self->get_selected()] ?? null;
                    if ($name !== null && $name !== $section) {
                        $jump($opensAt[$name]);
                    }
                },
            );

            // Previous/Next walk the whole list, not just the section.
            $step = static function (int $delta) use ($pages, &$class, $jump): void {
                $names = array_map(static fn(array $page): string => $page['class'], $pages);
                $at = array_search($class, $names, true);
                $at = is_int($at) ? $at : 0;
                $jump($names[($at + $delta + count($names)) % count($names)]);
            };
            $quit = new GSimpleAction('quit');
            $quit->connect('activate', static function () use ($app): void {
                $app->quit();
            });
            $app->add_action($quit);

            foreach (['previous' => -1, 'next' => 1] as $name => $delta) {
                $action = new GSimpleAction($name);
                $action->connect('activate', static function () use ($step, $delta): void {
                    $step($delta);
                });
                $app->add_action($action);
            }
            $nav = static function (string $label, string $action, string $tip) use ($app): GtkButton {
                $button = GtkButton::new_with_label($label);
                $button->set_tooltip_text($tip);
                $button->connect('clicked', static function () use ($app, $action): void {
                    $app->activate_action($action);
                });
                return $button;
            };

            // The window controls live in the titlebar, where the theme puts them; the
            // window title (Demo::status()) is what GtkHeaderBar shows in the middle.
            $header = new GtkHeaderBar();
            $header->pack_start($nav('◀', 'previous', 'Previous page'));
            $header->pack_start($nav('▶', 'next', 'Next page'));
            $header->pack_end($nav('Quit', 'quit', 'Close the demo'));

            $scroller = new GtkScrolledWindow();
            $scroller->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
            $scroller->set_child($list);
            $scroller->set_vexpand(true);

            $sidebar = new GtkBox(GtkOrientation::Vertical, 8);
            $sidebar->add_css_class('demo-sidebar');
            $sidebar->append($chooser);
            $sidebar->append($scroller);
            $sidebar->append($count);

            $stage = new GtkFrame();
            $stage->add_css_class('demo-stage');
            $stage->set_child($content);

            $page = new GtkBox(GtkOrientation::Vertical, 10);
            $page->add_css_class('demo-page');
            $page->append($heading);
            $page->append(new GtkSeparator(GtkOrientation::Horizontal));
            $page->append($stage);

            $paned = new GtkPaned(GtkOrientation::Horizontal);
            $paned->set_start_child($sidebar);
            $paned->set_end_child($page);
            $paned->set_position(220);
            $paned->set_resize_start_child(false);
            $paned->set_shrink_start_child(false);

            $win->set_titlebar($header);
            $win->set_child($paned);

            // This is the primary window: closing it ends the application, even
            // though a page may have opened toplevels of its own (GtkWindow does).
            $win->connect('close-request', static function () use ($app): bool {
                $app->quit();
                return false;
            });

            $show($pages[0]['class']);
            $win->present();
        });

        exit($app->run());
    }

    /**
     * The showcase's own stylesheet: the borders and spacing that separate the sidebar,
     * the heading and the page from each other. Everything else is the user's theme -
     * only `alpha(currentColor, …)` is used for colour, so this reads the same on a
     * light theme and a dark one.
     */
    private static function chrome(GtkWindow $win): void
    {
        $provider = new GtkCssProvider();
        $provider->load_from_string(<<<'CSS'
            .demo-sidebar {
                padding: 10px;
                border-right: 1px solid alpha(currentColor, 0.15);
            }
            .demo-item {
                padding-left: 10px;
                padding-right: 10px;
            }
            .demo-page {
                padding: 14px;
            }
            .demo-heading {
                padding-left: 2px;
            }
            .demo-stage {
                border: 1px solid alpha(currentColor, 0.15);
                border-radius: 8px;
                background-color: alpha(currentColor, 0.03);
            }
            CSS);
        Gtk::add_provider_for_display($win->get_display(), $provider, GtkStyleProviderPriority::APPLICATION);
    }

    /**
     * Put a line of state in the window title, under whatever heading is current.
     *
     * Standalone that reads "php-gtk4 · GtkLabel — selection 0..14"; inside the
     * showcase, "12/35 · GtkLabel — selection 0..14". A page that wants to drive the
     * title itself (GObject, GParamSpec, GtkWindow) just calls set_title() instead.
     */
    public static function status(string $text = ''): void
    {
        self::$window?->set_title($text === '' ? self::$prefix : self::$prefix . ' — ' . $text);
    }

    /** A centred, wrapping Pango-markup label - the text surface most examples write on. */
    public static function label(string $markup = ''): GtkLabel
    {
        $label = new GtkLabel();
        $label->set_markup($markup);
        $label->wrap = true;
        $label->set_halign(GtkAlign::Center);
        $label->set_valign(GtkAlign::Center);
        return $label;
    }

    /** @param callable(GtkDrawingArea, CairoContext, int, int): void $draw */
    public static function canvas(int $width, int $height, callable $draw): GtkDrawingArea
    {
        $area = new GtkDrawingArea();
        $area->set_content_width($width);
        $area->set_content_height($height);
        $area->set_draw_func($draw);
        return $area;
    }

    /** Fill the whole area with PAPER and return a pen colour - the start of every canvas. */
    public static function sheet(CairoContext $cr, string $css = self::INK): GdkRGBA
    {
        $cr->set_source_color(new GdkRGBA(self::PAPER));
        $cr->paint();
        return new GdkRGBA($css);
    }

    /** Draw $text at ($x, $y) in $css at $size pixels. */
    public static function text(
        CairoContext $cr,
        float $x,
        float $y,
        string $text,
        string $css = self::INK,
        float $size = 13.0,
    ): void {
        $cr->set_source_color(new GdkRGBA($css));
        $cr->set_font_size($size);
        $cr->move_to($x, $y);
        $cr->show_text($text);
    }

    /** @return list<array{name: string, born: int}> The dataset the list examples share. */
    public static function people(): array
    {
        return [
            ['name' => 'Ada Lovelace', 'born' => 1815],
            ['name' => 'Grace Hopper', 'born' => 1906],
            ['name' => 'Alan Turing', 'born' => 1912],
            ['name' => 'Katherine Johnson', 'born' => 1918],
            ['name' => 'Margaret Hamilton', 'born' => 1936],
        ];
    }

    /** The same people as a GListStore of PhpValue items. */
    public static function store(): GListStore
    {
        $store = new GListStore(PhpValue::class);
        foreach (self::people() as $row) {
            $store->append(new PhpValue($row));
        }
        return $store;
    }

    /** Name of a PhpValue row, '?' for anything else. */
    public static function name(?GObject $item): string
    {
        $row = $item instanceof PhpValue ? $item->get_value() : null;
        $name = is_array($row) ? ($row['name'] ?? null) : null;
        return is_string($name) ? $name : '?';
    }

    /** Birth year of a PhpValue row, 0 for anything else. */
    public static function born(?GObject $item): int
    {
        $row = $item instanceof PhpValue ? $item->get_value() : null;
        $born = is_array($row) ? ($row['born'] ?? null) : null;
        return is_int($born) ? $born : 0;
    }

    /**
     * One labelled bar per item of $model - the shared picture of a list model.
     *
     * The model is read on every draw, so any change only needs queue_draw(); the
     * caption is a callable for the same reason.
     *
     * @param callable(): string $caption
     */
    public static function bars(GListModel $model, callable $caption, string $css = self::ACCENT): GtkDrawingArea
    {
        return self::canvas(440, 240, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            $model,
            $caption,
            $css
        ): void {
            self::sheet($cr);
            $count = $model->get_n_items();
            self::text($cr, 14, 24, sprintf('%s — %d item(s)', $caption(), $count), self::INK, 14);

            for ($i = 0; $i < $count; $i++) {
                $y = 44 + $i * 26;
                if ($y + 20 > $height) {
                    self::text($cr, 14, $height - 8, '…', self::MUTED, 12);
                    break;
                }
                $item = $model->get_item($i);
                // 1800..1950 mapped onto the space left of the caption column.
                $bar = max(8, (int) round((self::born($item) - 1800) / 150 * ($width - 210)));
                $cr->set_source_color(new GdkRGBA($css));
                $cr->rectangle(14, $y, $bar, 16);
                $cr->fill();
                $caption = sprintf('%s · %d', self::name($item), self::born($item));
                self::text($cr, 14 + $bar + 8, $y + 13, $caption, self::INK, 12);
            }
        });
    }
}
