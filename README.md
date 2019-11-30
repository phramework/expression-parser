# expression-parser
Α "LISP like" expression parser for evaluating expressions
 [![Build Status](https://travis-ci.org/phramework/expression-parser.svg?branch=master)](https://travis-ci.org/phramework/expression-parser) [![Coverage Status](https://coveralls.io/repos/github/phramework/expression-parser/badge.svg?branch=master)](https://coveralls.io/github/phramework/expression-parser?branch=master)

## Requirements 
- php 7.0 or newer
- [composer](https://getcomposer.org/)

## Install

Require package using composer 
```bash
composer require phramework/expression-parser
```

## Usage examples

### Define a simple language

```php
<?php
/*
 * Define a language
 * with a Boolean function "or"
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

/*
 * Create an expression parser based on the language
 */
$parser = new ExpressionParser(
    $language
);

/*
 * Evaluate an expression, expect result to be true
 */
$result = $parser->evaluate([
    'or', // first argument is always the function name 
    true, // will be passed as or function's first argument
    false // will be passed as or function's second argument
]);
```


### A more complex example
```php
<?php
/*
 * Define a language
 * with "+" (addition) and "-" (subtraction) functions for float numbers
 */
$language = (new Language())
    /*
     * Define "+" (addition) function
     */
    ->set(
        '+',
        function(float $l, float $r) : float {
            return $l + $r;
        }
    )
    /*
     * Define "-" (subtraction) function
     */
    ->set(
        '-',
        function(float $l, float $r) : float {
            return $l - $r;
        }
    );

/*
 * Create an expression parser based on the language
 */
$parser = new ExpressionParser(
    $language
);

/*
 * Evaluate expression
 * expect result to be 5 + (4.5 - 1) = 8.5
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

## License
Copyright 2016-2018 Xenofon Spafaridis

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

```
http://www.apache.org/licenses/LICENSE-2.0
```

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
