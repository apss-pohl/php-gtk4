<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GListModel;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListView;
use Gtk4\GtkOrientation;
use Gtk4\GtkSelectionModel;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSnapshot;
use PhpGtk4\Tests\Subclass\PhpSelectionModel;

/**
 * A GtkSelectionModel written in PHP, driven by a real GtkListView. The interface has GListModel
 * as a prerequisite, which GLib refuses to infer: core/subtype has to add GListModel to the PHP
 * class' GType first, or the whole type comes out half-built (a CRITICAL, then every
 * GtkSelectionModel parameter rejects it).
 */
final class PhpSelectionModelTest extends GtkTestCase
{
    private function listView(PhpSelectionModel $model): GtkListView
    {
        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', static function (GtkSignalListItemFactory $factory, GtkListItem $item): void {
            $item->set_child(new GtkLabel('row'));
        });

        $view = new GtkListView($model, $factory);
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $box->append($view);
        $window = $this->window();
        $window->set_child($box);
        $window->present();
        $box->allocate(200, 200);
        $box->snapshot_child($view, new GtkSnapshot());

        return $view;
    }

    public function testThePhpObjectIsBothAListModelAndASelectionModel(): void
    {
        $model = new PhpSelectionModel(3);

        self::assertInstanceOf(GtkSelectionModel::class, $model);
        self::assertInstanceOf(GListModel::class, $model, 'the interface prerequisite went on too');
        self::assertSame(3, $model->get_n_items());
    }

    public function testTheListViewAsksPhpWhichRowsAreSelected(): void
    {
        $model = new PhpSelectionModel(3);

        $this->listView($model);

        self::assertContains('is_selected(0)', $model->calls, 'GTK asked PHP while rendering');
    }

    public function testTheSelectAllActionReachesThePhpSlot(): void
    {
        $model = new PhpSelectionModel(3);
        $view = $this->listView($model);
        $model->calls = [];

        $view->activate_action('list.select-all');

        self::assertContains('select_all', $model->calls);
        self::assertSame([true, true, true], [
            $model->selected[0] ?? false,
            $model->selected[1] ?? false,
            $model->selected[2] ?? false,
        ]);
    }

    public function testTheUnselectAllActionReachesThePhpSlot(): void
    {
        $model = new PhpSelectionModel(3);
        $view = $this->listView($model);
        $view->activate_action('list.select-all');
        $model->calls = [];

        $view->activate_action('list.unselect-all');

        self::assertContains('unselect_all', $model->calls);
        self::assertSame([], $model->selected);
    }
}
