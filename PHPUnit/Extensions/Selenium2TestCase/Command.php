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

use InvalidArgumentException;

/**
 * Base class for implementing commands with special semantics.
 */
abstract class Command
{
    protected $jsonParameters;
    private $url;

    /**
     * @param array $jsonParameters null in case of no parameters
     */
    public function __construct(?array $jsonParameters, URL $url)
    {
        if (! is_array($jsonParameters) && $jsonParameters !== null) {
            throw new InvalidArgumentException('The JSON parameters must be an array, or a NULL value in case they are not required.');
        }

        $this->jsonParameters = $jsonParameters;
        $this->url            = $url;
    }

    public function url(): URL
    {
        return $this->url;
    }

    abstract public function httpMethod(): string;

    public function jsonParameters()
    {
        return $this->jsonParameters;
    }
}
