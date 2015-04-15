<?php namespace Cmgmyr\TextFormatter;

/**
 * Class TextFormatter
 *
 * Rules:
 *  1. First word in a sentence is capitalized
 *  2. Last word in a sentence is capitalized
 *  3. Words within brackets (or similar) are capitalized, similar to rules #1 & #2
 *  4. Words within the $ignoredWords array should not be capitalized as long as it
 *      doesn't conflict with rules #1-#3
 *  5. Words preceded by multiple special characters should be capitalized: $$$Money
 *  6. All dashed words should be capitalized: Super-Awesome-Post
 *  7. Ignore words that already include at least one uppercase letter. We'll assume
 *      that the author knows what they're doing: eBay, iPad, McCormick, etc
 */
class TextFormatter
{
    /**
     * The title that we need to format and return
     *
     * @var string|null
     */
    protected $title = null;

    /**
     * The separator character for in between words
     *
     * @var string
     */
    protected $separator = ' ';

    /**
     * Encoding used for mb_ functions
     *
     * @var string
     */
    protected $encoding = 'UTF-8';

    /**
     * Collection of words generated from the original title
     *
     * @var array
     */
    protected $indexedWords = [];

    /**
     * Words that should be ignored from capitalization
     *
     * @var array
     */
    protected $ignoredWords = [
        'a',
        'an',
        'and',
        'as',
        'at',
        'but',
        'by',
        'en',
        'for',
        'if',
        'in',
        'is',
        'of',
        'on',
        'or',
        'to',
        'the',
        'vs',
        'via'
    ];

    /**
     * Construct, just needs the title to get going
     *
     * @param string $title
     * @param string $separator
     */
    public function __construct($title, $separator = ' ')
    {
        $this->setTitle($title);
        $this->separator = $separator;
    }

    /**
     * Converts the initial title to a correctly formatted one
     *
     * @return string
     */
    public function convertTitle()
    {
        if ($this->title === null) {
            return '';
        }

        foreach ($this->splitWords() as $index => $word) {
            if ($this->wordShouldBeUppercase($index, $word)) {
                $this->rebuildTitle($index, $this->uppercaseWord($word));
            }
        }

        return $this->title;
    }

    /**
     * Returns the newly formatted title
     *
     * @param string $title
     * @param string $separator
     * @return string
     */
    public static function titleCase($title, $separator = ' ')
    {
        // hack in order to keep static method call
        $obj = new TextFormatter($title, $separator);
        return $obj->convertTitle();
    }

    /**
     * Sets the title after cleaning up extra spaces
     *
     * @param string $title
     */
    protected function setTitle($title)
    {
        $title = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $title)));

        if ($title != '') {
            $this->title = $title;
        }
    }

    /**
     * Creates an array of words from the title to be formatted
     */
    protected function splitWords()
    {
        $indexedWords = [];
        $offset = 0;

        $words = explode($this->separator, $this->title);
        foreach ($words as $word) {
            $wordIndex = $this->getWordIndex($word, $offset);

            if ($this->hasDash($word)) {
                $word = TextFormatter::titleCase($word, '-');
                $this->rebuildTitle($wordIndex, $word);
            }

            $indexedWords[$wordIndex] = $word;
            $offset += mb_strlen($word, $this->encoding) + 1; // plus space
        }

        return $this->indexedWords = $indexedWords;
    }

    /**
     * Finds the correct index of the word within the title
     *
     * @param $word
     * @param $offset
     * @return int
     */
    protected function getWordIndex($word, $offset)
    {
        $index = mb_strpos($this->title, $word, $offset, $this->encoding);
        return $this->correctIndexOffset($index);
    }

    /**
     * Corrects the potential offset issue with some UTF-8 characters
     *
     * @param $index
     * @return int
     */
    protected function correctIndexOffset($index)
    {
        return mb_strlen(substr($this->title, 0, $index), $this->encoding);
    }

    /**
     * Replaces a formatted word within the current title
     *
     * @param int $index
     * @param string $word
     */
    protected function rebuildTitle($index, $word)
    {
        $this->title =
            mb_substr($this->title, 0, $index, $this->encoding) .
            $word .
            mb_substr(
                $this->title,
                $index + mb_strlen($word, $this->encoding),
                mb_strlen($this->title, $this->encoding),
                $this->encoding
            );
    }

    /**
     * Performs the uppercase action on the given word
     *
     * @param $word
     * @return string
     */
    protected function uppercaseWord($word)
    {
        // see if first characters are special
        $prefix = '';
        $hasPunctuation = true;
        do {
            $first = mb_substr($word, 0, 1, $this->encoding);
            if ($this->isPunctuation($first)) {
                $prefix .= $first;
                $word = mb_substr($word, 1, -1, $this->encoding);
            } else {
                $hasPunctuation = false;
            }
        } while ($hasPunctuation == true);

        return $prefix . ucwords($word);
    }

    /**
     * Condition to see if the given word should be uppercase
     *
     * @param $index
     * @param $word
     * @return bool
     */
    protected function wordShouldBeUppercase($index, $word)
    {
        return
            (
                $this->isFirstWordOfSentence($index) ||
                $this->isLastWord($word) ||
                !$this->isIgnoredWord($word)
            ) &&
            (
                !$this->hasUppercaseLetter($word)
            );
    }

    /**
     * Checks to see if the word is the last word in the title
     *
     * @param $word
     * @return bool
     */
    protected function isLastWord($word)
    {
        if ($word === end($this->indexedWords)) {
            return true;
        }

        return false;
    }

    /**
     * Checks to see if the word the start of a new sentence
     *
     * @param $index
     * @return bool
     */
    protected function isFirstWordOfSentence($index)
    {
        if ($index == 0) {
            return true;
        }

        $twoCharactersBack = mb_substr($this->title, $index - 2, 1, $this->encoding);

        if ($this->isPunctuation($twoCharactersBack)) {
            return true;
        }

        return false;
    }

    /**
     * Checks to see if the given string is a punctuation character
     *
     * @param $string
     * @return int
     */
    protected function isPunctuation($string)
    {
        return preg_match("/[\p{P}\p{S}]/u", $string);
    }

    /**
     * Checks if the given word should be ignored
     *
     * @param $word
     * @return bool
     */
    protected function isIgnoredWord($word)
    {
        return in_array($word, $this->ignoredWords);
    }

    /**
     * Checks to see if a word has an uppercase letter
     *
     * @param $word
     * @return int
     */
    protected function hasUppercaseLetter($word)
    {
        return preg_match("/[A-Z]/", $word);
    }

    /**
     * Checks to see if the word has a dash
     *
     * @param $word
     * @return int
     */
    protected function hasDash($word)
    {
        return preg_match("/\-/", $word) && mb_strlen($word, $this->encoding) > 1;
    }
}
