# Title Text Formatter

A simple to use text formatter, primarily used for page/blog titles.

## Install

Via Composer

	$ composer require spartz/text-formatter

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
