<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase;

use BadMethodCallException;
use Exception;
use InvalidArgumentException;

/**
 * Object representing elements, or everything that may have subcommands.
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
