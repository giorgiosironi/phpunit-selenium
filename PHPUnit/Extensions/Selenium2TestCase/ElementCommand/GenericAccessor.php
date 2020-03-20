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

use PHPUnit\Extensions\Selenium2TestCase\Command;

/**
 * Class for implementing commands that just return a value
 * (obtained with GET).
 */
class GenericAccessor extends Command
{
    public function httpMethod()
    {
        return 'GET';
    }
}
