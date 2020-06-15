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

/**
 * Indicates an exception as a result of a non-sucessful WebDriver response status code.
 */
class WebDriverException extends \PHPUnit\Extensions\Selenium2TestCase\Exception
{
    /* @see http://code.google.com/p/selenium/wiki/JsonWireProtocol#Response_Status_Codes */
    public const Success                    = 0;
    public const NoSuchDriver               = 6;
    public const NoSuchElement              = 7;
    public const NoSuchFrame                = 8;
    public const UnknownCommand             = 9;
    public const StaleElementReference      = 10;
    public const ElementNotVisible          = 11;
    public const InvalidElementState        = 12;
    public const UnknownError               = 13;
    public const ElementIsNotSelectable     = 15;
    public const JavaScriptError            = 17;
    public const XPathLookupError           = 19;
    public const Timeout                    = 21;
    public const NoSuchWindow               = 23;
    public const InvalidCookieDomain        = 24;
    public const UnableToSetCookie          = 25;
    public const UnexpectedAlertOpen        = 26;
    public const NoAlertOpenError           = 27;
    public const ScriptTimeout              = 28;
    public const InvalidElementCoordinates  = 29;
    public const IMENotAvailable            = 30;
    public const IMEEngineActivationFailed  = 31;
    public const InvalidSelector            = 32;
    public const SessionNotCreatedException = 33;
    public const MoveTargetOutOfBounds      = 34;
}
