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
 *
 * @package    PHPUnit_Selenium
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @author     Nicolas Fabre <nicolas.fabre@gmail.com>
 * @copyright  2010-2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.phpunit.de/
 */

require_once 'PHPUnit/Extensions/SeleniumTestCase/Driver.php';

/**
 * Tests for PHPUnit_Extensions_SeleniumTestCase_Driver.
 *
 * @package    PHPUnit_Selenium
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @author     Nicolas Fabre <nicolas.fabre@gmail.com>
 * @copyright  2010-2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 */

class Extensions_SeleniumTestCase_DriverTest extends PHPUnit_Framework_TestCase
{
	protected $driver;
	protected $url;
	
	public function setUp() {
		$this->driver = new PHPUnit_Extensions_SeleniumTestCase_Driver();	
		$this->url = sprintf(
          'http://%s:%d/selenium-server/tests/',
          PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST,
          PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT
        );
	}
	
	public function testStartButBrowserUrlNotSet() {
		try {
			$this->driver->start();
		} catch (PHPUnit_Framework_Exception $e) {
			$this->assertStringEndsWith('setBrowserUrl() needs to be called before start().', $e->getMessage());
			return ;	
		}
		$this->fail();	
	}
	
	public function testStart() {
		$this->checkSelenium();
		$this->driver->setBrowserUrl($this->url);
		$this->driver->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
		$sessionId = $this->driver->start();
		$this->assertAttributeNotEmpty('sessionId', $this->driver);
	}
	
	public function testStop() {
		$this->testStart();
		$this->driver->stop();
		$this->assertAttributeEquals(null, 'sessionId', $this->driver);	
	}

	public function testSetCollectCodeCoverageInformation() {
		$this->driver->setCollectCodeCoverageInformation(true);
		$this->assertAttributeEquals(true, 'collectCodeCoverageInformation', $this->driver);
		$this->assertAttributeType('bool', 'collectCodeCoverageInformation', $this->driver);	
	}	
	
	public function testSetCollectCodeCoverageInformationButInvalidArgumentException() {
		try {
			$this->driver->setCollectCodeCoverageInformation('true');
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a boolean', $e->getMessage());
			return ;	
		}
		$this->fail();
	}
	
	public function testSetTestCase() {
		$this->driver->setTestCase(new Extensions_SeleniumTestCaseTest());
		$this->assertAttributeType('Extensions_SeleniumTestCaseTest', 'testCase', $this->driver);	
		$this->assertAttributeInstanceOf('PHPUnit_Extensions_SeleniumTestCase', 'testCase', $this->driver);	
	}
	
	public function testSetTestId() {
		$this->driver->setTestId('123abc');
		$this->assertAttributeEquals('123abc', 'testId', $this->driver);	
		$this->assertAttributeType('string', 'testId', $this->driver);	
	}
	
	public function testSetName() {
		$this->driver->setName('Foo');
		$this->assertAttributeEquals('Foo', 'name', $this->driver);	
		$this->assertAttributeType('string', 'name', $this->driver);	
	}
	
	public function testSetNameButInvalidArgumentException() {
		try {
			$this->driver->setName(true);
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a string', $e->getMessage());
			return ;	
		}
		$this->fail();
	}
	
	public function testGetName() {
		$this->driver->setName('Firefox On Windows');
		$this->assertEquals('Firefox On Windows', $this->driver->getName());		
	}
	
	public function testSetBrowser() {
		$this->driver->setBrowser('*firefox');
		$this->assertAttributeEquals('*firefox', 'browser', $this->driver);	
		$this->assertAttributeType('string', 'browser', $this->driver);	
	}
	
	public function testSetBrowserButInvalidArgumentException() {
		try {
			$this->driver->setBrowser(true);
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a string', $e->getMessage());
			return ;	
		}
		$this->fail();
	}

	public function testSetBrowserUrl() {
		$this->driver->setBrowserUrl('http://foo.bar');
		$this->assertAttributeEquals('http://foo.bar', 'browserUrl', $this->driver);	
		$this->assertAttributeType('string', 'browserUrl', $this->driver);	
	}
	
	public function testSetBrowserUrlButInvalidArgumentException() {
		try {
			$this->driver->setBrowserUrl(true);
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a string', $e->getMessage());
			return ;	
		}
		$this->fail();
	}
	
	public function testSetHost() {
		$this->driver->setHost('127.0.0.1');
		$this->assertAttributeEquals('127.0.0.1', 'host', $this->driver);	
		$this->assertAttributeType('string', 'host', $this->driver);	
	}
	
	public function testSetHostButInvalidArgumentException() {
		try {
			$this->driver->setHost(true);
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a string', $e->getMessage());
			return ;	
		}
		$this->fail();
	}
	
	public function testSetPort() {
		$this->driver->setPort(4445);
		$this->assertAttributeEquals(4445, 'port', $this->driver);	
		$this->assertAttributeType('int', 'port', $this->driver);	
	}
	
	public function testSetPortButInvalidArgumentException() {
		try {
			$this->driver->setPort('4445');
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a integer', $e->getMessage());
			return ;	
		}
		$this->fail();
	}
	
	public function testSetTimeout() {
		$this->driver->setTimeout(3600);
		$this->assertAttributeEquals(3600, 'seleniumTimeout', $this->driver);	
		$this->assertAttributeType('int', 'seleniumTimeout', $this->driver);	
	}
	
	public function testSetTimeoutButInvalidArgumentException() {
		try {
			$this->driver->setTimeout('3600');
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a integer', $e->getMessage());
			return ;	
		}
		$this->fail();
	}

	public function testSetHttpTimeout() {
		$this->driver->setHttpTimeout(3600);
		$this->assertAttributeEquals(3600, 'httpTimeout', $this->driver);	
		$this->assertAttributeType('int', 'httpTimeout', $this->driver);	
	}
	
	public function testSetHttpTimeoutButInvalidArgumentException() {
		try {
			$this->driver->setHttpTimeout('3600');
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a integer', $e->getMessage());
			return ;	
		}
		$this->fail();
	}
	
	public function testSetCaptureScreenshotOnFailure() {
		$this->driver->setCaptureScreenshotOnFailure(true);
		$this->assertAttributeEquals(true, 'captureScreenshotOnFailure', $this->driver);
		$this->assertAttributeType('bool', 'captureScreenshotOnFailure', $this->driver);	
	}	
	
	public function testSetCaptureScreenshotOnFailureButInvalidArgumentException() {
		try {
			$this->driver->setCaptureScreenshotOnFailure('true');
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a boolean', $e->getMessage());
			return ;	
		}
		$this->fail();
	}

	public function testGetCaptureScreenshotOnFailure() {
		$this->driver->setCaptureScreenshotOnFailure(true);
		$this->assertEquals(true, $this->driver->getCaptureScreenshotOnFailure());		
	}
	
	public function testSetScreenshotPath() {
		$this->driver->setScreenshotPath('/foo');
		$this->assertAttributeEquals('/foo', 'screenshotPath', $this->driver);	
		$this->assertAttributeType('string', 'screenshotPath', $this->driver);
	}	
	
	public function testSetScreenshotPathButInvalidArgumentException() {
		try {
			$this->driver->setScreenShotPath(true);
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a string', $e->getMessage());
			return ;	
		}
		$this->fail();
	}

	public function testGetScreenshotPath() {
		$this->driver->setScreenshotPath('/foo/bar');
		$this->assertEquals('/foo/bar', $this->driver->getScreenshotPath());		
	}

	public function testHasScreenshotPath() {
		$this->driver->setScreenshotPath('/foo/bar');
		$this->assertEquals(true, $this->driver->hasScreenshotPath());
		$this->driver->setScreenshotPath('');	
		$this->assertEquals(false, $this->driver->hasScreenshotPath());	
	}
	
	public function testSetScreenshotUrl() {
		$this->driver->setScreenshotUrl('http://localhost');
		$this->assertAttributeEquals('http://localhost', 'screenshotUrl', $this->driver);	
		$this->assertAttributeType('string', 'screenshotUrl', $this->driver);
	}	
	
	public function testSetScreenshotUrlButInvalidArgumentException() {
		try {
			$this->driver->setScreenShotUrl(true);
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a string', $e->getMessage());
			return ;	
		}
		$this->fail();
	}

	public function testGetScreenshotUrl() {
		$this->driver->setScreenshotUrl('http://localhost');
		$this->assertEquals('http://localhost', $this->driver->getScreenshotUrl());		
	}

	public function testHasScreenshotUrl() {
		$this->driver->setScreenshotUrl('http://localhost');
		$this->assertEquals(true, $this->driver->hasScreenshotUrl());
		$this->driver->setScreenshotUrl('');	
		$this->assertEquals(false, $this->driver->hasScreenshotUrl());	
	}
	
	public function testSetCaptureMethod() {
		$this->driver->setCaptureMethod('captureScreenshot');
		$this->assertAttributeEquals('captureScreenshot', 'captureMethod', $this->driver);	
		$this->assertAttributeType('string', 'captureMethod', $this->driver);
	}	
	
	public function testSetCaptureMethodButInvalidArgumentException() {
		try {
			$this->driver->setCaptureMethod(true);
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a string', $e->getMessage());
			return ;	
		}
		$this->fail();
	}

	public function testGetCaptureMethod() {
		$this->driver->setCaptureMethod('captureScreenshot');
		$this->assertEquals('captureScreenshot', $this->driver->getCaptureMethod());		
	}
	
	public function testSetSleep() {
		$this->driver->setSleep(300);
		$this->assertAttributeEquals(300, 'sleep', $this->driver);	
		$this->assertAttributeType('int', 'sleep', $this->driver);	
	}	
	
	public function testSetSleepButInvalidArgumentException() {
		try {
			$this->driver->setSleep(true);
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a integer', $e->getMessage());
			return ;	
		}
		$this->fail();
	}
	
	public function testSetWait() {
		$this->driver->setWait(360);
		$this->assertAttributeEquals(360, 'wait', $this->driver);	
		$this->assertAttributeType('int', 'wait', $this->driver);	
	}	
	
	public function testSetWaitButInvalidArgumentException() {
		try {
			$this->driver->setWait(true);
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a integer', $e->getMessage());
			return ;	
		}
		$this->fail();
	}
	
	public function testSetWaitForPageToLoad() {
		$this->driver->setWaitForPageToLoad(false);
		$this->assertAttributeEquals(false, 'useWaitForPageToLoad', $this->driver);	
		$this->assertAttributeType('bool', 'useWaitForPageToLoad', $this->driver);	
	}	
	
	public function testSetWaitForPageToLoadButInvalidArgumentException() {
		try {
			$this->driver->setWaitForPageToLoad('true');
		} catch (InvalidArgumentException $e) {
			$this->assertStringEndsWith(' must be a boolean', $e->getMessage());
			return ;	
		}
		$this->fail();
	}
	
	
	
	protected function checkSelenium() {
	 if (!@fsockopen(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST, PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT, $errno, $errstr)) {
            $this->markTestSkipped(
              sprintf(
                'Selenium RC not running on %s:%d.',
                PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST,
                PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT
              )
            );
        }
	}
}