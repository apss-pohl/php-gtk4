<?php

declare(strict_types=1);

/*
 * Stress/shutdown script for the sanitizer and coverage runs (not a PHPUnit
 * test): churns through every runtime path - handles, identity, signals,
 * user data, disconnect, exceptions in handlers, destroy vs. release order -
 * many times, then exits normally so that module shutdown runs with live and
 * dead objects around. ASan/LSan report use-after-free, overflows and leaks.
 */

use Gtk4\ExceptionMode;
use Gtk4\GLib;
use Gtk4\GListModel;
use Gtk4\GMainLoop;
use Gtk4\GObject;
use Gtk4\Gtk;
use Gtk4\GtkApplication;
use Gtk4\GtkBitset;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCssProvider;
use Gtk4\GtkCssSection;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListScrollFlags;
use Gtk4\GtkListView;
use Gtk4\GtkOrientation;
use Gtk4\GtkScrollInfo;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkStyleProviderPriority;
use Gtk4\GtkTreeListModel;
use Gtk4\GtkWindow;
use PhpGtk4\Tests\Scripts\StressSquare;

if (!Gtk::init()) {
    fwrite(STDERR, "no display\n");
    exit(1);
}

$rounds = (int) ($argv[1] ?? 200);

require_once __DIR__ . '/StressSquare.php';
$reported = 0;
Gtk::set_exception_handler(function () use (&$reported): void {
    $reported++;
});

$keep = [];
for ($i = 0; $i < $rounds; $i++) {
    $w = new GtkWindow();
    $w->set_title("w$i");
    $w->set_default_size(100 + $i, 100);

    $ids = [];
    for ($j = 0; $j < 5; $j++) {
        $ids[] = $w->connect('notify::title', function (GObject $o, Gtk4\GParamSpec $p) use (&$hits, $j): void {
            $hits = ($hits ?? 0) + $j;
        });
    }
    $ids[] = $w->connect_after('notify::title', fn() => null);
    $w->connect('notify::title', function (): void {
        throw new RuntimeException('expected');
    });
    $w->set_title("x$i");

    // toggle ref: a child kept alive by its parent only, PHP state intact, freed on remove
    $box = new GtkBox(GtkOrientation::Horizontal, 0);
    StressSquare::$round = $i;
    $box->append(new GtkButton());
    $child = $box->get_first_child();
    if (!$child instanceof GtkButton) {
        fwrite(STDERR, "no child\n");
        exit(1);
    }
    $child->set_name("b$i");
    unset($child);
    $child = $box->get_first_child();
    if (!$child instanceof GtkButton || $child->get_name() !== "b$i") {
        fwrite(STDERR, "toggle hold broken\n");
        exit(1);
    }
    $box->remove($child);
    unset($child);

    // PHP subtype: vfunc dispatch, exception in a vfunc (Log mode: reported), construction churn
    $sq = new StressSquare();
    $want = $i % 3 === 2 ? 0 : 40 + $i % 3;  // a throwing vfunc leaves GTK the zero fallback
    if ($sq->measure(GtkOrientation::Horizontal, -1)[0] !== $want) {
        fwrite(STDERR, "vfunc dispatch broken\n");
        exit(1);
    }
    $box->append($sq);
    unset($sq);
    $sq = $box->get_first_child();
    if (!$sq instanceof StressSquare) {
        fwrite(STDERR, "subtype identity broken\n");
        exit(1);
    }
    $box->remove($sq);
    unset($sq);
    $w->set_child($box);
    unset($box);

    // CSS: a provider attached to the display and detached again, a stylesheet reloaded
    // (GTK keeps and frees the old one), and a parsing error handing over a GtkCssSection
    // handle that only the handler's frame holds.
    $display = $w->get_display();
    $css = new GtkCssProvider();
    $css->connect('parsing-error', function (GtkCssProvider $p, GtkCssSection $s) use (&$sections): void {
        $sections = ($sections ?? 0) + strlen($s->to_string()) + $s->get_start_location()['bytes'];
    });
    $css->load_from_string(".stress-$i { color: rgb(1,2,3); }");
    Gtk::add_provider_for_display($display, $css, GtkStyleProviderPriority::APPLICATION);
    $css->load_from_string(".stress-$i { color: nonsense-value; }");   // one parsing error
    $w->add_css_class("stress-$i");
    Gtk::remove_provider_for_display($display, $css);
    if ($i % 2 === 0) {
        unset($css);          // dropped while its handlers are still connected
    } else {
        $keep[] = $css;       // outlives the loop
    }

    // list views: a selection model over a store, rows built by a factory, and a tree whose
    // create function is a PHP callable GTK owns (notified scope + the qdata teardown hook).
    $strings = new GtkStringList(["a$i", "b$i", 'leaf']);
    $selection = new GtkSingleSelection($strings);
    $selection->select_item(1, true);
    $factory = new GtkSignalListItemFactory();
    $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
        $item->set_child(new GtkLabel());
    });
    $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
        $child = $item->get_child();
        $object = $item->get_item();
        if ($child instanceof GtkLabel && $object instanceof GtkStringObject) {
            $child->set_text($object->get_string());
        }
    });
    $list = new GtkListView($selection, $factory);
    $list->scroll_to(0, GtkListScrollFlags::SELECT, new GtkScrollInfo());
    $set = $selection->get_selection();
    $union = clone $set;
    $union->union(GtkBitset::new_range(0, 3));
    $tree = new GtkTreeListModel(
        $strings,
        false,
        $i % 2 === 0,
        // Children for the roots only: with autoexpand on, a tree that keeps answering with
        // children would unfold forever.
        fn(GObject $item): ?GListModel => $item instanceof GtkStringObject
            && str_starts_with($item->get_string(), 'a') ? new GtkStringList(['leaf']) : null,
    );
    $tree->get_row(0)?->set_expanded(true);
    if ($i % 2 === 0) {
        $keep[] = $tree;     // outlives the loop: its create func is released at shutdown
    }
    unset($list, $factory, $selection, $tree, $strings, $set, $union);

    // owner-holding handles (object_hold_owner, BOXED_OWNERS): a composite widget and the private
    // child it handed out, a buffer and one of its iters - dropped together, collected together
    $owner = new \Gtk4\GtkScale(\Gtk4\GtkOrientation::Horizontal, null);
    $gizmo = $owner->get_first_child();
    unset($owner, $gizmo);
    $buffer = new \Gtk4\GtkTextBuffer(null);
    $buffer->set_text('owner');
    $iter = $buffer->get_start_iter();
    unset($buffer);
    $iter->forward_char();
    unset($iter);
    if ($i % 10 === 0) {
        gc_collect_cycles();
    }

    // identity + object property round trip
    $other = new GtkWindow();
    $w->set_property('transient-for', $other);
    if ($w->get_property('transient-for') !== $other) {
        fwrite(STDERR, "identity broken\n");
        exit(1);
    }
    $w->set_property('transient-for', null);

    foreach ($ids as $k => $id) {
        if ($k % 2 === 0) {
            $w->handler_disconnect($id);
        }
    }
    $w->set_title("y$i");

    // mix of lifetimes: some destroyed, some only released, some kept alive
    switch ($i % 4) {
        case 0:
            $w->present();
            $w->destroy();
            break;
        case 1:
            $w->destroy();
            unset($w);
            break;
        case 2:
            unset($w);
            break;
        case 3:
            $keep[] = $w;   // outlives the loop; freed at shutdown
            break;
    }
    unset($other);
}

// main loops: bare loop with idle/timeout sources, then an application run
$loop = new GMainLoop();
$ticks = 0;
GLib::timeout_add(1, function () use (&$ticks, $loop): bool {
    if (++$ticks >= 5) {
        $loop->quit();
        return false;
    }
    return true;
});
$idleRan = false;
GLib::idle_add(function () use (&$idleRan): bool {
    $idleRan = true;
    throw new RuntimeException('expected idle');
});
$loop->run();
// With many windows alive the frame clocks outrank the idle and the timeouts may quit the loop
// first; make sure it has fired (in Log mode) before the mode changes below.
while (!$idleRan) {
    GLib::main_context_iteration(true);
}
// C-driven nested loop: a parked Throwable + a chained one, rethrown by run().
Gtk::set_exception_mode(ExceptionMode::Rethrow);
$nested = new GMainLoop();
GLib::idle_add(function (): bool {
    GLib::timeout_add(0, fn() => throw new RuntimeException('nested 1'));
    GLib::timeout_add(0, fn() => throw new RuntimeException('nested 2'));
    usleep(2000);
    Gtk::testing_iterate_nested(2);
    return false;
});
try {
    $nested->run();
    fwrite(STDERR, "nested rethrow missing\n");
    exit(1);
} catch (RuntimeException $e) {
    if ($e->getMessage() !== 'nested 1' || $e->getPrevious()?->getMessage() !== 'nested 2') {
        fwrite(STDERR, "nested chain wrong\n");
        exit(1);
    }
}
Gtk::set_exception_mode(ExceptionMode::Log);
$app = new GtkApplication(null, 1 << 5);
$app->connect('activate', function (GtkApplication $a): void {
    $w = new GtkWindow();
    $w->set_application($a);
    $w->present();
    $w->close();
});
$app->run();

Gtk::set_exception_handler(null);
// per round: two set_title() emissions (+ the throwing vfunc every third round), plus the idle
// source, plus the two nested ones
$expected = 2 * $rounds + intdiv($rounds, 3) + 1 + 2;
if ($reported !== $expected) {
    fwrite(STDERR, "expected $expected reported exceptions, got $reported\n");
    exit(1);
}

echo "stress ok: $rounds rounds, ", count($keep), " handles alive at shutdown\n";
