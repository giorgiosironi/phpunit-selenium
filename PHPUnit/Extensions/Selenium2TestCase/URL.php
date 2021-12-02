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

/**
 * URL Value Object allowing easy concatenation.
 */
final class URL
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $host
     * @param int    $port
     * @param bool   $secure
     *
     * @return URL
     */
    public static function fromHostAndPort($host, $port, $secure)
    {
        $prefix = 'http://';
        if ($secure) {
            $prefix = 'https://';
        }

        return new self($prefix . $host . ':' . $port);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * @param string $addition
     *
     * @return URL
     */
    public function descend($addition)
    {
        if ($addition === '') {
            // if we're adding nothing, respect the current url's choice of
            // whether or not to include a trailing slash; prevents inadvertent
            // adding of slashes to urls that can't handle it
            $newValue = $this->value;
        } else {
            $newValue = rtrim($this->value, '/')
                      . '/'
                      . ltrim($addition, '/');
        }

        return new self($newValue);
    }

    /**
     * @return URL
     */
    public function ascend()
    {
        $lastSlash = strrpos($this->value, '/');
        $newValue  = substr($this->value, 0, $lastSlash);

        return new self($newValue);
    }

    /**
     * @return string
     */
    public function lastSegment()
    {
        $segments = explode('/', $this->value);

        return end($segments);
    }

    /**
     * @param string $command
     *
     * @return URL
     */
    public function addCommand($command)
    {
        return $this->descend($this->camelCaseToUnderScores($command));
    }

    /**
     * @param string $newUrl
     *
     * @return URL
     */
    public function jump($newUrl)
    {
        if ($this->isAbsolute($newUrl)) {
            return new self($newUrl);
        } else {
            return $this->descend($newUrl);
        }
    }

    private function camelCaseToUnderScores($string)
    {
        $string = preg_replace('/([A-Z]{1,1})/', ' \1', $string);
        $string = strtolower($string);

        return str_replace(' ', '_', $string);
    }

    private function isAbsolute($urlValue)
    {
        return preg_match('/^(http|https):\/\//', $urlValue) > 0;
    }
}
