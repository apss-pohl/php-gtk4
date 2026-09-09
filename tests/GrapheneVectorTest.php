<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GrapheneVec2;
use Gtk4\GrapheneVec3;
use Gtk4\GrapheneVec4;

/**
 * The three vector value types, which are arithmetic and nothing else: no display, no GTK state,
 * no ownership question beyond the boxed one. An operation answers with a *new* vector and leaves
 * the receiver alone - only the `init_*` pair writes into the value it was called on - so every
 * test below checks the answer and that the operand did not move.
 *
 * They are here because they are cheap to be sure about, and because a wrong float crossing the
 * boundary is invisible: `get_x()` declares float and PHP never checks it (CLAUDE.md, "The
 * declared type is a promise the engine does not keep").
 */
final class GrapheneVectorTest extends GtkTestCase
{
    private const EPSILON = 1.0e-6;

    private static function vec2(float $x, float $y): GrapheneVec2
    {
        $v = GrapheneVec2::alloc();
        $v->init($x, $y);
        return $v;
    }

    private static function vec3(float $x, float $y, float $z): GrapheneVec3
    {
        $v = GrapheneVec3::alloc();
        $v->init($x, $y, $z);
        return $v;
    }

    private static function vec4(float $x, float $y, float $z, float $w): GrapheneVec4
    {
        $v = GrapheneVec4::alloc();
        $v->init($x, $y, $z, $w);
        return $v;
    }

    // ---------------------------------------------------------------- Vec2

    public function testAVec2CarriesWhatItWasInitialisedWith(): void
    {
        $v = self::vec2(3.0, 4.0);

        self::assertSame(3.0, $v->get_x());
        self::assertSame(4.0, $v->get_y());
        self::assertSame(5.0, $v->length(), '3-4-5');
    }

    public function testTheVec2ConstantsAreWhatTheyClaim(): void
    {
        self::assertSame([0.0, 0.0], [GrapheneVec2::zero()->get_x(), GrapheneVec2::zero()->get_y()]);
        self::assertSame([1.0, 1.0], [GrapheneVec2::one()->get_x(), GrapheneVec2::one()->get_y()]);
        self::assertSame([1.0, 0.0], [GrapheneVec2::x_axis()->get_x(), GrapheneVec2::x_axis()->get_y()]);
        self::assertSame([0.0, 1.0], [GrapheneVec2::y_axis()->get_x(), GrapheneVec2::y_axis()->get_y()]);
    }

    public function testVec2ArithmeticAnswersWithANewVector(): void
    {
        $a = self::vec2(3.0, 4.0);
        $b = self::vec2(1.0, 2.0);

        $sum = $a->add($b);
        self::assertSame([4.0, 6.0], [$sum->get_x(), $sum->get_y()]);
        self::assertSame([3.0, 4.0], [$a->get_x(), $a->get_y()], 'the operand is untouched');

        $difference = $a->subtract($b);
        self::assertSame([2.0, 2.0], [$difference->get_x(), $difference->get_y()]);

        $product = $a->multiply($b);
        self::assertSame([3.0, 8.0], [$product->get_x(), $product->get_y()]);

        // graphene is float32 and divides through a reciprocal, so 4/2 comes back as
        // 1.9999998807907104 - exact equality is the wrong question to ask a vector type.
        $quotient = $a->divide($b);
        self::assertEqualsWithDelta(3.0, $quotient->get_x(), self::EPSILON);
        self::assertEqualsWithDelta(2.0, $quotient->get_y(), self::EPSILON);

        $scaled = $a->scale(2.0);
        self::assertSame([6.0, 8.0], [$scaled->get_x(), $scaled->get_y()]);

        $negated = $a->negate();
        self::assertSame([-3.0, -4.0], [$negated->get_x(), $negated->get_y()]);
    }

    public function testAVec2KnowsItsDotProductAndDirection(): void
    {
        $a = self::vec2(3.0, 4.0);

        self::assertSame(11.0, $a->dot(self::vec2(1.0, 2.0)), '3*1 + 4*2');

        $unit = $a->normalize();
        self::assertEqualsWithDelta(1.0, $unit->length(), self::EPSILON);
        self::assertEqualsWithDelta(0.6, $unit->get_x(), self::EPSILON);
        self::assertEqualsWithDelta(0.8, $unit->get_y(), self::EPSILON);
    }

    public function testAVec2ComparesExactlyAndApproximately(): void
    {
        $a = self::vec2(1.0, 2.0);

        self::assertTrue($a->equal(self::vec2(1.0, 2.0)));
        self::assertFalse($a->equal(self::vec2(1.0, 2.5)));
        self::assertTrue($a->near(self::vec2(1.0, 2.000001), 1.0e-3), 'near takes an epsilon');
        self::assertFalse($a->near(self::vec2(1.0, 2.5), 1.0e-3));
    }

    public function testAVec2PicksComponentsAndInterpolates(): void
    {
        $a = self::vec2(1.0, 8.0);
        $b = self::vec2(5.0, 2.0);

        self::assertSame([1.0, 2.0], [$a->min($b)->get_x(), $a->min($b)->get_y()]);
        self::assertSame([5.0, 8.0], [$a->max($b)->get_x(), $a->max($b)->get_y()]);

        $half = $a->interpolate($b, 0.5);
        self::assertEqualsWithDelta(3.0, $half->get_x(), self::EPSILON);
        self::assertEqualsWithDelta(5.0, $half->get_y(), self::EPSILON);
    }

    public function testAVec2CanBeInitialisedFromAnother(): void
    {
        $copy = GrapheneVec2::alloc();
        $copy->init_from_vec2(self::vec2(7.0, 9.0));

        self::assertSame([7.0, 9.0], [$copy->get_x(), $copy->get_y()]);
    }

    // ---------------------------------------------------------------- Vec3

    public function testAVec3CarriesThreeComponents(): void
    {
        $v = self::vec3(1.0, 2.0, 2.0);

        self::assertSame([1.0, 2.0, 2.0], [$v->get_x(), $v->get_y(), $v->get_z()]);
        self::assertSame(3.0, $v->length(), '1-2-2 has length 3');
    }

    public function testTheVec3ConstantsAreTheAxes(): void
    {
        $axes = [
            'x' => [GrapheneVec3::x_axis(), [1.0, 0.0, 0.0]],
            'y' => [GrapheneVec3::y_axis(), [0.0, 1.0, 0.0]],
            'z' => [GrapheneVec3::z_axis(), [0.0, 0.0, 1.0]],
            'one' => [GrapheneVec3::one(), [1.0, 1.0, 1.0]],
            'zero' => [GrapheneVec3::zero(), [0.0, 0.0, 0.0]],
        ];
        foreach ($axes as $name => [$vector, $expected]) {
            self::assertSame($expected, [$vector->get_x(), $vector->get_y(), $vector->get_z()], $name);
        }
    }

    public function testVec3ArithmeticAnswersWithANewVector(): void
    {
        $a = self::vec3(2.0, 4.0, 6.0);
        $b = self::vec3(1.0, 2.0, 3.0);

        self::assertSame([3.0, 6.0, 9.0], self::xyz($a->add($b)));
        self::assertSame([1.0, 2.0, 3.0], self::xyz($a->subtract($b)));
        self::assertSame([2.0, 8.0, 18.0], self::xyz($a->multiply($b)));
        self::assertEqualsWithDelta([2.0, 2.0, 2.0], self::xyz($a->divide($b)), self::EPSILON, 'float32 reciprocal');
        self::assertSame([1.0, 2.0, 3.0], self::xyz($a->scale(0.5)));
        self::assertSame([-2.0, -4.0, -6.0], self::xyz($a->negate()));
        self::assertSame([2.0, 4.0, 6.0], self::xyz($a), 'the operand is untouched');
    }

    public function testAVec3CrossesAndDots(): void
    {
        $cross = GrapheneVec3::x_axis()->cross(GrapheneVec3::y_axis());
        self::assertSame([0.0, 0.0, 1.0], self::xyz($cross), 'x cross y is z');

        self::assertSame(0.0, GrapheneVec3::x_axis()->dot(GrapheneVec3::y_axis()), 'the axes are orthogonal');
        self::assertSame(28.0, self::vec3(1.0, 2.0, 3.0)->dot(self::vec3(2.0, 4.0, 6.0)));
    }

    public function testAVec3NormalisesAndInterpolates(): void
    {
        $unit = self::vec3(0.0, 3.0, 4.0)->normalize();
        self::assertEqualsWithDelta(1.0, $unit->length(), self::EPSILON);
        self::assertEqualsWithDelta(0.6, $unit->get_y(), self::EPSILON);

        $quarter = self::vec3(0.0, 0.0, 0.0)->interpolate(self::vec3(4.0, 8.0, 12.0), 0.25);
        self::assertEqualsWithDelta(1.0, $quarter->get_x(), self::EPSILON);
        self::assertEqualsWithDelta(3.0, $quarter->get_z(), self::EPSILON);
    }

    public function testAVec3ProjectsIntoItsNeighbours(): void
    {
        $v = self::vec3(1.0, 2.0, 3.0);

        $xy = $v->get_xy();
        self::assertInstanceOf(GrapheneVec2::class, $xy);
        self::assertSame([1.0, 2.0], [$xy->get_x(), $xy->get_y()]);

        self::assertSame([1.0, 2.0, 0.0], self::xyz($v->get_xy0()), 'z dropped');

        $xyz0 = $v->get_xyz0();
        self::assertInstanceOf(GrapheneVec4::class, $xyz0);
        self::assertSame(0.0, $xyz0->get_w(), 'a direction');
        self::assertSame(1.0, $v->get_xyz1()->get_w(), 'a position');
        self::assertSame(7.0, $v->get_xyzw(7.0)->get_w());

        self::assertTrue($v->near(self::vec3(1.0, 2.0, 3.0), self::EPSILON));
        self::assertTrue($v->equal(self::vec3(1.0, 2.0, 3.0)));
    }

    public function testAVec3PicksComponentsAndCopies(): void
    {
        $a = self::vec3(1.0, 8.0, 3.0);
        $b = self::vec3(5.0, 2.0, 3.0);

        self::assertSame([1.0, 2.0, 3.0], self::xyz($a->min($b)));
        self::assertSame([5.0, 8.0, 3.0], self::xyz($a->max($b)));

        $copy = GrapheneVec3::alloc();
        $copy->init_from_vec3($a);
        self::assertSame([1.0, 8.0, 3.0], self::xyz($copy));
    }

    // ---------------------------------------------------------------- Vec4

    public function testAVec4CarriesFourComponents(): void
    {
        $v = self::vec4(1.0, 2.0, 2.0, 4.0);

        self::assertSame([1.0, 2.0, 2.0, 4.0], [$v->get_x(), $v->get_y(), $v->get_z(), $v->get_w()]);
        self::assertSame(5.0, $v->length(), '1-2-2-4 has length 5');
    }

    public function testTheVec4ConstantsIncludeW(): void
    {
        self::assertSame(1.0, GrapheneVec4::w_axis()->get_w());
        self::assertSame(0.0, GrapheneVec4::w_axis()->get_x());
        self::assertSame(1.0, GrapheneVec4::x_axis()->get_x());
        self::assertSame(1.0, GrapheneVec4::y_axis()->get_y());
        self::assertSame(1.0, GrapheneVec4::z_axis()->get_z());
        self::assertSame(0.0, GrapheneVec4::zero()->get_w());
        self::assertSame(1.0, GrapheneVec4::one()->get_w());
    }

    public function testVec4ArithmeticAnswersWithANewVector(): void
    {
        $a = self::vec4(2.0, 4.0, 6.0, 8.0);
        $b = self::vec4(1.0, 2.0, 3.0, 4.0);

        self::assertSame([3.0, 6.0, 9.0, 12.0], self::xyzw($a->add($b)));
        self::assertSame([1.0, 2.0, 3.0, 4.0], self::xyzw($a->subtract($b)));
        self::assertSame([2.0, 8.0, 18.0, 32.0], self::xyzw($a->multiply($b)));
        self::assertEqualsWithDelta(
            [2.0, 2.0, 2.0, 2.0],
            self::xyzw($a->divide($b)),
            self::EPSILON,
            'float32 reciprocal',
        );
        self::assertSame([1.0, 2.0, 3.0, 4.0], self::xyzw($a->scale(0.5)));
        self::assertSame([-2.0, -4.0, -6.0, -8.0], self::xyzw($a->negate()));
        self::assertSame([2.0, 4.0, 6.0, 8.0], self::xyzw($a), 'the operand is untouched');

        self::assertSame(60.0, $a->dot($b), '2+8+18+32');
    }

    public function testAVec4NormalisesInterpolatesAndCompares(): void
    {
        $unit = self::vec4(0.0, 0.0, 3.0, 4.0)->normalize();
        self::assertEqualsWithDelta(1.0, $unit->length(), self::EPSILON);
        self::assertEqualsWithDelta(0.8, $unit->get_w(), self::EPSILON);

        $half = GrapheneVec4::zero()->interpolate(self::vec4(2.0, 4.0, 6.0, 8.0), 0.5);
        self::assertEqualsWithDelta(1.0, $half->get_x(), self::EPSILON);
        self::assertEqualsWithDelta(4.0, $half->get_w(), self::EPSILON);

        $a = self::vec4(1.0, 2.0, 3.0, 4.0);
        self::assertTrue($a->equal(self::vec4(1.0, 2.0, 3.0, 4.0)));
        self::assertFalse($a->equal(GrapheneVec4::one()));
        self::assertTrue($a->near(self::vec4(1.0, 2.0, 3.0, 4.000001), 1.0e-3));

        self::assertSame([1.0, 2.0, 3.0, 4.0], self::xyzw($a->max(GrapheneVec4::zero())));
        self::assertSame([0.0, 0.0, 0.0, 0.0], self::xyzw($a->min(GrapheneVec4::zero())));
    }

    public function testAVec4IsBuiltFromItsSmallerNeighbours(): void
    {
        $fromVec2 = GrapheneVec4::alloc();
        $fromVec2->init_from_vec2(self::vec2(1.0, 2.0), 3.0, 4.0);
        self::assertSame([1.0, 2.0, 3.0, 4.0], self::xyzw($fromVec2));

        $fromVec3 = GrapheneVec4::alloc();
        $fromVec3->init_from_vec3(self::vec3(5.0, 6.0, 7.0), 8.0);
        self::assertSame([5.0, 6.0, 7.0, 8.0], self::xyzw($fromVec3));

        $copy = GrapheneVec4::alloc();
        $copy->init_from_vec4($fromVec3);
        self::assertSame([5.0, 6.0, 7.0, 8.0], self::xyzw($copy));

        // and back down again
        self::assertSame([5.0, 6.0], [$fromVec3->get_xy()->get_x(), $fromVec3->get_xy()->get_y()]);
        self::assertSame([5.0, 6.0, 7.0], self::xyz($fromVec3->get_xyz()));
    }

    /** @return list<float> */
    private static function xyz(GrapheneVec3 $v): array
    {
        return [$v->get_x(), $v->get_y(), $v->get_z()];
    }

    /** @return list<float> */
    private static function xyzw(GrapheneVec4 $v): array
    {
        return [$v->get_x(), $v->get_y(), $v->get_z(), $v->get_w()];
    }
}
