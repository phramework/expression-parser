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

use Phramework\Operator\Operator;

/**
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @coversDefaultClass  Phramework\ExpressionParser\LanguageUtil
 */
class LanguageUtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::getMethod
     */
    public function testMax()
    {
        $p = new ExpressionParser(
            (new Language())
                ->set('max', LanguageUtil::getMethod('max'))
        );

        $r = $p->evaluate([
            'max',
            1,
            3,
            10,
            -2
        ]);

        $this->assertSame(
            10,
            $r
        );
    }

    /**
     * @covers ::getMethod
     */
    public function testGreater()
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    Operator::GREATER,
                    LanguageUtil::getMethod(Operator::GREATER)
                )
        );

        $r = $p->evaluate([
            Operator::GREATER,
            1,
            3,
            5,
            2
        ]);

        $this->assertFalse($r);

        $r = $p->evaluate([
            Operator::GREATER,
            1,
            3,
            5,
            20
        ]);

        $this->assertTrue($r);
    }

    /**
     * @covers ::getMethod
     */
    public function testNotEqual()
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    Operator::NOT_EQUAL,
                    LanguageUtil::getMethod(Operator::NOT_EQUAL)
                )
        );

        $r = $p->evaluate([
            Operator::NOT_EQUAL,
            1,
            1,
            2
        ]);

        $this->assertTrue($r);
    }

    /**
     * @covers ::getMethod
     */
    public function testRange()
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    'range',
                    LanguageUtil::getMethod('range')
                )
        );

        $r = $p->evaluate([
            'range',
            2,
            1,
            3,
            true,
            true
        ]);

        $this->assertTrue($r, 'Expect true, since 1 <= 2 <= 3');
    }

    /**
     * @covers ::getMethod
     */
    public function testRangeSecond()
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    'range',
                    LanguageUtil::getMethod('range')
                )
        );

        $r = $p->evaluate([
            'range',
            3,
            1,
            3,
            false,
            false
        ]);

        $this->assertFalse($r, 'Expect false, since 1 < 3 < 3');
    }

    /**
     * @covers ::getMethod
     */
    public function testRangeNull()
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    'range',
                    LanguageUtil::getMethod('range')
                )
        );

        $r = $p->evaluate([
            'range',
            null,
            0,
            4
        ]);

        $this->assertFalse($r);
    }
}
