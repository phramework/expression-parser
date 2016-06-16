<?php
/**
 * Copyright 2016 Xenofon Spafaridis
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Phramework\ExpressionParser;

use Phramework\Operator\Operator;

/**
 * Expression parser
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class ExpressionParser
{

    /**
     * @var \stdClass
     */
    protected $functions;

    /**
     * @var callable
     */
    public $eval;

    public function __construct()
    {
        $this->functions = new \stdClass();

        $symbol = (object) [
            'member' => function($item, $list) : bool {
                return in_array($item, $list);
            },
            'or' => function (bool ...$op) : bool {
                foreach ($op as $item) {
                    if ($item === true) {
                        return true;
                    }
                }

                return false;
            },
            'and' => function (bool ...$op) : bool {
                foreach ($op as $item) {
                    if ($item === false) {
                        return false;
                    }
                }

                return true;
            },
            '==' => function (...$op) {
                return count(array_unique($op)) === 1;
            },
            Operator::NOT_EQUAL => function (...$op) : bool {
                return count(array_unique($op)) > 1;
            },
            Operator::LESS => function (...$op) : bool {
                $init = reset($op);

                next($op);

                while (list($key, $val) = each($op)) {
                    if ($val >= $init) {
                        return false;
                    }
                    $init = $val;
                }

                return true;
            },
            Operator::GREATER => function (...$op) : bool {
                $init = reset($op);

                next($op);

                while (list($key, $val) = each($op)) {
                    if ($val <= $init) {
                        return false;
                    }
                    $init = $val;
                }

                return true;
            },
            '!' => function($op) {
                return !$op;
            },
            'range' => function(
                $item,
                $lower,
                $upper,
                bool $inclusiveLower = true,
                bool $inclusiveUpper = true
            ) : bool {
                if ($item > $lower || (!$inclusiveLower && $item >= $lower)) {
                    return false;
                } elseif ($item < $upper || (!$inclusiveUpper && $item <= $lower)) {
                    return false;
                }

                return true;
            }
        ];

        /**
         * A source named input
         */
        $input = (object) [
            'a' => 5
        ];

        /**
         * Gather source collections
         */
        $sourceCollection = (object) [
            'input' => function ($key) use ($input) {
                return $input->{$key};
            }
        ];

        $this->eval = $eval = function ($exp) use ($symbol, $sourceCollection, &$eval) {
            if (is_bool($exp)) { //bool
                return $exp;
            }

            if (is_string($exp) && property_exists($symbol, $exp)) {
                return $symbol->{$exp}; //return callable
            }

            if (!is_array($exp)) { //literal
                return $exp;
            }

            if ($exp[0] === 'quote') {
                return $exp[1];
            }

            if (is_string($exp[0]) && property_exists($sourceCollection, $exp[0])) {
                //$exp[0] is the name of the source, rest are source key arguments
                $source = $exp[0];

                $s = $sourceCollection->{$source};
                return $s(...array_slice($exp, 1));
            }

            $result = array_map($eval, $exp);

            $function = $result[0];
            $args     = array_slice($result, 1);

            return $function(...$args);
        };
    }
}
