<?php
class Tests_Selenium2TestCase_TimeoutTest extends Tests_Selenium2TestCase_BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setSeleniumServerRequestsTimeout(60);
    }

    public function testOpen()
    {
        $this->url('html/test_open.html');
        $this->assertStringEndsWith('html/test_open.html', $this->url());
    }

    public function testAnImplicitWaitValueToRespectOnTheServerMustBeSmallerThanTheSeleniumServerCallsTimeout()
    {
        $this->markTestIncomplete();
    }
}
