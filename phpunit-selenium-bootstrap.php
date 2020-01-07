<?php

use PHPUnit\Extensions\Selenium2TestCase;

require_once __DIR__ . '/vendor/autoload.php';

require_once 'Tests/Selenium2TestCase/BaseTestCase.php';
Selenium2TestCase::shareSession(true);
