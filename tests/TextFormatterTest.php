<?php namespace Cmgmyr\TextFormatter\Test;

use Cmgmyr\TextFormatter\TextFormatter;

class TextFormatterTest extends TestCase
{
    protected $tests = [
        [
            // tests single sentence with mid-word special character
            'title' => 'this sh*t is a test',
            'correct' => 'This Sh*t is a Test'
        ],
        [
            // tests multiple sentences
            'title' => 'is this sh*t is a test. for sure!!!',
            'correct' => 'Is This Sh*t is a Test. For Sure!!!'
        ],
        [
            // tests sentences in brackets
            'title' => 'is this sh*t is a test. for sure. [yikes as example]',
            'correct' => 'Is This Sh*t is a Test. For Sure. [Yikes as Example]'
        ],
        [
            // tests dashed words
            'title' => 'this should be a super-awesome post! cool? not-so-much!',
            'correct' => 'This Should Be a Super-Awesome Post! Cool? Not-So-Much!'
        ],
        [
            // tests sentence with extra spaces
            'title' => '   this    should be interesting    ',
            'correct' => 'This Should Be Interesting'
        ],
        [
            // tests simple sentence
            'title' => 'very simple sentence',
            'correct' => 'Very Simple Sentence'
        ],
        [
            // tests to make sure last word is capitalized
            'title' => 'very simple sentence of',
            'correct' => 'Very Simple Sentence Of'
        ],
        [
            // tests words that have capital letters, they should be ignored
            'title' => 'i think eBay is the greatest site! also, McCormick has the best spices!',
            'correct' => 'I Think eBay is the Greatest Site! Also, McCormick Has the Best Spices!'
        ],
        [
            // tests words with multiple punctuation prefixes
            'title' => 'this post is $$$money',
            'correct' => 'This Post is $$$Money'
        ],
        [
            // tests for all CAPS words, they should be ignored
            'title' => 'i really like playing with LEGOS, they are a lot of fun!',
            'correct' => 'I Really Like Playing With LEGOS, They Are a Lot of Fun!'
        ],
        [
            // tests for a bug in first words with apostrophes, should not capitalize second letter
            'title' => 'it\'s really- something, isn\'t it?',
            'correct' => 'It\'s Really- Something, Isn\'t It?'
        ],
        [
            // tests for a bug with a dash separator
            'title' => 'test - jet fighters',
            'correct' => 'Test - Jet Fighters'
        ],
        [
            // tests for a bug with multiple separators
            'title' => 'test --- jet-- fighters - test ==== testtest',
            'correct' => 'Test --- Jet-- Fighters - Test ==== Testtest'
        ],
        [
            // tests numbers only
            'title' => '1234 567',
            'correct' => '1234 567'
        ],
        [
            // tests colon
            'title' => 'this is a test: cool, huh?',
            'correct' => 'This is a Test: Cool, Huh?'
        ],
        [
            // tests for a bug with empty title
            'title' => '',
            'correct' => ''
        ],
        [
            // tests for a bug with null title
            'title' => null,
            'correct' => ''
        ],
        [
            // tests for a bug with all spaces
            'title' => '     ',
            'correct' => ''
        ],
        [
            // tests for a bug with all spaces, one word
            'title' => '     test',
            'correct' => 'Test'
        ],
        [
            // tests one word
            'title' => 'test',
            'correct' => 'Test'
        ],
    ];

    /** @test */
    public function it_should_format_titles_correctly()
    {
        foreach ($this->tests as $test) {
            $newTitle = TextFormatter::titleCase($test['title']);

            $this->assertEquals($test['correct'], $newTitle);
        }
    }
}
