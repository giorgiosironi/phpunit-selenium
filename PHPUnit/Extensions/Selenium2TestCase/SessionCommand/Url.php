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
use PHPUnit\Extensions\Selenium2TestCase\URL as SeleniumURL;

/**
 * Gets or sets the current URL of the window.
 */
class Url extends Command
{
    public function __construct($url, $commandUrl, SeleniumURL $baseUrl)
    {
        if ($url !== null) {
            $absoluteLocation = $baseUrl->jump($url)->getValue();
            $jsonParameters   = ['url' => $absoluteLocation];
        } else {
            $jsonParameters = null;
        }

        parent::__construct($jsonParameters, $commandUrl);
    }

    public function httpMethod(): string
    {
        if ($this->jsonParameters) {
            return 'POST';
        }

        return 'GET';
    }
}
