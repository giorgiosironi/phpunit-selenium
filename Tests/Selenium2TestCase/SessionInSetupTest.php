<?php
class Tests_Selenium2TestCase_SessionInSetupTest extends Tests_Selenium2TestCase_BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->prepareSession();
        $this->url('html/test_open.html');
    }

    public function testTheSessionStartedInSetupAndCanBeUsedNow()
    {
        $this->assertStringEndsWith('html/test_open.html', $this->url());
    }
}
