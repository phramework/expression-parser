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
 * Input collection for parser
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Input
{
    /**
     * @var \stdClass
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new \stdClass();
    }

    /**
     * Get key value
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->repository->{$key};
    }

    /**
     * Check if key is set
     * @param string $key
     * @return bool
     */
    public function isset(string $key) : bool
    {
        return property_exists($this->repository, $key);
    }

    /**
     * Set value for key
     * @param string $key
     * @param mixed  $value
     * @return $this
     */
    public function set(string $key, $value)
    {
        $this->repository->{$key} = $value;

        return $this;
    }
}
