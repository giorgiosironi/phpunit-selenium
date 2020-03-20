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
 * Object representing an HTTP response from the Selenium Server.
 */
class Response
{
    /** @var array   decoded response */
    private $jsonResponse;

    /** @var array   CURL info for the response. */
    private $info;

    public function __construct($jsonResponse, $info)
    {
        $this->jsonResponse = $jsonResponse;
        $this->info         = $info;
    }

    public function getValue()
    {
        if (isset($this->jsonResponse['value'])) {
            return $this->jsonResponse['value'];
        }
    }

    /**
     * @return URL
     */
    public function getURL()
    {
        $url       = $this->info['url'];
        $sessionId = $this->jsonResponse['sessionId'];

        // if url doesn't have sessionId included - append it manually
        // this change was performed in selenium v2.34
        // @see https://code.google.com/p/selenium/issues/detail?id=6089
        // @see https://github.com/sebastianbergmann/phpunit-selenium/issues/265
        if (strpos($url, $sessionId) === false) {
            $url .= '/' . $sessionId;
        }

        return new URL($url);
    }
}
