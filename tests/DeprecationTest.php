<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The binding exposes the modern GTK 4 API only (docs/DESIGN.md, decided 2026-08-27): every
 * member GIR marks deprecated is skipped by the generator, and nothing carries #[\Deprecated].
 * This holds the extension to that against the installed .gir files - a hand-written override or
 * a promoted class cannot smuggle a deprecated member back in - and checks the policy has teeth
 * (the GIR really does mark hundreds of members, so an empty diff is not an empty check).
 */
final class DeprecationTest extends TestCase
{
    private const array NAMESPACES = [
        'Gtk-4.0', 'Gdk-4.0', 'Gsk-4.0', 'Gio-2.0', 'GObject-2.0', 'GLib-2.0', 'Pango-1.0', 'Graphene-1.0',
    ];
    private const string GIR_NS = 'http://www.gtk.org/introspection/core/1.0';
    private const string C_NS = 'http://www.gtk.org/introspection/c/1.0';

    /** @var array<string, array{prefix: string, xpath: \DOMXPath}> */
    private static array $girs = [];

    public static function setUpBeforeClass(): void
    {
        require_once __DIR__ . '/../gen/gir/config.php';
        foreach (self::NAMESPACES as $ns) {
            $file = null;
            foreach (\PhpGtk4\Gen\GIR_DIRS as $dir) {
                if (is_file("$dir/$ns.gir")) {
                    $file = "$dir/$ns.gir";
                }
            }
            if ($file === null) {
                self::markTestSkipped("$ns.gir not installed (gir1.2-gtk-4.0)");
            }
            $dom = new \DOMDocument();
            self::assertTrue($dom->load($file), "cannot parse $file");
            $xpath = new \DOMXPath($dom);
            $xpath->registerNamespace('gi', self::GIR_NS);
            $xpath->registerNamespace('c', self::C_NS);
            $node = self::first($xpath, '/gi:repository/gi:namespace');
            self::assertInstanceOf(\DOMElement::class, $node);
            $prefix = explode(',', $node->getAttributeNS(self::C_NS, 'identifier-prefixes'))[0];
            self::$girs[$ns] = ['prefix' => $prefix, 'xpath' => $xpath];
        }
    }

    /** The first node an XPath query finds, or null (a failed query answers false). */
    private static function first(\DOMXPath $xpath, string $query): \DOMNode|\DOMNameSpaceNode|null
    {
        $list = $xpath->query($query);
        return $list === false ? null : $list->item(0);
    }

    /**
     * Every class, interface and enum the extension registers.
     *
     * @return iterable<string, array{class-string}>
     */
    public static function registeredClasses(): iterable
    {
        foreach (new \ReflectionExtension('gtk4')->getClasses() as $class) {
            yield $class->getName() => [$class->getName()];
        }
    }

    private static function short(string $fqcn): string
    {
        return substr($fqcn, strrpos($fqcn, '\\') + 1);
    }

    /** The GIR element (class, interface, record, enum, bitfield) a registered class is named after. */
    private static function girType(string $short): ?\DOMElement
    {
        foreach (self::$girs as ['prefix' => $prefix, 'xpath' => $xpath]) {
            if (strncasecmp($short, $prefix, strlen($prefix)) !== 0) {
                continue;
            }
            $name = substr($short, strlen($prefix));
            $query = sprintf(
                '/gi:repository/gi:namespace/*[self::gi:class or self::gi:interface or self::gi:record'
                . ' or self::gi:enumeration or self::gi:bitfield][@name=%s]',
                self::literal($name),
            );
            $node = self::first($xpath, $query);
            if ($node instanceof \DOMElement) {
                return $node;
            }
        }
        return null;
    }

    private static function literal(string $s): string
    {
        return "'" . str_replace("'", "''", $s) . "'";
    }

    private static function isDeprecated(\DOMElement $node): bool
    {
        return $node->hasAttribute('deprecated') || $node->hasAttribute('deprecated-version');
    }

    /**
     * Deprecated members of a GIR type as PHP member names, by kind.
     *
     * @return array{method: list<string>, vfunc: list<string>, property: list<string>, case: list<string>}
     */
    private static function deprecatedMembers(\DOMElement $type): array
    {
        $out = ['method' => [], 'vfunc' => [], 'property' => [], 'case' => []];
        foreach ($type->childNodes as $child) {
            if (
                !$child instanceof \DOMElement || $child->namespaceURI !== self::GIR_NS
                || !self::isDeprecated($child)
            ) {
                continue;
            }
            $name = $child->getAttribute('name');
            match ($child->localName) {
                'method', 'function', 'constructor' => $out['method'][] = $name,
                'virtual-method' => $out['vfunc'][] = 'vfunc_' . $name,
                'property' => $out['property'][] = str_replace('-', '_', $name),
                'member' => $out['case'][] = $name,
                default => null,
            };
        }
        return $out;
    }

    public function testTheGirActuallyMarksMembersDeprecated(): void
    {
        // The other tests assert absence; this one makes sure there is something to be absent.
        $list = self::$girs['Gtk-4.0']['xpath']->query('//*[@deprecated or @deprecated-version]');
        self::assertNotFalse($list);
        self::assertGreaterThan(500, $list->length, 'Gtk-4.0.gir marks hundreds of members deprecated');
    }

    /** @param class-string $fqcn */
    #[DataProvider('registeredClasses')]
    public function testNoDeprecatedTypeIsRegistered(string $fqcn): void
    {
        $short = self::short($fqcn);
        $type = self::girType($short);
        if ($type === null) {
            $this->addToAssertionCount(1);   // hand-written (PhpValue, CairoContext, ...) or a fallback class
            return;
        }
        self::assertFalse(
            self::isDeprecated($type),
            "$short is deprecated in GIR (" . $type->getAttribute('deprecated-version') . '); the binding'
            . ' exposes the modern API only - bind the replacement docs/GTK3-MAP.md names instead',
        );
    }

    /** @param class-string $fqcn */
    #[DataProvider('registeredClasses')]
    public function testNoDeprecatedMemberIsBound(string $fqcn): void
    {
        $short = self::short($fqcn);
        $type = self::girType($short);
        if ($type === null) {
            $this->addToAssertionCount(1);   // no GIR counterpart
            return;
        }
        $class = new \ReflectionClass($fqcn);
        $members = self::deprecatedMembers($type);
        $bound = [];
        foreach (array_merge($members['method'], $members['vfunc']) as $method) {
            // Declared here, not inherited: a subclass may legitimately declare the same name.
            if (
                $class->hasMethod($method)
                && $class->getMethod($method)->getDeclaringClass()->getName() === $class->getName()
            ) {
                $bound[] = $method . '()';
            }
        }
        if ($members['property'] !== []) {
            $stub = (string) file_get_contents(__DIR__ . '/../stubs/gtk4.php');
            $pattern = '~/\*\*((?:(?!\*/).)*)\*/\s*(?:final |abstract )?(?:class|interface) '
                . preg_quote($short, '~') . '\b~s';
            $docblock = preg_match($pattern, $stub, $m) === 1 ? $m[1] : '';
            foreach ($members['property'] as $property) {
                $tag = '/@property(?:-read|-write)?\s+\S+\s+\$' . preg_quote($property, '/') . '\b/';
                if (preg_match($tag, $docblock) === 1) {
                    $bound[] = '$' . $property;
                }
            }
        }
        if ($members['case'] !== [] && $class->isEnum()) {
            $cases = [];
            foreach ($class->getReflectionConstants() as $constant) {
                if ($constant->isEnumCase()) {
                    $cases[] = $constant->getName();
                }
            }
            foreach ($members['case'] as $case) {
                // GIR spells a member `foo_bar`; the stub's case is CamelCase (gen/gir: `FooBar`).
                $camel = str_replace('_', '', ucwords($case, '_'));
                if (in_array($camel, $cases, true)) {
                    $bound[] = '::' . $camel;
                }
            }
        }
        self::assertSame([], $bound, "$short binds deprecated GIR members: " . implode(', ', $bound));
    }

    /** @param class-string $fqcn */
    #[DataProvider('registeredClasses')]
    public function testNothingCarriesTheDeprecatedAttribute(string $fqcn): void
    {
        // Deprecated API is skipped, never shipped with a warning - so no method may say otherwise.
        $class = new \ReflectionClass($fqcn);
        $flagged = [];
        foreach ($class->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() === $class->getName() && $method->isDeprecated()) {
                $flagged[] = $method->getName();
            }
        }
        $short = self::short($fqcn);
        self::assertSame([], $flagged, "$short has #[\\Deprecated] methods: " . implode(', ', $flagged));
    }
}
