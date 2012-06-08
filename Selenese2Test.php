<?php
require_once 'PHPUnit/Extensions/Selenium2TestCase.php';

class SeleneseTest extends PHPUnit_Extensions_Selenium2TestCase
{
    public static $seleneseDirectory = './selenium-1-tests/selenese/';
    
    protected function setUp()
    {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
    }
}
