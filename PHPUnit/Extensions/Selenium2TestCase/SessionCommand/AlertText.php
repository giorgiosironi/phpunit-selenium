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
 * Obtains the text of an alert, or types into a prompt.
 */
class AlertText extends Command
{
    public function __construct(?string $jsonParameters, URL $url)
    {
        if ($jsonParameters !== null) {
            $jsonParameters = ['text' => $jsonParameters];
        }

        parent::__construct($jsonParameters, $url);
    }

    public function httpMethod(): string
    {
        if ($this->jsonParameters) {
            return 'POST';
        }

        return 'GET';
    }
}
