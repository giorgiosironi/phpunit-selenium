<?php
require_once __DIR__ . '/vendor/autoload.php';

if (!defined('PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST') && getenv('SAUCE_USERNAME') && getenv('SAUCE_ACCESS_KEY')) {
    define('PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST', sprintf('%s:%s@ondemand.saucelabs.com', getenv('SAUCE_USERNAME'), getenv('SAUCE_ACCESS_KEY')));
}

define('TUNNEL_IDENTIFIER', getenv('TRAVIS_JOB_NUMBER') ?: '');

require_once 'Tests/Selenium2TestCase/BaseTestCase.php';
PHPUnit_Extensions_Selenium2TestCase::shareSession(true);
