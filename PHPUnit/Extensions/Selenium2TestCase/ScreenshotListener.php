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

namespace PHPUnit\Extensions\Selenium2TestCase;

use PHPUnit\Extensions\Selenium2TestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use Throwable;

/**
 * Base class for implementing commands with special semantics.
 */
class ScreenshotListener implements TestListener
{
    use TestListenerDefaultImplementation;

    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function addError(Test $test, Throwable $e, float $time): void
    {
        $this->storeAScreenshot($test);
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        $this->storeAScreenshot($test);
    }

    private function storeAScreenshot(Test $test)
    {
        if ($test instanceof Selenium2TestCase)
        {
            $className = str_replace('\\', '_', get_class($test));

            try {
                $file = $this->directory . '/' . $className . '__' . $test->getName() . '__' . date('Y-m-d\TH-i-s') . '.png';
                file_put_contents($file,        $test->currentScreenshot());
            } catch (\Exception $e) {
                $file = $this->directory . '/' . $className . '__' . $test->getName() . '__' . date('Y-m-d\TH-i-s') . '.txt';
                file_put_contents($file, "Screenshot generation doesn't work." . "\n"
                                         . $e->getMessage() . "\n"
                                         . $e->getTraceAsString());
            }
        }
    }
}
