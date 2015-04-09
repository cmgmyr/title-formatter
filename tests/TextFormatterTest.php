<?php namespace Cmgmyr\TextFormatter\Test;

use Cmgmyr\TextFormatter\TextFormatter;

class TextFormatterTest extends TestCase
{
    /** @test */
    public function it_should_format_single_sentence()
    {
        $startingTitle = "is this sh*t is a test";
        $endingTitle = TextFormatter::titleCase($startingTitle);
        $correctTitle = "Is This Sh*t is a Test";

        $this->assertEquals($correctTitle, $endingTitle);
    }

    /** @test */
    public function it_should_format_multiple_sentences()
    {
        $startingTitle = "is this sh*t is a test. for sure!!!";
        $endingTitle = TextFormatter::titleCase($startingTitle);
        $correctTitle = "Is This Sh*t is a Test. For Sure!!!";

        $this->assertEquals($correctTitle, $endingTitle);
    }

    /** @test */
    public function it_should_format_multiple_sentences_with_brackets()
    {
        $startingTitle = "is this sh*t is a test. for sure. [yikes as example]";
        $endingTitle = TextFormatter::titleCase($startingTitle);
        $correctTitle = "Is This Sh*t is a Test. For Sure. [Yikes as Example]";

        $this->assertEquals($correctTitle, $endingTitle);
    }

    /** @test */
    public function it_should_format_dashed_word()
    {
        $startingTitle = "this should be a super-awesome post!";
        $endingTitle = TextFormatter::titleCase($startingTitle);
        $correctTitle = "This Should Be a Super-awesome Post!";

        $this->assertEquals($correctTitle, $endingTitle);
    }

    /** @test */
    public function it_should_format_multiple_spaces()
    {
        $startingTitle = "this    should be interesting";
        $endingTitle = TextFormatter::titleCase($startingTitle);
        $correctTitle = "This Should Be Interesting";

        $this->assertEquals($correctTitle, $endingTitle);
    }

    /** @test */
    public function it_should_capitalize_the_first_word()
    {
        $startingTitle = "very simple sentence";
        $endingTitle = TextFormatter::titleCase($startingTitle);
        $correctTitle = "Very Simple Sentence";

        $this->assertEquals($correctTitle, $endingTitle);
    }

    /** @test */
    public function it_should_capitalize_the_last_word()
    {
        $startingTitle = "very simple sentence of";
        $endingTitle = TextFormatter::titleCase($startingTitle);
        $correctTitle = "Very Simple Sentence Of";

        $this->assertEquals($correctTitle, $endingTitle);
    }
}
