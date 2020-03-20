<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Extensions\SeleniumCommon\ExitHandler;

// By default the code coverage files are written to the same directory
// that contains the covered sourcecode files. Use this setting to change
// the default behaviour and set a specific directory to write the files to.
// If you change the default setting, please make sure to also configure
// the same directory in phpunit_coverage.php. Also note that the webserver
// needs write access to the directory.

if (! isset($GLOBALS['PHPUNIT_COVERAGE_DATA_DIRECTORY'])) {
    $GLOBALS['PHPUNIT_COVERAGE_DATA_DIRECTORY'] = false;
}

if (isset($_COOKIE['PHPUNIT_SELENIUM_TEST_ID']) &&
    ! isset($_GET['PHPUNIT_SELENIUM_TEST_ID']) &&
    extension_loaded('xdebug')) {
    $GLOBALS['PHPUNIT_FILTERED_FILES'] = [__FILE__];

    xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
}

include 'ExitHandler.php';
ExitHandler::init();
