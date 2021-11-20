<?php

namespace Cmgmyr\TitleFormatter;

class TitleFormatter
{
    /**
     * The title that we need to format and return.
     */
    protected ?string $title = null;

    /**
     * The separator character for in between words.
     */
    protected string $separator = ' ';

    /**
     * Encoding used for mb_ functions.
     */
    protected string $encoding = 'UTF-8';

    /**
     * Collection of words generated from the original title.
     */
    protected array $indexedWords = [];

    /**
     * Words that should be ignored from capitalization.
     */
    protected array $ignoredWords = [
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
        'via',
    ];

    private function __construct(?string $title = null, string $separator = ' ')
    {
        $this->setTitle($title);
        $this->separator = $separator;
    }

    /**
     * Converts the initial title to a correctly formatted one.
     */
    public function convertTitle(): string
    {
        if ($this->title === null) {
            return '';
        }

        foreach ($this->splitWords() as $index => $word) {
            if ($this->wordShouldBeUppercase($index, $word)) {
                $this->rebuildTitle($index, $this->uppercaseWord($word));
            }
        }

        return $this->title ?? '';
    }

    /**
     * Returns the newly formatted title.
     */
    public static function titleCase(?string $title = null, string $separator = ' '): string
    {
        return (new self($title, $separator))->convertTitle();
    }

    /**
     * Sets the title after cleaning up extra spaces.
     */
    protected function setTitle(?string $title = null): void
    {
        $title = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", ' ', $title)));

        if ($title !== '') {
            $this->title = $title;
        }
    }

    /**
     * Creates an array of words from the title to be formatted.
     */
    protected function splitWords(): array
    {
        $indexedWords = [];
        $offset = 0;

        foreach (explode($this->separator, $this->title) as $word) {
            if (mb_strlen($word, $this->encoding) === 0) {
                continue;
            }

            $wordIndex = $this->getWordIndex($word, $offset);

            if ($this->hasDash($word)) {
                $word = self::titleCase($word, '-');
                $this->rebuildTitle($wordIndex, $word);
            }

            $indexedWords[$wordIndex] = $word;
            $offset += mb_strlen($word, $this->encoding) + 1; // plus space
        }

        return $this->indexedWords = $indexedWords;
    }

    /**
     * Finds the correct index of the word within the title.
     */
    protected function getWordIndex(string $word, int $offset): int
    {
        $index = mb_strpos($this->title, $word, $offset, $this->encoding);

        return $this->correctIndexOffset($index);
    }

    /**
     * Corrects the potential offset issue with some UTF-8 characters.
     */
    protected function correctIndexOffset(?int $index): int
    {
        return mb_strlen(mb_substr($this->title, 0, $index, $this->encoding), $this->encoding);
    }

    /**
     * Replaces a formatted word within the current title.
     */
    protected function rebuildTitle(int $index, string $word): void
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
     * Performs the uppercase action on the given word.
     */
    protected function uppercaseWord(string $word): string
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
        } while ($hasPunctuation === true);

        return $prefix . ucwords($word);
    }

    /**
     * Condition to see if the given word should be uppercase.
     */
    protected function wordShouldBeUppercase(int $index, string $word): bool
    {
        return
            (
                $this->isFirstWordOfSentence($index) ||
                $this->isLastWord($word) ||
                ! $this->isIgnoredWord($word)
            ) &&
            (
                ! $this->hasUppercaseLetter($word)
            );
    }

    /**
     * Checks to see if the word is the last word in the title.
     */
    protected function isLastWord(string $word): bool
    {
        if ($word === end($this->indexedWords)) {
            return true;
        }

        return false;
    }

    /**
     * Checks to see if the word is the start of a new sentence.
     */
    protected function isFirstWordOfSentence(int $index): bool
    {
        if ($index === 0) {
            return true;
        }

        $twoCharactersBack = mb_substr($this->title, $index - 2, 1, $this->encoding);

        if ($this->isPunctuation($twoCharactersBack)) {
            return true;
        }

        return false;
    }

    /**
     * Checks to see if the given string is a punctuation character.
     */
    protected function isPunctuation(string $string): int
    {
        return preg_match("/[\p{P}\p{S}]/u", $string);
    }

    /**
     * Checks if the given word should be ignored.
     */
    protected function isIgnoredWord(string $word): bool
    {
        return in_array($word, $this->ignoredWords, true);
    }

    /**
     * Checks to see if a word has an uppercase letter.
     */
    protected function hasUppercaseLetter(string $word): int
    {
        return preg_match('/[A-Z]/', $word);
    }

    /**
     * Checks to see if the word has a dash.
     */
    protected function hasDash(string $word): bool
    {
        $wordWithoutDashes = str_replace('-', '', $word);

        return (bool) preg_match("/\-/", $word) && mb_strlen($wordWithoutDashes, $this->encoding) > 1;
    }
}
