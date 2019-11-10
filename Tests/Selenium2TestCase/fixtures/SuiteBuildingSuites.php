<?php

class Extensions_Selenium2TestCaseSample extends PHPUnit_Extensions_Selenium2TestCase
{
    public function testFirst() {}
    public function testSecond() {}
}

class Extensions_Selenium2MultipleBrowsersTestCaseSample extends PHPUnit_Extensions_Selenium2TestCase
{
    public static $browsers = array(
        array(
            'browserName' => PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER,
            'host'        => PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST,
            'port'        => 4444,
            'desiredCapabilities' => array(
                'tunnel-identifier' => TUNNEL_IDENTIFIER
            )
        ),
        array(
            'browserName' => 'safari',
            'host'        => PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST,
            'port'        => 4444,
            'desiredCapabilities' => array(
                'tunnel-identifier' => TUNNEL_IDENTIFIER
            )
        ),
    );

    public function testSingle() {}
}
