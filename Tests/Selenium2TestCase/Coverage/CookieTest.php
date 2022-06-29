<?php

namespace Tests\Selenium2TestCase\Coverage;

use PHPUnit\Framework\TestResult;
use Tests\Selenium2TestCase\BaseTestCase;

class CookieTest extends BaseTestCase
{
    // this is a dummy URL (returns down coverage data in HTML), but Firefox still sets domain cookie, which is what's needed
    protected $coverageScriptUrl = PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL .'/coverage/dummy.html';

    public function run(TestResult $result = NULL): TestResult
    {
        // make sure code coverage collection is enabled
        if ($result === NULL) {
            $result = $this->createResult();
        }
        if (!$result->getCollectCodeCoverageInformation()) {
            $result->setCodeCoverage(new \SebastianBergmann\CodeCoverage\CodeCoverage());
        }

        parent::run($result);

        $result->getCodeCoverage()->clear();
        return $result;
    }

    protected function getTestIdCookie()
    {
        return $this->prepareSession()->cookie()->get('PHPUNIT_SELENIUM_TEST_ID');
    }

    public function testTestIdCookieIsSet()
    {
        $this->url('/');
        $testIdCookie = $this->getTestIdCookie();
        $this->assertNotEmpty($testIdCookie);
        return $testIdCookie;
    }

    /**
     * @depends testTestIdCookieIsSet
     */
    public function testTestsHaveUniqueTestIdCookies($previousTestIdCookie)
    {
        $this->url('/');
        $this->assertNotEquals($this->getTestIdCookie(), $previousTestIdCookie);
    }

    public function testGetAll()
    {
        $cookies = $this->prepareSession()->cookie()->getAll();

        $this->assertIsArray($cookies);
        $this->assertCount(1, $cookies);
        $this->assertArrayHasKey('PHPUNIT_SELENIUM_TEST_ID', $cookies);
        $this->assertEquals($this->getTestId(), $cookies['PHPUNIT_SELENIUM_TEST_ID']);
    }
}
