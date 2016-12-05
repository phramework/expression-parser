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
     * @covers ::__construct
     */
    public function testConstruct()
    {
        new ExpressionParser(Language::getDefault());
    }

    /**
     * @covers ::evaluate
     */
    public function testEval()
    {
        $parser = new ExpressionParser(
            Language::getDefault(),
            (new Input())
                ->set(
                    'a',
                    5
                )
        );

        $r = $parser->evaluate([
            'member',
            ['input',  'a'],
            ['quote', [1, 2, 3, 5]]
        ]);

        $this->assertTrue($r);

        $r = $parser->evaluate([
            'member',
            ['quote',  '5'],
            ['quote', [1, 2, 3, 5]]
        ]);

        $this->assertTrue($r);

        $r = $parser->evaluate([
            'and',
            ['quote', true],
            ['quote', false]
        ]);

        $this->assertFalse($r);

        $r = $parser->evaluate([
            'and',
            ['quote', true],
            ['quote', true]
        ]);

        $this->assertTrue($r);
        
        $r = $parser->evaluate([
            'and',
            ['quote', true],
            [
                'member',
                ['quote',  '5'],
                ['quote', [1, 2, 3, 5]]
            ]
        ]);

        $this->assertTrue($r);

        $r = $parser->evaluate([
            'range',
            ['input', 'a'],
            1, 3,
            true,
            true
        ]);

        $this->assertFalse($r);

        $r = $parser->evaluate(
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
    }
}
