<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$directory = realpath(__DIR__);
while ($directory !== '/') {
    $autoloadCandidate = $directory . '/vendor/autoload.php';
    if (file_exists($autoloadCandidate)) {
        require_once $autoloadCandidate;
        break;
    }

    $directory = realpath($directory . '/..');
}

// Set this to the directory that contains the code coverage files.
// It defaults to getcwd(). If you have configured a different directory
// in prepend.php, you need to configure the same directory here.
$GLOBALS['PHPUNIT_COVERAGE_DATA_DIRECTORY'] = getcwd();

if (isset($_GET['PHPUNIT_SELENIUM_TEST_ID'])) {
    $facade              = new \SebastianBergmann\FileIterator\Facade();
    $sanitizedCookieName = str_replace(['\\'], '_', $_GET['PHPUNIT_SELENIUM_TEST_ID']);
    $files               = $facade->getFilesAsArray(
        $GLOBALS['PHPUNIT_COVERAGE_DATA_DIRECTORY'],
        $sanitizedCookieName
    );

    $coverage = [];

    foreach ($files as $file) {
        $data = unserialize(file_get_contents($file));
        unlink($file);
        unset($file);
        $filter = new \SebastianBergmann\CodeCoverage\Filter();

        foreach ($data as $file => $lines) {
            if ($filter->isFile($file)) {
                if (! isset($coverage[$file])) {
                    $coverage[$file] = [
                        'md5' => md5_file($file),
                        'coverage' => $lines,
                    ];
                } else {
                    foreach ($lines as $line => $flag) {
                        if (! isset($coverage[$file]['coverage'][$line]) ||
                            $flag > $coverage[$file]['coverage'][$line]) {
                            $coverage[$file]['coverage'][$line] = $flag;
                        }
                    }
                }
            }
        }
    }

    print serialize($coverage);
}
