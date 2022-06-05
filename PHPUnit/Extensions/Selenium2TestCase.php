<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;
use RuntimeException;
use Throwable;

/**
 * TestCase class that uses Selenium 2
 * (WebDriver API and JsonWire protocol) to provide
 * the functionality required for web testing.
 *
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

    public const VERSION = '9.0.0';

    /** @var string  override to provide code coverage data from the server */
    protected $coverageScriptUrl;

    /** @var Session */
    private $session;

    /** @var array */
    private $parameters;

    /** @var SessionStrategy */
    protected static $sessionStrategy;

    /** @var SessionStrategy */
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

    /** @var SessionStrategy */
    protected $localSessionStrategy;

    /** @var array */
    private static $lastBrowserParams;

    /** @var string */
    private $testId;

    /** @var bool */
    private $collectCodeCoverageInformation;

    /** @var KeysHolder */
    private $keysHolder;

    /** @param boolean */
    private static $keepSessionOnFailure = false;

    public static function shareSession(bool $shareSession): void
    {
        if (! $shareSession) {
            self::$sessionStrategy = self::defaultSessionStrategy();
        } else {
            self::$sessionStrategy = new Shared(
                self::defaultSessionStrategy(),
                self::$keepSessionOnFailure
            );
        }
    }

    public static function keepSessionOnFailure(bool $keepSession): void
    {
        if ($keepSession) {
            self::$keepSessionOnFailure = true;
        }
    }

    private static function sessionStrategy(): SessionStrategy
    {
        if (! self::$sessionStrategy) {
            self::$sessionStrategy = self::defaultSessionStrategy();
        }

        return self::$sessionStrategy;
    }

    private static function defaultSessionStrategy(): SessionStrategy
    {
        return new Isolated();
    }

    /**
     * Get the default timeout for WaitUntil
     */
    public static function defaultWaitUntilTimeout(): int
    {
        return self::$defaultWaitUntilTimeout;
    }

    /**
     * Set the default timeout for WaitUntil
     */
    public static function setDefaultWaitUntilTimeout(int $timeout): void
    {
        self::$defaultWaitUntilTimeout = $timeout > 0 ? $timeout : 0;
    }

    /**
     * Get the default sleep delay for WaitUntil
     */
    public static function defaultWaitUntilSleepInterval(): int
    {
        return self::$defaultWaitUntilSleepInterval;
    }

    /**
     * Set default sleep delay for WaitUntil
     */
    public static function setDefaultWaitUntilSleepInterval(int $sleepDelay): void
    {
        self::$defaultWaitUntilSleepInterval = $sleepDelay > 0 ? $sleepDelay : 0;
    }

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->parameters = [
            'host' => 'localhost',
            'port' => 4444,
            'browser' => null,
            'browserName' => null,
            'desiredCapabilities' => [],
            'seleniumServerRequestsTimeout' => 60,
            'secure' => false,
        ];

        $this->keysHolder = new KeysHolder();
    }

    public function setupSpecificBrowser(array $params): void
    {
        if (isset($params['keepSession'])) {
            $this->keepSessionOnFailure(true);
        }

        $this->setUpSessionStrategy($params);
        $params = array_merge($this->parameters, $params);
        $this->setHost($params['host']);
        $this->setPort($params['port']);
        $this->setBrowser($params['browserName']);
        $this->parameters['browser'] = $params['browser'];
        $this->setDesiredCapabilities($params['desiredCapabilities']);
        $this->setSeleniumServerRequestsTimeout(
            $params['seleniumServerRequestsTimeout']
        );
    }

    protected function setUpSessionStrategy(array $params): void
    {
        // This logic enables us to have a session strategy reused for each
        // item in self::$browsers. We don't want them both to share one
        // and we don't want each test for a specific browser to have a
        // new strategy

        // phpcs:disable Generic.CodeAnalysis.EmptyStatement
        if ($params === self::$lastBrowserParams) {
            // do nothing so we use the same session strategy for this browser
        } elseif (isset($params['sessionStrategy'])) {
            $strat = $params['sessionStrategy'];
            if ($strat !== 'isolated' && $strat !== 'shared') {
                throw new InvalidArgumentException("Session strategy must be either 'isolated' or 'shared'");
            } elseif ($strat === 'isolated') {
                self::$browserSessionStrategy = new Isolated();
            } else {
                self::$browserSessionStrategy = new Shared(self::defaultSessionStrategy(), self::$keepSessionOnFailure);
            }
        } else {
            self::$browserSessionStrategy = self::defaultSessionStrategy();
        }

        self::$lastBrowserParams    = $params;
        $this->localSessionStrategy = self::$browserSessionStrategy;
    }

    private function getStrategy(): SessionStrategy
    {
        if ($this->localSessionStrategy) {
            return $this->localSessionStrategy;
        } else {
            return self::sessionStrategy();
        }
    }

    public function prepareSession(): Session
    {
        try {
            if (! $this->session) {
                $this->session = $this->getStrategy()->session($this->parameters);
            }
        } catch (NoSeleniumException $e) {
            $this->markTestSkipped(sprintf('The Selenium Server is not active on host %s at port %s.', $this->parameters['host'], $this->parameters['port']));
        }

        return $this->session;
    }

    public function run(?TestResult $result = null): TestResult
    {
        $this->testId = static::class . '__' . $this->getName();

        if ($result === null) {
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
                $coverage->get(),
                $this
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

        $thrownException = null;

        if ($this->collectCodeCoverageInformation) {
            $this->url($this->coverageScriptUrl);   // phpunit_coverage.php won't do anything if the cookie isn't set, which is exactly what we want
            $this->session->cookie()->add('PHPUNIT_SELENIUM_TEST_ID', $this->testId)->set();
        }

        try {
            $this->setUpPage();
            $result = parent::runTest();

            if (! empty($this->verificationErrors)) {
                $this->fail(implode("\n", $this->verificationErrors));
            }
        } catch (Exception $e) {
            $thrownException = $e;
        }

        if ($this->collectCodeCoverageInformation) {
            $this->session->cookie()->remove('PHPUNIT_SELENIUM_TEST_ID');
        }

        if ($thrownException !== null) {
            throw $thrownException;
        }

        return $result;
    }

    /**
     * @return SeleniumTestSuite
     */
    public static function suite(string $className)
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
     *
     * @return mixed
     */
    public function __call($command, $arguments)
    {
        if ($this->session === null) {
            throw new \PHPUnit\Extensions\Selenium2TestCase\Exception(sprintf("There is currently no active session to execute the '%s' command. You're probably trying to set some option in setUp() with an incorrect setter name. You may consider using setUpPage() instead.", $command));
        }

        return call_user_func_array(
            [$this->session, $command],
            $arguments
        );
    }

    public function setHost(string $host): void
    {
        $this->parameters['host'] = $host;
    }

    public function getHost(): string
    {
        return $this->parameters['host'];
    }

    public function setPort(int $port): void
    {
        $this->parameters['port'] = $port;
    }

    public function getPort(): int
    {
        return $this->parameters['port'];
    }

    public function setSecure(bool $secure): void
    {
        $this->parameters['secure'] = $secure;
    }

    public function getSecure(): bool
    {
        return $this->parameters['secure'];
    }

    public function setBrowser(string $browserName): void
    {
        $this->parameters['browserName'] = $browserName;
    }

    public function getBrowser(): string
    {
        return $this->parameters['browserName'];
    }

    public function setBrowserUrl(string $browserUrl): void
    {
        $this->parameters['browserUrl'] = new URL($browserUrl);
    }

    public function getBrowserUrl(): string
    {
        return $this->parameters['browserUrl'] ?? '';
    }

    /**
     * @see http://code.google.com/p/selenium/wiki/JsonWireProtocol
     */
    public function setDesiredCapabilities(array $capabilities): void
    {
        $this->parameters['desiredCapabilities'] = $capabilities;
    }

    public function getDesiredCapabilities(): array
    {
        return $this->parameters['desiredCapabilities'];
    }

    public function setSeleniumServerRequestsTimeout(int $timeout): void
    {
        $this->parameters['seleniumServerRequestsTimeout'] = $timeout;
    }

    public function getSeleniumServerRequestsTimeout(): int
    {
        return $this->parameters['seleniumServerRequestsTimeout'];
    }

    /**
     * Get test id (generated internally)
     */
    public function getTestId(): string
    {
        return $this->testId;
    }

    /**
     * Get Selenium2 current session id
     */
    public function getSessionId(): string
    {
        if ($this->session) {
            return $this->session->id();
        }

        return false;
    }

    /**
     * Wait until callback isn't null or timeout occurs
     *
     * @return mixed
     */
    public function waitUntil($callback, $timeout = null)
    {
        $waitUntil = new WaitUntil($this);

        return $waitUntil->run($callback, $timeout);
    }

    /**
     * Sends a special key
     * Deprecated due to issues with IE webdriver. Use keys() method instead
     *
     * @deprecated
     *
     * @see KeysHolder
     *
     * @throws \PHPUnit\Extensions\Selenium2TestCase\Exception
     */
    public function keysSpecial(string $name): void
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
     *
     * @return true|null
     */
    public function alertIsPresent()
    {
        try {
            $this->alertText();

            return true;
        } catch (Exception $e) {
            return null;
        }
    }
}
