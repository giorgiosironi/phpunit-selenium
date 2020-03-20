<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions;

use PHPUnit\Framework\TestSuite;
use ReflectionClass;
use ReflectionMethod;

/**
 * TestSuite class for a set of tests from a single Testcase Class
 * executed with a particular browser.
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
