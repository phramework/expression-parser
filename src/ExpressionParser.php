<?php
declare(strict_types=1);
/**
 * Copyright 2016-2018 Xenofon Spafaridis
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
     * @var Language
     */
    protected $language;

    /**
     * @var Input
     */
    protected $input;

    /**
     * @var callable
     */
    private $evaluate;

    /**
     * Evaluate an expression
     * @param $exp
     * @return callable|mixed
     * @todo describe in depth
     */
    public function evaluate($exp)
    {
        if (is_string($exp) && $this->language->isset($exp)) {
            /*
             * return callable
             */
            return $this->language->get($exp);
        }

        /*
         * If is literal
         */
        if (!is_array($exp)) {
            return $exp;
        }

        if ($exp[0] === 'quote') {
            return $exp[1];
        }

        /*
         * Process 'input' (dereference input)
         */
        if ($exp[0] === 'input') { //is_string($exp[0]) &&
            return $this->input->get($exp[1]);
        }

        $result = array_map(
            [
                $this,
                'evaluate'
            ],
            $exp
        );

        $function = $result[0];
        $args     = array_slice($result, 1);

        return $function(...$args);
    }

    /**
     * ExpressionParser constructor.
     * @param Language   $language
     * @param Input|null $input Input could be part of the language,
     *                          but it's ok to provide the default functionality
     */
    public function __construct(
        Language $language,
        Input $input = null
    ) {
        $this->language = $language;
        $this->input = $input ?? new Input();
    }
}
