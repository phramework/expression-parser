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

use PHPUnit\Framework\TestCase;

/**
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 */
class InputTest extends TestCase
{
    public function testSet()
    {
        $input = new Input();

        $someValue = (object) [
            'a' => 5,
            'b' => 5,
        ];

        $this->assertFalse(
            $input->isset('some-key')
        );

        $input->set(
            'some-key',
            $someValue
        );

        $this->assertTrue(
            $input->isset('some-key')
        );

        $this->assertSame(
            $someValue,
            $input->get('some-key')
        );
    }
}
