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
 * Gets the active element from the session
 */
class Active extends Command
{
    public function __construct($jsonParameters, URL $url)
    {
        $url = $url->addCommand('element')->addCommand('active');
        parent::__construct($jsonParameters, $url);
    }

    public function httpMethod()
    {
        return 'POST';
    }
}
