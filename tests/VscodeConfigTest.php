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
                // A page is for looking at, so the inspector comes up with it - in both envs,
                // because the windows block replaces the base one rather than merging into it.
                $windows = $config['windows'] ?? null;
                self::assertIsArray($windows, "$name: no windows block");
                $windowsEnv = $windows['env'] ?? null;
                foreach ([$config['env'] ?? null, $windowsEnv] as $i => $env) {
                    self::assertIsArray($env, $name);
                    $where = $i === 0 ? $name : "$name (windows)";
                    self::assertSame(
                        'interactive',
                        $env['GTK_DEBUG'] ?? null,
                        "$where: GTK_DEBUG=interactive opens the GTK inspector with the page",
                    );
                }
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
        if (PHP_OS_FAMILY !== 'Windows') {  // no executable bit on NTFS checkouts
            self::assertTrue(is_executable($script), 'bin/php-gtk4-debug must be executable');
        }
        $source = (string) file_get_contents($script);
        self::assertStringContainsString('XDEBUG_MODE=debug', $source);
        self::assertStringContainsString('php-gtk4', $source, 'it must go through the launcher');
    }

    /**
     * Every configuration carries a `windows` block, which VS Code overlays property by
     * property on that platform. It deliberately does *not* run bin/php-gtk4.cmd: the debug
     * extension spawns runtimeExecutable without a shell, and Node's CVE-2024-27980 fix makes
     * spawn() refuse a .cmd outright ("spawn EINVAL"). So the two things that launcher does on
     * Windows - put the gvsbuild bin\ on PATH, add -dextension - are inlined, and this pins
     * them: a php.exe, an extension argument naming a DLL this build produces, and a PATH that
     * still inherits the rest. The overlay is a replacement rather than a merge, so the block
     * also has to repeat everything that starts the Xdebug session.
     *
     * The paths themselves are this machine's and are not checked for existence: the suite runs
     * on Linux too, and the Windows CI job runs tests\run.cmd rather than these.
     */
    public function testTheWindowsOverridesLaunchPhpDirectlyRatherThanTheCmd(): void
    {
        $seen = 0;
        foreach (self::entries('.vscode/launch.json', 'configurations') as $config) {
            $name = self::str($config, 'name');
            if (!isset($config['program'])) {
                self::assertArrayNotHasKey('windows', $config, "$name: the listen-only one starts nothing");
                continue;
            }
            $windows = $config['windows'] ?? null;
            self::assertIsArray($windows, "$name: no windows block, so Windows would run bin/php-gtk4");
            /** @var array<string, mixed> $windows */
            $seen++;

            $exe = self::str($windows, 'runtimeExecutable', "$name (windows)");
            self::assertStringEndsWith('php.exe', $exe, "$name (windows): spawn() cannot run a .cmd");

            $args = $windows['runtimeArgs'] ?? null;
            self::assertIsArray($args, "$name (windows): nothing loads the extension");
            $extension = null;
            foreach ($args as $arg) {
                if (is_string($arg) && str_starts_with($arg, '-dextension=')) {
                    $extension = substr($arg, strlen('-dextension='));
                }
            }
            self::assertIsString($extension, "$name (windows): no -dextension argument");
            self::assertStringEndsWith('php_gtk4.dll', $extension, "$name (windows): -dextension");
            self::assertStringStartsWith(
                '${workspaceFolder}/x64/',
                $extension,
                "$name (windows): the DLL is the one this checkout built",
            );

            $env = $windows['env'] ?? null;
            self::assertIsArray($env, "$name: a windows env that replaces the base one, or Xdebug stays off");
            /** @var array<string, mixed> $env */
            $path = self::str($env, 'PATH', "$name (windows)");
            self::assertStringContainsString('bin', $path, "$name (windows): PATH must reach the GTK DLLs");
            self::assertStringContainsString(
                '${env:PATH}',
                $path,
                "$name (windows): PATH must extend the inherited one, not replace it",
            );

            self::assertSame('debug', $env['XDEBUG_MODE'] ?? null, "$name (windows): XDEBUG_MODE");
            self::assertArrayHasKey('XDEBUG_SESSION', $env, "$name (windows): nothing triggers the session");
            $port = $config['port'] ?? null;
            self::assertIsInt($port, "$name (windows): port");
            self::assertStringContainsString(
                "client_port=$port",
                self::str($env, 'XDEBUG_CONFIG', "$name (windows)"),
                "$name (windows): XDEBUG_CONFIG must name the port",
            );
            self::assertArrayNotHasKey('GDK_BACKEND', $env, "$name (windows): there is no X11 on Windows");
        }
        self::assertGreaterThan(0, $seen, 'no windows override left');
    }
    /**
     * The tasks that run something go through a launcher this repository ships, on both
     * platforms - `command` for Linux and the `windows` override for the .cmd.
     */
    public function testTasksThatRunSomethingHaveAWindowsCommand(): void
    {
        foreach (self::entries('.vscode/tasks.json', 'tasks') as $task) {
            $label = self::str($task, 'label');
            if (!str_starts_with(self::str($task, 'command', $label), 'bin/php-gtk4')) {
                continue;   // ./ci.sh is Linux-only on purpose - see the comment in tasks.json
            }
            $windows = $task['windows'] ?? null;
            self::assertIsArray($windows, "task '$label': no windows command");
            /** @var array<string, mixed> $windows */
            $command = self::str($windows, 'command', $label);
            self::assertStringStartsWith('bin/php-gtk4.cmd ', $command, "task '$label'");
            $script = explode(' ', $command)[1] ?? '';
            self::assertFileExists(self::ROOT . '/' . $script, "task '$label': $script is gone");

            // Same two knobs as launch.json, so Run Task needs no environment either.
            $options = $windows['options'] ?? null;
            self::assertIsArray($options, "task '$label': no windows options");
            $env = $options['env'] ?? null;
            self::assertIsArray($env, "task '$label': no windows env");
            /** @var array<string, mixed> $env */
            self::assertStringEndsWith('php.exe', self::str($env, 'PHP', $label), "task '$label': PHP");
            self::assertNotSame('', self::str($env, 'GTK4_ROOT', $label), "task '$label': GTK4_ROOT");
        }
    }
}
