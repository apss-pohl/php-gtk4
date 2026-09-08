<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GError;
use Gtk4\GKeyFile;
use Gtk4\GtkPageSetup;
use Gtk4\GtkPaperSize;
use Gtk4\GtkUnit;

/**
 * GLib's .ini-shaped file, which is how GTK's print settings, page setup and paper size are
 * stored and read back.
 */
final class KeyFileTest extends GtkTestCase
{
    private string $path = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->path = sys_get_temp_dir() . '/php-gtk4-keyfile-' . getmypid() . '.ini';
    }

    protected function tearDown(): void
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }
        parent::tearDown();
    }

    public function testValuesRoundTripThroughGroupsAndKeys(): void
    {
        $file = new GKeyFile();
        $file->set_string('Printer', 'name', 'Laser');
        $file->set_integer('Printer', 'copies', 3);
        $file->set_boolean('Printer', 'duplex', true);
        $file->set_double('Printer', 'scale', 1.5);

        self::assertSame('Laser', $file->get_string('Printer', 'name'));
        self::assertSame(3, $file->get_integer('Printer', 'copies'));
        self::assertTrue($file->get_boolean('Printer', 'duplex'));
        self::assertSame(1.5, $file->get_double('Printer', 'scale'));
        self::assertSame('Printer', $file->get_start_group());
        self::assertTrue($file->has_group('Printer'));
        self::assertTrue($file->has_key('Printer', 'copies'));
        self::assertFalse($file->has_key('Printer', 'nothing'));
        self::assertSame(['Printer'], $file->get_groups());
        self::assertSame(['name', 'copies', 'duplex', 'scale'], $file->get_keys('Printer'));
    }

    /** to_data() is the document as text, and load_from_data() reads it back. */
    public function testTheDocumentRoundTripsThroughItsText(): void
    {
        $file = new GKeyFile();
        $file->set_string('Section', 'key', 'value');

        $text = $file->to_data();
        self::assertStringContainsString('[Section]', $text);
        self::assertStringContainsString('key=value', $text);

        $again = new GKeyFile();
        $again->load_from_data($text, strlen($text), 0);
        self::assertSame('value', $again->get_string('Section', 'key'));
    }

    /**
     * The list getters answer with a PHP list, GLib's length out parameter having become its
     * `count()`. Each element type has its own conversion.
     */
    public function testListsComeBackAsPhpLists(): void
    {
        $file = new GKeyFile();
        $file->load_from_data(
            $data = "[L]\nstrings=a;b;c;\nnumbers=1;2;3;\nflags=true;false;\nsizes=1.5;2.5;\n",
            strlen($data),
            0,
        );

        self::assertSame(['a', 'b', 'c'], $file->get_string_list('L', 'strings'));
        self::assertSame([1, 2, 3], $file->get_integer_list('L', 'numbers'));
        self::assertSame([true, false], $file->get_boolean_list('L', 'flags'));
        self::assertSame([1.5, 2.5], $file->get_double_list('L', 'sizes'));
    }

    /** An empty group has no keys, and the list is empty rather than null. */
    public function testAGroupWithoutKeysHasAnEmptyKeyList(): void
    {
        $file = new GKeyFile();
        $file->load_from_data($data = "[Empty]\n", strlen($data), 0);

        self::assertSame(['Empty'], $file->get_groups());
        self::assertSame([], $file->get_keys('Empty'));
    }

    public function testItLoadsAndSavesAFile(): void
    {
        $file = new GKeyFile();
        $file->set_string('A', 'b', 'c');
        $file->save_to_file($this->path);

        $read = new GKeyFile();
        self::assertTrue($read->load_from_file($this->path, 0));
        self::assertSame('c', $read->get_string('A', 'b'));
    }

    /** A key that is not there is a GError, not a null. */
    public function testAMissingKeyIsAGError(): void
    {
        $file = new GKeyFile();
        $file->set_string('A', 'b', 'c');

        $this->expectException(GError::class);
        $file->get_string('A', 'nothing');
    }

    /** load_from_data_dirs() looks the file up *in* the data dirs, so it takes a relative path. */
    public function testAnAbsolutePathForTheDataDirsIsAValueError(): void
    {
        $file = new GKeyFile();

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be a relative path');
        $file->load_from_data_dirs('/etc/php-gtk4-nothing.ini', 0);
    }

    /** What binding it was for: a page setup written to a key file and read back. */
    public function testAPageSetupRoundTripsThroughAKeyFile(): void
    {
        $setup = new GtkPageSetup();
        $setup->set_paper_size(GtkPaperSize::new_custom('php-gtk4', 'php-gtk4 test', 100.0, 200.0, GtkUnit::Mm));
        $setup->set_top_margin(7.5, GtkUnit::Mm);

        $file = new GKeyFile();
        $setup->to_key_file($file, 'Page Setup');

        $back = GtkPageSetup::new_from_key_file($file, 'Page Setup');
        self::assertSame('php-gtk4', $back->get_paper_size()->get_name());
        self::assertSame(7.5, $back->get_top_margin(GtkUnit::Mm));
    }
}
