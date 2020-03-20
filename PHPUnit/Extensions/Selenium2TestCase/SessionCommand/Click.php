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

use BadMethodCallException;
use PHPUnit\Extensions\Selenium2TestCase\Command;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Sends session click command for emulating LEFT, MIDDLE or RIGHT mouse buttons
 */
class Click extends Command
{
    public const LEFT   = 0;
    public const MIDDLE = 1;
    public const RIGHT  = 2;

    public function __construct($argument, URL $url)
    {
        if ($argument === null) {
            $jsonParameters = null;
        } elseif (! is_scalar($argument) || ! in_array($argument, [
            self::LEFT,
            self::RIGHT,
            self::MIDDLE,
        ])) {
            throw new BadMethodCallException('Wrong parameter for click(): expecting 0, 1 or 2.');
        } else {
            $jsonParameters = ['button' => $argument];
        }

        parent::__construct($jsonParameters, $url);
    }

    public function httpMethod()
    {
        return 'POST';
    }
}
