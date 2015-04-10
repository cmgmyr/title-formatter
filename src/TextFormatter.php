<?php namespace Cmgmyr\TextFormatter;

use Illuminate\Support\Collection;

class TextFormatter
{
    /**
     * The title that we need to format and return
     *
     * @var string
     */
    protected $title = '';

    /**
     * Collection of words generated from the original title
     *
     * @object Collection
     */
    protected $indexedWords;

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
     */
    public function __construct($title)
    {
        $this->setTitle($title);
        $this->splitWords();
    }

    /**
     * Converts the initial title to a correctly formatted one
     *
     * @return string
     */
    public function convertTitle()
    {
        foreach ($this->indexedWords as $index => $word) {
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
     * @return string
     */
    public static function titleCase($title)
    {
        // hack in order to keep static method call
        $obj = new TextFormatter($title);
        return $obj->convertTitle();
    }

    /**
     * Sets the title after cleaning up extra spaces
     *
     * @param string $title
     */
    protected function setTitle($title)
    {
        $this->title = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $title)));
    }

    /**
     * Creates an array of words from the title to be formatted
     */
    protected function splitWords()
    {
        $indexedWords = [];
        $offset = 0;

        $words = explode(' ', $this->title);
        foreach ($words as $word) {
            $indexedWords[$this->getWordIndex($word, $offset)] = strtolower($word);
            $offset += strlen($word) + 1; // plus space
        }

        $this->indexedWords = new Collection($indexedWords);
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
        $index = strpos($this->title, $word, $offset);
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
        return mb_strlen(substr($this->title, 0, $index), 'UTF-8');
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
            mb_substr($this->title, 0, $index, 'UTF-8') .
            $word .
            mb_substr($this->title, $index + mb_strlen($word, 'UTF-8'), mb_strlen($this->title, 'UTF-8'), 'UTF-8');
    }

    /**
     * Performs the uppercase action on the given word
     *
     * @param $word
     * @return string
     */
    protected function uppercaseWord($word)
    {
        // see if first character is special
        $first = mb_substr($word, 0, 1);
        if ($this->isPunctuation($first)) {
            $word = mb_substr($word, 1, -1);

            return $first . ucwords($word);
        }

        return ucwords($word);
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
            $this->isFirstWord($index) ||
            $this->isLastWord($word) ||
            $this->isFirstWordOfSentence($index) ||
            !$this->isIgnoredWord($word);
    }

    /**
     * Checks if the index is the first
     *
     * @param $index
     * @return bool
     */
    protected function isFirstWord($index)
    {
        return $index == 0;
    }

    /**
     * Checks to see if the word is the last word in the title
     *
     * @param $word
     * @return bool
     */
    protected function isLastWord($word)
    {
        if ($word === $this->indexedWords->last()) {
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
        $twoCharactersBack = mb_substr($this->title, $index - 2, 1);

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
        return preg_match("/\p{P}/u", $string);
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
}
