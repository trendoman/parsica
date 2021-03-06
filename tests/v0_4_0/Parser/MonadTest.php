<?php declare(strict_types=1);
/**
 * This file is part of the Parsica library.
 *
 * Copyright (c) 2020 Mathias Verraes <mathias@verraes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Verraes\Parsica\v0_4_0\Parser;

use PHPUnit\Framework\TestCase;
use Verraes\Parsica\PHPUnit\ParserAssertions;
use function Verraes\Parsica\anySingle;
use function Verraes\Parsica\bind;
use function Verraes\Parsica\char;
use function Verraes\Parsica\pure;
use function Verraes\Parsica\sequence;

final class MonadTest extends TestCase
{
    use ParserAssertions;

    /** @test */
    public function bind()
    {
        // This parser checks if the second character is the same as the first, by taking the output of the first
        // parser and binding it to a function that produces the second parser from that output.
        $parser = anySingle()->bind(fn(string $c) => char($c));
        $this->assertParse("a", $parser, "aa");
        $this->assertParse("b", $parser, "bb");
        $this->assertNotParse($parser, "ab");

        $parser = bind(anySingle(), fn(string $c) => char($c));
        $this->assertParse("a", $parser, "aa");
        $this->assertParse("b", $parser, "bb");
        $this->assertNotParse($parser, "ab");
    }

    /** @test */
    public function bind_fails()
    {
        // If the first parser fails, bind() returns the first one.
        $parser = char('x')->bind(fn(string $c) => char($c));
        $this->assertParse("x", $parser, "xx");
        $this->assertNotParse($parser, "yx");
    }

    /** @test */
    public function sequence()
    {
        $parser = char('a')->sequence(char('b'));
        $this->assertParse("b", $parser, "ab");
        $this->assertNotParse($parser, "aa");

        $parser = sequence(char('a'), char('b'));
        $this->assertParse("b", $parser, "ab");
        $this->assertNotParse($parser, "aa");
    }

    /** @test */
    public function pure()
    {
        $parser = pure("hi");
        $this->assertParse("hi", $parser, "something else");

    }


}
