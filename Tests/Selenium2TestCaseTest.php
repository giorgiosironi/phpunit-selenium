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

    public function testElementsCanBeSelectedAsChildrenOfAlreadyFoundElements()
    {
        $this->url('html/test_element_selection.html');
        $parent = $this->byCssSelector('div#parentElement');
        $child = $parent->element($this->using('css selector')->value('span'));
        $this->assertEquals('Child span', $child->text());

        $rows = $this->byCssSelector('table')->elements($this->using('css selector')->value('tr'));
        $this->assertEquals(2, count($rows));
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
    
    public function testTypingNonLatinText()
    {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->byName('username');
        $usernameInput->value('テストユーザ');
        $this->assertEquals('テストユーザ', $usernameInput->value());
    }

    public function testSelectElements()
    {
        $this->url('html/test_select.html');
        $option = $this->byId('o2');
        $this->assertEquals('Second Option', $option->text());
        $this->assertEquals('option2', $option->value());
        $this->assertTrue($option->selected());
        $option = $this->byId('o3');
        $this->assertFalse($option->selected());
        $option->click();
        $this->assertTrue($option->selected());
    }

    public function testASelectObjectCanBeBuildWithASpecificAPI()
    {
        $this->url('html/test_select.html');
        $select = $this->select($this->byCssSelector('select'));
        
        // basic
        $this->assertEquals('Second Option', $select->selectedLabel());
        $this->assertEquals('option2', $select->selectedValue());

        // by text, value attribute or generic criteria
        $select->selectOptionByLabel('Fourth Option');
        $this->assertEquals('option4', $select->selectedValue());

        $select->selectOptionByValue('option3');
        $this->assertEquals('Third Option', $select->selectedLabel());

        $select->selectOptionByCriteria($this->using('id')->value('o4'));
        $this->assertEquals('option4', $select->selectedValue());

        // empty values
        $select->selectOptionByValue('');
        $this->assertEquals('Empty Value Option', $select->selectedLabel());

        $select->selectOptionByLabel('');
        $this->assertEquals('', $select->selectedLabel());

    }

    public function testFormsCanBeSubmitted()
    {
        $this->url('html/test_submit.html');
        $form = $this->byId('searchForm');
        $form->submit();
        $this->assertEquals('onsubmit called', $this->alertText());
        $this->acceptAlert();

        $box = $this->byName('okayToSubmit');
        $box->click();
        $box->submit();
        $this->assertEquals('onsubmit called', $this->alertText());
        $this->acceptAlert();
        $this->assertEquals('form submitted', $this->alertText());
    }

    public function testCheckboxesCanBeSelectedAndDeselected()
    {
        $this->markTestIncomplete("Flaky: fails on clicking in some browsers.");
        $this->url('html/test_check_uncheck.html');
        $beans = $this->byId('option-beans');
        $butter = $this->byId('option-butter');

        $this->assertTrue($beans->selected());
        $this->assertFalse($butter->selected());

        $butter->click();
        $this->assertTrue($butter->selected());
        $butter->click();
        $this->assertFalse($butter->selected());
    }

    public function testRadioBoxesCanBeSelected()
    {
        $this->url('html/test_check_uncheck.html');
        $spud = $this->byId('base-spud');
        $rice = $this->byId('base-rice');

        $this->assertTrue($spud->selected());
        $this->assertFalse($rice->selected());

        $rice->click();
        $this->assertFalse($spud->selected());
        $this->assertTrue($rice->selected());

        $spud->click();
        $this->assertTrue($spud->selected());
        $this->assertFalse($rice->selected());
    }

    public function testWaitPeriodsAreImplicitInSelection()
    {
        $this->timeouts()->implicitWait(10000);
        $this->url('html/test_delayed_element.html');
        $element = $this->byId('createElementButton')->click();
        $div = $this->byXPath("//div[@id='delayedDiv']");
        $this->assertEquals('Delayed div.', $div->text());
    }

    public function testTheBackAndForwardButtonCanBeUsedToNavigate()
    {
        $this->url('html/test_click_page1.html');
        $this->assertEquals('Click Page 1', $this->title());

        $this->clickOnElement('link');
        $this->assertEquals('Click Page Target', $this->title());

        $this->back();
        $this->assertEquals('Click Page 1', $this->title());

        $this->forward();
        $this->assertEquals('Click Page Target', $this->title());
    }

    public function testThePageCanBeRefreshed()
    {
        $this->url('html/test_page.slow.html');
        $this->assertStringEndsWith('html/test_page.slow.html', $this->url());
        $this->assertEquals('Slow Loading Page', $this->title());

        $this->clickOnElement('changeSpan');
        $this->assertEquals('Changed the text', $this->byId('theSpan')->text());
        $this->refresh();
        $this->assertEquals('This is a slow-loading page.', $this->byId('theSpan')->text());

        $this->clickOnElement('changeSpan');
        $this->assertEquals('Changed the text', $this->byId('theSpan')->text());
    }

    public function testLinkEventsAreGenerated()
    {
        $this->url('html/test_form_events.html');
        $eventLog = $this->byId('eventlog');
        $this->assertEquals('', $eventLog->value());
        $this->clickOnElement('theLink');
        $this->assertContains('{focus(theLink)} {click(theLink)}', $eventLog->value());
        $this->assertEquals('link clicked', $this->alertText());
        $this->acceptAlert();
    }

    public function testButtonEventsAreGenerated()
    {
        $this->url('html/test_form_events.html');
        $eventLog = $this->byId('eventlog');
        $this->assertEquals('', $eventLog->value());
        $this->clickOnElement('theButton');
        $this->assertContains('{focus(theButton)} {click(theButton)}', $eventLog->value());
        $eventLog->value('');

        $this->clickOnElement('theSubmit');
        $this->assertContains('{focus(theSubmit)} {click(theSubmit)} {submit}', $eventLog->value());

    }
}
