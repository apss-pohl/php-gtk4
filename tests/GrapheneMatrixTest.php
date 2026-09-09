<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GrapheneMatrix;
use Gtk4\GraphenePoint;
use Gtk4\GraphenePoint3D;
use Gtk4\GrapheneRect;
use Gtk4\GrapheneVec3;
use Gtk4\GrapheneVec4;

/**
 * The 4x4 matrix behind every `GskTransform`, tested as the value type it is: `init_*` writes into
 * the matrix it was called on, everything else answers with a new one, and the in-place operations
 * (`rotate`, `scale`, `skew_*`, `translate`) return void because they change the receiver.
 *
 * `GrapheneTest` covers what a transform and a snapshot do with a matrix; this covers the matrix
 * itself - the arithmetic, the projections, and the answers it gives about its own shape.
 */
final class GrapheneMatrixTest extends GtkTestCase
{
    private const EPSILON = 1.0e-5;

    private static function scale(float $x, float $y, float $z): GrapheneMatrix
    {
        $m = GrapheneMatrix::alloc();
        $m->init_scale($x, $y, $z);
        return $m;
    }

    private static function identity(): GrapheneMatrix
    {
        $m = GrapheneMatrix::alloc();
        $m->init_identity();
        return $m;
    }

    public function testAScaleMatrixKnowsItsFactors(): void
    {
        $m = self::scale(2.0, 3.0, 4.0);

        self::assertSame(2.0, $m->get_x_scale());
        self::assertSame(3.0, $m->get_y_scale());
        self::assertSame(4.0, $m->get_z_scale());
        self::assertFalse($m->is_identity());
        self::assertFalse($m->is_singular(), 'a scale by non-zero factors is invertible');
        // graphene's determinant carries the sign of its own row order; the magnitude is 2*3*4.
        self::assertEqualsWithDelta(24.0, abs($m->determinant()), self::EPSILON);
    }

    public function testATranslationKnowsWhereItMoves(): void
    {
        $m = GrapheneMatrix::alloc();
        $m->init_translate(new GraphenePoint3D(10.0, 20.0, 30.0));

        self::assertSame(10.0, $m->get_x_translation());
        self::assertSame(20.0, $m->get_y_translation());
        self::assertSame(30.0, $m->get_z_translation());
    }

    public function testAMatrixIsBuiltFromItsRowsAndReadBackByRow(): void
    {
        $rows = [
            (static fn(): GrapheneVec4 => GrapheneVec4::alloc())()->init(1.0, 0.0, 0.0, 0.0),
            (static fn(): GrapheneVec4 => GrapheneVec4::alloc())()->init(0.0, 2.0, 0.0, 0.0),
            (static fn(): GrapheneVec4 => GrapheneVec4::alloc())()->init(0.0, 0.0, 3.0, 0.0),
            (static fn(): GrapheneVec4 => GrapheneVec4::alloc())()->init(0.0, 0.0, 0.0, 1.0),
        ];
        $m = GrapheneMatrix::alloc();
        $m->init_from_vec4($rows[0], $rows[1], $rows[2], $rows[3]);

        self::assertSame(2.0, $m->get_value(1, 1));
        self::assertSame(3.0, $m->get_row(2)->get_z());
        self::assertSame(1.0, $m->get_row(3)->get_w());
    }

    public function testAMatrixCopiesFromAnother(): void
    {
        $copy = GrapheneMatrix::alloc();
        $copy->init_from_matrix(self::scale(5.0, 6.0, 7.0));

        self::assertSame(5.0, $copy->get_x_scale());
        self::assertTrue($copy->equal(self::scale(5.0, 6.0, 7.0)));
        self::assertTrue($copy->equal_fast($copy), 'the fast path compares identity of storage');
        self::assertTrue($copy->near(self::scale(5.0, 6.0, 7.000001), 1.0e-3));
    }

    public function testATwoDimensionalMatrixSaysSoAndComesApart(): void
    {
        $m = GrapheneMatrix::alloc();
        $m->init_from_2d(1.0, 0.0, 0.0, 1.0, 30.0, 40.0);

        self::assertTrue($m->is_2d());
        self::assertSame([1.0, 0.0, 0.0, 1.0, 30.0, 40.0], $m->to_2d(), 'the six components back');

        $perspective = GrapheneMatrix::alloc();
        $perspective->init_perspective(60.0, 1.5, 1.0, 100.0);
        self::assertFalse($perspective->is_2d());
        self::assertNull($perspective->to_2d(), 'a projection has no 2D form');
    }

    public function testTheProjectionsBuildWithoutComplaint(): void
    {
        $ortho = GrapheneMatrix::alloc();
        $ortho->init_ortho(0.0, 800.0, 0.0, 600.0, -1.0, 1.0);
        self::assertFalse($ortho->is_singular());

        $frustum = GrapheneMatrix::alloc();
        $frustum->init_frustum(-1.0, 1.0, -1.0, 1.0, 1.0, 100.0);
        self::assertFalse($frustum->is_singular());

        $lookAt = GrapheneMatrix::alloc();
        $lookAt->init_look_at(
            self::vec3(0.0, 0.0, 10.0),
            GrapheneVec3::zero(),
            GrapheneVec3::y_axis(),
        );
        self::assertFalse($lookAt->is_identity());
    }

    public function testMultiplyingComposesAndInvertingUndoes(): void
    {
        $scale = self::scale(2.0, 2.0, 2.0);
        $composed = $scale->multiply(self::scale(3.0, 3.0, 3.0));
        self::assertEqualsWithDelta(6.0, $composed->get_x_scale(), self::EPSILON);
        self::assertSame(2.0, $scale->get_x_scale(), 'the operand is untouched');

        $inverse = $scale->inverse();
        self::assertInstanceOf(GrapheneMatrix::class, $inverse);
        self::assertEqualsWithDelta(0.5, $inverse->get_x_scale(), self::EPSILON);
        self::assertTrue($scale->multiply($inverse)->near(self::identity(), self::EPSILON));
    }

    public function testASingularMatrixHasNoInverse(): void
    {
        $flat = self::scale(0.0, 0.0, 0.0);

        self::assertTrue($flat->is_singular());
        self::assertNull($flat->inverse(), 'nothing to invert');
    }

    public function testTransposeAndNormaliseAnswerNewMatrices(): void
    {
        $m = GrapheneMatrix::alloc();
        $m->init_from_2d(1.0, 2.0, 3.0, 4.0, 0.0, 0.0);

        $transposed = $m->transpose();
        self::assertSame($m->get_value(0, 1), $transposed->get_value(1, 0));
        self::assertSame(1.0, $m->get_value(0, 0), 'the operand is untouched');

        self::assertFalse($m->normalize()->is_singular());
    }

    public function testAMatrixTransformsThePointTypes(): void
    {
        $m = self::scale(2.0, 3.0, 4.0);

        $point = $m->transform_point(new GraphenePoint(1.0, 1.0));
        self::assertEqualsWithDelta(2.0, $point->x, self::EPSILON);
        self::assertEqualsWithDelta(3.0, $point->y, self::EPSILON);

        $point3d = $m->transform_point3d(new GraphenePoint3D(1.0, 1.0, 1.0));
        self::assertEqualsWithDelta(4.0, $point3d->z, self::EPSILON);

        $vec3 = $m->transform_vec3(GrapheneVec3::one());
        self::assertEqualsWithDelta(2.0, $vec3->get_x(), self::EPSILON);

        $vec4 = $m->transform_vec4(GrapheneVec4::one());
        self::assertEqualsWithDelta(3.0, $vec4->get_y(), self::EPSILON);
    }

    public function testAMatrixTransformsAndProjectsRectangles(): void
    {
        $m = self::scale(2.0, 2.0, 1.0);
        $rect = GrapheneRect::alloc();
        $rect->init(0.0, 0.0, 10.0, 20.0);

        $bounds = $m->transform_bounds($rect);
        self::assertEqualsWithDelta(20.0, $bounds->get_width(), self::EPSILON);
        self::assertEqualsWithDelta(40.0, $bounds->get_height(), self::EPSILON);

        $projected = $m->project_rect_bounds($rect);
        self::assertEqualsWithDelta(20.0, $projected->get_width(), self::EPSILON);

        $back = $m->untransform_bounds($bounds, $rect);
        self::assertInstanceOf(GrapheneRect::class, $back);

        $projectedPoint = $m->project_point(new GraphenePoint(3.0, 4.0));
        self::assertEqualsWithDelta(6.0, $projectedPoint->x, self::EPSILON);

        $untransformed = $m->untransform_point(new GraphenePoint(6.0, 8.0), $bounds);
        self::assertInstanceOf(GraphenePoint::class, $untransformed);
        self::assertEqualsWithDelta(3.0, $untransformed->x, self::EPSILON);
    }

    public function testTheInPlaceOperationsChangeTheReceiver(): void
    {
        $m = self::identity();
        $m->scale(2.0, 2.0, 2.0);
        self::assertEqualsWithDelta(2.0, $m->get_x_scale(), self::EPSILON, 'scale() is in place');

        $m->translate(new GraphenePoint3D(5.0, 0.0, 0.0));
        // The offset lands in the translation row as given: graphene does not run it through the
        // scale already in the matrix, so this is 5 and not 10.
        self::assertEqualsWithDelta(5.0, $m->get_x_translation(), self::EPSILON);
        self::assertEqualsWithDelta(2.0, $m->get_x_scale(), self::EPSILON, 'and the scale is still there');

        $rotated = self::identity();
        $rotated->rotate_z(90.0);
        self::assertFalse($rotated->is_identity());
        $rotated->rotate_x(90.0);
        $rotated->rotate_y(90.0);
        $rotated->rotate(45.0, GrapheneVec3::z_axis());
        self::assertFalse($rotated->is_singular(), 'rotation preserves volume');

        $skewed = self::identity();
        $skewed->skew_xy(0.5);
        $skewed->skew_xz(0.25);
        $skewed->skew_yz(0.25);
        self::assertFalse($skewed->is_identity());
    }

    public function testInitSkewAndPerspectiveAndInterpolation(): void
    {
        $skew = GrapheneMatrix::alloc();
        $skew->init_skew(0.5, 0.25);
        self::assertFalse($skew->is_identity());

        $perspective = self::identity()->perspective(100.0);
        self::assertInstanceOf(GrapheneMatrix::class, $perspective);

        $half = self::identity()->interpolate(self::scale(3.0, 3.0, 3.0), 0.5);
        self::assertEqualsWithDelta(2.0, $half->get_x_scale(), self::EPSILON, 'halfway between 1 and 3');

        // The identity faces the viewer; half a turn about y shows its back.
        self::assertFalse(self::identity()->is_backface_visible());
        $turned = self::identity();
        $turned->rotate_y(180.0);
        self::assertTrue($turned->is_backface_visible());
    }

    public function testAPointIsUnprojectedThroughAModelview(): void
    {
        $modelview = self::identity();
        $projection = GrapheneMatrix::alloc();
        $projection->init_perspective(60.0, 1.0, 1.0, 100.0);

        $unprojected = $projection->unproject_point3d($modelview, new GraphenePoint3D(0.0, 0.0, -5.0));
        self::assertInstanceOf(GraphenePoint3D::class, $unprojected);
    }

    private static function vec3(float $x, float $y, float $z): GrapheneVec3
    {
        $v = GrapheneVec3::alloc();
        $v->init($x, $y, $z);
        return $v;
    }
}
