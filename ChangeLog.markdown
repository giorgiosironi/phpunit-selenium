PHPUnit_Selenium 1.1
====================

This is the list of changes for the PHPUnit_Selenium 1.1 release series.

PHPUnit_Selenium 1.1.0
----------------------

* Implemented GH-31: Skip tests when connection to Selenium RC server is not possible.
* Implemented GH-37: Replace usage of `fopen()` with cURL in `doCommand()`.
* Added `addUserCommand()` utility method.
* Fixed HTTP deadlock with Selenium RC server.
