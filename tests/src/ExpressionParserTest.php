<?php
declare(strict_types=1);
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
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @coversDefaultClass  Phramework\ExpressionParser\ExpressionParser
 */
class ExpressionParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExpressionParser
     */
    protected $parser;

    public function setUp()
    {
        $this->parser = new ExpressionParser(
            Language::getDefault(),
            (new Input())
                ->set(
                    'a',
                    5
                )
        );
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        new ExpressionParser(Language::getDefault());
    }

    /**
     * @covers ::evaluate
     */
    public function testEvaluateLiteral()
    {
        $r = $this->parser->evaluate(
            true
        );

        $this->assertSame(
            true,
            $r
        );

        $r = $this->parser->evaluate(
            5
        );

        $this->assertSame(
            5,
            $r
        );
    }

    /**
     * @covers ::evaluate
     */
    public function testEvaluate()
    {
        $r = $this->parser->evaluate([
            'member',
            ['input',  'a'],
            ['quote', [1, 2, 3, 5]]
        ]);

        $this->assertTrue($r);

        $r = $this->parser->evaluate([
            'and',
            ['quote', true],
            ['quote', false]
        ]);

        $this->assertFalse($r);

        $r = $this->parser->evaluate([
            'and',
            ['quote', true],
            ['quote', true]
        ]);

        $this->assertTrue($r);
        
        $r = $this->parser->evaluate([
            'and',
            ['quote', true],
            [
                'member',
                ['quote',  '5'],
                ['quote', [1, 2, 3, 5]]
            ]
        ]);

        $this->assertTrue($r);

    }

    /**
     * @covers ::evaluate
     */
    public function testEvaluateQuote()
    {
        $r = $this->parser->evaluate(
            ['quote',  true]
        );

        $this->assertSame(true, $r);

        $r = $this->parser->evaluate(
            ['quote', [1, 5, 3]]
        );

        $this->assertSame([1, 5, 3], $r);
    }

    /**
     * @covers ::evaluate
     */
    public function testEvaluateInput()
    {
        $r = $this->parser->evaluate(
            ['input',  'a']
        );

        $this->assertSame(5, $r);
    }

    /**
     * @covers ::evaluate
     */
    public function testCustomLanguage()
    {
        $language = (new Language())
            ->set(
                'or',
                function(bool $l, bool $r) {
                    return $l || $r;
                }
            );

        $parser = new ExpressionParser(
            $language
        );

        $r = $parser->evaluate([
            'or',
            ['quote', true],
            ['quote', false],
        ]);

        $this->assertTrue($r);

        $r = $parser->evaluate([
            'or',
            ['quote', false],
            ['quote', false],
        ]);

        $this->assertFalse($r);

        $r = $parser->evaluate([
            'or',
            true,
            false
        ]);

        $this->assertTrue($r);
    }

    /**
     * @covers ::evaluate
     */
    public function testComplex()
    {
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
         * Evaluate expression
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

        $this->assertSame(
            5 + 4.5 - 1,
            $result,
            'Expect result to be 5 + 4.5 - 1 = 8.5'
        );
    }
}
