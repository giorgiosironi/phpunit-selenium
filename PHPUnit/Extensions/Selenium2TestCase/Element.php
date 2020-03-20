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
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\Attribute;
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\Click;
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\Css;
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\Equals;
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\GenericAccessor;
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\GenericPost;
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\Rect;
use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\Value;

/**
 * Object representing a DOM element.
 *
 * @method string attribute($name) Retrieves an element's attribute
 * @method void clear() Empties the content of a form element.
 * @method void click() Clicks on element
 * @method string css($propertyName) Retrieves the value of a CSS property
 * @method bool displayed() Checks an element's visibility
 * @method bool enabled() Checks a form element's state
 * @method bool equals(Element $another) Checks if the two elements are the same on the page
 * @method array rect() Retrieves the element's coordinates: keys 'x', 'y', 'width' and 'height' in the returned array
 * @method array location() Retrieves the element's position in the page: keys 'x' and 'y' in the returned array
 * @method bool selected() Checks the state of an option or other form element
 * @method array size() Retrieves the dimensions of the element: 'width' and 'height' of the returned array
 * @method void submit() Submits a form; can be called on its children
 * @method string text() Get content of ordinary elements
 */
class Element extends Accessor
{
    /**
     * @return \self
     *
     * @throws InvalidArgumentException
     */
    public static function fromResponseValue(array $value, URL $parentFolder, Driver $driver)
    {
        if (! isset($value['ELEMENT'])) {
            throw new InvalidArgumentException('Element not found.');
        }

        $url = $parentFolder->descend($value['ELEMENT']);

        return new self($driver, $url);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->url->lastSegment();
    }

    /**
     * @return array    class names
     */
    protected function initCommands()
    {
        return [
            'attribute' => Attribute::class,
            'clear' => GenericPost::class,
            'click' => Click::class,
            'css' => Css::class,
            'displayed' => GenericAccessor::class,
            'enabled' => GenericAccessor::class,
            'equals' => Equals::class,
            'location' => GenericAccessor::class,
            'name' => GenericAccessor::class,
            'rect' => Rect::class,
            'selected' => GenericAccessor::class,
            'size' => GenericAccessor::class,
            'submit' => GenericPost::class,
            'text' => GenericAccessor::class,
            'value' => Value::class,
            'tap' => $this->touchCommandFactoryMethod('touch/click'),
            'scroll' => $this->touchCommandFactoryMethod('touch/scroll'),
            'doubletap' => $this->touchCommandFactoryMethod('touch/doubleclick'),
            'longtap' => $this->touchCommandFactoryMethod('touch/longclick'),
            'flick' => $this->touchCommandFactoryMethod('touch/flick'),
        ];
    }

    protected function getSessionUrl()
    {
        return $this->url->ascend()->ascend();
    }

    private function touchCommandFactoryMethod($urlSegment)
    {
        $url  = $this->getSessionUrl()->addCommand($urlSegment);
        $self = $this;

        return static function ($jsonParameters, $commandUrl) use ($url, $self) {
            if ((is_array($jsonParameters) &&
                    ! isset($jsonParameters['element'])) ||
                    $jsonParameters === null) {
                $jsonParameters['element'] = $self->getId();
            }

            return new GenericPost($jsonParameters, $url);
        };
    }

    /**
     * Retrieves the tag name
     *
     * @return string
     */
    public function name()
    {
        return strtolower(parent::name());
    }

    /**
     * Generates an array that is structured as the WebDriver Object of the JSONWireProtocoll
     *
     * @return array
     */
    public function toWebDriverObject()
    {
        return ['ELEMENT' => (string) $this->getId()];
    }

    /**
     * Get or set value of form elements. If the element already has a value, the set one will be appended to it.
     * Created **ONLY** for keeping backward compatibility, since in selenium v2.42.0 it was removed
     * The currently recommended solution is to use `$element->attribute('value')`
     *
     * @see https://code.google.com/p/selenium/source/detail?r=953007b48e83f90450f3e41b11ec31e2928f1605
     * @see https://code.google.com/p/selenium/source/browse/java/CHANGELOG
     *
     * @param string $newValue
     *
     * @return string|null
     */
    public function value($newValue = null)
    {
        if ($newValue !== null) {
            return parent::value($newValue);
        }

        return $this->attribute('value');
    }
}
