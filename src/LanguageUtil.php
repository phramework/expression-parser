<?php
declare(strict_types=1);
/**
 * Copyright 2016-2017 Xenofon Spafaridis
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
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @coversDefaultClass  Phramework\ExpressionParser\ExpressionParser
 * @since 0.0.0
 */
final class LanguageUtil
{
    /**
     * @var LanguageUtil
     */
    protected static $instance = null;

    /**
     * @var \stdClass
     */
    protected $methodLibrary = null;

    public static function getInstance() : LanguageUtil
    {
        if (static::$instance === null) {
            static::$instance = new LanguageUtil();
        }

        return static::$instance;
    }

    protected function __construct()
    {
        $this->methodLibrary = (object) [
            'member'            => function ($item, $list): bool {
                return in_array($item, $list);
            },
            'or'                => function (bool ...$op): bool {
                foreach ($op as $item) {
                    if ($item === true) {
                        return true;
                    }
                }

                return false;
            },
            'and'               => function (bool ...$op): bool {
                foreach ($op as $item) {
                    if ($item === false) {
                        return false;
                    }
                }

                return true;
            },
            Operator::EQUAL     => function (...$op) {
                return count(array_unique($op)) === 1;
            },
            Operator::NOT_EQUAL => function (...$op): bool {
                return count(array_unique($op)) > 1;
            },
            Operator::LESS      => function (...$op): bool {
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
            Operator::GREATER   => function (...$op): bool {
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
            '!'                 => function ($op) {
                return !$op;
            },
            /**
             * Will return true, only if given $item is inside the range
             * @return bool
             */
            'range'             => function (
                $item,
                $lower,
                $upper,
                bool $inclusiveLower = true,
                bool $inclusiveUpper = true
            ) : bool {
                if ($item === null) {
                    return false;
                }

                if ($item > $upper || (!$inclusiveUpper && $item == $upper)) {
                    return false;
                }
                
                if ($item < $lower || (!$inclusiveLower && $item == $lower)) {
                    return false;
                }

                return true;
            },
            'max'              => function (...$op) {
                return max(...$op);
            },
            'min'              => function (...$op) {
                return min(...$op);
            }
        ];
    }

    /**
     * Get method stored in library
     * @param string $key
     * @return callable
     */
    public static function getMethod(string $key) : callable
    {
        $instance = static::getInstance();

        return $instance->methodLibrary->{$key};
    }
}
