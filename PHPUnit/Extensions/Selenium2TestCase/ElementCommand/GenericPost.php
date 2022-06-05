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
 * Class for implementing commands that just accomplishes an action (via POST).
 */
class GenericPost extends Command
{
    public function httpMethod(): string
    {
        return 'POST';
    }
}
