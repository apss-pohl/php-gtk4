<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GrapheneMatrix;
use Gtk4\GraphenePoint3D;
use Gtk4\GrapheneVec3;
use Gtk4\GskTransform;
use Gtk4\GtkSnapshot;

/**
 * The 3D types a transform and a snapshot speak: a 4x4 matrix, the vectors an axis is given as,
 * and the point a translation moves by. Values, all of them - a matrix operation answers with a
 * new matrix rather than changing the one it was called on.
 */
final class GrapheneTest extends GtkTestCase
{
    /** The matrix is opaque, so `new` is private and alloc() is how one is made. */
    public function testAMatrixStartsAsWhateverItWasInitialisedTo(): void
    {
        $identity = GrapheneMatrix::alloc();
        $identity->init_identity();

        self::assertTrue($identity->is_identity());
        self::assertSame(1.0, $identity->get_value(0, 0));
        self::assertSame(0.0, $identity->get_value(0, 1));
    }

    public function testATranslationIsNotTheIdentity(): void
    {
        $matrix = GrapheneMatrix::alloc();
        $point = new GraphenePoint3D();
        $point->init(10.0, 20.0, 0.0);
        $matrix->init_translate($point);

        self::assertFalse($matrix->is_identity());
        self::assertTrue($matrix->is_2d(), 'a translation in x and y stays flat');
    }

    /** A transform takes a matrix, which is what binding Graphene.Matrix was for. */
    public function testATransformTakesAMatrix(): void
    {
        $matrix = GrapheneMatrix::alloc();
        $matrix->init_scale(2.0, 3.0, 1.0);

        $transform = new GskTransform()->matrix($matrix);
        self::assertInstanceOf(GskTransform::class, $transform);
        self::assertStringContainsString('matrix', strtolower($transform->to_string()));
    }

    /** rotate_3d() takes its axis as a vector, which had no PHP type before. */
    public function testATransformRotatesAboutAVector(): void
    {
        $axis = GrapheneVec3::alloc();
        $axis->init(0.0, 0.0, 1.0);

        $transform = new GskTransform()->rotate_3d(45.0, $axis);
        self::assertInstanceOf(GskTransform::class, $transform);
    }

    /** A snapshot speaks the same types. */
    public function testASnapshotTranslatesInThreeDimensions(): void
    {
        $point = new GraphenePoint3D();
        $point->init(5.0, 5.0, 1.0);

        $snapshot = new GtkSnapshot();
        $snapshot->translate_3d($point);
        self::assertInstanceOf(GtkSnapshot::class, $snapshot);
    }

    /** A matrix is a value: multiplying answers with a new one. */
    public function testMultiplyingAnswersWithANewMatrix(): void
    {
        $a = GrapheneMatrix::alloc();
        $a->init_scale(2.0, 2.0, 1.0);
        $b = GrapheneMatrix::alloc();
        $b->init_identity();

        $product = $a->multiply($b);
        self::assertSame(2.0, $product->get_value(0, 0));
        self::assertSame(2.0, $a->get_value(0, 0), 'the original is untouched');
    }
}
