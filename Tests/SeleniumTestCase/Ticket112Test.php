<?php
class Tests_SeleniumTestCase_Ticket112Test extends PHPUnit_Extensions_SeleniumTestCase
{
    public function setUp()
    {
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int)PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
        $this->setBrowser('*chrome');
        $this->setBrowserUrl('https://www.github.com');
    }

    public function test404Page()
    {
        $this->open('asdddsadfjjfjfffd');
        $this->assertTrue(
            $this->isTextPresent('Rackspace Hosting'),
            '404 page not present'
        );
    }
}
