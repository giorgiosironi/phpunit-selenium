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

    public function __construct($driver, URL $url, $maximumTimeout)
    {
        parent::__construct($driver, $url);
        $this->maximumTimeout = $maximumTimeout;
    }

    protected function initCommands()
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

    public function setLastImplicitWaitValue($implicitWait)
    {
        $this->lastImplicitWaitValue = $implicitWait;
    }

    public function getLastImplicitWaitValue()
    {
        return $this->lastImplicitWaitValue;
    }

    public function check($timeout)
    {
        if ($timeout > $this->maximumTimeout) {
            throw new \PHPUnit\Extensions\Selenium2TestCase\Exception('There is no use in setting this timeout unless you also call $this->setSeleniumServerRequestsTimeout($seconds) in setUp().');
        }
    }
}
