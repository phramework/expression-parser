# expression-parser
LISP like expression parser for phramework
 [![Build Status](https://travis-ci.org/phramework/expression-parser.svg?branch=master)](https://travis-ci.org/phramework/expression-parser) [![Coverage Status](https://coveralls.io/repos/github/phramework/expression-parser/badge.svg?branch=master)](https://coveralls.io/github/phramework/expression-parser?branch=master)

## Requirements 
- php 7.0 or newer
- [composer](https://getcomposer.org/)

## Usage

Require package using composer 
```bash
composer require phramework/expression-parser
```

### Example

```php
/**
 * Define a language of functions
 */
$language = (new Language())
    /*
     * Define 'or' function
     */
    ->set(
        'or',
        function(bool $l, bool $r) : bool {
            return $l || $r;
        }
    );

/**
 * Create a parser based on a language
 */
$parser = new ExpressionParser(
    $language
);

/*
 * Evaluate an expression, expect result to be true
 */
$result = $parser->evaluate([
    'or',
    true,
    false,
]);
```

```php
<?php
/**
 * Define a language of functions
 */
$language = (new Language())
    /*
     * Define '+' function
     */
    ->set(
        '+',
        function(float $l, float $r) : bool {
            return $l || $r;
        }
    );

/**
 * Create a parser based on a language
 */
$parser = new ExpressionParser(
    $language
);

/*
 * Evaluate expression, expect result to be true
 */
$result = $parser->evaluate([
    'or',
    true,
    false,
]);
```

A more complex

```php
<?php
/**
 * Define a language of functions
 */
$language = (new Language())
    /*
     * Define '+' function
     */
    ->set(
        '+',
        function(float $l, float $r) : float {
            return $l + $r;
        }
    )
    /*
     * Define '+' function
     */
    ->set(
        '-',
        function(float $l, float $r) : float {
            return $l - $r;
        }
    );

/**
 * Create a parser based on a language
 */
$parser = new ExpressionParser(
    $language
);

/*
 * Evaluate expression, expect result to be 5 + 4.5 - 1 = 8.5
 */
$result = $parser->evaluate([
    '+',
    5,
    [
        '-',
        4.5,
        1
    ],
]);
```

## Development

To install composer dependencies:
```bash
composer update
```

To run the tests:
```bash
composer test
```
