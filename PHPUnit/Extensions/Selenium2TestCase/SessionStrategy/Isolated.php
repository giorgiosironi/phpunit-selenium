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

use PHPUnit\Extensions\Selenium2TestCase\Driver;
use PHPUnit\Extensions\Selenium2TestCase\Session;
use PHPUnit\Extensions\Selenium2TestCase\SessionStrategy;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Produces a new Session object shared for each test.
 */
class Isolated implements SessionStrategy
{
    public function session(array $parameters): Session
    {
        $seleniumServerUrl = URL::fromHostAndPort($parameters['host'], $parameters['port'], $parameters['secure']);
        $driver            = new Driver($seleniumServerUrl, $parameters['seleniumServerRequestsTimeout']);
        $capabilities      = array_merge(
            $parameters['desiredCapabilities'],
            [
                'browserName' => $parameters['browserName'],
            ]
        );

        return $driver->startSession($capabilities, $parameters['browserUrl']);
    }

    public function notSuccessfulTest(): void
    {
    }

    public function endOfTest(?Session $session = null): void
    {
        if ($session !== null) {
            $session->stop();
        }
    }
}
