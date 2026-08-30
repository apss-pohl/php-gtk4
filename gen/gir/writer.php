<?php

/**
 * The writer: where generated text lands.
 *
 * Everything the generator produces goes through here, so that two rules hold for every file
 * it owns: a .cpp is clang-formatted the way the cpp-lint stage expects, and a file whose
 * content did not change is not touched at all (mtimes are what `make` reads -
 * GeneratorIdempotenceTest).
 *
 * Part of gen/gir.php (docs/PLAN.md milestone 3); gen/README.md describes the flow.
 */

declare(strict_types=1);

namespace PhpGtk4\Gen;

final class Writer
{
    /** @var array<string, string> relative path -> the absolute one written */
    private array $files = [];

    public function __construct(private string $out) {}

    /**
     * Every file the generator wrote or left in place, by its path relative to the out dir.
     *
     * @return array<string, string>
     */
    public function files(): array
    {
        return $this->files;
    }

    /**
     * Record a file written outside the out directory (an examples/ skeleton, a generated smoke
     * test) so gen/report.md still lists everything the generator owns.
     */
    public function wrote(string $rel, string $path): void
    {
        $this->files[$rel] = $path;
    }

    public function write(string $rel, string $text): void
    {
        $path = $this->out . '/' . $rel;
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0o777, true);
        }
        $content = rtrim($text) . "\n";
        if (str_ends_with($rel, '.cpp')) {
            // Same style the cpp-lint stage enforces (.clang-format); the generator is not a
            // formatter. Through a pipe, not `-i`, so an unchanged file is never touched.
            $content = self::clangFormatted($content, $path);
        }
        self::putIfChanged($path, $content);
        $this->files[$rel] = $path;
    }

    /**
     * Write only when the content really changed.
     *
     * The generator runs on every `./ci.sh` (the `gen` stage regenerates in place and fails on
     * a diff), and rewriting an identical file still bumps its mtime - which had `make`
     * recompiling `src/gtk4.cpp` and every generated class on each run, ~50 s of nothing.
     * Content is the only thing that decides here; timestamps stay put.
     */
    public static function putIfChanged(string $path, string $content): void
    {
        if (is_file($path) && file_get_contents($path) === $content) {
            return;
        }
        file_put_contents($path, $content);
    }

    /** $source through clang-format, with $path only deciding which .clang-format applies. */
    private static function clangFormatted(string $source, string $path): string
    {
        $binary = self::clangFormat();
        if ($binary === null) {
            return $source;
        }
        $cmd = escapeshellarg($binary) . ' --style=file --assume-filename=' . escapeshellarg($path);
        $pipes = [];
        $process = proc_open($cmd, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
        if (!is_resource($process)) {
            return $source;
        }
        fwrite($pipes[0], $source);
        fclose($pipes[0]);
        $formatted = (string) stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $errors = (string) stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        if (proc_close($process) !== 0 || $formatted === '') {
            throw new \RuntimeException("clang-format failed for $path: $errors");
        }
        return $formatted;
    }

    private static function clangFormat(): ?string
    {
        static $bin = false;
        if ($bin === false) {
            $bin = getenv('CLANG_FORMAT') ?: null;
            if ($bin === null) {
                $newest = 'ls /usr/bin/clang-format-[0-9]* 2>/dev/null | sort -t- -k2 -V | tail -1';
                $found = trim((string) shell_exec($newest));
                $bin = $found !== '' ? $found : (trim((string) shell_exec('command -v clang-format')) ?: null);
            }
        }
        return $bin;
    }
}
