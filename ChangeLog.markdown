PHPUnit_Selenium 1.2
====================

This is the list of changes for the PHPUnit_Selenium 1.2 release series.

PHPUnit_Selenium 1.2.1
----------------------

* Fixed #82: @depends annotation on SeleniumTestCase
* Fixed package.xml, including Selenium 2 support classes

PHPUnit_Selenium 1.2.0
----------------------

* Introduced PHPUnit_Extensions_Selenium2TestCase class for using WebDriver API.
* Introduced session sharing for WebDriver API.
  Introduced URL opening and element selection in WebDriver API.
* Introduced clicking on elements and clickOnElement($id) shorthand in WebDriver API.
* Introduced partial alert() management in WebDriver API.
* Introduced element manipulation in WebDriver API: text accessor, value mutator.
* Introduced by*() quick selectors in WebDriver API.
* Extracted a base Command class for extending the supported Session and Element commands in WebDriver API.

PHPUnit_Selenium 1.1
====================

This is the list of changes for the PHPUnit_Selenium 1.1 release series.

PHPUnit_Selenium 1.1.3
----------------------

* Fixed #71: errors in the result string are detected and raised as exceptions
* Completed #30: shared session is terminated at the end of the PHPUnit proces
* Fixed #65: when required, a screenshot is captured in the case of any except
* Fixed #80. Removed capability of automated stopping from the Driver: it is a

PHPUnit_Selenium 1.1.2
----------------------

* Parameterized timeout for server ping (detection of a not running server).
* Fixed #75: Tests are skipped if the configuration in `setUp()` is not `localhost`.
* Fixed #78: `onNotSuccessfulTest()` erroneous construction of exceptions.

PHPUnit_Selenium 1.1.1
----------------------

* Implemented #30: Introduced the possibility of sharing the session between tests.
* Fixed #13: Assertions made on non-target commands.
* Fixed #54: `waitFor*` calls with Selenium 2.
* Fixed #66: Comparison failure is not set.
* Fixed #76: Selenese variables are now translated in parameters.

PHPUnit_Selenium 1.1.0
----------------------

* Implemented GH-31: Skip tests when connection to Selenium RC server is not possible.
* Implemented GH-37: Replace usage of `fopen()` with cURL in `doCommand()`.
* Added `addUserCommand()` utility method.
* Fixed HTTP deadlock with Selenium RC server.
