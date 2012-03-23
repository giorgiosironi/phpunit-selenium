This package contains a base Testcase Class that can be used to run end-to-end tests against Selenium 2 (using its Selenium 1 backward compatible Api).
Please direct *pull requests* to giorgiosironi/phpunit-selenium for automated testing upon merging. A feature branch containing all the commits you want to prose works best.

= Running the test suite =
To run the test suite for this package, you should serve selenium-1-tests via HTTP:
selenium-1-tests/ $ python -m SimpleHTTPServer 8080
and configure the constant that you will be asked for accordingly.
You also need to run a Selenium Server.
$ java -jar java -jar  selenium-server-standalone-2.x.xjar
Dependencies are managed via git submodules.
$ git submodule init
$ git submodule update
$ php run-phpunit.php
You can copy phpunit.xml.dist to phpunit.xml and setup a custom configuration for browsers.
