<?php
class Tests_Selenium2TestCase_Coverage_RemoteCoverageTest extends PHPUnit_Framework_TestCase
{
    public function testObtainsCodeCoverageInformationFromAPossiblyRemoteHttpServer()
    {
        $coverageScriptUrl = PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL . '/coverage/dummy.txt';
        $coverage = new PHPUnit_Extensions_SeleniumCommon_RemoteCoverage(
            $coverageScriptUrl,
            'dummyTestId'
        );
        $content = $coverage->get();
        $dummyClassSourceFile = $this->classSourceFile('DummyClass', $content);
        $expectedCoverage = array(
            3 => 1,
            6 => 1,
            7 => -2,
            11 => -1,
            12 => -2,
            14 => 1
        );
        $this->assertTrue(isset($content[$dummyClassSourceFile]), "Coverage: " . var_export($content, true));
        $this->assertEquals($expectedCoverage, $content[$dummyClassSourceFile]);
    }

    private function classSourceFile($className, array $content)
    {
        foreach ($content as $file => $coverage) {
            if (strstr($file, $className)) {
                return $file;
            }
        }
        $this->fail("Class $className not found in coverage: " . var_export($content, true));
    }

    public function testAlternateStreamContext()
    {
        // http://php.net/manual/en/migration56.openssl.php
        $streamOpts = array(
            'ssl' => array(
                'verify_peer'      => false,
                'verify_peer_name' => false
            )
        );
        $currentOpts = stream_context_get_options(stream_context_get_default());
        stream_context_set_default( $streamOpts );

        $coverageScriptUrl = PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL_HTTPS . '/coverage/dummy.txt';
        $coverage = new PHPUnit_Extensions_SeleniumCommon_RemoteCoverage(
            $coverageScriptUrl,
            'dummyTestId'
        );
        $content = $coverage->get();
        $dummyClassSourceFile = $this->classSourceFile('DummyClass', $content);
        $expectedCoverage = array(
            3 => 1,
            6 => 1,
            7 => -2,
            11 => -1,
            12 => -2,
            14 => 1
        );
        $this->assertTrue(isset($content[$dummyClassSourceFile]), "Coverage: " . var_export($content, true));
        $this->assertEquals($expectedCoverage, $content[$dummyClassSourceFile]);

        stream_context_set_default( $currentOpts );
    }
}
