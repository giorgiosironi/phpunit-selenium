<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2010-2011, Sebastian Bergmann <sb@sebastian-bergmann.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <giorgio.sironi@asp-poli.it>
 * @copyright  2010-2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 1.2.0
 */

/**
 * Implementation of the Selenium RC client/server protocol.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <giorgio.sironi@asp-poli.it>
 * @copyright  2010-2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 1.2.0
 */
class PHPUnit_Extensions_Selenium2TestCase_Driver
{
    public function __construct($host, $port, $browser)
    {
        $this->browser = $browser;
        $this->host = $host;
        $this->port = $port;
    }

    public function startSession($browserUrl)
    {
        $this->url = "http://{$this->host}:{$this->port}/wd/hub";
        $response = $this->curl('POST', $this->url . '/session', array(
            'desiredCapabilities' => array(
                'browserName' => $this->browser
            )
        ));
        $sessionPrefix = $response->getUrl() . '/';
        return new PHPUnit_Extensions_Selenium2TestCase_Session($this, $sessionPrefix, $browserUrl);
    }

    /**
     * Performs an HTTP request to the Selenium 2 server.
     *
     * @param string $method      'GET'|'POST'|'DELETE'|...
     * @param string $url
     * @param array $params       JSON parameters for POST requests
     */
    public function curl($http_method, $url, $params = null) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array('application/json;charset=UTF-8'));

        if ($http_method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            if ($params && is_array($params)) {
               curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
            }
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        } else if ($http_method == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $rawResponse = trim(curl_exec($curl));
        $info = curl_getinfo($curl);
        curl_close($curl);
        $content = json_decode($rawResponse, true);
        return new PHPUnit_Extensions_Selenium2TestCase_Response($content, $info);
    }
}
