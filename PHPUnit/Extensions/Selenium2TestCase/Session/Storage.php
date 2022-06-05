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
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Manage the local storage HTML 5 database.
 */
class Storage
{
    private $driver;
    private $url;

    public function __construct(Driver $driver, URL $url)
    {
        $this->driver = $driver;
        $this->url    = $url;
    }

    public function __set($name, $value)
    {
        $this->driver->curl('POST', $this->url, [
            'key' => $name,
            'value' => (string) $value,
        ]);
    }

    public function __get($name)
    {
        return $this->driver->curl(
            'GET',
            $this->url->descend('key')->descend($name)
        )->getValue();
    }
}
