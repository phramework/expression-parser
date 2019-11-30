<?php

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

declare(strict_types=1);

namespace Phramework\ExpressionParser;

use PHPUnit\Framework\TestCase;

/**
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 */
class LanguageUtilTest extends TestCase
{
    /**
     * @todo maybe it should require it
     */
    public function testAndToBeTrueWhenEmpty(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set('and', LanguageUtil::getMethod('and'))
        );

        $r = $p->evaluate([
            'and',
        ]);

        $this->assertTrue($r);
    }

    public function testOrToBeTrue(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set('or', LanguageUtil::getMethod('or'))
        );

        $r = $p->evaluate([
            'or',
            false,
            false,
            true
        ]);

        $this->assertTrue($r);
    }

    public function testOrToBeFalseWhenEmpty(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set('or', LanguageUtil::getMethod('or'))
        );

        $r = $p->evaluate([
            'or',
        ]);

        $this->assertFalse($r);
    }

    public function testMax(): void
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

    public function testMix(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set('min', LanguageUtil::getMethod('min'))
        );

        $r = $p->evaluate([
            'min',
            1,
            3,
            10,
            -2
        ]);

        $this->assertSame(
            -2,
            $r
        );
    }

    public function testGreaterFalse(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    '>',
                    LanguageUtil::getMethod('>')
                )
        );

        $r = $p->evaluate([
            '>',
            1,
            3,
            5,
            2
        ]);

        $this->assertFalse($r);
    }

    public function testGreaterTrue(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    '>',
                    LanguageUtil::getMethod('>')
                )
        );

        $r = $p->evaluate([
            '>',
            1,
            3,
            5,
            20
        ]);

        $this->assertTrue($r);
    }

    public function testGreaterEmptyShouldBeTrue(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    '>',
                    LanguageUtil::getMethod('>')
                )
        );

        $r = $p->evaluate([
            '>'
        ]);

        $this->assertTrue($r);
    }

    public function testLessFalse(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    '<',
                    LanguageUtil::getMethod('<')
                )
        );

        $r = $p->evaluate([
            '<',
            2,
            5,
            3,
            1
        ]);

        $this->assertFalse($r);
    }

    public function testLessTrue(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    '<',
                    LanguageUtil::getMethod('<')
                )
        );

        $r = $p->evaluate([
            '<',
            20,
            5,
            3,
            1
        ]);

        $this->assertTrue($r);
    }

    public function testLessEmptyShouldBeTrue(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    '<',
                    LanguageUtil::getMethod('<')
                )
        );

        $r = $p->evaluate([
            '<'
        ]);

        $this->assertTrue($r);
    }

    public function testEqualTrue(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    '==',
                    LanguageUtil::getMethod('==')
                )
        );

        $r = $p->evaluate([
            '==',
            1,
            1,
            1
        ]);

        $this->assertTrue($r);
    }

    public function testNotEqual(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    '!=',
                    LanguageUtil::getMethod('!=')
                )
        );

        $r = $p->evaluate([
            '!=',
            1,
            1,
            2
        ]);

        $this->assertTrue($r);
    }

    public function testRange(): void
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

    public function testRangeSecond(): void
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
    
    public function testRangeNull(): void
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

    public function testRangeLowerInclusiveFalseShouldNotIncludeLower(): void
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
            0,
            0,
            1,
            false,
            false
        ]);
        
        $this->assertFalse($r, 'Expect false since 0 <= 0 (not inclusive)');
    }

    public function testRangeLowerInclusiveTrueShouldIncludeLower(): void
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
            0,
            0,
            1,
            true,
            false
        ]);

        $this->assertTrue($r, 'Expect true since 0 <= 0 (inclusive)');
    }
    
    public function testRangeUpperInclusiveFalseShouldNotIncludeUpper(): void
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
            1,
            0,
            1,
            false,
            false
        ]);
        
        $this->assertFalse($r, 'Expect false since 1 >= 1 (not inclusive)');
    }

    public function testRangeUpperInclusiveTrueShouldIncludeUpper(): void
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
            1,
            0,
            1,
            false,
            true
        ]);

        $this->assertTrue($r, 'Expect true since 1 >= 1 (inclusive)');
    }

    public function testNot(): void
    {
        $p = new ExpressionParser(
            (new Language())
                ->set(
                    '!',
                    LanguageUtil::getMethod('!')
                )
        );

        $r = $p->evaluate([
            '!',
            true
        ]);

        $this->assertFalse($r);


        $r = $p->evaluate([
            '!',
            false
        ]);

        $this->assertTrue($r);
    }
}
