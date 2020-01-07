<?php

use PHPUnit\Extensions\Selenium2TestCase;

class Extensions_Selenium2TestCaseSample extends Selenium2TestCase
{
    public function testFirst() {}
    public function testSecond() {}
}

class Extensions_Selenium2MultipleBrowsersTestCaseSample extends Selenium2TestCase
{
    public static $browsers = array(
        array(
            'browserName' => 'firefox',
            'host'        => 'localhost',
            'port'        => 4444,
        ),
        array(
            'browserName' => 'safari',
            'host'        => 'localhost',
            'port'        => 4444,
        ),
    );

    public function testSingle() {}
}
