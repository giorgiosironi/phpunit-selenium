PHPUnit_Selenium 1.2
====================

This is the list of changes for the PHPUnit_Selenium 1.2 release series.

PHPUnit_Selenium 1.2.2
----------------------

* Fixed #83: `setUpBeforeClass` and `tearDownAfterClass` do not work with `PHPUnit_Extensions_SeleniumTestCase`.
* Fixed #85: using POST instead of GET in Selenium RC Driver.

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
