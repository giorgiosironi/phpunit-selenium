<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase\Session\Cookie;

// phpcs:disable SlevomatCodingStandard.Classes.UnusedPrivateElements
use PHPUnit\Extensions\Selenium2TestCase\Session\Cookie;

/**
 * Adds a cookie.
 */
class Builder
{
    private $cookieFacade;
    private $name;
    private $value;
    private $path;
    private $domain;
    private $secure = false;
    private $expiry;

    public function __construct(Cookie $cookieFacade, string $name, string $value)
    {
        $this->cookieFacade = $cookieFacade;
        $this->name         = $name;
        $this->value        = $value;
    }

    public function path(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function domain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function secure(bool $secure): self
    {
        $this->secure = $secure;

        return $this;
    }

    public function expiry(int $expiry): self
    {
        $this->expiry = $expiry;

        return $this;
    }

    public function set(): void
    {
        $cookieData = [
            'name' => $this->name,
            'value' => $this->value,
            'secure' => $this->secure,
        ];
        foreach (['path', 'domain', 'expiry'] as $parameter) {
            if ($this->$parameter !== null) {
                $cookieData[$parameter] = $this->$parameter;
            }
        }

        $this->cookieFacade->postCookie($cookieData);
    }
}
