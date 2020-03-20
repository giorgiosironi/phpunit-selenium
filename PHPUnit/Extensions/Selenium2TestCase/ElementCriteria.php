<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase;

use ArrayObject;

/**
 * Conditions for selecting a DOM element.
 *
 * @see        http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element
 */
class ElementCriteria extends ArrayObject
{
    public function __construct($strategy)
    {
        $this['using'] = $strategy;
    }

    /**
     * @return ElementCriteria
     */
    public function value($searchTarget)
    {
        $this['value'] = $searchTarget;

        return $this;
    }
}
