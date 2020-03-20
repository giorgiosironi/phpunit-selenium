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
