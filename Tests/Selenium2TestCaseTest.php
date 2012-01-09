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
 * @author     Giorgio Sironi <giorgio.sironi@asp-poli.it>
 * @copyright  2010-2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 1.0.0
 */

/**
 * Tests for PHPUnit_Extensions_SeleniumTestCase.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <giorgio.sironi@asp-poli.it>
 * @copyright  2010-2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 1.0.0
 */
class Extensions_Selenium2TestCaseTest extends PHPUnit_Extensions_Selenium2TestCase
{
    public function setUp()
    {
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int)PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM2_BROWSER);
        if (!defined('PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL')) {
            $this->markTestSkipped("You must serve the selenium-1-tests folder from an HTTP server and configure the PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL constant accordingly.");
        }
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
    }

    public function testOpen()
    {
        $this->url('html/test_open.html');
        $this->assertStringEndsWith('html/test_open.html', $this->url());
    }

    public function testElementSelection()
    {
        $this->url('html/test_open.html');
        $element = $this->byCssSelector('body');
        $this->assertEquals('This is a test of the open command.', $element->text());

        $this->url('html/test_click_page1.html');
        $link = $this->byId('link');
        $this->assertEquals('Click here for next page', $link->text());
    }

    public function testShortenedApiForSelectionOfElement()
    {
        $this->url('html/test_element_selection.html');

        $element = $this->byClassName('theDivClass');
        $this->assertEquals('The right div', $element->text());

        $element = $this->byCssSelector('div.theDivClass');
        $this->assertEquals('The right div', $element->text());

        $element = $this->byId('theDivId');
        $this->assertEquals('The right div', $element->text());

        $element = $this->byName('theDivName');
        $this->assertEquals('The right div', $element->text());

        $element = $this->byXPath('//div[@id]');
        $this->assertEquals('The right div', $element->text());
    }

    public function testClick()
    {
        $this->url('html/test_click_page1.html');
        $link = $this->byId('link');
        $link->click();
        $this->assertEquals('Click Page Target', $this->title());
        $back = $this->byId('previousPage');
        $back->click();
        $this->assertEquals('Click Page 1', $this->title());

        $withImage = $this->byId('linkWithEnclosedImage');
        $withImage->click();
        $this->assertEquals('Click Page Target', $this->title());
        $back = $this->byId('previousPage');
        $back->click();

        $enclosedImage = $this->byId('enclosedImage');
        $enclosedImage->click();
        $this->assertEquals('Click Page Target', $this->title());
        $back = $this->byId('previousPage');
        $back->click();

        $toAnchor = $this->byId('linkToAnchorOnThisPage');
        $toAnchor->click();
        $this->assertEquals('Click Page 1', $this->title());

        $withOnClick = $this->byId('linkWithOnclickReturnsFalse');
        $withOnClick->click();
        $this->assertEquals('Click Page 1', $this->title());

    }

    public function testClicksOnJavaScriptHref()
    {
        $this->url('html/test_click_javascript_page.html');
        $this->clickOnElement('link');
        $this->assertEquals('link clicked', $this->alertText());
        $this->markTestIncomplete("Should guarantee alerts to be checked in the right order and be dismissed; should reset the session in case alerts are still displayed as they would block the next test.");

        $this->clickOnElement('linkWithMultipleJavascriptStatements');
        $this->assertEquals('alert1', $this->alertText());
        $this->acceptAlert();
        $this->assertEquals('alert2', $this->alertText());
        $this->dismissAlert();
        $this->assertEquals('alert3', $this->alertText());

        $this->clickOnElement('linkWithJavascriptVoidHref');
        $this->assertEquals('onclick', $this->alertText());
        $this->assertEquals('Click Page 1', $this->title());

        $this->clickOnElement('linkWithOnclickReturnsFalse');
        $this->assertEquals('Click Page 1', $this->title());

        $this->clickOnElement('enclosedImage');
        $this->assertEquals('enclosedImage clicked', $this->alertText());
    }

    public function testTypingViaTheKeyboard()
    {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->byName('username');
        $usernameInput->value('TestUser');
        $this->assertEquals('TestUser', $usernameInput->value());

        $passwordInput = $this->byName('password');
        $passwordInput->value('testUserPassword');
        $this->assertEquals('testUserPassword', $passwordInput->value());

        $this->clickOnElement('submitButton');
        $h2 = $this->byCssSelector('h2');
        $this->assertRegExp('/Welcome, TestUser!/', $h2->text());
    }
}
