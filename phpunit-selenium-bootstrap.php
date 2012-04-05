<?php
require_once 'PHPUnit/Autoload.php';
PHPUnit_Extensions_SeleniumTestCase::shareSession(true);
require_once 'Tests/Selenium2TestCase/BaseTestCase.php';
PHPUnit_Extensions_Selenium2TestCase::shareSession(true);
