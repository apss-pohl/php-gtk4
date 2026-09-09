<?php

declare(strict_types=1);

namespace PhpGtk4\Examples\Notes;

use Gtk4\GAsyncResult;
use Gtk4\GError;
use Gtk4\GLib;
use Gtk4\GListStore;
use Gtk4\GMenu;
use Gtk4\GObject;
use Gtk4\GSimpleAction;
use Gtk4\Gtk;
use Gtk4\GtkAboutDialog;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkApplicationWindow;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCssProvider;
use Gtk4\GtkCustomFilter;
use Gtk4\GtkCustomSorter;
use Gtk4\GtkEntry;
use Gtk4\GtkEntryIconPosition;
use Gtk4\GtkEventControllerKey;
use Gtk4\GtkFileDialog;
use Gtk4\GtkFileFilter;
use Gtk4\GtkFilterChange;
use Gtk4\GtkFilterListModel;
use Gtk4\GtkHeaderBar;
use Gtk4\GtkImage;
use Gtk4\GtkLabel;
use Gtk4\GtkLicense;
use Gtk4\GtkListItem;
use Gtk4\GtkListView;
use Gtk4\GtkMenuButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkOverlay;
use Gtk4\GtkPaned;
use Gtk4\GtkPolicyType;
use Gtk4\GtkRevealer;
use Gtk4\GtkRevealerTransitionType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkSorterChange;
use Gtk4\GtkSortListModel;
use Gtk4\GtkStack;
use Gtk4\GtkStackTransitionType;
use Gtk4\GtkStyleProviderPriority;
use Gtk4\GtkTextView;
use Gtk4\GtkWidget;
use Gtk4\GtkWrapMode;
use Gtk4\PangoEllipsizeMode;

/**
 * The one window of the Notes application.
 *
 * Header bar with the primary menu, a paned body: a searchable, sortable
 * GtkListView of notes on the left, the editor (title entry + text view) on
 * the right, an empty state when nothing is selected, and a toast with Undo
 * after a delete. Edits are saved automatically shortly after they stop.
 *
 * The models stack: NoteStore's GListStore -> GtkFilterListModel (search) ->
 * GtkSortListModel (menu) -> GtkSingleSelection (what the editor shows). The
 * window never touches the list widget's rows itself: it changes the models
 * and GTK redraws what changed.
 */
final class NotesWindow extends GtkApplicationWindow
{
    private const int ESCAPE = 0xff1b;
    private const int AUTOSAVE_MS = 700;
    private const int TOAST_MS = 5000;

    private readonly GtkCustomFilter $filter;
    private readonly GtkCustomSorter $sorter;
    private readonly GtkSingleSelection $selection;
    private readonly GtkSortListModel $sorted;
    private readonly GtkEntry $search;
    private readonly GtkEntry $titleEntry;
    private readonly GtkTextView $editor;
    private readonly GtkStack $stack;
    private readonly GtkLabel $subtitle;
    private readonly GtkLabel $saveState;
    private readonly GtkLabel $counts;
    private readonly GtkRevealer $toast;
    private readonly GtkLabel $toastText;
    private readonly GtkButton $toastUndo;
    private readonly GSimpleAction $deleteAction;
    private readonly GSimpleAction $exportAction;

    private ?Note $current = null;
    private bool $loading = false;
    private bool $dirty = false;
    private int $saveSource = 0;
    private int $toastSource = 0;
    private string $sortKey = 'modified';
    /** @var array{int, Note}|null The last deleted note and where it was, until the toast goes. */
    private ?array $undo = null;

    public function __construct(GtkApplication $app, public readonly NoteStore $store)
    {
        parent::__construct($app);
        $this->set_title('Notes');
        $this->set_default_size(960, 640);
        $this->set_icon_name('accessories-text-editor');
        self::style($this);

        $this->search = new GtkEntry();
        $this->titleEntry = new GtkEntry();
        $this->editor = new GtkTextView();
        $this->stack = new GtkStack();
        $this->subtitle = new GtkLabel();
        $this->saveState = new GtkLabel();
        $this->counts = new GtkLabel();
        $this->toast = new GtkRevealer();
        $this->toastText = new GtkLabel();
        $this->toastUndo = GtkButton::new_with_label('Undo');
        $this->deleteAction = new GSimpleAction('delete');
        $this->exportAction = new GSimpleAction('export');

        // store -> filter -> sort -> selection
        $this->filter = new GtkCustomFilter(fn(GObject $item): bool =>
            $item instanceof Note && $item->matches(trim($this->search->get_text())));
        $this->sorter = new GtkCustomSorter(fn(GObject $a, GObject $b): int => $this->compare($a, $b));
        $filtered = new GtkFilterListModel($store->model, $this->filter);
        $this->sorted = new GtkSortListModel($filtered, $this->sorter);
        $this->selection = new GtkSingleSelection($this->sorted);
        $this->selection->set_autoselect(false);
        $this->selection->set_can_unselect(true);

        $this->installActions($app);
        $this->set_titlebar($this->buildHeaderBar());

        $paned = new GtkPaned(GtkOrientation::Horizontal);
        $paned->set_start_child($this->buildSidebar());
        $paned->set_end_child($this->buildContent());
        $paned->set_shrink_start_child(false);
        $paned->set_shrink_end_child(false);
        $paned->set_resize_start_child(false);
        $paned->set_position(300);
        $this->set_child($paned);

        $this->selection->connect('notify::selected-item', function (): void {
            $this->showSelected();
        });
        $store->model->connect('items-changed', function (): void {
            $this->refreshSubtitle();
        });

        // Closing the window is the one moment an autosave must not wait for its timer.
        $this->connect('close-request', function (): bool {
            $this->flush();
            return false;
        });

        $this->refreshSubtitle();
        $this->showSelected();
        if ($this->selection->get_n_items() > 0) {
            $this->selection->set_selected(0);
            $this->editor->grab_focus();
        }
    }

    // ---- building the widgets ------------------------------------------------------------

    private function buildHeaderBar(): GtkHeaderBar
    {
        $bar = new GtkHeaderBar();

        $title = new GtkLabel('Notes');
        $title->add_css_class('title');
        $this->subtitle->add_css_class('subtitle');
        $titles = new GtkBox(GtkOrientation::Vertical, 0);
        $titles->set_valign(GtkAlign::Center);
        $titles->append($title);
        $titles->append($this->subtitle);
        $bar->set_title_widget($titles);

        $new = GtkButton::new_from_icon_name('list-add-symbolic');
        $new->set_tooltip_text('New note (Ctrl+N)');
        $new->connect('clicked', fn() => $this->activate_action('app.new'));
        $bar->pack_start($new);

        $sort = new GMenu();
        $sort->append('Last edited', 'win.sort::modified');
        $sort->append('Title', 'win.sort::title');
        $sort->append('Created', 'win.sort::created');
        $file = new GMenu();
        $file->append('Import text file…', 'win.import');
        $file->append('Export note…', 'win.export');
        $about = new GMenu();
        $about->append('About Notes', 'app.about');
        $about->append('Quit', 'app.quit');
        $menu = new GMenu();
        $menu->append_section('Sort by', $sort);
        $menu->append_section(null, $file);
        $menu->append_section(null, $about);

        $primary = new GtkMenuButton();
        $primary->set_icon_name('open-menu-symbolic');
        $primary->set_tooltip_text('Main menu');
        $primary->set_primary(true);
        $primary->set_menu_model($menu);
        $bar->pack_end($primary);

        return $bar;
    }

    private function buildSidebar(): GtkWidget
    {
        $this->search->set_placeholder_text('Search notes');
        $this->search->set_icon_from_icon_name(GtkEntryIconPosition::Primary, 'edit-find-symbolic');
        $this->search->set_margin_start(8);
        $this->search->set_margin_end(8);
        $this->search->set_margin_top(8);
        $this->search->set_margin_bottom(8);
        $this->search->connect('changed', function (): void {
            $text = trim($this->search->get_text());
            $this->search->set_icon_from_icon_name(
                GtkEntryIconPosition::Secondary,
                $text === '' ? null : 'edit-clear-symbolic',
            );
            $this->filter->changed(GtkFilterChange::Different);
            $this->refreshSubtitle();
        });
        $this->search->connect('icon-press', function (GtkEntry $entry, GtkEntryIconPosition $pos): void {
            if ($pos === GtkEntryIconPosition::Secondary) {
                $entry->set_text('');
            }
        });
        $keys = new GtkEventControllerKey();
        $keys->connect('key-pressed', function (GtkEventControllerKey $c, int $keyval): bool {
            if ($keyval !== self::ESCAPE) {
                return false;
            }
            if ($this->search->get_text() !== '') {
                $this->search->set_text('');
            } else {
                $this->editor->grab_focus();
            }
            return true;
        });
        $this->search->add_controller($keys);

        // One row widget per visible note; bind() fills it in for whichever note scrolls into it.
        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', static function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $item->set_child(NoteRow::build());
        });
        $factory->connect('bind', static function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $row = $item->get_child();
            $note = $item->get_item();
            if ($row instanceof GtkBox && $note instanceof Note) {
                NoteRow::show($row, $note);
            }
        });

        $list = new GtkListView($this->selection, $factory);
        $list->add_css_class('navigation-sidebar');
        $list->connect('activate', function (): void {
            $this->editor->grab_focus();
        });

        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
        $scroller->set_child($list);
        $scroller->set_vexpand(true);

        $sidebar = new GtkBox(GtkOrientation::Vertical, 0);
        $sidebar->add_css_class('notes-sidebar');
        $sidebar->set_size_request(220, -1);
        $sidebar->append($this->search);
        $sidebar->append($scroller);
        return $sidebar;
    }

    private function buildContent(): GtkWidget
    {
        // The empty state: what the right half shows while nothing is selected.
        $icon = GtkImage::new_from_icon_name('document-edit-symbolic');
        $icon->set_pixel_size(96);
        $icon->add_css_class('dim-label');
        $headline = new GtkLabel('No note selected');
        $headline->add_css_class('title-1');
        $hint = new GtkLabel('Pick one on the left, or press Ctrl+N to start a new one.');
        $hint->add_css_class('dim-label');
        $empty = new GtkBox(GtkOrientation::Vertical, 12);
        $empty->set_valign(GtkAlign::Center);
        $empty->set_halign(GtkAlign::Center);
        $empty->append($icon);
        $empty->append($headline);
        $empty->append($hint);

        // The editor: a frameless title entry over a wrapping text view, a status row beneath.
        $this->titleEntry->set_placeholder_text('Title');
        $this->titleEntry->set_has_frame(false);
        $this->titleEntry->add_css_class('note-title');
        $this->titleEntry->set_margin_start(18);
        $this->titleEntry->set_margin_end(18);
        $this->titleEntry->set_margin_top(12);
        $this->titleEntry->connect('changed', function (): void {
            $this->edited();
        });
        $this->titleEntry->connect('activate', function (): void {
            $this->editor->grab_focus();
        });

        $this->editor->set_wrap_mode(GtkWrapMode::WordChar);
        $this->editor->set_left_margin(24);
        $this->editor->set_right_margin(24);
        $this->editor->set_top_margin(8);
        $this->editor->set_bottom_margin(24);
        $this->editor->set_pixels_above_lines(3);
        $this->editor->add_css_class('note-body');
        $buffer = $this->editor->get_buffer();
        $buffer->set_enable_undo(true);
        $buffer->connect('changed', function (): void {
            $this->edited();
        });
        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
        $scroller->set_child($this->editor);
        $scroller->set_vexpand(true);

        $this->saveState->add_css_class('dim-label');
        $this->saveState->add_css_class('caption');
        $this->saveState->set_xalign(0.0);
        $this->saveState->set_hexpand(true);
        $this->counts->add_css_class('dim-label');
        $this->counts->add_css_class('caption');
        $delete = GtkButton::new_from_icon_name('user-trash-symbolic');
        $delete->add_css_class('flat');
        $delete->set_tooltip_text('Delete note (Ctrl+Delete)');
        $delete->connect('clicked', fn() => $this->activate_action('win.delete'));
        $status = new GtkBox(GtkOrientation::Horizontal, 12);
        $status->add_css_class('note-status');
        $status->set_margin_start(18);
        $status->set_margin_end(6);
        $status->set_margin_top(4);
        $status->set_margin_bottom(4);
        $status->append($this->saveState);
        $status->append($this->counts);
        $status->append($delete);

        $editor = new GtkBox(GtkOrientation::Vertical, 0);
        $editor->append($this->titleEntry);
        $editor->append($scroller);
        $editor->append($status);

        $this->stack->set_transition_type(GtkStackTransitionType::Crossfade);
        $this->stack->set_transition_duration(150);
        $this->stack->add_named($empty, 'empty');
        $this->stack->add_named($editor, 'editor');

        // The toast floats over the bottom of the content, slides up, and goes away by itself.
        $this->toastText->set_ellipsize(PangoEllipsizeMode::End);
        $this->toastUndo->add_css_class('flat');
        $this->toastUndo->connect('clicked', fn() => $this->activate_action('win.undo-delete'));
        $close = GtkButton::new_from_icon_name('window-close-symbolic');
        $close->add_css_class('flat');
        $close->connect('clicked', function (): void {
            $this->hideToast();
        });
        $card = new GtkBox(GtkOrientation::Horizontal, 8);
        $card->add_css_class('toast');
        $card->append($this->toastText);
        $card->append($this->toastUndo);
        $card->append($close);
        $this->toast->set_child($card);
        $this->toast->set_transition_type(GtkRevealerTransitionType::SlideUp);
        $this->toast->set_valign(GtkAlign::End);
        $this->toast->set_halign(GtkAlign::Center);
        $this->toast->set_margin_bottom(16);

        $overlay = new GtkOverlay();
        $overlay->set_child($this->stack);
        $overlay->add_overlay($this->toast);
        $overlay->set_hexpand(true);
        return $overlay;
    }

    /** Sort-by is a stateful action with a string state: the menu renders it as radio items. */
    private function installActions(GtkApplication $app): void
    {
        $sort = GSimpleAction::new_stateful('sort', 's', $this->sortKey);
        $sort->connect('change-state', function (GSimpleAction $action, mixed $value): void {
            if (!is_string($value)) {
                return;
            }
            $action->set_state($value);
            $this->sortKey = $value;
            $this->sorter->changed(GtkSorterChange::Different);
        });
        $this->add_action($sort);

        $this->deleteAction->connect('activate', function (): void {
            $this->deleteCurrent();
        });
        $this->add_action($this->deleteAction);
        $this->exportAction->connect('activate', function (): void {
            $this->exportCurrent();
        });
        $this->add_action($this->exportAction);

        $undo = new GSimpleAction('undo-delete');
        $undo->connect('activate', function (): void {
            $this->undoDelete();
        });
        $this->add_action($undo);

        $import = new GSimpleAction('import');
        $import->connect('activate', function (): void {
            $this->import();
        });
        $this->add_action($import);

        $search = new GSimpleAction('search');
        $search->connect('activate', function (): void {
            $this->search->grab_focus();
        });
        $this->add_action($search);

        $app->set_accels_for_action('win.delete', ['<Control>Delete']);
        $app->set_accels_for_action('win.export', ['<Control>e']);
        $app->set_accels_for_action('win.import', ['<Control>o']);
        $app->set_accels_for_action('win.search', ['<Control>f']);
    }

    /** The application's stylesheet: the few looks the default theme has no class for. */
    private static function style(GtkWidget $widget): void
    {
        $css = new GtkCssProvider();
        $css->load_from_string(
            '.note-title { font-size: 20px; font-weight: bold; background: none; }'
            . ' .note-title text { background: none; }'
            . ' .note-body { font-size: 15px; }'
            . ' .note-row-title { font-weight: bold; }'
            . ' .toast { background: alpha(#241f31, 0.92); color: #ffffff; border-radius: 14px;'
            . '   padding: 4px 4px 4px 16px; }'
            . ' .toast label { color: #ffffff; }'
            . ' .toast button { color: #ffffff; min-height: 24px; }',
        );
        Gtk::add_provider_for_display($widget->get_display(), $css, GtkStyleProviderPriority::APPLICATION);
    }

    // ---- the editor --------------------------------------------------------------------

    /** Put the selected note into the editor, or show the empty state. */
    private function showSelected(): void
    {
        $note = $this->selection->get_selected_item();
        if (!$note instanceof Note) {
            $this->flush();
            $this->current = null;
            $this->stack->set_visible_child_name('empty');
            $this->deleteAction->set_enabled(false);
            $this->exportAction->set_enabled(false);
            return;
        }
        if ($note === $this->current) {
            return;
        }
        $this->flush();
        $this->current = $note;
        $this->loading = true;
        $buffer = $this->editor->get_buffer();
        // Switching notes is not an edit: keep undo history from crossing between them.
        $buffer->begin_irreversible_action();
        $this->titleEntry->set_text($note->title);
        $buffer->set_text($note->body);
        $buffer->end_irreversible_action();
        $this->loading = false;
        $this->dirty = false;
        $this->stack->set_visible_child_name('editor');
        $this->deleteAction->set_enabled(true);
        $this->exportAction->set_enabled(true);
        $this->refreshStatus();
    }

    /** Called on every keystroke in either editor widget: copy back, mark dirty, arm the timer. */
    private function edited(): void
    {
        if ($this->loading || $this->current === null) {
            return;
        }
        $buffer = $this->editor->get_buffer();
        $this->current->title = $this->titleEntry->get_text();
        $this->current->body = $buffer->get_text($buffer->get_start_iter(), $buffer->get_end_iter(), true);
        $this->current->modified = time();
        $this->dirty = true;
        $this->refreshStatus();

        if ($this->saveSource !== 0) {
            GLib::source_remove($this->saveSource);
        }
        $this->saveSource = GLib::timeout_add(self::AUTOSAVE_MS, function (): bool {
            $this->saveSource = 0;
            $this->flush();
            return false;
        });
    }

    /** Save now if there is anything to save; cancels a pending autosave. */
    private function flush(): void
    {
        if ($this->saveSource !== 0) {
            GLib::source_remove($this->saveSource);
            $this->saveSource = 0;
        }
        if (!$this->dirty) {
            return;
        }
        $this->store->save();
        $this->dirty = false;
        // Title and date live in the list rows: re-sorting rebinds every visible row.
        $this->sorter->changed(GtkSorterChange::Different);
        $this->refreshStatus();
    }

    private function refreshStatus(): void
    {
        $note = $this->current;
        if ($note === null) {
            return;
        }
        $this->saveState->set_text($this->dirty ? 'Unsaved changes' : 'Saved · edited ' . Note::when($note->modified));
        $words = preg_match_all('/\S+/u', $note->body);
        $this->counts->set_text(sprintf(
            '%d word%s · %d character%s',
            $words,
            $words === 1 ? '' : 's',
            mb_strlen($note->body),
            mb_strlen($note->body) === 1 ? '' : 's',
        ));
    }

    private function refreshSubtitle(): void
    {
        $total = $this->store->model->get_n_items();
        $shown = $this->sorted->get_n_items();
        $this->subtitle->set_text(match (true) {
            $total === 0 => 'No notes',
            $shown !== $total => sprintf('%d of %d notes', $shown, $total),
            default => sprintf('%d note%s', $total, $total === 1 ? '' : 's'),
        });
    }

    private function compare(GObject $a, GObject $b): int
    {
        if (!$a instanceof Note || !$b instanceof Note) {
            return 0;
        }
        // The id breaks every tie, so two notes made in the same second have a stable order.
        return match ($this->sortKey) {
            'title' => strcasecmp($a->displayTitle(), $b->displayTitle()) ?: $b->modified <=> $a->modified,
            'created' => $b->created <=> $a->created,
            default => $b->modified <=> $a->modified,
        } ?: strcmp($a->id, $b->id);
    }

    // ---- the actions -------------------------------------------------------------------

    /** A new empty note, selected and focused - `app.new` lands here. */
    public function newNote(string $title = '', string $body = ''): void
    {
        $this->flush();
        $note = Note::fresh($title, $body);
        $this->store->add($note);
        $this->search->set_text('');
        $this->select($note);
        $this->dirty = true;
        $this->flush();
        $this->titleEntry->grab_focus();
    }

    /** Select $note in the list, wherever filter and sorter put it. */
    private function select(Note $note): void
    {
        for ($i = 0, $n = $this->sorted->get_n_items(); $i < $n; $i++) {
            if ($this->sorted->get_item($i) === $note) {
                $this->selection->set_selected($i);
                return;
            }
        }
    }

    private function deleteCurrent(): void
    {
        $note = $this->current;
        if ($note === null) {
            return;
        }
        $this->flush();
        $this->hideToast();
        $shown = $this->selection->get_selected();
        $position = $this->store->remove($note);
        $this->dirty = true;
        $this->flush();
        // Land on the neighbour, the way a mail client does, rather than on the empty state.
        if ($this->sorted->get_n_items() > 0) {
            $this->selection->set_selected(min($shown, $this->sorted->get_n_items() - 1));
        }
        $this->undo = [$position, $note];
        $this->toastText->set_text(sprintf('"%s" deleted', $note->displayTitle()));
        $this->toastUndo->set_visible(true);
        $this->toast->set_reveal_child(true);
        $this->toastSource = GLib::timeout_add(self::TOAST_MS, function (): bool {
            $this->toastSource = 0;
            $this->hideToast();
            return false;
        });
    }

    private function undoDelete(): void
    {
        if ($this->undo === null) {
            return;
        }
        [$position, $note] = $this->undo;
        $this->hideToast();
        $this->store->restore($position, $note);
        $this->dirty = true;
        $this->flush();
        $this->select($note);
    }

    private function hideToast(): void
    {
        if ($this->toastSource !== 0) {
            GLib::source_remove($this->toastSource);
            $this->toastSource = 0;
        }
        $this->undo = null;
        $this->toast->set_reveal_child(false);
    }

    /** Show a message in the toast with no Undo - for what the file dialogs report. */
    private function notify(string $text): void
    {
        $this->hideToast();
        $this->toastText->set_text($text);
        $this->toastUndo->set_visible(false);
        $this->toast->set_reveal_child(true);
        $this->toastSource = GLib::timeout_add(self::TOAST_MS, function (): bool {
            $this->toastSource = 0;
            $this->hideToast();
            return false;
        });
    }

    /** Write the current note as Markdown wherever the save dialog says. */
    private function exportCurrent(): void
    {
        $note = $this->current;
        if ($note === null) {
            return;
        }
        $this->flush();
        $dialog = new GtkFileDialog();
        $dialog->set_title('Export note');
        $dialog->set_initial_name(preg_replace('/[^\w\- ]+/u', '', $note->displayTitle()) . '.md');
        $dialog->save($this, null, function (?GObject $source, GAsyncResult $result) use ($dialog, $note): void {
            try {
                $path = $dialog->save_finish($result);
            } catch (GError) {
                return;   // dismissed
            }
            if ($path === null) {
                return;
            }
            $text = '# ' . $note->displayTitle() . "\n\n" . $note->body . "\n";
            if (file_put_contents($path, $text) === false) {
                $this->notify('Could not write ' . basename($path));
                return;
            }
            $this->notify('Exported to ' . basename($path));
        });
    }

    /** Read a text file into a new note; its first line becomes the title. */
    private function import(): void
    {
        $text = new GtkFileFilter();
        $text->set_name('Text files');
        $text->add_mime_type('text/plain');
        $text->add_suffix('txt');
        $text->add_suffix('md');
        $any = new GtkFileFilter();
        $any->set_name('All files');
        $any->add_pattern('*');
        $filters = new GListStore('GtkFileFilter');
        $filters->append($text);
        $filters->append($any);

        $dialog = new GtkFileDialog();
        $dialog->set_title('Import text file');
        $dialog->set_filters($filters);
        $dialog->set_default_filter($text);
        $dialog->open($this, null, function (?GObject $source, GAsyncResult $result) use ($dialog): void {
            try {
                $path = $dialog->open_finish($result);
            } catch (GError) {
                return;   // dismissed
            }
            if ($path === null) {
                return;
            }
            $content = file_get_contents($path);
            if ($content === false || !mb_check_encoding($content, 'UTF-8')) {
                $this->notify(basename($path) . ' is not a UTF-8 text file');
                return;
            }
            $lines = explode("\n", str_replace("\r\n", "\n", trim($content)), 2);
            $title = ltrim(trim($lines[0]), '# ');
            $this->newNote($title, trim($lines[1] ?? ''));
            $this->notify('Imported ' . basename($path));
        });
    }

    public function about(): void
    {
        $about = new GtkAboutDialog();
        $about->set_program_name('Notes');
        $about->set_version(\Gtk4\VERSION);
        $about->set_comments('A small notes application, written in PHP with php-gtk4.');
        $about->set_website('https://github.com/apss-pohl/php-gtk4');
        $about->set_website_label('php-gtk4 on GitHub');
        $about->set_license_type(GtkLicense::MitX11);
        $about->set_logo_icon_name('accessories-text-editor');
        $about->set_transient_for($this);
        $about->set_modal(true);
        $about->present();
    }
}
