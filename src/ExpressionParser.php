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

        $f = (object) [
            'member' => function($a, $list) {
                return in_array($a, $list);
            },
            'or' => function (bool ...$op) {
                foreach ($op as $o) {
                    if ($o === true) {
                        return true;
                    }
                }

                return false;
            },
            'and' => function (bool ...$op) {
                foreach ($op as $o) {
                    if ($o === false) {
                        return false;
                    }
                }

                return true;
            }
        ];

        $input = (object) [
            'a' => 5
        ];

        $source = (object) [
            'input' => function ($key) use ($input) {
                return $input->{$key};
            }
        ];

        $this->eval = $eval = function ($exp) use ($f, $source, &$eval) {
            if (is_bool($exp)) { //bool
                return $exp;
            }

            if (is_string($exp)) {
                return $f->{$exp}; //return callable
            }

            if (!is_array($exp)) { //literal
                return $exp;
            }

            if ($exp[0] == 'quote') {
                return $exp[1];
            }

            if (property_exists($source, $exp[0])) {
                $s = $source->{$exp[0]};
                return $s(...array_slice($exp, 1));
            }

            $result = array_map($eval, $exp);

            $function = $result[0];
            $args     = array_slice($result, 1);

            return $function(...$args);
        };
    }
}
