<?php
class GitHub404Test extends PHPUnit_Extensions_SeleniumTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setBrowser('*chrome');
        $this->setBrowserUrl('https://www.github.com');
        $this->start();
    }

    public function testPage()
    {
        $this->open('sebastianbergmann');
        $this->assertTrue(
            $this->isTextPresent('Rackspace Hosting'),
            'Hosting message not present.'
        );
    }

    public function test404Page()
    {
        $this->open('asdddsadfjjfjfffd');
        $this->assertTrue(
            $this->isTextPresent('Rackspace Hosting'),
            'Hosting message not present.'
        );
    }

    public function test404PageAndWait()
    {
        $this->open('asdddsadfjjfjfffd');
        $this->waitForPageToLoad();
        $this->assertTrue(
            $this->isTextPresent('Rackspace Hosting'),
            'Hosting message not present.'
        );
    }
}
