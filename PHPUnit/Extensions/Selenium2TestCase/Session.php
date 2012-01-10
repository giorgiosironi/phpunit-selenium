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
 * Browser session for Selenium 2: main point of entry for functionality.
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
        $this->commandFactories = array(
            'acceptAlert' => $this->factoryMethod('PHPUnit_Extensions_Selenium2TestCase_SessionCommand_AcceptAlert'),
            'alertText' => $this->factoryMethod('PHPUnit_Extensions_Selenium2TestCase_SessionCommand_GenericAccessor'),
            'dismissAlert' => $this->factoryMethod('PHPUnit_Extensions_Selenium2TestCase_SessionCommand_DismissAlert'),
            'title' => $this->factoryMethod('PHPUnit_Extensions_Selenium2TestCase_SessionCommand_GenericAccessor'),
            'url' => function ($jsonParameters, $commandUrl) use ($baseUrl) {
                return new PHPUnit_Extensions_Selenium2TestCase_SessionCommand_Url($jsonParameters, $commandUrl, $baseUrl);
            }
        );
    }

    /**
     * @params string $commandClass     a class name, descending from
                                        PHPUnit_Extensions_Selenium2TestCase_Command
     * @return callable
     */
    private function factoryMethod($commandClass)
    {
        return function($jsonParameters, $url) use ($commandClass) {
            return new $commandClass($jsonParameters, $url);
        };
    }

    public function __destruct()
    {
        $this->stop();
    }

    /**
     * @return PHPUnit_Extensions_Selenium2TestCase_URL
     */
    public function getSessionUrl()
    {
        return $this->sessionUrl;
    }

    public function stop()
    {
        $this->driver->curl('DELETE', $this->sessionUrl);
    }

    public function __call($commandName, $arguments)
    {
        $jsonParameters = $this->extractJsonParameters($arguments);
        $response = $this->driver->execute($this->newCommand($commandName, $jsonParameters));
        return $response->getValue();
    }

    private function extractJsonParameters($arguments)
    {
        $this->checkArguments($arguments);

        if (count($arguments) == 0) {
            return NULL;
        }
        return $arguments[0];
    }

    private function checkArguments($arguments)
    {
        if (count($arguments) > 1) {
            throw new Exception('You cannot call a command with multiple method arguments.');
        }
    }

    /**
     * @return string
     */
    private function newCommand($commandName, $arguments)
    {
        if (isset($this->commandFactories[$commandName])) {
            $factoryMethod = $this->commandFactories[$commandName];
            $commandUrl = $this->sessionUrl->addCommand($commandName);
            $commandObject = $factoryMethod($arguments, $commandUrl);
            return $commandObject;
        }
        throw new BadMethodCallException("The command '$commandName' is not existent or not supported.");
    }

    /**
     * @param string $value     e.g. 'container'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byClassName($value)
    {
        return $this->by('class name', $value);
    }

    /**
     * @param string $value     e.g. 'div.container'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byCssSelector($value)
    {
        return $this->by('css selector', $value);
    }

    /**
     * @param string $value     e.g. 'uniqueId'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byId($value)
    {
        return $this->by('id', $value);
    }

    /**
     * @param string $value     e.g. 'email_address'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byName($value)
    {
        return $this->by('name', $value);
    }

    /**
     * @param string $value     e.g. '/div[@attribute="value"]'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byXPath($value)
    {
        return $this->by('xpath', $value);
    }

    /**
     * @param string $strategy     supported by JsonWireProtocol element/ command
     * @param string $value
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected function by($strategy, $value)
    {
        return $this->element($this->using($strategy)->value($value));
    }

    /**
     * @param string $strategy
     * @return PHPUnit_Extensions_Selenium2TestCase_ElementCriteria
     */
    public function using($strategy)
    {
        return new PHPUnit_Extensions_Selenium2TestCase_ElementCriteria($strategy);
    }

    /**
     * @return PHPUnit_Extensions_Selenium2TestCase_ElementCriteria
     */
    public function element(PHPUnit_Extensions_Selenium2TestCase_ElementCriteria $jsonParameters)
    {
        $response = $this->driver->curl('POST',
                                        $this->sessionUrl->descend('element'),
                                        $jsonParameters->getArrayCopy());
        $value = $response->getValue();
        $url = $this->sessionUrl->descend('element')->descend($value['ELEMENT']);
        return new PHPUnit_Extensions_Selenium2TestCase_Element($this->driver, $url);
    }

    /**
     * @param string $id    id attribute, e.g. 'container'
     * @return void
     */
    public function clickOnElement($id)
    {
        return $this->element($this->using('id')->value($id))->click();
    }

}
