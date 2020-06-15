<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase\SessionStrategy;

use PHPUnit\Extensions\Selenium2TestCase\Session;
use PHPUnit\Extensions\Selenium2TestCase\SessionStrategy;

/**
 * Keeps a Session object shared between test runs to save time.
 */
class Shared implements SessionStrategy
{
    private $original;
    private $session;
    private $mainWindow;
    private $lastTestWasNotSuccessful = false;
    private $keepSessionOnFailure;

    public function __construct(SessionStrategy $originalStrategy, $keepSessionOnFailure)
    {
        $this->original             = $originalStrategy;
        $this->keepSessionOnFailure = $keepSessionOnFailure;
    }

    public function session(array $parameters): Session
    {
        if ($this->lastTestWasNotSuccessful && ! $this->keepSessionOnFailure) {
            if ($this->session !== null) {
                $this->session->stop();
                $this->session = null;
            }

            $this->lastTestWasNotSuccessful = false;
        }

        if ($this->session === null) {
            $this->session    = $this->original->session($parameters);
            $this->mainWindow = $this->session->windowHandle();
        } else {
            $this->session->window($this->mainWindow);
        }

        return $this->session;
    }

    public function notSuccessfulTest(): void
    {
        $this->lastTestWasNotSuccessful = true;
    }

    public function endOfTest(?Session $session = null): void
    {
    }
}
