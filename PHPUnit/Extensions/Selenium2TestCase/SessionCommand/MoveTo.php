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
use PHPUnit\Extensions\Selenium2TestCase\Element;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Moves the mouse pointer.
 */
class MoveTo extends Command
{
    public function __construct($element, URL $url)
    {
        if (! is_array($element)) {
            $element = ['element' => $element,];
        }

        $validKeys = [
            'element' => null,
            'xoffset' => null,
            'yoffset' => null,
        ];

        $jsonParameters = array_intersect_key($element, $validKeys);

        if (isset($jsonParameters['element'])) {
            if (! ($jsonParameters['element'] instanceof Element)) {
                throw new \PHPUnit\Extensions\Selenium2TestCase\Exception(sprintf('Only moving over an element is supported. Please pass a \'%s\' instance.', Element::class));
            }

            $jsonParameters['element'] = $jsonParameters['element']->getId();
        }

        if (isset($jsonParameters['xoffset']) || isset($jsonParameters['yoffset'])) {
            // @see https://github.com/sebastianbergmann/phpunit-selenium/pull/250#issuecomment-21308153
            // @see https://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/moveto
            error_log('Even though this method is a part of the WebDriver Wire protocol it might be not supported by your browser yet');
        }

        parent::__construct($jsonParameters, $url);
    }

    /**
     * @return string
     */
    public function httpMethod()
    {
        return 'POST';
    }
}
