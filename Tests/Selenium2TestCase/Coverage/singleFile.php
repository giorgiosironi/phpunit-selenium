<?php
$GLOBALS['PHPUNIT_COVERAGE_DATA_DIRECTORY'] = __DIR__;
$_COOKIE['PHPUNIT_SELENIUM_TEST_ID'] = 'dummyTestId';
require __DIR__ . '/../../../PHPUnit/Extensions/SeleniumTestCase/prepend.php';

require_once 'DummyClass.php';
$object = new DummyClass();
$object->coveredMethod();

require __DIR__ . '/../../../PHPUnit/Extensions/SeleniumTestCase/append.php';
