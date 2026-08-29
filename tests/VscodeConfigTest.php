<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;

/**
 * .vscode/launch.json is how an example is debugged (docs/CONTRIBUTING.md "Debugging"),
 * and it refers to this repository by path: bin/php-gtk4, examples/demo.php,
 * vendor/bin/phpunit. Renaming one of those would break F5 silently, months later, for
 * whoever next tries to set a breakpoint - so the paths are checked here.
 */
final class VscodeConfigTest extends TestCase
{
    private const string ROOT = __DIR__ . '/..';
    private const string LAUNCHER = '${workspaceFolder}/bin/php-gtk4';

    /**
     * Both files are JSONC; the comments in them sit on their own lines.
     *
     * @return array<string, mixed>
     */
    private static function jsonc(string $file): array
    {
        $text = (string) file_get_contents(self::ROOT . '/' . $file);
        $stripped = preg_replace('~^\s*//.*$~m', '', $text);
        self::assertIsString($stripped);
        $data = json_decode($stripped, true);
        self::assertIsArray($data, "$file is not valid JSON once its comment lines are removed");
        /** @var array<string, mixed> $data */
        return $data;
    }

    /** @return list<array<string, mixed>> */
    private static function entries(string $file, string $key): array
    {
        $list = self::jsonc($file)[$key] ?? null;
        self::assertIsArray($list, "$file: no $key");
        self::assertNotSame([], $list);
        $entries = [];
        foreach ($list as $entry) {
            self::assertIsArray($entry);
            /** @var array<string, mixed> $entry */
            $entries[] = $entry;
        }
        return $entries;
    }

    /** @param array<string, mixed> $entry */
    private static function str(array $entry, string $key, string $where = ''): string
    {
        $value = $entry[$key] ?? null;
        self::assertIsString($value, trim("$where $key must be a string"));
        return $value;
    }

    public function testEveryLaunchConfigurationPointsAtSomethingThatExists(): void
    {
        foreach (self::entries('.vscode/launch.json', 'configurations') as $config) {
            $name = self::str($config, 'name');
            self::assertSame('php', $config['type'] ?? null, "$name: the PHP Debug extension is the debugger");
            self::assertSame(9003, $config['port'] ?? null, "$name: Xdebug's default port, as in the env below");

            foreach (['program', 'runtimeExecutable'] as $key) {
                if (!isset($config[$key])) {
                    continue;
                }
                $path = self::str($config, $key, $name);
                if (str_contains($path, '${file')) {
                    continue;   // ${file} / ${fileBasenameNoExtension}: whatever is open
                }
                self::assertFileExists(
                    self::ROOT . '/' . str_replace('${workspaceFolder}/', '', $path),
                    "$name: $key does not exist",
                );
            }
        }
    }

    /**
     * The environment is what starts the session: XDEBUG_SESSION is the trigger and
     * XDEBUG_CONFIG points Xdebug back at the port the configuration listens on. Without
     * them a launch runs the program and never stops anywhere.
     */
    public function testLaunchingConfigurationsTurnXdebugOn(): void
    {
        foreach (self::entries('.vscode/launch.json', 'configurations') as $config) {
            if (!isset($config['program'])) {
                continue;   // the listen-only configuration starts nothing
            }
            $name = self::str($config, 'name');
            $env = $config['env'] ?? null;
            self::assertIsArray($env, "$name: no env, so nothing enables Xdebug");
            self::assertSame('debug', $env['XDEBUG_MODE'] ?? null, "$name: XDEBUG_MODE");
            self::assertArrayHasKey('XDEBUG_SESSION', $env, "$name: nothing triggers the session");
            $port = $config['port'] ?? null;
            self::assertIsInt($port, "$name: port");
            /** @var array<string, mixed> $env */
            self::assertStringContainsString(
                "client_port=$port",
                self::str($env, 'XDEBUG_CONFIG', $name),
                "$name: XDEBUG_CONFIG must name the port this configuration listens on",
            );
        }
    }

    /**
     * php-gtk3 must stay out of the process, so the debugger runs bin/php-gtk4 rather
     * than php - and an example only *describes* a page, so what runs is demo.php with
     * the class name.
     */
    public function testExamplesAreDebuggedThroughTheLaunchers(): void
    {
        $examples = 0;
        foreach (self::entries('.vscode/launch.json', 'configurations') as $config) {
            if (!isset($config['program'])) {
                continue;
            }
            $name = self::str($config, 'name');
            self::assertSame(self::LAUNCHER, $config['runtimeExecutable'] ?? null, $name);
            if (str_starts_with($name, 'Example:')) {
                $examples++;
                self::assertSame('${workspaceFolder}/examples/demo.php', $config['program'], $name);
            }
        }
        self::assertGreaterThan(0, $examples, 'no Example: configuration left');
    }

    public function testTasksRunCommandsThisRepositoryHas(): void
    {
        foreach (self::entries('.vscode/tasks.json', 'tasks') as $task) {
            $label = self::str($task, 'label');
            $binary = explode(' ', self::str($task, 'command', $label))[0];
            self::assertFileExists(self::ROOT . '/' . $binary, "task '$label': $binary is gone");
        }
    }

    public function testTheTerminalDebugLauncherIsUsable(): void
    {
        $script = self::ROOT . '/bin/php-gtk4-debug';
        self::assertFileExists($script);
        self::assertTrue(is_executable($script), 'bin/php-gtk4-debug must be executable');
        $source = (string) file_get_contents($script);
        self::assertStringContainsString('XDEBUG_MODE=debug', $source);
        self::assertStringContainsString('php-gtk4', $source, 'it must go through the launcher');
    }
}
