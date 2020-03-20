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

use InvalidArgumentException;
use PHPUnit\Extensions\Selenium2TestCase\Command;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Gets or sets the current URL of the window.
 */
class Keys extends Command
{
    public function __construct($jsonParameters, URL $url)
    {
        if ($jsonParameters === null) {
            parent::__construct(null, $url);
        } else {
            $jsonParameters = $this->keysForText($jsonParameters);
            parent::__construct($jsonParameters, $url);
        }
    }

    /**
     * @return string
     */
    public function httpMethod(): string
    {
        return 'POST';
    }

    /**
     * Given a string returns an array of the characters that compose the string
     *
     * @param string $text
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function keysForText($text)
    {
        if (is_scalar($text)) {
            return ['value' => preg_split('//u', (string) $text, -1, PREG_SPLIT_NO_EMPTY)];
        }

        if (is_array($text)) {
            return $text;
        }

        throw new InvalidArgumentException('The "text" argument should be a string or an array of special characters!');
    }
}
