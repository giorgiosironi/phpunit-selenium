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
 * Gets or sets an attribute of an object.
 */
class StateCommand extends Command
{
    public function httpMethod()
    {
        if ($this->jsonParameters) {
            return 'POST';
        }

        return 'GET';
    }
}
