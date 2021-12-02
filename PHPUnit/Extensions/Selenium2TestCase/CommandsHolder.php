<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2010-2013, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @link       http://www.phpunit.de/
 */

namespace PHPUnit\Extensions\Selenium2TestCase;

use BadMethodCallException;
use Exception;
use InvalidArgumentException;

/**
 * Object representing elements, or everything that may have subcommands.
 *
 * @link       http://www.phpunit.de/
 */
abstract class CommandsHolder
{
    /** @var Driver */
    protected $driver;

    /** @var string  the API URL for this element, */
    protected $url;

    /**
     * @var array   instances of
     *              PHPUnit_Extensions_Selenium2TestCase_ElementCommand
     */
    protected $commands;

    public function __construct($driver, URL $url)
    {
        $this->driver   = $driver;
        $this->url      = $url;
        $this->commands = [];
        foreach ($this->initCommands() as $commandName => $handler) {
            if (is_string($handler)) {
                $this->commands[$commandName] = $this->factoryMethod($handler);
            } elseif (is_callable($handler)) {
                $this->commands[$commandName] = $handler;
            } else {
                throw new InvalidArgumentException(sprintf('Command %s is not configured correctly.', $commandName));
            }
        }
    }

    /**
     * @return array    class names, or
     *                  callables of the form function($parameter, $commandUrl)
     */
    abstract protected function initCommands();

    public function __call($commandName, $arguments)
    {
        $jsonParameters = $this->extractJsonParameters($arguments);
        $response       = $this->driver->execute($this->newCommand($commandName, $jsonParameters));

        return $response->getValue();
    }

    protected function postCommand($name, ElementCriteria $criteria)
    {
        $response = $this->driver->curl(
            'POST',
            $this->url->addCommand($name),
            $criteria->getArrayCopy()
        );

        return $response->getValue();
    }

    /**
     * @return callable
     *
     * @params string $commandClass     a class name, descending from Command
     */
    private function factoryMethod($commandClass)
    {
        return static function ($jsonParameters, $url) use ($commandClass) {
            return new $commandClass($jsonParameters, $url);
        };
    }

    private function extractJsonParameters($arguments)
    {
        $this->checkArguments($arguments);

        if (count($arguments) === 0) {
            return null;
        }

        return $arguments[0];
    }

    private function checkArguments($arguments)
    {
        if (count($arguments) > 1) {
            throw new Exception('You cannot call a command with multiple method arguments.');
        }
    }

    /**
     * @param string $commandName    The called method name
     *                                defined as a key in initCommands()
     * @param array  $jsonParameters
     *
     * @return Command
     */
    protected function newCommand($commandName, $jsonParameters)
    {
        if (isset($this->commands[$commandName])) {
            $factoryMethod = $this->commands[$commandName];
            $url           = $this->url->addCommand($commandName);

            return $factoryMethod($jsonParameters, $url);
        }

        throw new BadMethodCallException(sprintf("The command '%s' is not existent or not supported yet.", $commandName));
    }
}
