<?php

namespace Cmgmyr\TitleFormatter\Tests;

use Cmgmyr\TitleFormatter\TitleFormatter;
use PHPUnit\Framework\TestCase;

class TitleFormatterTest extends TestCase
{
    /**
     * @test
     * @dataProvider titleProvider
     */
    public function it_should_format_titles_correctly(?string $initial, string $expected): void
    {
        $this->assertEquals($expected, TitleFormatter::titleCase($initial));
    }

    public function titleProvider(): iterable
    {
        yield 'single sentence with mid-word special character' => [
            'initial' => 'this sh*t is a test',
            'expected' => 'This Sh*t is a Test',
        ];

        yield 'multiple sentences' => [
            'initial' => 'is this sh*t is a test. for sure!!!',
            'expected' => 'Is This Sh*t is a Test. For Sure!!!',
        ];

        yield 'sentences in brackets' => [
            'initial' => 'is this sh*t is a test. for sure. [yikes as example]',
            'expected' => 'Is This Sh*t is a Test. For Sure. [Yikes as Example]',
        ];

        yield 'dashed words' => [
            'initial' => 'this should be a super-awesome post! cool? not-so-much!',
            'expected' => 'This Should Be a Super-Awesome Post! Cool? Not-So-Much!',
        ];

        yield 'sentence with extra spaces' => [
            'initial' => '   this    should be interesting    ',
            'expected' => 'This Should Be Interesting',
        ];

        yield 'simple sentence' => [
            'initial' => 'very simple sentence',
            'expected' => 'Very Simple Sentence',
        ];

        yield 'make sure last word is capitalized' => [
            'initial' => 'very simple sentence of',
            'expected' => 'Very Simple Sentence Of',
        ];

        yield 'words that have capital letters, they should be ignored' => [
            'initial' => 'i think eBay is the greatest site! also, McCormick has the best spices!',
            'expected' => 'I Think eBay is the Greatest Site! Also, McCormick Has the Best Spices!',
        ];

        yield 'words with multiple punctuation prefixes' => [
            'initial' => 'this post is $$$money',
            'expected' => 'This Post is $$$Money',
        ];

        yield 'all CAPS words should be ignored' => [
            'initial' => 'i really like playing with LEGOS, they are a lot of fun!',
            'expected' => 'I Really Like Playing With LEGOS, They Are a Lot of Fun!',
        ];

        yield 'bug in first words with apostrophes, should not capitalize second letter' => [
            'initial' => 'it\'s really- something, isn\'t it?',
            'expected' => 'It\'s Really- Something, Isn\'t It?',
        ];

        yield 'bug with a dash separator' => [
            'initial' => 'test - jet fighters',
            'expected' => 'Test - Jet Fighters',
        ];

        yield 'bug with multiple separators' => [
            'initial' => 'test --- jet-- fighters - test ==== testtest',
            'expected' => 'Test --- Jet-- Fighters - Test ==== Testtest',
        ];

        yield 'numbers only' => [
            'initial' => '1234 567',
            'expected' => '1234 567',
        ];

        yield 'colon' => [
            'initial' => 'this is a test: cool, huh?',
            'expected' => 'This is a Test: Cool, Huh?',
        ];

        yield 'bug with empty title' => [
            'initial' => '',
            'expected' => '',
        ];

        yield 'bug with null title' => [
            'initial' => null,
            'expected' => '',
        ];

        yield 'bug with all spaces' => [
            'initial' => '     ',
            'expected' => '',
        ];

        yield 'bug with all spaces, one word' => [
            'initial' => '     test',
            'expected' => 'Test',
        ];

        yield 'one word' => [
            'initial' => 'test',
            'expected' => 'Test',
        ];

        yield 'bug with special characters' => [
            'initial' => 'get up and dance — 7 reasons why you should be… moving your feet to the beat',
            'expected' => 'Get Up and Dance — 7 Reasons Why You Should Be… Moving Your Feet to the Beat',
        ];
    }
}
