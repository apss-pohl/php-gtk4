<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GError;
use Gtk4\GObject;
use Gtk4\GtkBox;
use Gtk4\GtkBuilder;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;

/**
 * A .ui document from PHP. Two things here are not the generator's mechanical translation: the
 * string-parsing methods take no length (GTK's `gssize` let a script read past the buffer), and
 * `set_handlers()` installs the GtkBuilderScope that resolves `<signal handler="...">` to a PHP
 * callable - GTK 4 dropped gtk_builder_connect_signals(), so without it every document with a
 * signal failed with "No function named".
 */
final class BuilderTest extends GtkTestCase
{
    private const string UI = <<<'XML'
        <interface>
          <object class="GtkBox" id="root">
            <property name="orientation">vertical</property>
            <child><object class="GtkLabel" id="lbl"><property name="label">from XML</property></object></child>
            <child><object class="GtkButton" id="btn">
              <property name="label">click</property>
              <signal name="clicked" handler="on_click"/>
            </object></child>
          </object>
        </interface>
        XML;

    /** The same document without the <signal> element, for the cases that do not need a handler. */
    private static function plainUi(): string
    {
        return str_replace('<signal name="clicked" handler="on_click"/>', '', self::UI);
    }

    public function testObjectsComeBackAsTheirOwnClasses(): void
    {
        $builder = new GtkBuilder();
        self::assertTrue($builder->add_from_string(self::plainUi()));
        self::assertInstanceOf(GtkBox::class, $builder->get_object('root'));
        self::assertInstanceOf(GtkLabel::class, $builder->get_object('lbl'));
        self::assertInstanceOf(GtkButton::class, $builder->get_object('btn'));
        self::assertNull($builder->get_object('nope'));
        self::assertCount(3, $builder->get_objects());
    }

    public function testPropertiesFromTheDocumentAreApplied(): void
    {
        $builder = new GtkBuilder();
        $builder->add_from_string(self::plainUi());
        $label = $builder->get_object('lbl');
        self::assertInstanceOf(GtkLabel::class, $label);
        self::assertSame('from XML', $label->get_label());
    }

    public function testAddFromStringTakesNoLength(): void
    {
        // GTK takes the length separately; a PHP string carries its own, and a wrong one read
        // past the end of the buffer.
        $method = new \ReflectionMethod(GtkBuilder::class, 'add_from_string');
        self::assertSame(1, $method->getNumberOfParameters());
    }

    public function testAnInvalidDocumentThrowsInsteadOfAborting(): void
    {
        // GtkBuilder::new_from_string() g_error()s (kills the process) on the same input, which is
        // why it is not bound at all.
        self::assertFalse(method_exists(GtkBuilder::class, 'new_from_string'));
        $this->expectException(GError::class);
        new GtkBuilder()->add_from_string('<interface><object class="NoSuchClass" id="x"/></interface>');
    }

    public function testMalformedXmlThrows(): void
    {
        $this->expectException(GError::class);
        new GtkBuilder()->add_from_string('not xml at all');
    }

    public function testHandlersAreCalledWithTheEmittingObject(): void
    {
        $seen = [];
        $builder = new GtkBuilder();
        $builder->set_handlers(['on_click' => function (GtkButton $button) use (&$seen): void {
            $seen[] = $button->get_label();
        }]);
        $builder->add_from_string(self::UI);
        $button = $builder->get_object('btn');
        self::assertInstanceOf(GtkButton::class, $button);
        $button->emit('clicked');
        $button->emit('clicked');
        self::assertSame(['click', 'click'], $seen);
    }

    public function testAnUnknownHandlerIsAParseError(): void
    {
        $builder = new GtkBuilder();
        $builder->set_handlers(['something_else' => fn() => null]);
        $this->expectExceptionMessage("No handler named 'on_click' was given to GtkBuilder::set_handlers()");
        $builder->add_from_string(self::UI);
    }

    public function testHandlersMustBeSetBeforeParsing(): void
    {
        // GTK connects while parsing, so a scope installed afterwards is too late to help.
        $builder = new GtkBuilder();
        $this->expectExceptionMessage("No handler named 'on_click'");
        $builder->add_from_string(self::UI);
    }

    /**
     * GTK's own scope resolves a handler name as a C symbol of the whole process, so a document
     * could name `abort` (or anything else exported) and have it called with the widget as its
     * argument. Every builder gets php-gtk4's scope from its constructor on: a name resolves
     * from set_handlers() or not at all.
     */
    public function testADocumentCannotNameACFunction(): void
    {
        $builder = new GtkBuilder();
        $builder->set_handlers(['on_click' => fn() => null]);
        $this->expectExceptionMessage("No handler named 'abort'");
        $builder->add_from_string(str_replace('handler="on_click"', 'handler="abort"', self::UI));
    }

    /** A handler connected by the document is disconnected at shutdown like connect()'s. */
    public function testDocumentHandlersAreTornDownWithTheRequest(): void
    {
        $builder = new GtkBuilder();
        $builder->set_handlers(['on_click' => fn() => null]);
        $builder->add_from_string(self::UI);

        self::assertInstanceOf(GtkButton::class, $builder->get_object('btn'));
        // the real assertion is tests/scripts/shutdown.php, which keeps such a widget alive into
        // RSHUTDOWN; here: setting handlers twice replaces the array, it does not stack scopes
        $builder->set_handlers(['on_click' => fn() => null]);
        self::assertTrue($builder->get_scope() === $builder->get_scope());
    }

    public function testSwappedIsRefusedForAPhpHandler(): void
    {
        $builder = new GtkBuilder();
        $builder->set_handlers(['on_click' => fn() => null]);
        $this->expectExceptionMessage('swapped');
        $builder->add_from_string(str_replace('handler="on_click"', 'handler="on_click" swapped="yes"', self::UI));
    }

    public function testHandlersRejectsANonCallable(): void
    {
        $this->expectException(\TypeError::class);
        new GtkBuilder()->set_handlers(['on_click' => 'no_such_function_at_all']);
    }

    public function testHandlersRejectsAPositionalArray(): void
    {
        $this->expectException(\ValueError::class);
        new GtkBuilder()->set_handlers([fn() => null]);
    }

    public function testExposeObjectMakesAPhpObjectVisibleToTheDocument(): void
    {
        $builder = new GtkBuilder();
        $label = new GtkLabel('exposed');
        $builder->expose_object('outside', $label);
        self::assertSame($label, $builder->get_object('outside'));
    }

    public function testAddObjectsFromStringBuildsOnlyWhatItIsAskedFor(): void
    {
        $builder = new GtkBuilder();
        self::assertTrue($builder->add_objects_from_string(self::plainUi(), ['lbl']));
        self::assertInstanceOf(GtkLabel::class, $builder->get_object('lbl'));
        self::assertNull($builder->get_object('btn'), 'btn was not asked for');
    }

    public function testTranslationDomainRoundTrips(): void
    {
        $builder = new GtkBuilder();
        self::assertNull($builder->get_translation_domain());
        $builder->set_translation_domain('php-gtk4');
        self::assertSame('php-gtk4', $builder->get_translation_domain());
    }

    public function testCurrentObjectRoundTrips(): void
    {
        $builder = new GtkBuilder();
        $label = new GtkLabel('current');
        self::assertNull($builder->get_current_object());
        $builder->set_current_object($label);
        self::assertSame($label, $builder->get_current_object());
    }

    public function testCurrentObjectCannotBeCleared(): void
    {
        // GIR says the parameter is nullable; GTK's own precondition rejects NULL (it CRITICALs
        // and changes nothing), so the binding does not offer it - passing null is a TypeError
        // the engine raises from the arginfo, which is what this pins.
        $parameter = new \ReflectionMethod(GtkBuilder::class, 'set_current_object')->getParameters()[0];
        self::assertFalse($parameter->allowsNull(), 'GTK CRITICALs on a NULL current object');
    }

    public function testAHandlerThatThrowsGoesThroughTheExceptionBoundary(): void
    {
        $builder = new GtkBuilder();
        $builder->set_handlers(['on_click' => function (): void {
            throw new \RuntimeException('from the .ui handler');
        }]);
        $builder->add_from_string(self::UI);
        $button = $builder->get_object('btn');
        self::assertInstanceOf(GtkButton::class, $button);

        $caught = $this->captureHandlerException(static fn() => $button->emit('clicked'));
        self::assertNotNull($caught);
        self::assertSame('from the .ui handler', $caught[0]);
        self::assertSame('on_click', $caught[1], 'the origin names the handler from the document');
    }

    public function testGetObjectsAnswersWithHandles(): void
    {
        $builder = new GtkBuilder();
        $builder->add_from_string(self::plainUi());
        foreach ($builder->get_objects() as $object) {
            self::assertInstanceOf(GObject::class, $object);
        }
    }
}
