<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase\Session;

use PHPUnit\Extensions\Selenium2TestCase\Driver;
use PHPUnit\Extensions\Selenium2TestCase\Session\Cookie\Builder;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Adds and remove cookies.
 */
class Cookie
{
    private $driver;
    private $url;

    public function __construct(Driver $driver, URL $url)
    {
        $this->driver = $driver;
        $this->url    = $url;
    }

    public function add(string $name, string $value): Builder
    {
        return new Builder($this, $name, $value);
    }

    public function get(string $name): string
    {
        $cookies = $this->driver->curl('GET', $this->url)->getValue();
        foreach ($cookies as $cookie) {
            if ($cookie['name'] === $name) {
                return $cookie['value'];
            }
        }

        throw new \PHPUnit\Extensions\Selenium2TestCase\Exception(sprintf("There is no '%s' cookie available on this page.", $name));
    }

    public function remove(string $name): void
    {
        $url = $this->url->descend($name);
        $this->driver->curl('DELETE', $url);
    }

    public function clear(): void
    {
        $this->driver->curl('DELETE', $this->url);
    }

    /**
     * @internal
     */
    public function postCookie(array $data): void
    {
        $this->driver->curl(
            'POST',
            $this->url,
            ['cookie' => $data,]
        );
    }
}
