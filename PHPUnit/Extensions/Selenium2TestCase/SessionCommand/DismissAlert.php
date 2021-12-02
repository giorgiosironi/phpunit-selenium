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

use PHPUnit\Extensions\Selenium2TestCase\Command;

/**
 * Clicks Cancel on an alert popup.
 */
class DismissAlert extends Command
{
    public function httpMethod()
    {
        return 'POST';
    }
}
