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
 * Changes the focus to a window.
 */
class Window extends Command
{
    public function __construct($name, URL $commandUrl)
    {
        $jsonParameters = ['name' => $name];
        parent::__construct($jsonParameters, $commandUrl);
    }

    public function httpMethod(): string
    {
        return 'POST';
    }
}
