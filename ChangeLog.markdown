PHPUnit_Selenium 1.1
====================

This is the list of changes for the PHPUnit_Selenium 1.1 release series.

PHPUnit_Selenium 1.1.0
----------------------

* Implemented GH-31: Skip tests when connection to Selenium RC server is not possible.
* Implemented GH-37: Replace usage of `fopen()` with cURL in `doCommand()`.
* Added `addUserCommand()` utility method.
* Fixed HTTP deadlock with Selenium RC server.

PHPUnit_Selenium 1.1.1
----------------------

* Implemented GH-30: introduced the possibility of sharing the session between tests.
* Fixed GH-13: assertions made on onn-target commands.
* Fixed GH-54: waitFor* calls with Selenium 2.
* Fixed GH-66: comparison failure is not set.
* Fixed GH-76: Selenese variables are now translated in parameters.
* Added coverage for SeleniumTestCase::suite()

PHPUnit_Selenium 1.1.2
----------------------

* Fixed GH-75: tests are skipped if the configuration in setUp() is not localhost
* Parameterized timeout for server ping (detection of a not running server)
* Improved coverage of onNotSuccessfulTest()
* Fixed GH-78: onNotSuccessfulTest() erroneous construction of exceptions
