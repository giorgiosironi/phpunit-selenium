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
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Retrieves the value of a CSS property.
 */
class Css extends Command
{
    /**
     * @param array $propertyName
     */
    public function __construct($propertyName, URL $cssResourceBaseUrl)
    {
        $this->jsonParameters = [];
        $this->url            = $cssResourceBaseUrl->descend($propertyName);
    }

    public function httpMethod()
    {
        return 'GET';
    }
}
