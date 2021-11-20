[![Tests](https://github.com/cmgmyr/title-formatter/actions/workflows/run-tests.yml/badge.svg)](https://github.com/cmgmyr/title-formatter/actions/workflows/run-tests.yml)
[![Latest Version](https://img.shields.io/github/release/cmgmyr/title-formatter.svg)](https://github.com/cmgmyr/title-formatter/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/cmgmyr/title-formatter.svg)](https://packagist.org/packages/cmgmyr/title-formatter)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)

# Title Text Formatter

A simple to use text formatter, primarily used for page/blog titles.

## Install

Via Composer

```
"require": {
    "cmgmyr/title-formatter": "~2.0"
}
```

## Usage

```php
use Cmgmyr\TitleFormatter\TitleFormatter;

$title = "this is a messy title. [can you fix it?]";
$title = TitleFormatter::titleCase($title);
echo $title; // This is a Messy Title. [Can You Fix It?]
```

## Formatting "Rules"

1. First word in a sentence is capitalized
2. Last word in a sentence is capitalized
3. Words within brackets (or similar) are capitalized, similar to rules #1 & #2
4. Words within the `$ignoredWords` array should not be capitalized as long as it doesn't conflict with rules #1-#3
5. Words preceded by multiple special characters should be capitalized: $$$Money
6. All dashed words should be capitalized: Super-Awesome-Post
7. Ignore words that already include at least one uppercase letter. We'll assume that the author knows what they're doing: eBay, iPad, McCormick, etc

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Chris Gmyr](https://github.com/cmgmyr)
- [All Contributors](../../contributors)
