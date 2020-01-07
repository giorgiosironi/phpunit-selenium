<?php

use PHPUnit\Extensions\Selenium2TestCase;

class CodeCoverageTest extends Selenium2TestCase
{
    protected $coverageScriptUrl = 'http://localhost/phpunit_coverage.php';

    public function setUp(): void
    {
        $this->markTestIncomplete('Would require PHP 5.4 for running .php files on the server');
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM2_BROWSER);
        $this->setBrowserUrl('http://localhost/');
    }

    public function testCoverageIsRetrieved()
    {
        $this->url('example.php');
    }
}
