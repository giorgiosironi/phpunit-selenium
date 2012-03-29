PHPUnit_Selenium 1.2
====================

This is the list of changes for the PHPUnit_Selenium 1.2 release series.

PHPUnit_Selenium 1.2.6
----------------------

* Fixed #114 and #115: regressions of @depends/@dataProvider.
* Added $this->cookie() for adding and removing cookies via a Builder.
* Added Selenium2TestCase_Exception in the Cookie api.
* Supporting absolute URLs (http://...) in $this->url().
* Supporting uppercase URLs.
* Raising error message for stale elements reference (#117).
* No 500 errors when communicating with Selenium Server.
* Supporting Selenium 2.20.
* Tests for 404 pages.
* Supporting @depends/@dataProvider and similar annotations in SeleniumTestCase.
* Added getCssCount() in SeleniumTestCase.

PHPUnit_Selenium 1.2.5
----------------------

* Added Window object accessible via $this->currentWindow().
* Implemented $this->timeouts()->asyncScript().
* Fixed #105: $browsers static property.

PHPUnit_Selenium 1.2.4
----------------------

* Implemented $element->size().
* Implemented $element->location().
* Implemented $element->name(), $element->attribute(), $element->equals(), $element->enabled(), $element->displayed(), $element->css().
* Implemented $this->elements() for multiple element selection in the whole page.
* Implemented $this->frame() to switch focus between frames on a page.
* Implemented $this->execute() and $this->executeAsync() for executing arbitrary JavaScript.
* Implemented $this->windowHandle(), $this->windowHandles and $this->source().
* Implemented $this->alertText("...") for answering prompts.
* Supporting form submit (also via children elements).
* Supporting radio boxes.
* Supporting implicit waits on $this->by*().
* Supporting back and forward buttons via $this->back() and $this->forward().
* Supporting refresh of pages via $this->refresh().
* Supporting $element->clear().
* Correctly marking Selenium 1 tests as skipped when server is not running.

PHPUnit_Selenium 1.2.3
----------------------

* Fixed package.xml to include missing SeleniumTestSuite.php file.

PHPUnit_Selenium 1.2.2
----------------------

* Implemented Select object, available via $this->select().
* Added defaults for Selenium Server host and port.
* Added @method annotations on Selenium2TestCase.
* Fixed #83: `setUpBeforeClass` and `tearDownAfterClass` do not work with `PHPUnit_Extensions_SeleniumTestCase`.
* Fixed #85: using POST instead of GET in Selenium RC Driver.
* Supporting AndroidDriver, both on devices and emulators.
* Supporting UTF-8 characters in Element::value().

PHPUnit_Selenium 1.2.1
----------------------

* Fixed #82: `@depends` annotation does not work with `PHPUnit_Extensions_SeleniumTestCase`.
* `package.xml` misses classes for Selenium 2 support.

PHPUnit_Selenium 1.2.0
----------------------

* Introduced `PHPUnit_Extensions_Selenium2TestCase` class for using WebDriver API.
* Introduced session sharing for WebDriver API.
* Introduced URL opening and element selection in WebDriver API.
* Introduced clicking on elements and `clickOnElement($id)` shorthand in WebDriver API.
* Introduced partial `alert()` management in WebDriver API.
* Introduced element manipulation in WebDriver API: text accessor, value mutator.
* Introduced `by*()` quick selectors in WebDriver API.
* Extracted a base command class for extending the supported session and element commands in WebDriver API.
