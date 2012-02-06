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
 * Object representing a DOM element.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <giorgio.sironi@asp-poli.it>
 * @copyright  2010-2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 1.2.0
 * @method void click()
 * @method string value($newValue = NULL) Get or set value of form elements
 * @method string text() Get content of ordinary elements
 */
class PHPUnit_Extensions_Selenium2TestCase_Element
{
    /**
     * @var PHPUnit_Extensions_Selenium2TestCase_Driver
     */
    private $driver;

    /**
     * @var string  the API URL for this element,
     */
    private $url;

    /**
     * @var array   instances of 
     *              PHPUnit_Extensions_Selenium2TestCase_ElementCommand
     */
    private $commands;

    public function __construct($driver,
                                PHPUnit_Extensions_Selenium2TestCase_URL $url)
    {
        $this->driver = $driver;
        $this->url = $url;
        $this->commands = array(
            'click' => 'PHPUnit_Extensions_Selenium2TestCase_ElementCommand_Click',
            'value' => 'PHPUnit_Extensions_Selenium2TestCase_ElementCommand_Value',
            'text' => 'PHPUnit_Extensions_Selenium2TestCase_ElementCommand_GenericAccessor'
        );
    }

    public function __call($commandName, $arguments)
    {
        if (count($arguments) > 1) {
            throw new InvalidArgumentException("At most 1 argument can be passed.");
        }
        if ($arguments === array()) {
            $jsonParameters = NULL;
        } else {
            $jsonParameters = $arguments[0];
        }
        $response = $this->driver->execute($this->newCommand($commandName, $jsonParameters));
        return $response->getValue();
    }

    private function newCommand($commandName, $jsonParameters)
    {
        if (isset($this->commands[$commandName])) {
            $className = $this->commands[$commandName];
            $url = $this->url->addCommand($commandName);
            $command = new $className($jsonParameters, $url);
            return $command;
        }
        throw new RuntimeException("The command '$commandName' is not supported yet.");
    }
}
