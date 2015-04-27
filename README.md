[![Build Status](https://img.shields.io/travis/SpartzInc/text-formatter.svg?style=flat-square)](https://travis-ci.org/SpartzInc/text-formatter)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/SpartzInc/text-formatter.svg?style=flat-square)](https://scrutinizer-ci.com/g/SpartzInc/text-formatter/code-structure/)
[![Code Quality](https://img.shields.io/scrutinizer/g/SpartzInc/text-formatter.svg?style=flat-square)](https://scrutinizer-ci.com/g/SpartzInc/text-formatter/)
[![Code Climate](https://img.shields.io/codeclimate/github/SpartzInc/text-formatter.svg?style=flat-square)](https://codeclimate.com/github/SpartzInc/text-formatter)
[![Latest Version](https://img.shields.io/github/release/SpartzInc/text-formatter.svg?style=flat-square)](https://github.com/SpartzInc/text-formatter/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

# Title Text Formatter

A simple to use text formatter, primarily used for page/blog titles.

## Install

Via Composer

    "require": {
        "spartz/text-formatter": "~1.0"
    }

## Usage

	use Spartz\TextFormatter\TextFormatter;
	
	$title = "this is a messy title. [can you fix it?]";
	$title = TextFormatter::titleCase($title);
	echo $title; // This is a Messy Title. [Can You Fix It?]
	
## Formatting "Rules"

1. First word in a sentence is capitalized
2. Last word in a sentence is capitalized
3. Words within brackets (or similar) are capitalized, similar to rules #1 & #2
4. Words within the `$ignoredWords` array should not be capitalized as long as it doesn't conflict with rules #1-#3
5. Words preceded by multiple special characters should be capitalized: $$$Money
6. All dashed words should be capitalized: Super-Awesome-Post
7. Ignore words that already include at least one uppercase letter. We'll assume that the author knows what they're doing: eBay, iPad, McCormick, etc

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email [Chris Gmyr](mailto:cgmyr@spartzinc.com) instead of using the issue tracker.

## Credits

- [Chris Gmyr](https://github.com/cmgmyr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
