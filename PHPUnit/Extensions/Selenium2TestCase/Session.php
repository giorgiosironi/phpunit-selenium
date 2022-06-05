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

use InvalidArgumentException;
use PHPUnit\Extensions\Selenium2TestCase\Element\Accessor;
use PHPUnit\Extensions\Selenium2TestCase\Element\Select;
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\GenericAccessor;
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\GenericPost;
use PHPUnit\Extensions\Selenium2TestCase\Session\Cookie;
use PHPUnit\Extensions\Selenium2TestCase\Session\Storage;
use PHPUnit\Extensions\Selenium2TestCase\Session\Timeouts;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\AcceptAlert;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Active;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\AlertText;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Click;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\DismissAlert;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\File;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Frame;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\GenericAccessor as SessionGenericAccessor;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\GenericAttribute;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Keys as SessionKeys;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Location;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Log;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\MoveTo;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Orientation;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Window as SessionWindow;

/**
 * Browser session for Selenium 2: main point of entry for functionality.
 *
 * @method void acceptAlert() Press OK on an alert, or confirms a dialog
 * @method mixed alertText($value = NULL) Gets the alert dialog text, or sets the text for a prompt dialog
 * @method void back()
 * @method void dismissAlert() Press Cancel on an alert, or does not confirm a dialog
 * @method void doubleclick() Double-clicks at the current mouse coordinates (set by moveto).
 * @method string execute(array $javaScriptCode) Injects arbitrary JavaScript in the page and returns the last. See unit tests for usage
 * @method string executeAsync(array $javaScriptCode) Injects arbitrary JavaScript and wait for the callback (last element of arguments) to be called. See unit tests for usage
 * @method void forward()
 * @method void frame(mixed $element) Changes the focus to a frame in the page (by frameCount of type int, htmlId of type string, htmlName of type string or element of type Element)
 * @method void moveto(Element $element) Move the mouse by an offset of the specificed element.
 * @method void refresh()
 * @method string source() Returns the HTML source of the page
 * @method string title()
 * @method void|string url($url = NULL)
 * @method void window($name) Changes the focus to another window
 * @method string windowHandle() Retrieves the current window handle
 * @method string windowHandles() Retrieves a list of all available window handles
 * @method string keys() Send a sequence of key strokes to the active element.
 * @method string file($file_path) Upload a local file. Returns the fully qualified path to the transferred file.
 * @method array log(string $type) Get the log for a given log type. Log buffer is reset after each request.
 * @method array logTypes() Get available log types.
 */
class Session extends Accessor
{
    /** @var string  the base URL for this session, which all relative URLs will refer to */
    private $baseUrl;

    /** @var Timeouts */
    private $timeouts;

    /** @var bool */
    private $stopped = false;

    public function __construct(
        Driver $driver,
        URL $url,
        URL $baseUrl,
        Timeouts $timeouts
    ) {
        $this->baseUrl  = $baseUrl;
        $this->timeouts = $timeouts;
        parent::__construct($driver, $url);
    }

    public function id(): string
    {
        return $this->url->lastSegment();
    }

    protected function initCommands(): array
    {
        $baseUrl = $this->baseUrl;

        return [
            'acceptAlert' => AcceptAlert::class,
            'alertText' => AlertText::class,
            'back' => GenericPost::class,
            'click' => Click::class,
            'buttondown' => GenericPost::class,
            'buttonup' => GenericPost::class,
            'dismissAlert' => DismissAlert::class,
            'doubleclick' => GenericPost::class,
            'execute' => GenericPost::class,
            'executeAsync' => GenericPost::class,
            'forward' => GenericPost::class,
            'frame' => Frame::class,
            'keys' => SessionKeys::class,
            'moveto' => MoveTo::class,
            'refresh' => GenericPost::class,
            'screenshot' => GenericAccessor::class,
            'source' => SessionGenericAccessor::class,
            'title' => SessionGenericAccessor::class,
            'log' => Log::class,
            'logTypes' => $this->attributeCommandFactoryMethod('log/types'),
            'url' => static function ($jsonParameters, $commandUrl) use ($baseUrl) {
                return new \PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Url($jsonParameters, $commandUrl, $baseUrl);
            },
            'window' => SessionWindow::class,
            'windowHandle' => SessionGenericAccessor::class,
            'windowHandles' => SessionGenericAccessor::class,
            'touchDown' => $this->touchCommandFactoryMethod('touch/down'),
            'touchUp' => $this->touchCommandFactoryMethod('touch/up'),
            'touchMove' => $this->touchCommandFactoryMethod('touch/move'),
            'touchScroll' => $this->touchCommandFactoryMethod('touch/scroll'),
            'flick' => $this->touchCommandFactoryMethod('touch/flick'),
            'location' => Location::class,
            'orientation' => Orientation::class,
            'file' => File::class,
        ];
    }

    private function attributeCommandFactoryMethod(string $urlSegment): callable
    {
        $url = $this->url->addCommand($urlSegment);

        return static function ($jsonParameters, $commandUrl) use ($url) {
            return new GenericAttribute($jsonParameters, $url);
        };
    }

    private function touchCommandFactoryMethod(string $urlSegment): callable
    {
        $url = $this->url->addCommand($urlSegment);

        return static function ($jsonParameters, $commandUrl) use ($url) {
            return new GenericPost($jsonParameters, $url);
        };
    }

    public function __destruct()
    {
        $this->stop();
    }

    public function getSessionUrl(): URL
    {
        return $this->url;
    }

    /**
     * Closed the browser.
     */
    public function stop(): void
    {
        if ($this->stopped) {
            return;
        }

        try {
            $this->driver->curl('DELETE', $this->url);
        } catch (Exception $e) {
            // sessions which aren't closed because of sharing can time out on the server. In no way trying to close them should make a test fail, as it already finished before arriving here.
            'Closing sessions: ' . $e->getMessage() . "\n";
        }

        $this->stopped = true;
        if ($this->stopped) {
            return;
        }
    }

    public function select(Element $element): Select
    {
        $tag = $element->name();
        if ($tag !== 'select') {
            throw new InvalidArgumentException(sprintf('The element is not a `select` tag but a `%s`.', $tag));
        }

        return Select::fromElement($element);
    }

    /**
     * @param array $value WebElement JSON object
     */
    public function elementFromResponseValue(array $value): Element
    {
        return Element::fromResponseValue($value, $this->getSessionUrl()->descend('element'), $this->driver);
    }

    /**
     * @param string $id id attribute, e.g. 'container'
     */
    public function clickOnElement(string $id): void
    {
        $this->element($this->using('id')->value($id))->click();
    }

    public function timeouts(): Timeouts
    {
        return $this->timeouts;
    }

    /**
     * @return string a BLOB of a PNG file
     */
    public function currentScreenshot(): string
    {
        return base64_decode($this->screenshot());
    }

    public function currentWindow(): Window
    {
        $url = $this->url->descend('window')->descend(trim($this->windowHandle(), '{}'));

        return new Window($this->driver, $url);
    }

    public function closeWindow(): void
    {
        $this->driver->curl('DELETE', $this->url->descend('window'));
    }

    /**
     * Get the element on the page that currently has focus.
     */
    public function active(): Element
    {
        $command  = new Active(null, $this->url);
        $response = $this->driver->execute($command);

        return $this->elementFromResponseValue($response->getValue());
    }

    public function cookie(): Cookie
    {
        $url = $this->url->descend('cookie');

        return new Cookie($this->driver, $url);
    }

    public function localStorage(): Storage
    {
        $url = $this->url->addCommand('localStorage');

        return new Storage($this->driver, $url);
    }

    public function landscape(): void
    {
        $this->orientation('LANDSCAPE');
    }

    public function portrait(): void
    {
        $this->orientation('PORTRAIT');
    }
}
