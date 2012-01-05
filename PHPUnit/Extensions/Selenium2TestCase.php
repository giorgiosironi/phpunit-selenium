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
 * TestCase class that uses Selenium 2
 * (WebDriver API and JsonWire protocol) to provide
 * the functionality required for web testing.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <giorgio.sironi@asp-poli.it>
 * @copyright  2010-2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 1.2.0
 */
abstract class PHPUnit_Extensions_Selenium2TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Extensions_Selenium2TestCase_Session
     */
    private $session;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $browser;

    /**
     * @var PHPUnit_Extensions_Selenium2TestCase_URL
     */
    private $browserUrl;

    /**
     * @var boolean
     */
    private static $shareSession;

    /**
     * @var PHPUnit_Extensions_Selenium2TestCase_URL
     */
    private static $sharedSessionUrl;

    /**
     * @param boolean
     */
    public static function shareSession($shareSession)
    {
        self::$shareSession = $shareSession;
    }

    /**
     * @throws RuntimeException
     */
    protected function runTest()
    {
        $driver = $this->getDriver();

        if (self::$shareSession and self::$sharedSessionUrl !== NULL) {
            $this->session = new PHPUnit_Extensions_Selenium2TestCase_Session($driver, self::$sharedSessionUrl, $this->browserUrl);
        } else {
            $this->session = $driver->startSession($this->browser, $this->browserUrl);
            self::$sharedSessionUrl = $this->session->getSessionUrl();
        }

        parent::runTest();

        if (!empty($this->verificationErrors)) {
            $this->fail(implode("\n", $this->verificationErrors));
        }

        if (!self::$shareSession) {
            $this->session->stop();
        }
    }

    public function onNotSuccessfulTest(Exception $e)
    {
        self::$sharedSessionUrl = NULL;
        parent::onNotSuccessfulTest($e);
    }

    /**
     * Delegate method calls to the driver.
     *
     * @param  string $command
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($command, $arguments)
    {
        $result = call_user_func_array(
          array($this->session, $command), $arguments
        );

        return $result;
    }

    private function getDriver()
    {
        $seleniumServerUrl = PHPUnit_Extensions_Selenium2TestCase_URL::fromHostAndPort($this->host, $this->port);
        return new PHPUnit_Extensions_Selenium2TestCase_Driver($seleniumServerUrl);
    }

    /**
     * @param  string $host
     * @throws InvalidArgumentException
     */
    public function setHost($host)
    {
        if (!is_string($host)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        $this->host = $host;
    }

    /**
     * @param  integer $port
     * @throws InvalidArgumentException
     */
    public function setPort($port)
    {
        if (!is_int($port)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'integer');
        }

        $this->port = $port;
    }

    /**
     * @param  string $browser
     * @throws InvalidArgumentException
     */
    public function setBrowser($browser)
    {
        if (!is_string($browser)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        $this->browser = $browser;
    }

    /**
     * @param  string $browserUrl
     * @throws InvalidArgumentException
     */
    public function setBrowserUrl($browserUrl)
    {
        if (!is_string($browserUrl)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        $this->browserUrl = new PHPUnit_Extensions_Selenium2TestCase_URL($browserUrl);
    }
}
