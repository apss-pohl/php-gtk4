<?php

declare(strict_types=1);

namespace PhpGtk4\Examples\Notes;

use Gtk4\GObject;

/**
 * One note - a GObject so it can live in a GListStore and flow through the
 * filter/sort/selection models unchanged. The PHP properties are the state; the
 * GObject is what GTK's list machinery can hold a reference to.
 */
final class Note extends GObject
{
    public function __construct(
        public string $id,
        public string $title,
        public string $body,
        public int $created,
        public int $modified,
    ) {
        parent::__construct();
    }

    public static function fresh(string $title = '', string $body = ''): self
    {
        $now = time();
        return new self(bin2hex(random_bytes(8)), $title, $body, $now, $now);
    }

    /** @param array<mixed> $row A decoded JSON row; anything malformed answers null. */
    public static function fromArray(array $row): ?self
    {
        $id = $row['id'] ?? null;
        $title = $row['title'] ?? '';
        $body = $row['body'] ?? '';
        $created = $row['created'] ?? null;
        $modified = $row['modified'] ?? $created;
        if (!is_string($id) || !is_string($title) || !is_string($body) || !is_int($created) || !is_int($modified)) {
            return null;
        }
        return new self($id, $title, $body, $created, $modified);
    }

    /** @return array{id: string, title: string, body: string, created: int, modified: int} */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'created' => $this->created,
            'modified' => $this->modified,
        ];
    }

    /** The title, or the first line of the body, or a placeholder - what a list row shows. */
    public function displayTitle(): string
    {
        if (trim($this->title) !== '') {
            return trim($this->title);
        }
        $first = strtok(trim($this->body), "\n");
        return is_string($first) && trim($first) !== '' ? trim($first) : 'Untitled';
    }

    /** The first line of the body that is not the title, shortened for a list row. */
    public function preview(): string
    {
        foreach (explode("\n", $this->body) as $line) {
            $line = trim($line);
            if ($line === '' || (trim($this->title) === '' && $line === $this->displayTitle())) {
                continue;
            }
            return mb_strlen($line) > 90 ? mb_substr($line, 0, 90) . '…' : $line;
        }
        return 'No additional text';
    }

    /** True when $needle occurs in the title or the body, case-insensitively. */
    public function matches(string $needle): bool
    {
        return $needle === ''
            || mb_stripos($this->title, $needle) !== false
            || mb_stripos($this->body, $needle) !== false;
    }

    /** "14:02" today, "Yesterday", "Tue", "5 Sep" this year, "5 Sep 2025" otherwise. */
    public static function when(int $timestamp): string
    {
        $dayOf = static fn(int $t): int => intdiv($t + (int) date('Z', $t), 86_400);
        $day = $dayOf($timestamp);
        $today = $dayOf(time());
        return match (true) {
            $day === $today => date('H:i', $timestamp),
            $day === $today - 1 => 'Yesterday',
            $today - $day < 7 => date('D', $timestamp),
            date('Y', $timestamp) === date('Y') => date('j M', $timestamp),
            default => date('j M Y', $timestamp),
        };
    }
}
