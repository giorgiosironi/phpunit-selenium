<?php
class Tests_SeleniumTestCase_Ticket114Test extends PHPUnit_Extensions_SeleniumTestCase
{
    protected function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://www.example.com/');
    }

    public function testDependable()
    {
        return 'dependsValue';
    }

    /**
     * @dataProvider exampleDataProvider
     * @depends testDependable
     */
    public function testDependent($dataProvider, $depends)
    {
        $this->assertSame($dataProvider, 'dataProviderValue');
        $this->assertSame($depends, 'dependsValue');
    }

    public function exampleDataProvider()
    {
        return array(
            array('dataProviderValue'),
        );
    }
}
