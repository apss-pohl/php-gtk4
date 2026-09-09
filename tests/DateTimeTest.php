<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GDateTime;
use Gtk4\GTimeZone;
use Gtk4\GtkCalendar;

/**
 * The date and time GLib hands out: what a `GtkCalendar` answers with, what a cookie's expiry
 * and a certificate's validity are. A boxed value, so every "add" answers with a new one and
 * the original is untouched.
 */
final class DateTimeTest extends GtkTestCase
{
    /** 1815-12-10 12:30:00 UTC, which is a date this project has a soft spot for. */
    private static function ada(): GDateTime
    {
        // new_utc() is nullable because GLib refuses an impossible instant (month 13); this one
        // is a real date, so a null here would be the binding's fault and worth failing on.
        $ada = GDateTime::new_utc(1815, 12, 10, 12, 30, 0.0);
        self::assertInstanceOf(GDateTime::class, $ada);
        return $ada;
    }

    public function testTheFieldsAreWhatItWasBuiltFrom(): void
    {
        $date = self::ada();

        self::assertSame(1815, $date->get_year());
        self::assertSame(12, $date->get_month());
        self::assertSame(10, $date->get_day_of_month());
        self::assertSame(12, $date->get_hour());
        self::assertSame(30, $date->get_minute());
        self::assertSame(0, $date->get_second());
    }

    public function testFormattingFollowsStrftime(): void
    {
        self::assertSame('1815-12-10', self::ada()->format('%Y-%m-%d'));
        self::assertSame('1815-12-10T12:30:00Z', self::ada()->format_iso8601());
    }

    /** Arithmetic answers with a new value; the original is a value and does not move. */
    public function testAddingAnswersWithANewDate(): void
    {
        $date = self::ada();
        $later = $date->add_years(200);

        self::assertSame(2015, $later?->get_year());
        self::assertSame(1815, $date->get_year(), 'the original is untouched');
    }

    public function testComparingAndDifference(): void
    {
        $early = self::ada();
        $late = $early->add_days(1) ?? self::fail('add_days');

        self::assertLessThan(0, $early->compare($late));
        self::assertSame(0, $early->compare(self::ada()));
        // A GTimeSpan is microseconds, which is the alias the generator reads through.
        self::assertSame(24 * 60 * 60 * 1000 * 1000, $late->difference($early));
    }

    public function testATimeZoneCanBeAskedForByName(): void
    {
        $utc = GTimeZone::new_utc();
        self::assertSame('UTC', $utc->get_identifier());

        // An IANA name needs a tz database GLib can read: Windows has none of its own, so
        // new_identifier() answers null there rather than a zone.
        $berlin = GTimeZone::new_identifier('Europe/Berlin');
        if ($berlin === null) {
            self::markTestSkipped('no IANA time zone database here (GLib answered null)');
        }
        self::assertSame('Europe/Berlin', $berlin->get_identifier());

        $utcNoon = GDateTime::new_utc(2026, 6, 1, 12, 0, 0.0);
        self::assertInstanceOf(GDateTime::class, $utcNoon);
        $noon = $utcNoon->to_timezone($berlin);
        self::assertSame(14, $noon?->get_hour(), 'summer time in Berlin is UTC+2');
    }

    public function testUnixTimeRoundTrips(): void
    {
        $date = GDateTime::new_from_unix_utc(1_000_000_000);
        self::assertInstanceOf(GDateTime::class, $date);

        self::assertSame(1_000_000_000, $date->to_unix());
        self::assertSame('2001-09-09', $date->format('%Y-%m-%d'));
    }

    /** What binding it was for: a calendar answers with a date rather than three integers. */
    public function testACalendarAnswersWithADate(): void
    {
        $calendar = new GtkCalendar();
        $piDay = GDateTime::new_utc(2026, 3, 14, 0, 0, 0.0);
        self::assertInstanceOf(GDateTime::class, $piDay);
        $calendar->select_day($piDay);

        $date = $calendar->get_date();
        self::assertSame(2026, $date->get_year());
        self::assertSame(3, $date->get_month());
        self::assertSame(14, $date->get_day_of_month());
    }
}
