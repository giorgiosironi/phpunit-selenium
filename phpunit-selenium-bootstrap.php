<?php
require_once 'PHPUnit/Autoload.php';
PHPUnit_Extensions_SeleniumTestCase::shareSession(true);
if (class_exists('PHPUnit_Extensions_Selenium2TestCase', true)) {
    PHPUnit_Extensions_Selenium2TestCase::shareSession(true);
}
