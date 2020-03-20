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

/**
 * Specifies how to create Session objects for running tests.
 */
interface SessionStrategy
{
    /**
     * @param array $parameters 'host' => Selenium Server machine
                                'port' => Selenium Server port
                                'secure' => Selenium Server secure flag
                                'browser' => a browser name
     *                          'browserUrl' => base URL to use during the test
     */
    public function session(array $parameters);

    public function notSuccessfulTest();

    public function endOfTest(?Session $session = null);
}
