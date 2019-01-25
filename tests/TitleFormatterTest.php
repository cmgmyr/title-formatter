<?php

namespace Cmgmyr\TitleFormatter\Test;

use Cmgmyr\TitleFormatter\TitleFormatter;

class TitleFormatterTest extends TestCase
{
    /**
     * @test
     * @dataProvider titleProvider
     * @param string $initial
     * @param string $expected
     */
    public function it_should_format_titles_correctly($initial, $expected)
    {
        $this->assertEquals($expected, TitleFormatter::titleCase($initial));
    }

    public function titleProvider()
    {
        return [
            [
                // tests single sentence with mid-word special character
                'this sh*t is a test',
                'This Sh*t is a Test',
            ],
            [
                // tests multiple sentences
                'is this sh*t is a test. for sure!!!',
                'Is This Sh*t is a Test. For Sure!!!',
            ],
            [
                // tests sentences in brackets
                'is this sh*t is a test. for sure. [yikes as example]',
                'Is This Sh*t is a Test. For Sure. [Yikes as Example]',
            ],
            [
                // tests dashed words
                'this should be a super-awesome post! cool? not-so-much!',
                'This Should Be a Super-Awesome Post! Cool? Not-So-Much!',
            ],
            [
                // tests sentence with extra spaces
                '   this    should be interesting    ',
                'This Should Be Interesting',
            ],
            [
                // tests simple sentence
                'very simple sentence',
                'Very Simple Sentence',
            ],
            [
                // tests to make sure last word is capitalized
                'very simple sentence of',
                'Very Simple Sentence Of',
            ],
            [
                // tests words that have capital letters, they should be ignored
                'i think eBay is the greatest site! also, McCormick has the best spices!',
                'I Think eBay is the Greatest Site! Also, McCormick Has the Best Spices!',
            ],
            [
                // tests words with multiple punctuation prefixes
                'this post is $$$money',
                'This Post is $$$Money',
            ],
            [
                // tests for all CAPS words, they should be ignored
                'i really like playing with LEGOS, they are a lot of fun!',
                'I Really Like Playing With LEGOS, They Are a Lot of Fun!',
            ],
            [
                // tests for a bug in first words with apostrophes, should not capitalize second letter
                'it\'s really- something, isn\'t it?',
                'It\'s Really- Something, Isn\'t It?',
            ],
            [
                // tests for a bug with a dash separator
                'test - jet fighters',
                'Test - Jet Fighters',
            ],
            [
                // tests for a bug with multiple separators
                'test --- jet-- fighters - test ==== testtest',
                'Test --- Jet-- Fighters - Test ==== Testtest',
            ],
            [
                // tests numbers only
                '1234 567',
                '1234 567',
            ],
            [
                // tests colon
                'this is a test: cool, huh?',
                'This is a Test: Cool, Huh?',
            ],
            [
                // tests for a bug with empty title
                '',
                '',
            ],
            [
                // tests for a bug with null title
                null,
                '',
            ],
            [
                // tests for a bug with all spaces
                '     ',
                '',
            ],
            [
                // tests for a bug with all spaces, one word
                '     test',
                'Test',
            ],
            [
                // tests one word
                'test',
                'Test',
            ],
            [
                // tests for a bug with special characters
                'get up and dance — 7 reasons why you should be… moving your feet to the beat',
                'Get Up and Dance — 7 Reasons Why You Should Be… Moving Your Feet to the Beat',
            ],
        ];
    }
}
