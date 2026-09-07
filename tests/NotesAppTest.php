<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GApplicationFlags;
use Gtk4\GLib;
use Gtk4\GtkApplication;
use Gtk4\GtkEntry;
use Gtk4\GtkRevealer;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkSortListModel;
use Gtk4\GtkStack;
use Gtk4\GtkTextView;
use PhpGtk4\Examples\Notes\Note;
use PhpGtk4\Examples\Notes\NoteStore;
use PhpGtk4\Examples\Notes\NotesWindow;

/**
 * examples/notes/ is the application-shaped example: a GtkApplicationWindow subclass over a
 * GListStore of PHP objects, filtered, sorted and selected by the GTK list models, saving itself
 * to a JSON file. This drives it the way a user would - actions, entries, the text buffer - and
 * reads the result back from the models and the file.
 */
final class NotesAppTest extends GtkTestCase
{
    private static int $apps = 0;
    private string $file = '';
    private ?NotesWindow $window = null;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['Note', 'NoteRow', 'NoteStore', 'NotesWindow'] as $class) {
            require_once __DIR__ . '/../examples/notes/' . $class . '.php';
        }
        $this->file = sys_get_temp_dir() . '/php-gtk4-notes-test-' . getmypid() . '-' . self::$apps . '/notes.json';
    }

    protected function tearDown(): void
    {
        $this->window?->destroy();
        $this->window = null;
        if (is_file($this->file)) {
            unlink($this->file);
            rmdir(dirname($this->file));
        }
        parent::tearDown();
    }

    private function open(): NotesWindow
    {
        // A registered application: GtkApplicationWindow wants one that has started.
        $app = new GtkApplication(
            sprintf('org.phpgtk4.tests.notes.p%d.n%d', getmypid(), self::$apps++),
            GApplicationFlags::NON_UNIQUE,
        );
        $app->register(null);
        $store = new NoteStore($this->file);
        $store->load();
        $this->window = new NotesWindow($app, $store);
        return $this->window;
    }

    /** A private widget or model of the window, by property name. */
    private function peek(NotesWindow $window, string $property): mixed
    {
        return new \ReflectionProperty(NotesWindow::class, $property)->getValue($window);
    }

    /** Run the main loop until $done() holds or $ms have passed; false if it never held. */
    private function pump(int $ms, callable $done): bool
    {
        $until = microtime(true) + $ms / 1000;
        do {
            while (GLib::main_context_iteration(false)) {
            }
            if ($done()) {
                return true;
            }
            usleep(10_000);
        } while (microtime(true) < $until);
        return false;
    }

    /** @return list<array<string, mixed>> */
    private function saved(): array
    {
        $rows = json_decode((string) file_get_contents($this->file), true);
        self::assertIsArray($rows);
        /** @var list<array<string, mixed>> $rows */
        return $rows;
    }

    /**
     * The saved row with that title, or null.
     *
     * @return array<string, mixed>|null
     */
    private function savedRow(string $title): ?array
    {
        foreach ($this->saved() as $row) {
            if (($row['title'] ?? null) === $title) {
                return $row;
            }
        }
        return null;
    }

    public function testFirstRunSeedsAWelcomeNoteAndShowsItInTheEditor(): void
    {
        $window = $this->open();
        self::assertFileExists($this->file, 'the seed is written, so a second run finds it');
        self::assertCount(1, $this->saved());
        self::assertSame('Welcome to Notes', $this->saved()[0]['title']);

        $stack = $this->peek($window, 'stack');
        self::assertInstanceOf(GtkStack::class, $stack);
        self::assertSame('editor', $stack->get_visible_child_name());
        $title = $this->peek($window, 'titleEntry');
        self::assertInstanceOf(GtkEntry::class, $title);
        self::assertSame('Welcome to Notes', $title->get_text());
        self::assertSame('Notes', $window->get_title());
    }

    public function testTypingIsSavedByItselfAndReloaded(): void
    {
        $window = $this->open();
        $window->newNote();
        self::assertSame(2, $window->store->model->get_n_items());
        $selection = $this->peek($window, 'selection');
        self::assertInstanceOf(GtkSingleSelection::class, $selection);
        $note = $selection->get_selected_item();
        self::assertInstanceOf(Note::class, $note, 'the new note is selected');
        self::assertSame('', $note->title);

        $title = $this->peek($window, 'titleEntry');
        self::assertInstanceOf(GtkEntry::class, $title);
        $editor = $this->peek($window, 'editor');
        self::assertInstanceOf(GtkTextView::class, $editor);
        $title->set_text('Shopping');
        $editor->get_buffer()->set_text("milk\neggs");
        self::assertSame('Shopping', $note->title, 'the entry writes straight through to the note');
        self::assertSame("milk\neggs", $note->body);

        // Nothing on disk yet: the autosave waits for the typing to stop.
        self::assertTrue(
            $this->pump(3000, fn(): bool => in_array('Shopping', array_column($this->saved(), 'title'), true)),
            'the autosave timer wrote the note',
        );
        self::assertSame("milk\neggs", $this->savedRow('Shopping')['body'] ?? null);

        $again = new NoteStore($this->file);
        $again->load();
        self::assertSame(['Welcome to Notes', 'Shopping'], array_map(
            static fn(Note $n): string => $n->title,
            $again->all(),
        ), 'store order is file order');
    }

    public function testSearchFiltersTheListAndEmptiesTheEditor(): void
    {
        $window = $this->open();
        $window->newNote('Recipes', 'pancakes');
        $sorted = $this->peek($window, 'sorted');
        self::assertInstanceOf(GtkSortListModel::class, $sorted);
        $stack = $this->peek($window, 'stack');
        self::assertInstanceOf(GtkStack::class, $stack);
        $search = $this->peek($window, 'search');
        self::assertInstanceOf(GtkEntry::class, $search);

        self::assertSame(2, $sorted->get_n_items());
        $search->set_text('pancake');
        self::assertSame(1, $sorted->get_n_items(), 'body text matches');
        $search->set_text('WELCOME');
        self::assertSame(1, $sorted->get_n_items(), 'case-insensitive title match');
        $search->set_text('nothing like this');
        self::assertSame(0, $sorted->get_n_items());
        self::assertSame('empty', $stack->get_visible_child_name(), 'no selection left, so the empty state');
        $search->set_text('');
        self::assertSame(2, $sorted->get_n_items());
    }

    public function testSortIsAStatefulWindowAction(): void
    {
        $window = $this->open();
        $window->newNote('Zebra', '');
        $window->newNote('Apple', '');
        $sorted = $this->peek($window, 'sorted');
        self::assertInstanceOf(GtkSortListModel::class, $sorted);
        $titles = static function () use ($sorted): array {
            $out = [];
            for ($i = 0; $i < $sorted->get_n_items(); $i++) {
                $item = $sorted->get_item($i);
                $out[] = $item instanceof Note ? $item->displayTitle() : '?';
            }
            return $out;
        };

        // All three were made within the same second; give them distinct histories.
        foreach ($window->store->all() as $note) {
            [$note->created, $note->modified] = match ($note->title) {
                'Zebra' => [2000, 1000],
                'Apple' => [3000, 2000],
                default => [1000, 3000],
            };
        }

        $sort = $window->lookup_action('sort');
        self::assertNotNull($sort);
        self::assertSame('modified', $sort->get_state());
        $sort->change_state('title');   // what the radio items in the primary menu do
        self::assertSame('title', $sort->get_state());
        self::assertSame(['Apple', 'Welcome to Notes', 'Zebra'], $titles());
        $sort->change_state('created');
        self::assertSame(['Apple', 'Zebra', 'Welcome to Notes'], $titles(), 'newest created first');
        $sort->change_state('modified');
        self::assertSame(['Welcome to Notes', 'Apple', 'Zebra'], $titles(), 'last edited first');
    }

    public function testDeleteShowsAToastAndUndoPutsTheNoteBack(): void
    {
        $window = $this->open();
        $window->newNote('Doomed', 'soon gone');
        $selection = $this->peek($window, 'selection');
        self::assertInstanceOf(GtkSingleSelection::class, $selection);
        $toast = $this->peek($window, 'toast');
        self::assertInstanceOf(GtkRevealer::class, $toast);
        self::assertFalse($toast->get_reveal_child());

        self::assertTrue($window->activate_action('win.delete'));
        self::assertSame(1, $window->store->model->get_n_items());
        self::assertCount(1, $this->saved(), 'a delete is saved at once');
        self::assertTrue($toast->get_reveal_child(), 'the toast offers Undo');
        $neighbour = $selection->get_selected_item();
        self::assertInstanceOf(Note::class, $neighbour);
        self::assertSame('Welcome to Notes', $neighbour->title, 'the next note is selected, not nothing');

        self::assertTrue($window->activate_action('win.undo-delete'));
        self::assertSame(2, $window->store->model->get_n_items());
        self::assertFalse($toast->get_reveal_child());
        $restored = $selection->get_selected_item();
        self::assertInstanceOf(Note::class, $restored);
        self::assertSame('Doomed', $restored->title, 'the restored note is selected again');
        self::assertContains('Doomed', array_column($this->saved(), 'title'));

        self::assertTrue($window->activate_action('win.undo-delete'), 'the action exists');
        self::assertSame(2, $window->store->model->get_n_items(), 'but a second undo has nothing to restore');
    }

    public function testClosingTheWindowFlushesAPendingAutosave(): void
    {
        $window = $this->open();
        $title = $this->peek($window, 'titleEntry');
        self::assertInstanceOf(GtkEntry::class, $title);
        $title->set_text('Renamed before closing');
        self::assertNull($this->savedRow('Renamed before closing'), 'the timer has not fired');

        // What the close button does; gtk_window_close() itself is a no-op on an unrealized window.
        self::assertFalse($window->emit('close-request'), 'the handler lets the close go ahead');
        self::assertNotNull($this->savedRow('Renamed before closing'));
    }

    public function testNoteHelpers(): void
    {
        $note = Note::fresh('', "  \nFirst line\nsecond line that goes on\n");
        self::assertSame('First line', $note->displayTitle(), 'an untitled note is named after its first line');
        self::assertSame('second line that goes on', $note->preview(), 'which the preview then skips');
        self::assertSame('Untitled', Note::fresh()->displayTitle());
        self::assertSame('No additional text', Note::fresh('Only a title')->preview());
        self::assertTrue($note->matches('SECOND'));
        self::assertFalse($note->matches('third'));
        self::assertNull(Note::fromArray(['id' => 1]), 'a malformed row is skipped, not fatal');
        $copy = Note::fromArray($note->toArray());
        self::assertInstanceOf(Note::class, $copy);
        self::assertSame($note->id, $copy->id);
        self::assertSame(date('H:i'), Note::when(time()));
        self::assertSame('Yesterday', Note::when(time() - 86_400));
    }
}
