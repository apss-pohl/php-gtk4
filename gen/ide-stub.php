#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * Derives the IDE stub (stubs/gtk4.php) from the API source of truth
 * (src/gtk4.stub.php): same declarations and docblocks, but every method
 * gets a dummy body (unset() each parameter, placeholder return for non-void)
 * so editors do not report "not all paths return a value" / unused parameters,
 * and `UNKNOWN` constant values become ''. The root GObject class additionally
 * gets __get/__set/__isset: the extension implements property access with
 * engine-level object handlers (invisible to static analysis), and PHPStan /
 * IDEs only honour the classes' `@property` tags when magic accessors exist.
 *
 *   php gen/ide-stub.php            # writes stubs/gtk4.php
 *   php gen/ide-stub.php --check    # exit 1 if stubs/gtk4.php is out of date
 */

require __DIR__ . '/../vendor/autoload.php';

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

$source = __DIR__ . '/../src/gtk4.stub.php';
$target = __DIR__ . '/../stubs/gtk4.php';

$code = file_get_contents($source);
if ($code === false) {
    fwrite(STDERR, "cannot read $source\n");
    exit(1);
}
$ast = new ParserFactory()->createForNewestSupportedVersion()->parse($code);
if ($ast === null) {
    fwrite(STDERR, "cannot parse $source\n");
    exit(1);
}

$traverser = new NodeTraverser();
$traverser->addVisitor(new class extends NodeVisitorAbstract {
    public function leaveNode(Node $node): ?Node
    {
        if (
            $node instanceof Stmt\Class_ && (
                $node->name?->toString() === 'GObject'
            || ($node->extends === null && str_contains((string) $node->getDocComment()?->getText(), '@property'))
            )
        ) {
            $node->stmts = array_merge($node->stmts, self::magicAccessors());
        }
        if ($node instanceof Stmt\ClassMethod) {
            if ($node->stmts === null) {
                return null;  // interface / abstract method: no body allowed
            }
            $body = [];
            foreach ($node->params as $param) {
                /** @var Node\Expr\Variable $var */
                $var = $param->var;
                $body[] = new Stmt\Expression(new Node\Expr\FuncCall(new Node\Name('unset'), [
                    new Node\Arg(new Node\Expr\Variable($var->name)),
                ]));
            }
            $ret = $node->returnType;
            $typeName = $ret instanceof Node\NullableType ? $ret->type : $ret;
            $name = $typeName instanceof Node\Identifier ? $typeName->toLowerString() : null;
            $placeholder = match (true) {
                $ret === null, $name === 'void', $name === 'never' => null,
                $ret instanceof Node\NullableType,
                $name === 'mixed',
                $name === 'null' => new Node\Expr\ConstFetch(new Node\Name('null')),
                $name === 'int' => new Node\Scalar\Int_(0),
                $name === 'float' => new Node\Scalar\Float_(0.0),
                $name === 'bool', $name === 'false' => new Node\Expr\ConstFetch(new Node\Name('false')),
                $name === 'true' => new Node\Expr\ConstFetch(new Node\Name('true')),
                $name === 'string' => new Node\Scalar\String_(''),
                $name === 'array', $name === 'iterable' => new Node\Expr\Array_([]),
                default => new Node\Expr\ConstFetch(new Node\Name('null')),
            };
            if ($placeholder !== null) {
                $body[] = new Stmt\Return_($placeholder);
            }
            $node->stmts = $body;
        }
        if ($node instanceof Stmt\ClassConst) {
            $typeName = $node->type instanceof Node\Identifier ? $node->type->toLowerString() : null;
            foreach ($node->consts as $const) {
                $v = $const->value;
                if ($v instanceof Node\Expr\ConstFetch && $v->name->toString() === 'UNKNOWN') {
                    $const->value = match ($typeName) {
                        'int' => new Node\Scalar\Int_(0),
                        'float' => new Node\Scalar\Float_(0.0),
                        'bool' => new Node\Expr\ConstFetch(new Node\Name('false')),
                        default => new Node\Scalar\String_(''),
                    };
                }
            }
        }
        if ($node instanceof Stmt\Const_) {
            foreach ($node->consts as $const) {
                $v = $const->value;
                if ($v instanceof Node\Expr\ConstFetch && $v->name->toString() === 'UNKNOWN') {
                    $const->value = new Node\Scalar\String_('');
                }
            }
        }
        return null;
    }

    /**
     * Stand-ins for the extension's read/write/has_property object handlers.
     *
     * @return list<Stmt\ClassMethod>
     */
    private static function magicAccessors(): array
    {
        $code = <<<'PHP'
            <?php
            class X {
                /** GObject property read (engine handler; see gen/ide-stub.php). */
                public function __get(string $name): mixed { unset($name); return null; }
                /** GObject property write (engine handler; see gen/ide-stub.php). */
                public function __set(string $name, mixed $value): void { unset($name); unset($value); }
                /** GObject property isset (engine handler; see gen/ide-stub.php). */
                public function __isset(string $name): bool { unset($name); return false; }
            }
            PHP;
        $ast = new ParserFactory()->createForNewestSupportedVersion()->parse($code);
        /** @var Stmt\Class_ $class */
        $class = $ast[0] ?? throw new \RuntimeException('magic accessor template did not parse');
        /** @var list<Stmt\ClassMethod> */
        return $class->stmts;
    }
});
$ast = $traverser->traverse($ast);

$printer = new class extends Standard {
    // php-parser drops the file docblock; keep our own header instead.
};
$header = <<<'PHP'
    <?php

    /**
     * php-gtk4 IDE stubs - GENERATED by gen/ide-stub.php from src/gtk4.stub.php.
     * Do not edit; edit src/gtk4.stub.php and run `./ci.sh --only=stubs --fix`.
     *
     * Declarations only, never executed. Bodies contain a dummy `unset()` of
     * each parameter and a placeholder `return` so IDE analysers stay quiet.
     */

    PHP;
// The pretty printer emits a fresh "<?php" prefix; replace it with our header.
$out = $printer->prettyPrintFile($ast);
$out = $header . (preg_replace('/^<\?php\s*/', '', $out) ?? '') . "\n";
// Drop the .stub.php file docblock (ours replaces it) and gen_stub's annotations.
$out = preg_replace('#^/\*\*\n \* php-gtk4 API declaration.*?\*/\n#ms', '', $out, 1) ?? '';
$out = preg_replace(
    '/^ \* @(generate-class-entries|generate-legacy-arginfo|not-serializable|cvalue)\b.*\n/m',
    '',
    $out,
) ?? '';

if (in_array('--check', $argv, true)) {
    $current = file_get_contents($target);
    if ($current !== $out) {
        fwrite(STDERR, "$target is out of date - run: php gen/ide-stub.php\n");
        exit(1);
    }
    echo "stubs/gtk4.php is up to date\n";
    exit(0);
}
file_put_contents($target, $out);
echo "wrote $target\n";
