<?php declare(strict_types=1);

namespace Mathias\ParserCombinator;

use Mathias\ParserCombinator\ParseResult\ParseResult;
use function Mathias\ParserCombinator\ParseResult\{parser, succeed, fail};

/**
 * @template T
 */
final class Parser
{
    /**
     * @var callable(string):ParseResult<T> $parser
     */
    private $parser;

    /**
     * @param callable(string):ParseResult<T> $parser
     */
    function __construct(callable $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Run the parser on an input
     *
     * @PSALMTODO return ParseResult<T>
     */
    public function run(string $input): ParseResult
    {
        $f = $this->parser;
        return $f($input);
    }

    /**
     * Label a parser. When a parser fails, instead of a generrated error message, you'll see your label.
     * eg (char(':')->followedBy(char(')')).followedBy(char(')')).
     */
    public function label(string $label): Parser
    {
        return parser(function (string $input) use ($label) : ParseResult {
            $result = $this->run($input);
            return ($result->isSuccess())
                ? $result
                : fail($label, $input);
        });
    }

    /**
     * @deprecated 0.2
     * @see ignore()
     */
    public function ignore(): Parser
    {
        return $this->into1(fn(string $_) => "");
    }

    /**
     * @deprecated 0.2
     * @see optional()
     */
    public function optional(): Parser
    {
        return parser(function (string $input): ParseResult {
            $r1 = $this->run($input);
            if ($r1->isSuccess()) {
                return $r1;
            } else {
                return succeed("", $input);
            }
        });
    }

    /**
     * @deprecated 0.2
     * @see seq()
     */
    public function followedBy(Parser $second): Parser
    {
        return parser(function (string $input) use ($second) : ParseResult {
            $r1 = $this->run($input);
            if ($r1->isSuccess()) {
                $r2 = $second->run($r1->remaining());
                if ($r2->isSuccess()) {
                    return succeed($r1->parsed() . $r2->parsed(), $r2->remaining());
                }
                return fail("seq ({$r1->parsed()} {$r2->expected()})", "@TODO");
            }
            return fail("seq ({$r1->expected()} ...)", "@TODO");
        });

    }

    /**
     * @deprecated 0.2
     * @see either()
     */
    public function or(Parser $second): Parser
    {
        return parser(function (string $input) use ($second) : ParseResult {
            $r1 = $this->run($input);
            if ($r1->isSuccess()) {
                return $r1;
            }

            $r2 = $second->run($input);
            if ($r2->isSuccess()) {
                return $r2;
            }

            $expectation = "either ({$r1->expected()} or {$r2->expected()})";
            return fail($expectation, "@TODO");
        });
    }

    /**
     * @param callable(T):T2 $transform
     *
     * @return Parser<T2>
     * @deprecated 0.2
     * @see into1()
     *
     * @template T2
     */
    public function into1(callable $transform): Parser
    {
        return into1($this, $transform);
    }

    /**
     * @param class-string<T2> $className
     *
     * @return Parser<T2>
     * @template T2
     * @see intoNew1()
     *
     * @deprecated 0.2
     */
    public function intoNew1(string $className): Parser
    {
        return $this->into1(
            fn(string $val) => new $className($val)
        );
    }
}