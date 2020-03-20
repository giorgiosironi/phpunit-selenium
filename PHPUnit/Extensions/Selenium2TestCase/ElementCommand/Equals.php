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

use InvalidArgumentException;
use PHPUnit\Extensions\Selenium2TestCase\Command;
use PHPUnit\Extensions\Selenium2TestCase\Element;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Checks equality (same element on the page) with another DOM element.
 */
class Equals extends Command
{
    /**
     * @param array $parameter
     */
    public function __construct($parameter, URL $equalsResourceBaseUrl)
    {
        $this->jsonParameters = [];
        if (! ($parameter instanceof Element)) {
            throw new InvalidArgumentException('Elements can only test equality with other Element instances.');
        }

        $this->url = $equalsResourceBaseUrl->descend($parameter->getId());
    }

    public function httpMethod()
    {
        return 'GET';
    }
}
