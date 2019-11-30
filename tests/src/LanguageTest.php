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
class LanguageTest extends TestCase
{
    public function testConstruct(): void
    {
        $l = (new Language());

        $this->assertFalse(
            $l->isset('max')
        );
    }

    public function testSet(): Language
    {
        $l = (new Language())
            ->set('max', LanguageUtil::getMethod('max'));

        $this->assertTrue($l->isset('max'));

        return $l;
    }

    /**
     * @depends testSet
     */
    public function testIsset(Language $language): void
    {
        $this->assertTrue(
            $language->isset('max')
        );

        $this->assertFalse(
            $language->isset('min')
        );
    }

    /**
     * @depends testSet
     */
    public function testGet(Language $language): void
    {
        $this->assertIsCallable(
            $language->get('max')
        );
    }
}
