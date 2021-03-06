<?php declare(strict_types=1);
/**
 * This file is part of the Parsica library.
 *
 * Copyright (c) 2020 Mathias Verraes <mathias@verraes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Verraes\Parsica;

use Verraes\Parsica\Internal\Assert;

/**
 * Parse a single character.
 *
 * @param string $c A single character
 *
 * @return Parser<string>
 * @api
 * @see charI()
 *
 */
function char(string $c): Parser
{
    Assert::singleChar($c);
    return satisfy(isEqual($c))->label("char($c)");
}

/**
 * Parse a single character, case-insensitive and case-preserving. On success it returns the string cased as the
 * actually parsed input.
 *
 * eg charI('a'')->run("ABC") will succeed with "A", not "a".
 *
 * @param string $c A single character
 *
 * @return Parser<string>
 * @api
 *
 * @see char()
 */
function charI(string $c): Parser
{
    Assert::singleChar($c);
    return satisfy(orPred(isEqual(mb_strtolower($c)), isEqual(mb_strtoupper($c))))->label("charI($c)");
}


/**
 * Parse a control character (a non-printing character of the Latin-1 subset of Unicode).
 *
 * @return Parser<string>
 * @api
 */
function controlChar(): Parser
{
    return satisfy(isControl())->label("controlChar");
}

/**
 * Parse an uppercase character A-Z.
 *
 * @return Parser<string>
 * @api
 */
function upperChar(): Parser
{
    return satisfy(isUpper())->label("upperChar");
}

/**
 * Parse a lowercase character a-z.
 *
 * @return Parser<string>
 * @api
 */
function lowerChar(): Parser
{
    return satisfy(isLower())->label("lowerChar");
}

/**
 * Parse an uppercase or lowercase character A-Z, a-z.
 *
 * @return Parser<string>
 * @api
 */
function alphaChar(): Parser
{
    return satisfy(isAlpha())->label("alphaChar");
}

/**
 * Parse an alpha or numeric character A-Z, a-z, 0-9.
 *
 * @return Parser<string>
 * @api
 */
function alphaNumChar(): Parser
{
    return satisfy(isAlphaNum())->label("alphaNumChar");
}

/**
 * Parse a printable ASCII char.
 *
 * @return Parser<string>
 * @api
 */
function printChar(): Parser
{
    return satisfy(isPrintable())->label("printChar");
}

/**
 * Parse a single punctuation character !"#$%&'()*+,-./:;<=>?@[\]^_`{|}~
 *
 * @return Parser<string>
 * @api
 */
function punctuationChar(): Parser
{
    return satisfy(isPunctuation())->label("punctuationChar");
}


/**
 * Parse 0-9. Returns the digit as a string. Use ->map('intval')
 * or similar to cast it to a numeric type.
 *
 * @return Parser<string>
 * @api
 */
function digitChar(): Parser
{
    return satisfy(isDigit())->label('digit');
}

/**
 * Parse a binary character 0 or 1.
 *
 * @return Parser<string>
 * @api
 */
function binDigitChar(): Parser
{
    return satisfy(isCharCode([0x30, 0x31]))->label("binDigitChar");
}

/**
 * Parse an octodecimal character 0-7.
 *
 * @return Parser<string>
 *
 * @api
 */
function octDigitChar(): Parser
{
    return satisfy(isCharCode(range(0x30, 0x37)))->label("octDigitChar");
}

/**
 * Parse a hexadecimal numeric character 0123456789abcdefABCDEF.
 *
 * @return Parser<string>
 * @api
 */
function hexDigitChar(): Parser
{
    return satisfy(isHexDigit())->label("hexDigitChar");
}
