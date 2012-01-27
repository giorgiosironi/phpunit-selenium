<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2010-2012, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <giorgio.sironi@asp-poli.it>
 * @copyright  2010-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 1.1.2
 */

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

/**
 * Tests for PHPUnit_Extensions_SeleniumTestCase.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <giorgio.sironi@asp-poli.it>
 * @copyright  2010-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 1.1.2
 */
class Extensions_SeleniumTestCaseFailuresTest extends PHPUnit_Extensions_SeleniumTestCase
{
    public function setUp()
    {
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int)PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        if (!defined('PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL')) {
            $this->markTestSkipped("You must serve the selenium-1-tests folder from an HTTP server and configure the PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL constant accordingly.");
        }
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
    }

    public function testOrdinaryExceptionsAreRethrown()
    {
        $exception = new BadMethodCallException('some error from production code'); // LINE 73
        try {
            $this->onNotSuccessfulTest($exception);
        } catch (PHPUnit_Framework_Error $e) {
            $this->assertTrue((bool) strstr($e->getMessage(), 'some error from production code'));
            $this->assertEquals(73, $e->getLine());
        }
    }

    /**
     * Fixes #78.
     * Also of interest for #84.
     */
    public function testExpectationFailuresAreRethrownCorrectly()
    {
        $exception = new PHPUnit_Framework_ExpectationFailedException("Some comparison error.");
        try {
            $this->onNotSuccessfulTest($exception);
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            $this->assertTrue((bool) strstr($e->getMessage(), 'Some comparison error.'));
        }
    }

    public function testWhenAComparisonFailureIsPresentItIsIncludedInTheMessage()
    {
        $exception = $this->getAFailure();
        try {
            $this->onNotSuccessfulTest($exception);
        } catch (PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertSame($exception, $e);
        }
    }

    public function testScreenshotsAreCapturedOnFailuresWhenRequired()
    {
        $this->captureScreenshotOnFailure = true;
        $this->screenshotPath = sys_get_temp_dir();
        $this->screenshotUrl = 'http://...';

        try {
            $exception = $this->getAFailure();
            $this->onNotSuccessfulTest($exception);
        } catch (PHPUnit_Framework_Error $e) {
            $this->assertTrue(file_exists($this->screenshotPath));
            $this->assertTrue((bool) strstr($e->getMessage(), 'Screenshot: http://.../'));
            return;
        }
        $this->fail('An exception should have been raised by now.');
    }

    private function getAFailure()
    {
        $failure = new PHPUnit_Framework_ComparisonFailure(1, 2, '1', '2');
        return new PHPUnit_Framework_ExpectationFailedException('1 is not 2', $failure);
    }

    /**
     * Issue #71.
     */
    public function testErrorMessagesFromTheDriverAreNotCutted()
    {
        try {
            $result = $this->getValue('inexistentSelector');
            $this->fail('Should have raised an exception.');
        } catch (RuntimeException $e) {
            $this->assertEquals('ERROR: Element inexistentSelector not found', $e->getMessage());
        }
    }

    public function testIncompleteTestsAreTreatedAsSuchAndNotAsFailing()
    {
        $original = new PHPUnit_Framework_IncompleteTestError('problem description');
        $this->isRethrownWithSameMessage($original);
    }

    public function testSkippedTestsAreTreatedAsSuchAndNotAsFailing()
    {
        $original = new PHPUnit_Framework_SkippedTestError('problem description');
        $this->isRethrownWithSameMessage($original);
    }

    /**
     * Issue #84.
     */
    public function testErrorsInExecutionMustBeSignaled()
    {
        $this->open('/');
        try {
            $this->type('id=wrongId', 'Search');
            $this->fail('A command not executed successfully should stop the test.');
        } catch (RuntimeException $e) {
            $this->assertEquals('ERROR: Element id=wrongId not found', $e->getMessage());
        }
    }

    private function isRethrownWithSameMessage(Exception $original)
    {
        try {
            $this->onNotSuccessfulTest($original);
        } catch (Exception $e) {
            $this->assertInstanceOf(get_class($original), $e);
            $this->assertEquals($original->getMessage(), $e->getMessage());
        }
    }
}
