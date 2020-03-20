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
/**
 * Adds a cookie.
 */
class Builder
{
    private $name;
    private $value;
    private $path;
    private $domain;
    private $secure = false;
    private $expiry;

    public function __construct($cookieFacade, $name, $value)
    {
        $this->cookieFacade = $cookieFacade;
        $this->name         = $name;
        $this->value        = $value;
    }

    /**
     * @param string $path
     *
     * @return Builder
     */
    public function path($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $domain
     *
     * @return Builder
     */
    public function domain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @param bool $secure
     *
     * @return Builder
     */
    public function secure($secure)
    {
        $this->secure = $secure;

        return $this;
    }

    /**
     * @param int $expiry
     *
     * @return Builder
     */
    public function expiry($expiry)
    {
        $this->expiry = $expiry;

        return $this;
    }

    /**
     * @return void
     */
    public function set()
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
