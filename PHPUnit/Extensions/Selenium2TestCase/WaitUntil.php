<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2010-2011, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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

namespace PHPUnit\Extensions\Selenium2TestCase;

use PHPUnit\Extensions\Selenium2TestCase;

/**
 * The WaitUntil implementation, inspired by Java and .NET clients
 *
 * @see        http://selenium.googlecode.com/svn/trunk/dotnet/src/WebDriver.Support/UI/WebDriverWait.cs
 * @see        http://selenium.googlecode.com/svn/trunk/java/client/src/org/openqa/selenium/support/ui/FluentWait.java
 */
class WaitUntil
{
    /**
     * PHPUnit Test Case instance
     *
     * @var Selenium2TestCase
     */
    private $testCase;

    public function __construct(Selenium2TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param callable $callback      Callback to run until it returns not null or timeout occurs
     * @param int|null $timeout
     * @param int|null $sleepInterval the delay between 2 iterations of the callback
     *
     * @return mixed
     *
     * @throws \PHPUnit\Extensions\Selenium2TestCase\Exception
     * @throws WebDriverException
     */
    public function run($callback, $timeout = null, $sleepInterval = null)
    {
        if (! is_callable($callback)) {
            throw new \PHPUnit\Extensions\Selenium2TestCase\Exception('The valid callback is expected');
        }

        // if there was an implicit timeout specified - remember it and temporarily turn it off
        $implicitWait = $this->testCase->timeouts()->getLastImplicitWaitValue();
        if ($implicitWait) {
            $this->testCase->timeouts()->implicitWait(0);
        }

        if ($sleepInterval === null) {
            $sleepInterval = Selenium2TestCase::defaultWaitUntilSleepInterval();
        }

        $sleepInterval *= 1000;

        if ($timeout === null) {
            $timeout = Selenium2TestCase::defaultWaitUntilTimeout();
        }

        $timeout /= 1000;

        $endTime = microtime(true) + $timeout;

        $lastException = null;

        while (true) {
            try {
                $result = call_user_func($callback, $this->testCase);

                if ($result !== null) {
                    if ($implicitWait) {
                        $this->testCase->timeouts()->implicitWait($implicitWait);
                    }

                    return $result;
                }
            } catch (\Exception $e) {
                $lastException = $e;
            }

            if (microtime(true) > $endTime) {
                if ($implicitWait) {
                    $this->testCase->timeouts()->implicitWait($implicitWait);
                }

                $message = sprintf('Timed out after %s second', $timeout) . ($timeout !== 1 ? 's' : '');

                throw new WebDriverException($message, WebDriverException::Timeout, $lastException);
            }

            usleep($sleepInterval);
        }
    }
}
