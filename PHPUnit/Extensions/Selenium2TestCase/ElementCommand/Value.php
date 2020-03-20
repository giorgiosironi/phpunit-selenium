<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase\ElementCommand;

use BadMethodCallException;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Keys;

/**
 * Get and set the element's value attribute.
 */
class Value extends Keys
{
    public function httpMethod()
    {
        if ($this->jsonParameters) {
            return 'POST';
        }

        throw new BadMethodCallException('JSON Wire Protocol only supports POST to /value now. To get the value of an element GET /attribute/:naem should be used and this object should never be involved.');
    }
}
