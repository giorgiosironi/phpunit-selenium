PHPUnit-Selenium [![Build Status](https://travis-ci.org/giorgiosironi/phpunit-selenium.svg?branch=master)](https://travis-ci.org/giorgiosironi/phpunit-selenium)

This package contains a Selenium2TestCase class that can be used to run end-to-end tests against Selenium 2.

Installing
---

Use [Composer](https://getcomposer.org) and run `composer require --dev phpunit/phpunit-selenium`.

Requirements
---

- Version `9.x` supports PHPUnit 9.x and is compatible with PHP 7.3+
- Version `8.x` supports PHPUnit 8.x and is compatible with PHP 7.2+
- Version `7.x` supports PHPUnit 7.x and is compatible with PHP 7.1+

Older unsupported lines which will probably see no new releases:

- `4.x` mainline supports (only) PHPUnit 6.x. This version is only compatible with PHP 7
- `3.x`: supports PHPUnit 5.x. Only compatible with PHP 5.6 and PHP 7.
- `2.x`: supports PHPUnit >= 4.8 instead.

Both these supported lines only work with the Selenium 2 API, using the `Selenium2TestCase` class.

The old line `1.x` is not maintained anymore, but will continue to be available for usage of `SeleniumTestCase`.

Please direct pull requests to [giorgiosironi/phpunit-selenium](https://github.com/giorgiosironi/phpunit-selenium) for automated testing upon merging. Pull requests should be feature branches containing all the commits you want to propose.

Running the test suite
---

#### Via Vagrant

Just run the following Vagrant commands (a minimal version of `v1.7` is required) and everything will be set up for you. The first start will take some time which depends on the speed of your connection (and less - speed of your computer):

    vagrant up
    vagrant provision
    vagrant ssh

    cd /vagrant
    vendor/bin/phpunit Tests

