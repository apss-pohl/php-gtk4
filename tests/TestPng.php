<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

/** Builds tiny valid PNGs without depending on GD - for texture tests. */
final class TestPng
{
    /** A width x height opaque red RGBA PNG. */
    public static function red(int $width, int $height): string
    {
        $raw = '';
        for ($y = 0; $y < $height; $y++) {
            $raw .= "\x00" . str_repeat("\xff\x00\x00\xff", $width);   // filter byte + RGBA pixels
        }
        $ihdr = pack('NNCCCCC', $width, $height, 8, 6, 0, 0, 0);       // 8-bit RGBA
        return "\x89PNG\r\n\x1a\n"
            . self::chunk('IHDR', $ihdr)
            . self::chunk('IDAT', (string) gzcompress($raw, 9))
            . self::chunk('IEND', '');
    }

    private static function chunk(string $type, string $data): string
    {
        return pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
    }
}
