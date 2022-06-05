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
use PHPUnit\Extensions\Selenium2TestCase\Element;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Checks equality (same element on the page) with another DOM element.
 */
class Equals extends Command
{
    public function __construct(Element $parameter, URL $equalsResourceBaseUrl)
    {
        parent::__construct([], $equalsResourceBaseUrl->descend($parameter->getId()));
    }

    public function httpMethod(): string
    {
        return 'GET';
    }
}
