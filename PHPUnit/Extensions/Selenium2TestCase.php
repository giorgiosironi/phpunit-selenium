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
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
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
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 1.2.0
 * @method void acceptAlert() Press OK on an alert, or confirms a dialog
 * @method mixed alertText($value = NULL) Gets the alert dialog text, or sets the text for a prompt dialog
 * @method void back()
 * @method \PHPUnit_Extensions_Selenium2TestCase_Element byCssSelector($value)
 * @method \PHPUnit_Extensions_Selenium2TestCase_Element byClassName($vaue)
 * @method \PHPUnit_Extensions_Selenium2TestCase_Element byId($value)
 * @method \PHPUnit_Extensions_Selenium2TestCase_Element byName($value)
 * @method \PHPUnit_Extensions_Selenium2TestCase_Element byXPath($value)
 * @method void clickOnElement($id)
 * @method string currentScreenshot() BLOB of the image file
 * @method void dismissAlert() Press Cancel on an alert, or does not confirm a dialog
 * @method \PHPUnit_Extensions_Selenium2TestCase_Element element(\PHPUnit_Extensions_Selenium2TestCase_ElementCriteria $criteria) Retrieves an element
 * @method array elements(\PHPUnit_Extensions_Selenium2TestCase_ElementCriteria $criteria) Retrieves an array of Element instances
 * @method string execute($javaScriptCode) Injects arbitrary JavaScript in the page and returns the last
 * @method string executeAsync($javaScriptCode) Injects arbitrary JavaScript and wait for the callback (last element of arguments) to be called
 * @method void forward()
 * @method void frame($elementId) Changes the focus to a frame in the page
 * @method void refresh()
 * @method \PHPUnit_Extensions_Selenium2TestCase_Element_Select select($element)
 * @method string source() Returns the HTML source of the page
 * @method \PHPUnit_Extensions_Selenium2TestCase_Session_Timeouts timeouts()
 * @method string title()
 * @method void|string url($url = NULL)
 * @method PHPUnit_Extensions_Selenium2TestCase_ElementCriteria using($strategy) Factory Method for Criteria objects
 * @method void window($name) Changes the focus to another window
 * @method string windowHandle() Retrieves the current window handle
 * @method string windowHandles() Retrieves a list of all available window handles
 * @method string keys() Send a sequence of key strokes to the active element.
 */
abstract class PHPUnit_Extensions_Selenium2TestCase extends PHPUnit_Framework_TestCase
{
    const VERSION = "1.2.7";

    /**
     * @var string  override to provide code coverage data from the server
     */
    protected $coverageScriptUrl;

    /**
     * @var PHPUnit_Extensions_Selenium2TestCase_Session
     */
    private $session;

    /**
     * @var string
     */
    private $host = 'localhost';

    /**
     * @var int
     */
    private $port = 4444;

    /**
     * @var array
     */
    private $desiredCapabilities = array();

    /**
     * @var string
     */
    private $browser;

    /**
     * @var PHPUnit_Extensions_Selenium2TestCase_URL
     */
    private $browserUrl;

    /**
     * @var PHPUnit_Extensions_Selenium2TestCase_SessionStrategy
     */
    private static $sessionStrategy;

    /**
     * @var string
     */
    private $testId;

    /**
     * @var boolean
     */
    private $collectCodeCoverageInformation;

    /**
     * @param boolean
     */
    public static function shareSession($shareSession)
    {
        if (!is_bool($shareSession)) {
            throw new InvalidArgumentException("The shared session support can only be switched on or off.");
        }
        if (!$shareSession) {
            self::$sessionStrategy = self::defaultSessionStrategy();
        } else {
            echo "Shared strategy\n";
            self::$sessionStrategy = new PHPUnit_Extensions_Selenium2TestCase_SessionStrategy_Shared(self::defaultSessionStrategy());
        }
    }

    private static function sessionStrategy()
    {
        if (!self::$sessionStrategy) {
            self::$sessionStrategy = self::defaultSessionStrategy();
        }
        return self::$sessionStrategy;
    }

    private static function defaultSessionStrategy()
    {
        return new PHPUnit_Extensions_Selenium2TestCase_SessionStrategy_Isolated;
    }

    public function prepareSession()
    {
        if (!$this->session) {
            $this->session = self::sessionStrategy()->session(array(
                'host'      => $this->host,
                'port'       => $this->port,
                'browserName' => $this->browserName,
                'browserUrl' => $this->browserUrl,
                'desiredCapabilities' => $this->desiredCapabilities
            ));
        }
        return $this->session;
    }

    public function run(PHPUnit_Framework_TestResult $result = NULL)
    {
        $this->testId = get_class($this) . '__' . $this->getName();

        if ($result === NULL) {
            $result = $this->createResult();
        }

        $this->collectCodeCoverageInformation = $result->getCollectCodeCoverageInformation();

        parent::run($result);

        if ($this->collectCodeCoverageInformation) {
            $coverage = new PHPUnit_Extensions_SeleniumCommon_RemoteCoverage(
                $this->coverageScriptUrl,
                $this->testId
            );
            $result->getCodeCoverage()->append(
                $coverage->get(), $this
            );
        }

        return $result;
    }

    /**
     * @throws RuntimeException
     */
    protected function runTest()
    {
        $this->prepareSession();
        $this->url('');

        $thrownException = NULL;

        if ($this->collectCodeCoverageInformation) {
            $this->session->cookie()->remove('PHPUNIT_SELENIUM_TEST_ID');
            $this->session->cookie()->add('PHPUNIT_SELENIUM_TEST_ID', $this->testId)->set();
        }

        try {
            $result = parent::runTest();

            if (!empty($this->verificationErrors)) {
                $this->fail(implode("\n", $this->verificationErrors));
            }
        } catch (Exception $e) {
            $thrownException = $e;
        }

        self::sessionStrategy()->endOfTest($this->session);

        if (NULL !== $thrownException) {
            throw $thrownException;
        }

        return $result;
    }

    public function onNotSuccessfulTest(Exception $e)
    {
        self::sessionStrategy()->notSuccessfulTest();
        parent::onNotSuccessfulTest($e);
    }

    /**
     * Delegate method calls to the Session.
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
    public function setBrowser($browserName)
    {
        if (!is_string($browserName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        $this->browserName = $browserName;
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

    /**
     * @see http://code.google.com/p/selenium/wiki/JsonWireProtocol
     */
    public function setDesiredCapabilities(array $capabilities)
    {
        $this->desiredCapabilities = $capabilities;
    }
}
