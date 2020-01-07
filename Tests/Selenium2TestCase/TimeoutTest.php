<?php
class Tests_Selenium2TestCase_TimeoutTest extends Tests_Selenium2TestCase_BaseTestCase
{
    public function setUp(): void
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
        $this->expectException(\PHPUnit\Extensions\Selenium2TestCase\Exception::class);
        $this->timeouts()->implicitWait(120000);
    }

    public function testGetLastImplicitWaitValue()
    {
        $this->assertEquals(0, $this->timeouts()->getLastImplicitWaitValue());
        $this->timeouts()->implicitWait(42);
        $this->assertEquals(42, $this->timeouts()->getLastImplicitWaitValue());
    }
}
