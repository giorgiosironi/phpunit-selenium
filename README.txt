This package contains a base Testcase Class that can be used to run end-to-end tests against Selenium 2 (using its Selenium 1 backward compatible Api).
Please direct *pull requests* to giorgiosironi/phpunit-selenium for automated testing upon merging. A feature branch containing all the commits you want to prose works best.

= Running the test suite =
To run the test suite for this package, you should serve selenium-1-tests via HTTP:
selenium-1-tests/ $ python -m SimpleHTTPServer 8080
and configure the constant that you will be asked for accordingly if you do not run the server on localhost:8080.
You also need to run a Selenium Server (the only supported version is 2.23, at this time the most recent).
$ java -jar java -jar  selenium-server-standalone-2.x.xjar
or with xvfb:
$ sudo xvfb-run java -jar bin/selenium-server-standalone-2.x.x.jar
Dependencies are managed via git submodules, so you must execute these commands once you have a clean working copy:
$ git submodule init
$ git submodule update
The tests can then be run with:
$ php run-phpunit.php
You can copy phpunit.xml.dist to phpunit.xml and setup a custom configuration for browsers, but the test suite is based on Firefox.
