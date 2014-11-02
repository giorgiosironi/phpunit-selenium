<?php

class Extensions_SeleniumTestCaseSample extends PHPUnit_Extensions_SeleniumTestCase
{
    public function testFirst() {}
    public function testSecond() {}
}

class Extensions_SeleniumMultipleBrowsersTestCaseSample extends PHPUnit_Extensions_SeleniumTestCase
{
    public static $browsers = array(
        array(
            'name'    => 'Firefox on Linux',
            'browser' => '*firefox',
            'host'    => 'localhost',
            'port'    => 4444,
            'timeout' => 30000,
        ),
        array(
            'name'    => 'Safari on MacOS X',
            'browser' => '*safari',
            'host'    => 'localhost',
            'port'    => 4444,
            'timeout' => 30000,
        ),
    );

    public function testSingle() {}
}
