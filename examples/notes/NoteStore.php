<?php

declare(strict_types=1);

namespace PhpGtk4\Examples\Notes;

use Gtk4\GListStore;
use Gtk4\GObject;

/**
 * The notes on disk and the GListStore the window watches.
 *
 * One JSON file, rewritten whole on every save (a notes file is small, and an
 * atomic rename means a crash mid-write never loses the previous version). The
 * store is the single source of truth: the filter, sort and selection models
 * in the window are views over it.
 */
final class NoteStore
{
    public readonly GListStore $model;

    public function __construct(public readonly string $path)
    {
        $this->model = new GListStore(GObject::class);
    }

    /** Where the notes live unless a path is given: XDG_DATA_HOME, LOCALAPPDATA or ~/.local/share. */
    public static function defaultPath(): string
    {
        $base = getenv('XDG_DATA_HOME');
        if (!is_string($base) || $base === '') {
            $base = getenv('LOCALAPPDATA');
        }
        if (!is_string($base) || $base === '') {
            $home = getenv('HOME');
            $base = (is_string($home) && $home !== '' ? $home : sys_get_temp_dir()) . '/.local/share';
        }
        return $base . '/php-gtk4-notes/notes.json';
    }

    /** Read the file into the model; a missing file seeds the welcome note. */
    public function load(): void
    {
        $this->model->remove_all();
        if (!is_file($this->path)) {
            $this->model->append(self::welcome());
            $this->save();
            return;
        }
        $rows = json_decode((string) file_get_contents($this->path), true);
        if (!is_array($rows)) {
            throw new \RuntimeException("$this->path is not a notes file");
        }
        foreach ($rows as $row) {
            $note = is_array($row) ? Note::fromArray($row) : null;
            if ($note !== null) {
                $this->model->append($note);
            }
        }
    }

    /** Write every note, in store order, atomically. */
    public function save(): void
    {
        $dir = dirname($this->path);
        if (!is_dir($dir) && !mkdir($dir, 0o755, true) && !is_dir($dir)) {
            throw new \RuntimeException("cannot create $dir");
        }
        $rows = array_map(static fn(Note $note): array => $note->toArray(), $this->all());
        $json = json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $tmp = $this->path . '.tmp';
        if ($json === false || file_put_contents($tmp, $json . "\n") === false || !rename($tmp, $this->path)) {
            throw new \RuntimeException("cannot write $this->path");
        }
    }

    /** @return list<Note> */
    public function all(): array
    {
        $notes = [];
        for ($i = 0, $n = $this->model->get_n_items(); $i < $n; $i++) {
            $item = $this->model->get_item($i);
            if ($item instanceof Note) {
                $notes[] = $item;
            }
        }
        return $notes;
    }

    public function add(Note $note): void
    {
        $this->model->append($note);
    }

    /** Remove the note and answer the position it had, so undo can put it back. */
    public function remove(Note $note): int
    {
        $position = $this->model->find($note) ?? 0;
        $this->model->remove($position);
        return $position;
    }

    public function restore(int $position, Note $note): void
    {
        $this->model->insert(min($position, $this->model->get_n_items()), $note);
    }

    private static function welcome(): Note
    {
        return Note::fresh(
            'Welcome to Notes',
            "This is a small notes application written in PHP against GTK 4.\n\n"
            . 'Everything you type is saved automatically, a moment after you stop typing. '
            . 'The list on the left is searchable (Ctrl+F) and sortable (the menu in the corner). '
            . "Ctrl+N starts a new note; the trash icon below the editor deletes one, with a few seconds to undo.\n\n"
            . 'The code is examples/notes/ in the php-gtk4 repository: a GtkApplication, a GtkApplicationWindow '
            . 'subclass with a GtkHeaderBar, a GtkPaned between a GtkListView and a GtkTextView, and a GListStore '
            . 'of PHP objects filtered and sorted by GtkFilterListModel and GtkSortListModel.',
        );
    }
}
