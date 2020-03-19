<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2010-2013, Sebastian Bergmann <sebastian@phpunit.de>.
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
 * @author     Giorgio Sironi <info@giorgiosironi.com>
 * @copyright  2010-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 1.2.0
 */

namespace PHPUnit\Extensions;

use Exception;
use InvalidArgumentException;
use PHPUnit\Extensions\Selenium2TestCase\Element;
use PHPUnit\Extensions\Selenium2TestCase\Element\Select;
use PHPUnit\Extensions\Selenium2TestCase\ElementCriteria;
use PHPUnit\Extensions\Selenium2TestCase\KeysHolder;
use PHPUnit\Extensions\Selenium2TestCase\NoSeleniumException;
use PHPUnit\Extensions\Selenium2TestCase\Session;
use PHPUnit\Extensions\Selenium2TestCase\Session\Timeouts;
use PHPUnit\Extensions\Selenium2TestCase\SessionStrategy;
use PHPUnit\Extensions\Selenium2TestCase\SessionStrategy\Isolated;
use PHPUnit\Extensions\Selenium2TestCase\SessionStrategy\Shared;
use PHPUnit\Extensions\Selenium2TestCase\URL;
use PHPUnit\Extensions\Selenium2TestCase\WaitUntil;
use PHPUnit\Extensions\Selenium2TestCase\Window;
use PHPUnit\Extensions\SeleniumCommon\RemoteCoverage;
use PHPUnit\Framework\InvalidArgumentException as PHPUnitInvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;
use RuntimeException;
use Throwable;

/**
 * TestCase class that uses Selenium 2
 * (WebDriver API and JsonWire protocol) to provide
 * the functionality required for web testing.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <info@giorgiosironi.com>
 * @copyright  2010-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 1.2.0
 * @method void acceptAlert() Press OK on an alert, or confirms a dialog
 * @method mixed alertText() alertText($value = NULL) Gets the alert dialog text, or sets the text for a prompt dialog
 * @method void back()
 * @method Element byClassName() byClassName($value)
 * @method Element byCssSelector() byCssSelector($value)
 * @method Element byId() byId($value)
 * @method Element byLinkText() byLinkText($value)
 * @method Element byPartialLinkText() byPartialLinkText($value)
 * @method Element byName() byName($value)
 * @method Element byTag() byTag($value)
 * @method Element byXPath() byXPath($value)
 * @method void click() click(int $button = 0) Click any mouse button (at the coordinates set by the last moveto command).
 * @method void clickOnElement() clickOnElement($id)
 * @method string currentScreenshot() BLOB of the image file
 * @method void dismissAlert() Press Cancel on an alert, or does not confirm a dialog
 * @method void doubleclick() Double clicks (at the coordinates set by the last moveto command).
 * @method Element element() element(ElementCriteria $criteria) Retrieves an element
 * @method array elements() elements(ElementCriteria $criteria) Retrieves an array of Element instances
 * @method string execute() execute($javaScriptCode) Injects arbitrary JavaScript in the page and returns the last
 * @method string executeAsync() executeAsync($javaScriptCode) Injects arbitrary JavaScript and wait for the callback (last element of arguments) to be called
 * @method void forward()
 * @method void frame() frame(mixed $element) Changes the focus to a frame in the page (by frameCount of type int, htmlId of type string, htmlName of type string or element of type Element)
 * @method void moveto() moveto(Element $element) Move the mouse by an offset of the specificed element.
 * @method void refresh()
 * @method Select select() select($element)
 * @method string source() Returns the HTML source of the page
 * @method Timeouts timeouts()
 * @method string title()
 * @method void|string url() url($url = NULL)
 * @method ElementCriteria using() using($strategy) Factory Method for Criteria objects
 * @method void window() window($name) Changes the focus to another window
 * @method string windowHandle() Retrieves the current window handle
 * @method string windowHandles() Retrieves a list of all available window handles
 * @method string keys($string) Send a sequence of key strokes to the active element.
 * @method string file($file_path) Upload a local file. Returns the fully qualified path to the transferred file.
 * @method array log(string $type) Get the log for a given log type. Log buffer is reset after each request.
 * @method array logTypes() Get available log types.
 * @method void closeWindow() Close the current window.
 * @method void stop() Close the current window and clear session data.
 * @method Element active() Get the element on the page that currently has focus.
 * @method Window currentWindow() get the current Window Object
 */
abstract class Selenium2TestCase extends TestCase
{
    const VERSION = '8.0.0';

    /**
     * @var string  override to provide code coverage data from the server
     */
    protected $coverageScriptUrl;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var SessionStrategy
     */
    protected static $sessionStrategy;

    /**
     * @var SessionStrategy
     */
    protected static $browserSessionStrategy;

    /**
     * Default timeout for wait until, ms
     *
     * @var int
     */
    private static $defaultWaitUntilTimeout = 0;

    /**
     * Default timeout for wait until, ms
     *
     * @var int
     */
    private static $defaultWaitUntilSleepInterval = 500;

    /**
     * @var SessionStrategy
     */
    protected $localSessionStrategy;

    /**
     * @var array
     */
    private static $lastBrowserParams;

    /**
     * @var string
     */
    private $testId;

    /**
     * @var boolean
     */
    private $collectCodeCoverageInformation;

    /**
     * @var KeysHolder
     */
    private $keysHolder;

    /**
     * @param boolean
     */
    private static $keepSessionOnFailure = FALSE;

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
            self::$sessionStrategy = new Shared(
              self::defaultSessionStrategy(), self::$keepSessionOnFailure
              );
        }
    }

    public static function keepSessionOnFailure($keepSession)
    {
      if (!is_bool($keepSession)) {
            throw new InvalidArgumentException("The keep session on fail support can only be switched on or off.");
        }
      if ($keepSession){
            self::$keepSessionOnFailure = TRUE;
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
        return new Isolated;
    }

    /**
     * Get the default timeout for WaitUntil
     * @return int the default timeout
     */
    public static function defaultWaitUntilTimeout(){
        return self::$defaultWaitUntilTimeout;
    }

    /**
     * Set the default timeout for WaitUntil
     * @param int $timeout the new default timeout
     */
    public static function setDefaultWaitUntilTimeout($timeout){
        $timeout = (int) $timeout;
        self::$defaultWaitUntilTimeout = $timeout > 0 ? $timeout : 0;
    }

    /**
     * Get the default sleep delay for WaitUntil
     * @return int
     */
    public static function defaultWaitUntilSleepInterval(){
        return self::$defaultWaitUntilSleepInterval;
    }

    /**
     * Set default sleep delay for WaitUntil
     * @param int $sleepDelay the new default sleep delay
     */
    public static function setDefaultWaitUntilSleepInterval($sleepDelay){
        $sleepDelay = (int) $sleepDelay;
        self::$defaultWaitUntilSleepInterval = $sleepDelay > 0 ? $sleepDelay : 0;
    }


    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->parameters = array(
            'host' => 'localhost',
            'port' => 4444,
            'browser' => NULL,
            'browserName' => NULL,
            'desiredCapabilities' => array(),
            'seleniumServerRequestsTimeout' => 60,
            'secure' => FALSE
        );

        $this->keysHolder = new KeysHolder();
    }

    public function setupSpecificBrowser($params)
    {
        if (isset($params['keepSession'])) {
            $this->keepSessionOnFailure(TRUE);
        }
        $this->setUpSessionStrategy($params);
        $params = array_merge($this->parameters, $params);
        $this->setHost($params['host']);
        $this->setPort($params['port']);
        $this->setBrowser($params['browserName']);
        $this->parameters['browser'] = $params['browser'];
        $this->setDesiredCapabilities($params['desiredCapabilities']);
        $this->setSeleniumServerRequestsTimeout(
            $params['seleniumServerRequestsTimeout']);
    }

    protected function setUpSessionStrategy($params)
    {
        // This logic enables us to have a session strategy reused for each
        // item in self::$browsers. We don't want them both to share one
        // and we don't want each test for a specific browser to have a
        // new strategy
        if ($params == self::$lastBrowserParams) {
            // do nothing so we use the same session strategy for this
            // browser
        } elseif (isset($params['sessionStrategy'])) {
            $strat = $params['sessionStrategy'];
            if ($strat != "isolated" && $strat != "shared") {
                throw new InvalidArgumentException("Session strategy must be either 'isolated' or 'shared'");
            } elseif ($strat == "isolated") {
                self::$browserSessionStrategy = new Isolated;
            } else {
                self::$browserSessionStrategy = new Shared(self::defaultSessionStrategy(), self::$keepSessionOnFailure);
            }
        } else {
            self::$browserSessionStrategy = self::defaultSessionStrategy();
        }
        self::$lastBrowserParams = $params;
        $this->localSessionStrategy = self::$browserSessionStrategy;

    }

    private function getStrategy()
    {
        if ($this->localSessionStrategy) {
            return $this->localSessionStrategy;
        } else {
            return self::sessionStrategy();
        }
    }

    public function prepareSession()
    {
        try {
            if (!$this->session) {
                $this->session = $this->getStrategy()->session($this->parameters);
            }
        } catch (NoSeleniumException $e) {
            $this->markTestSkipped("The Selenium Server is not active on host {$this->parameters['host']} at port {$this->parameters['port']}.");
        }
        return $this->session;
    }

    public function run(TestResult $result = NULL): TestResult
    {
        $this->testId = get_class($this) . '__' . $this->getName();

        if ($result === NULL) {
            $result = $this->createResult();
        }

        $this->collectCodeCoverageInformation = $result->getCollectCodeCoverageInformation() && $this->coverageScriptUrl;

        parent::run($result);

        if ($this->collectCodeCoverageInformation) {
            $coverage = new RemoteCoverage(
                $this->coverageScriptUrl,
                $this->testId
            );
            $result->getCodeCoverage()->append(
                $coverage->get(), $this
            );
        }

        // do not call this before to give the time to the Listeners to run
        $this->getStrategy()->endOfTest($this->session);

        return $result;
    }

    /**
     * @throws RuntimeException
     * @throws Exception
     */
    protected function runTest()
    {
        $this->prepareSession();

        $thrownException = NULL;

        if ($this->collectCodeCoverageInformation) {
            $this->url($this->coverageScriptUrl);   // phpunit_coverage.php won't do anything if the cookie isn't set, which is exactly what we want
            $this->session->cookie()->add('PHPUNIT_SELENIUM_TEST_ID', $this->testId)->set();
        }

        try {
            $this->setUpPage();
            $result = parent::runTest();

            if (!empty($this->verificationErrors)) {
                $this->fail(implode("\n", $this->verificationErrors));
            }
        } catch (Exception $e) {
            $thrownException = $e;
        }

        if ($this->collectCodeCoverageInformation) {
            $this->session->cookie()->remove('PHPUNIT_SELENIUM_TEST_ID');
        }

        if (NULL !== $thrownException) {
            throw $thrownException;
        }

        return $result;
    }


    public static function suite($className)
    {
        return SeleniumTestSuite::fromTestCaseClass($className);
    }

    public function onNotSuccessfulTest(Throwable $e): void
    {
        $this->getStrategy()->notSuccessfulTest();
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
        if ($this->session === NULL) {
            throw new \PHPUnit\Extensions\Selenium2TestCase\Exception("There is currently no active session to execute the '$command' command. You're probably trying to set some option in setUp() with an incorrect setter name. You may consider using setUpPage() instead.");
        }
        $result = call_user_func_array(
          array($this->session, $command), $arguments
        );

        return $result;
    }

    /**
     * @param  string $host
     * @throws PHPUnitInvalidArgumentException
     */
    public function setHost($host)
    {
        if (!is_string($host)) {
            throw PHPUnitInvalidArgumentException::create(1, 'string');
        }

        $this->parameters['host'] = $host;
    }

    public function getHost()
    {
        return $this->parameters['host'];
    }

    /**
     * @param  integer $port
     * @throws PHPUnitInvalidArgumentException
     */
    public function setPort($port)
    {
        if (!is_int($port)) {
            throw PHPUnitInvalidArgumentException::create(1, 'integer');
        }

        $this->parameters['port'] = $port;
    }

    public function getPort()
    {
        return $this->parameters['port'];
    }

    /**
     * @param boolean $secure
     * @throws PHPUnitInvalidArgumentException
     */
    public function setSecure($secure)
    {
        if(!is_bool($secure)) {
            throw PHPUnitInvalidArgumentException::create(1, 'boolean');
        }

        $this->parameters['secure'] = $secure;
    }

    public function getSecure()
    {
        return $this->parameters['secure'];
    }

    /**
     * @param  string $browser
     * @throws PHPUnitInvalidArgumentException
     */
    public function setBrowser($browserName)
    {
        if (!is_string($browserName)) {
            throw PHPUnitInvalidArgumentException::create(1, 'string');
        }

        $this->parameters['browserName'] = $browserName;
    }

    public function getBrowser()
    {
        return $this->parameters['browserName'];
    }

    /**
     * @param  string $browserUrl
     * @throws PHPUnitInvalidArgumentException
     */
    public function setBrowserUrl($browserUrl)
    {
        if (!is_string($browserUrl)) {
            throw PHPUnitInvalidArgumentException::create(1, 'string');
        }

        $this->parameters['browserUrl'] = new URL($browserUrl);
    }

    public function getBrowserUrl()
    {
        if (isset($this->parameters['browserUrl'])) {
            return $this->parameters['browserUrl'];
        }
        return '';
    }

    /**
     * @see http://code.google.com/p/selenium/wiki/JsonWireProtocol
     */
    public function setDesiredCapabilities(array $capabilities)
    {
        $this->parameters['desiredCapabilities'] = $capabilities;
    }


    public function getDesiredCapabilities()
    {
        return $this->parameters['desiredCapabilities'];
    }

    /**
     * @param int $timeout  seconds
     */
    public function setSeleniumServerRequestsTimeout($timeout)
    {
        $this->parameters['seleniumServerRequestsTimeout'] = $timeout;
    }

    public function getSeleniumServerRequestsTimeout()
    {
        return $this->parameters['seleniumServerRequestsTimeout'];
    }

    /**
     * Get test id (generated internally)
     * @return string
     */
    public function getTestId()
    {
        return $this->testId;
    }

    /**
     * Get Selenium2 current session id
     * @return string
     */
    public function getSessionId()
    {
        if ($this->session) {
            return $this->session->id();
        }
        return FALSE;
    }

    /**
     * Wait until callback isn't null or timeout occurs
     *
     * @param $callback
     * @param null $timeout
     * @return mixed
     */
    public function waitUntil($callback, $timeout = NULL)
    {
        $waitUntil = new WaitUntil($this);
        return $waitUntil->run($callback, $timeout);
    }

    /**
     * Sends a special key
     * Deprecated due to issues with IE webdriver. Use keys() method instead
     * @deprecated
     * @param string $name
     * @throws \PHPUnit\Extensions\Selenium2TestCase\Exception
     * @see KeysHolder
     */
    public function keysSpecial($name)
    {
        $names = explode(',', $name);

        foreach ($names as $key) {
            $this->keys($this->keysHolder->specialKey(trim($key)));
        }
    }

    /**
     * setUp method that is called after the session has been prepared.
     * It is possible to use session-specific commands like url() here.
     */
    public function setUpPage()
    {

    }

    /**
     * Check whether an alert box is present
     */
    public function alertIsPresent()
    {
        try {
            $this->alertText();
            return TRUE;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
