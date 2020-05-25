<?php declare(strict_types=1);

namespace Mathias\ParserCombinators;

use Mathias\ParserCombinators\Infra\Parser;
use Mathias\ParserCombinators\Infra\ParseResult;
use Mathias\ParserCombinators\Infra\Str;
use Webmozart\Assert\Assert;
use function Mathias\ParserCombinators\parser;

/**
 * Parse a single character
 */
function char(string $char): Parser
{
    Assert::length($char, 1, "char() expects a single character. Use string() if you want longer strings");
    return parser(
        function (string $input) use ($char): ParseResult {
            if ((strlen($input) === 0)) return fail("char($char), got EOF");
            return (Str::head($input) === $char)
                ? succeed($char, Str::tail($input))
                : fail("char($char)");
        }
    );
}

/**
 * Parse a non-empty string
 */
function string(string $str): Parser
{
    Assert::minLength($str, 1);
    $len = strlen($str);
    return parser(
        fn(string $input): ParseResult => substr($input, 0, $len) === $str
            ? succeed($str, substr($input, $len))
            : fail("string($str))")
    );
}

function space() : Parser
{
    return char(' ');
}