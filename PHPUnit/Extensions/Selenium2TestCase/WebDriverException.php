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
