<?php

namespace Tests\Selenium2TestCase\fixtures;

use PHPUnit\Extensions\Selenium2TestCase;

class TestCaseSample extends Selenium2TestCase
{
    public function testFirst() {}
    public function testSecond() {}
}

class MultipleBrowsersTestCaseSample extends Selenium2TestCase
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
