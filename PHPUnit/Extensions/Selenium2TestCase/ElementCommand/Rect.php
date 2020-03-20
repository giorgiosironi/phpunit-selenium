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
 * Retrieves the element's coordinates
 */
class Rect extends Command
{
    /**
     * @param array $parameter
     */
    public function __construct($parameter, URL $attributeResourceBaseUrl)
    {
        $this->jsonParameters = [];
        $this->url            = $attributeResourceBaseUrl->descend($parameter);
    }

    public function httpMethod()
    {
        return 'GET';
    }
}
