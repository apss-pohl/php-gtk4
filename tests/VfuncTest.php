<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GApplication;
use Gtk4\GApplicationFlags;
use Gtk4\GLib;
use Gtk4\GListStore;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkDirectionType;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkFilter;
use Gtk4\GtkFilterListModel;
use Gtk4\GtkOrientation;
use Gtk4\GtkSorter;
use Gtk4\GtkSortListModel;
use Gtk4\GtkStateFlags;
use Gtk4\GtkTextDirection;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

/**
 * Every generated vfunc thunk and native vfunc_*() method (src/core/subtype, gen/gir.php): a
 * subclass per class overrides *all* its vfunc_* methods, records the call and chains to
 * parent:: (the native implementation), then GTK is driven through them.
 */
final class VfuncTest extends GtkTestCase
{
    /**
     * Native vfunc_*() safe to call directly on an instance in any state. Lifecycle slots
     * (map/realize/root/size_allocate/...) g_assert() their preconditions - GTK reaches them
     * through the thunks above, chained to parent::; run_mainloop blocks, enable_debugging opens
     * the inspector, startup/shutdown/activate need a registered application.
     */
    private const array DIRECT = [
        GtkWidget::class => ['vfunc_measure', 'vfunc_get_request_mode', 'vfunc_contains', 'vfunc_focus',
            'vfunc_keynav_failed', 'vfunc_mnemonic_activate', 'vfunc_direction_changed',
            'vfunc_state_flags_changed', 'vfunc_system_setting_changed'],
        GtkButton::class => ['vfunc_clicked', 'vfunc_activate'],
        GtkDrawingArea::class => ['vfunc_resize'],
        GtkWindow::class => ['vfunc_close_request', 'vfunc_activate_default', 'vfunc_activate_focus',
            'vfunc_keys_changed'],
        GtkFilter::class => ['vfunc_get_strictness'],
        GtkSorter::class => ['vfunc_get_order'],
        GtkApplication::class => [],
        GApplication::class => [],
    ];

    /**
     * An eval'd subclass of $base overriding every vfunc_* with the exact signature, recording
     * the name in $hits and chaining to parent::.
     *
     * @template T of object
     * @param class-string<T> $base
     * @param array<string, string> $bodies vfunc name -> body replacing the parent:: chain (slots
     *        GTK leaves abstract-by-contract: GtkFilter::match CRITICALs "does not implement")
     * @return class-string<T>
     */
    private static function recordingSubclass(string $base, array $bodies = []): string
    {
        $short = 'Rec_' . str_replace('\\', '_', $base);
        $fqcn = 'PhpGtk4\\Tests\\Vfunc\\' . $short;
        if (class_exists($fqcn, false)) {
            /** @var class-string<T> $fqcn */
            return $fqcn;
        }
        $methods = '';
        foreach (new \ReflectionClass($base)->getMethods() as $m) {
            if (!str_starts_with($m->getName(), 'vfunc_')) {
                continue;
            }
            $params = [];
            $args = [];
            foreach ($m->getParameters() as $p) {
                $params[] = self::typeDecl((string) $p->getType()) . ' $' . $p->getName();
                $args[] = '$' . $p->getName();
            }
            $ret = (string) $m->getReturnType();
            $retDecl = $ret === '' ? '' : ': ' . self::typeDecl($ret);
            $body = $bodies[$m->getName()]
                ?? ($ret === 'void' ? '' : 'return ') . 'parent::' . $m->getName() . '(' . implode(', ', $args) . ');';
            $methods .= "    public function {$m->getName()}(" . implode(', ', $params) . ")$retDecl\n"
                . "    { \$this->hits[] = '{$m->getName()}'; $body }\n";
        }
        eval("namespace PhpGtk4\\Tests\\Vfunc; final class $short extends \\$base {\n"
            . "    public array \$hits = [];\n$methods}");
        self::assertTrue(class_exists($fqcn, false));
        self::assertTrue(is_a($fqcn, $base, true));

        return $fqcn;
    }

    /** A reflection type string as source: builtins bare, classes fully qualified. */
    private static function typeDecl(string $type): string
    {
        $bare = ltrim($type, '?');
        if (in_array($bare, ['void', 'bool', 'int', 'float', 'string', 'array'], true)) {
            return $type;
        }
        return (str_starts_with($type, '?') ? '?' : '') . '\\' . $bare;
    }

    /** @return list<string> */
    private static function hitsOf(object $o): array
    {
        $hits = $o->hits;  // @phpstan-ignore property.notFound
        self::assertIsArray($hits);

        return array_values(array_map(fn(mixed $h): string => is_string($h) ? $h : '', $hits));
    }

    /**
     * A plausible argument for each parameter type of a native vfunc_*() call.
     *
     * @return list<mixed>
     */
    private function sampleArgs(\ReflectionMethod $m): array
    {
        $args = [];
        foreach ($m->getParameters() as $p) {
            $t = ltrim((string) $p->getType(), '?');
            $args[] = match ($t) {
                'int' => 0,
                'float' => 1.0,
                'bool' => false,
                'string' => 's',
                GtkWidget::class => GtkButton::new_with_label('x'),
                GtkWindow::class => $this->window(),
                'Gtk4\\GObject' => null,
                default => enum_exists($t) ? $t::cases()[0] : null,
            };
        }
        return $args;
    }

    /**
     * @param class-string $base
     * @param list<string> $expectedHits thunks GTK itself must have called
     */
    private function assertAllVfuncs(string $base, object $o, array $expectedHits): void
    {
        foreach ($expectedHits as $h) {
            self::assertContains($h, self::hitsOf($o), "$base: GTK called $h through the thunk");
        }
        foreach (self::DIRECT[$base] as $name) {
            $m = new \ReflectionMethod($base, $name);
            $m->invokeArgs($o, $this->sampleArgs($m));  // the native implementation, directly
        }
    }

    /** A few non-blocking main-context iterations (never relied on: layout is driven directly). */
    private static function pump(): void
    {
        for ($i = 0; $i < 5; $i++) {
            GLib::main_context_iteration(false);
        }
    }

    public function testWidget(): void
    {
        $class = self::recordingSubclass(GtkWidget::class);
        $w = new $class();
        // A recording window as the toplevel: its vfunc_size_allocate() (a PHP subtype may call
        // its own slot) allocates the child synchronously - no frame clock, no timing.
        $winClass = self::recordingSubclass(GtkWindow::class);
        $win = new $winClass();
        $win->set_child($w);
        $w->set_focusable(true);
        $win->present();
        self::pump();
        $win->vfunc_size_allocate(200, 200, -1);
        $w->measure(GtkOrientation::Horizontal, -1);
        $w->grab_focus();
        $w->contains(1.0, 1.0);
        $w->mnemonic_activate(false);  // before INSENSITIVE: an insensitive widget short-circuits
        $w->child_focus(GtkDirectionType::TabForward);
        $w->keynav_failed(GtkDirectionType::Down);
        $w->set_state_flags(GtkStateFlags::INSENSITIVE, false);
        $w->set_direction(GtkTextDirection::Rtl);
        $win->set_child(null);
        $win->destroy();
        $this->assertAllVfuncs(GtkWidget::class, $w, [
            'vfunc_root', 'vfunc_realize', 'vfunc_map', 'vfunc_measure', 'vfunc_size_allocate',
            'vfunc_get_request_mode', 'vfunc_grab_focus', 'vfunc_contains', 'vfunc_state_flags_changed',
            'vfunc_direction_changed', 'vfunc_focus', 'vfunc_keynav_failed', 'vfunc_mnemonic_activate',
            'vfunc_unmap', 'vfunc_unrealize', 'vfunc_unroot',
        ]);
    }

    public function testButton(): void
    {
        $class = self::recordingSubclass(GtkButton::class);
        $b = new $class();
        $b->emit('clicked');
        $b->emit('activate');
        $this->assertAllVfuncs(GtkButton::class, $b, ['vfunc_clicked', 'vfunc_activate']);
    }

    public function testDrawingArea(): void
    {
        $class = self::recordingSubclass(GtkDrawingArea::class);
        $d = new $class();
        $d->set_content_width(80);
        $d->set_content_height(60);
        $win = $this->window();
        $win->set_child($d);
        $win->present();
        self::pump();
        $d->vfunc_size_allocate(80, 60, -1);  // GtkDrawingArea's own size_allocate emits `resize`
        $this->assertAllVfuncs(GtkDrawingArea::class, $d, ['vfunc_resize']);
    }

    public function testWindow(): void
    {
        $class = self::recordingSubclass(GtkWindow::class);
        $w = new $class();
        $w->set_child(GtkButton::new_with_label('x'));
        $w->present();
        self::pump();
        $w->emit('activate-default');
        $w->emit('activate-focus');
        $w->emit('keys-changed');
        $w->close();  // emits close-request synchronously
        self::pump();
        $this->assertAllVfuncs(GtkWindow::class, $w, [
            'vfunc_activate_default', 'vfunc_activate_focus', 'vfunc_keys_changed', 'vfunc_close_request',
        ]);
        $w->destroy();
    }

    public function testFilterAndSorter(): void
    {
        $fClass = self::recordingSubclass(GtkFilter::class, [
            'vfunc_match' => 'return true;', 'vfunc_get_strictness' => 'return \Gtk4\GtkFilterMatch::Some;',
        ]);
        $f = new $fClass();
        $store = new GListStore(PhpValue::class);
        $store->append(new PhpValue(1));
        $model = new GtkFilterListModel($store, $f);
        self::assertSame(1, $model->get_n_items());
        $this->assertAllVfuncs(GtkFilter::class, $f, ['vfunc_match', 'vfunc_get_strictness']);

        $sClass = self::recordingSubclass(GtkSorter::class, [
            'vfunc_compare' => 'return \Gtk4\GtkOrdering::Equal;',
            'vfunc_get_order' => 'return \Gtk4\GtkSorterOrder::Partial;',
        ]);
        $s = new $sClass();
        $store->append(new PhpValue(2));
        $sorted = new GtkSortListModel($store, $s);
        self::assertSame(2, $sorted->get_n_items());
        $s->compare(new PhpValue(1), new PhpValue(2));
        $this->assertAllVfuncs(GtkSorter::class, $s, ['vfunc_compare', 'vfunc_get_order']);
    }

    public function testApplications(): void
    {
        $class = self::recordingSubclass(GtkApplication::class);
        $app = new $class('org.phpgtk4.vfunc.' . getmypid(), GApplicationFlags::NON_UNIQUE);
        $app->connect('activate', function () use ($app): void {
            $w = new GtkWindow();
            $w->set_application($app);
            $w->destroy();
            $app->quit();
        });
        $app->run([]);
        $this->assertAllVfuncs(GtkApplication::class, $app, [
            'vfunc_startup', 'vfunc_activate', 'vfunc_shutdown', 'vfunc_window_added', 'vfunc_window_removed',
        ]);

        $gClass = self::recordingSubclass(GApplication::class);
        $g = new $gClass('org.phpgtk4.vfunc.g' . getmypid(), GApplicationFlags::NON_UNIQUE);
        $g->connect('activate', fn() => $g->quit());
        $g->run([]);
        $this->assertAllVfuncs(GApplication::class, $g, ['vfunc_startup', 'vfunc_activate', 'vfunc_shutdown']);
    }
}
