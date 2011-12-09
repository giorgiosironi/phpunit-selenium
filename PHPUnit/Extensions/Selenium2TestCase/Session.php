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
class PHPUnit_Extensions_Selenium2TestCase_Session
{
    /**
     * @var PHPUnit_Extensions_Selenium2TestCase_Driver
     */
    private $driver;

    /**
     * <code>localhost:80/.../session/42</code>
     * @var string  the session URL in Selenium 2 API
     */
    private $sessionUrl;

    /**
     * @var string  the base URL for this session,
     *              which all relative URLs will refer to
     */
    private $baseUrl;

    public function __construct($driver,
                                PHPUnit_Extensions_Selenium2TestCase_URL $sessionUrl,
                                PHPUnit_Extensions_Selenium2TestCase_URL $baseUrl)
    {
        $this->driver = $driver;
        $this->sessionUrl = $sessionUrl;
        $this->baseUrl = $baseUrl;
    }

    public function stop()
    {
        $this->curl('DELETE', $this->sessionUrl);
    }

    public function __call($command, $arguments)
    {
        if (count($arguments) == 1) {
            if (is_string($arguments[0])) {
                $jsonParameters = array('url' => $this->baseUrl->descend($arguments[0])->getValue()); 
            } else if (is_array($arguments[0])) {
                $jsonParameters = $arguments[0];
            } else {
                throw new Exception("The argument should be an associative array or a single string.");
            }
            $response = $this->curl('POST', $this->sessionUrl->descend($command), $jsonParameters);
        } else if (count($arguments) == 0) {
            $response = $this->curl('GET', $this->sessionUrl->descend($command)); 
        } else {
            throw new Exception('You cannot call a command with multiple method arguments.');
        }
        return $response->getValue();
    }

    /**
     * @param string $strategy
     */
    public function using($strategy)
    {
        return new PHPUnit_Extensions_Selenium2TestCase_ElementCriteria($strategy);
    }

    public function element($jsonParameters)
    {
        if (is_object($jsonParameters)) {
            $jsonParameters = $jsonParameters->getArrayCopy();
        }
        $response = $this->curl('POST', $this->sessionUrl->descend('element'), $jsonParameters);
        $value = $response->getValue();
        $url = $this->sessionUrl->descend('element')->descend($value['ELEMENT']);
        return new PHPUnit_Extensions_Selenium2TestCase_Element($this->driver, $url);
    }

    private function curl($method, $path, $arguments = null)
    {
        return $this->driver->curl($method, $path, $arguments);
    }
}
