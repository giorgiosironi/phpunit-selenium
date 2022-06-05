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
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Get the log for a given log type. Log buffer is reset after each request.
 */
class Log extends Command
{
    public function __construct($type, URL $commandUrl)
    {
        $jsonParameters = ['type' => $type];
        parent::__construct($jsonParameters, $commandUrl);
    }

    public function httpMethod(): string
    {
        return 'POST';
    }
}
