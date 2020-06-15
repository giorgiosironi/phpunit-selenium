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

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromHostAndPort(string $host, int $port, bool $secure): URL
    {
        $prefix = 'http://';
        if ($secure) {
            $prefix = 'https://';
        }

        return new self($prefix . $host . ':' . $port);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function descend(?string $addition): URL
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

    public function ascend(): URL
    {
        $lastSlash = strrpos($this->value, '/');
        $newValue  = substr($this->value, 0, $lastSlash);

        return new self($newValue);
    }

    public function lastSegment(): string
    {
        $segments = explode('/', $this->value);

        return end($segments);
    }

    public function addCommand(string $command): URL
    {
        return $this->descend($this->camelCaseToUnderScores($command));
    }

    public function jump(string $newUrl): URL
    {
        if ($this->isAbsolute($newUrl)) {
            return new self($newUrl);
        } else {
            return $this->descend($newUrl);
        }
    }

    private function camelCaseToUnderScores(string $string): string
    {
        $string = preg_replace('/([A-Z]{1,1})/', ' \1', $string);
        $string = strtolower($string);

        return str_replace(' ', '_', $string);
    }

    private function isAbsolute(string $urlValue): bool
    {
        return preg_match('/^(http|https):\/\//', $urlValue) > 0;
    }
}
