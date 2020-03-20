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

use BadMethodCallException;
use PHPUnit\Extensions\Selenium2TestCase\Session\Timeouts;

/**
 * Driver for creating browser session with Selenium 2 (WebDriver API).
 */
class Driver
{
    private $seleniumServerUrl;
    private $seleniumServerRequestsTimeout;

    public function __construct(URL $seleniumServerUrl, $timeout = 60)
    {
        $this->seleniumServerUrl             = $seleniumServerUrl;
        $this->seleniumServerRequestsTimeout = $timeout;
    }

    public function startSession(array $desiredCapabilities, URL $browserUrl)
    {
        $sessionCreation = $this->seleniumServerUrl->descend('/wd/hub/session');
        $response        = $this->curl('POST', $sessionCreation, ['desiredCapabilities' => $desiredCapabilities,]);
        $sessionPrefix   = $response->getURL();

        $timeouts = new Timeouts(
            $this,
            $sessionPrefix->descend('timeouts'),
            $this->seleniumServerRequestsTimeout * 1000
        );

        return new Session(
            $this,
            $sessionPrefix,
            $browserUrl,
            $timeouts
        );
    }

    /**
     * Performs an HTTP request to the Selenium 2 server.
     *
     * @param string $method 'GET'|'POST'|'DELETE'|...
     * @param string $url
     * @param array  $params JSON parameters for POST requests
     */
    public function curl($httpMethod, URL $url, $params = null)
    {
        $curl = curl_init($url->getValue());
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->seleniumServerRequestsTimeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            [
                'Content-type: application/json;charset=UTF-8',
                'Accept: application/json;charset=UTF-8',
            ]
        );

        if ($httpMethod === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            if ($params && is_array($params)) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
            } else {
                curl_setopt($curl, CURLOPT_POSTFIELDS, '');
            }

            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        } elseif ($httpMethod === 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $rawResponse = trim(curl_exec($curl));
        if (curl_errno($curl)) {
            throw new NoSeleniumException(
                'Error connection[' . curl_errno($curl) . '] to ' .
                $url->getValue() . ': ' . curl_error($curl)
            );
        }

        $info = curl_getinfo($curl);
        if ($info['http_code'] === 0) {
            throw new NoSeleniumException();
        }

        if ($info['http_code'] === 404) {
            throw new BadMethodCallException(sprintf('The command %s is not recognized by the server.', $url));
        }

        if (($info['http_code'] >= 400) && ($info['http_code'] < 500)) {
            throw new BadMethodCallException(sprintf("Something unexpected happened: '%s'", $rawResponse));
        }

        curl_close($curl);
        $content = json_decode($rawResponse, true);

        if ($content === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \PHPUnit\Extensions\Selenium2TestCase\Exception(
                sprintf(
                    "JSON decoding of remote response failed.\n" .
                    "Error code: %d\n" .
                    "The response: '%s'\n",
                    json_last_error(),
                    $rawResponse
                )
            );
        }

        $value = null;
        if (is_array($content) && array_key_exists('value', $content)) {
            $value = $content['value'];
        }

        $message = null;
        if (is_array($value) && array_key_exists('message', $value)) {
            $message = $value['message'];
        }

        $status = $content['status'] ?? 0;
        if ($status !== WebDriverException::Success) {
            throw new WebDriverException($message, $status);
        }

        return new Response($content, $info);
    }

    public function execute(Command $command)
    {
        return $this->curl(
            $command->httpMethod(),
            $command->url(),
            $command->jsonParameters()
        );
    }
}
