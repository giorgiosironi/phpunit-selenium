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

namespace PHPUnit\Extensions;

use PHPUnit\Framework\TestSuite;
use ReflectionClass;
use ReflectionMethod;

/**
 * TestSuite class for a set of tests from a single Testcase Class
 * executed with a particular browser.
 *
 * @link       http://www.phpunit.de/
 */
class SeleniumBrowserSuite extends TestSuite
{
    /**
     * Overriding the default: Selenium suites are always built from a TestCase class.
     *
     * @var bool
     */
    protected $testCase = true;

    public function addTestMethod(ReflectionClass $class, ReflectionMethod $method): void
    {
        parent::addTestMethod($class, $method);
    }

    public static function fromClassAndBrowser($className, array $browser)
    {
        $browserSuite = new self();
        if (isset($browser['browserName'])) {
            $name = $browser['browserName'];
        } elseif (isset($browser['name'])) {
            $name = $browser['name'];
        } else {
            $name = $browser['browser'];
        }

        $browserSuite->setName($className . ': ' . $name);

        return $browserSuite;
    }

    public function setupSpecificBrowser(array $browser)
    {
        $this->browserOnAllTests($this, $browser);
    }

    private function browserOnAllTests(TestSuite $suite, array $browser)
    {
        foreach ($suite->tests() as $test) {
            if ($test instanceof TestSuite) {
                $this->browserOnAllTests($test, $browser);
            } else {
                $test->setupSpecificBrowser($browser);
            }
        }
    }
}
