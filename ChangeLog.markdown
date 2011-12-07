PHPUnit_Selenium 1.1
====================

This is the list of changes for the PHPUnit_Selenium 1.1 release series.

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
