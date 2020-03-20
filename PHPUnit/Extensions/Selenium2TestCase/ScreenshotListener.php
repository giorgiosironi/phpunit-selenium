<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase;

use PHPUnit\Extensions\Selenium2TestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use Throwable;

/**
 * Base class for implementing commands with special semantics.
 */
class ScreenshotListener implements TestListener
{
    use TestListenerDefaultImplementation;

    private $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function addError(Test $test, Throwable $e, float $time): void
    {
        $this->storeAScreenshot($test);
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        $this->storeAScreenshot($test);
    }

    private function storeAScreenshot(Test $test): void
    {
        if ($test instanceof Selenium2TestCase)
        {
            $className = str_replace('\\', '_', get_class($test));

            try {
                $file = $this->directory . '/' . $className . '__' . $test->getName() . '__' . date('Y-m-d\TH-i-s') . '.png';
                file_put_contents($file,        $test->currentScreenshot());
            } catch (\Exception $e) {
                $file = $this->directory . '/' . $className . '__' . $test->getName() . '__' . date('Y-m-d\TH-i-s') . '.txt';
                file_put_contents($file, "Screenshot generation doesn't work." . "\n"
                                         . $e->getMessage() . "\n"
                                         . $e->getTraceAsString());
            }
        }
    }
}
