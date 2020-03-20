<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase\Session;

use PHPUnit\Extensions\Selenium2TestCase\CommandsHolder;
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\GenericPost;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Manages timeouts for the current browser session.
 *
 * @method implicitWait(int $ms) Sets timeout when searching for elements
 * @method asyncScript(int $ms) Sets timeout for asynchronous scripts executed by Session::executeAsync()
 */
class Timeouts extends CommandsHolder
{
    private $maximumTimeout;
    private $lastImplicitWaitValue = 0;

    public function __construct($driver, URL $url, int $maximumTimeout)
    {
        parent::__construct($driver, $url);
        $this->maximumTimeout = $maximumTimeout;
    }

    protected function initCommands(): array
    {
        $self = $this;

        return [
            'implicitWait' => static function ($milliseconds, $commandUrl) use ($self) {
                $self->check($milliseconds);
                $self->setLastImplicitWaitValue($milliseconds);
                $jsonParameters = ['ms' => $milliseconds];

                return new GenericPost($jsonParameters, $commandUrl);
            },
            'asyncScript' => static function ($milliseconds, $commandUrl) use ($self) {
                $self->check($milliseconds);
                $jsonParameters = ['ms' => $milliseconds];

                return new GenericPost($jsonParameters, $commandUrl);
            },

        ];
    }

    public function setLastImplicitWaitValue(int $implicitWait): void
    {
        $this->lastImplicitWaitValue = $implicitWait;
    }

    public function getLastImplicitWaitValue(): int
    {
        return $this->lastImplicitWaitValue;
    }

    public function check($timeout): void
    {
        if ($timeout > $this->maximumTimeout) {
            throw new \PHPUnit\Extensions\Selenium2TestCase\Exception('There is no use in setting this timeout unless you also call $this->setSeleniumServerRequestsTimeout($seconds) in setUp().');
        }
    }
}
