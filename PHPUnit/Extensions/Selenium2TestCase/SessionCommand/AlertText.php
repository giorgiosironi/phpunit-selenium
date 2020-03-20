<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase\SessionCommand;

use BadMethodCallException;
use PHPUnit\Extensions\Selenium2TestCase\Command;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Obtains the text of an alert, or types into a prompt.
 */
class AlertText extends Command
{
    public function __construct($argument, URL $url)
    {
        if (is_string($argument)) {
            $jsonParameters =['text' => $argument];
        } elseif ($argument === null) {
            $jsonParameters = null;
        } else {
            throw new BadMethodCallException('Wrong parameters for alertText().');
        }

        parent::__construct($jsonParameters, $url);
    }

    public function httpMethod()
    {
        if ($this->jsonParameters) {
            return 'POST';
        }

        return 'GET';
    }
}
