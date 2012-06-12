<?php
class Tests_Selenium2TestCase_Coverage_SingleFileTest extends PHPUnit_Framework_TestCase
{
    private $dummyTestId = 'dummyTestId';

    public function setUp()
    {
        $this->coverageFilePattern = __DIR__ . '/*.' . $this->dummyTestId;
        $coverageFiles = glob($this->coverageFilePattern);
        foreach ($coverageFiles as $file) {
            unlink($file);
        }
    }

    public function testExecutingAFileWithThePrependedAndAppendedCoverageScriptsProducesACoverageData()
    { 
        $output = exec('php ' . __DIR__ . '/singleFile.php');
        $coverageFiles = glob($this->coverageFilePattern);
        $this->assertEquals(1, count($coverageFiles));

        $content = unserialize(file_get_contents($coverageFiles[0]));
        $dummyClassCoverage = $content[__DIR__ . '/DummyClass.php'];
        $this->assertCovered(6, $dummyClassCoverage);
        $this->assertNotCovered(11, $dummyClassCoverage);
    }

    private function assertCovered($line, array $fileCoverage)
    {
        $this->assertEquals(1, $fileCoverage[$line]);
    }

    private function assertNotCovered($line, array $fileCoverage)
    {
        $this->assertEquals(-1, $fileCoverage[$line]);
    }
}
