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
 * Language of the parser,
 * contains the definitions of available functions
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Language
{
    /**
     * @var \stdClass
     */
    private $language;

    public function __construct()
    {
        $this->language = new \stdClass();
    }

    /**
     * Get key's method
     * @param string $key
     * @return callable
     */
    public function get(string $key)
    {
        return $this->language->{$key};
    }

    /**
     * Check if key is set
     * @param string $key
     * @return bool
     */
    public function isset(string $key) : bool
    {
        return property_exists($this->language, $key);
    }

    /**
     * Set method for a key
     * @param string   $key
     * @param callable $method
     * @return $this
     */
    public function set(string $key, callable $method)
    {
        $this->language->{$key} = $method;

        return $this;
    }

    /**
     * Return the default language
     * @return Language
     */
    public static function getDefault() : Language
    {
        $methodKeysFromLibrary = [
            'member',
            'or',
            'and',
            '==',
            '!=',
            '<',
            '>'
        ];

        $language = new Language();

        foreach ($methodKeysFromLibrary as $key) {
            $language->set($key, LanguageUtil::getMethod($key));
        }

        return $language;
    }
}
