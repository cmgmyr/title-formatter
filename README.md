# Text Formatter

A simple to use text formatter, primarily used for page/blog titles.

## Install

Via Composer

``` bash
$ composer require cmgmyr/text-formatter
```

## Usage

``` php
$title = "this is a messy title. [can you fix it?]";
$title = TextFormatter::titleCase($title);
echo $title; // This is a Messy Title. [Can You Fix It?]
```

## Testing

``` bash
$ phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
