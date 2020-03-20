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
 */

namespace PHPUnit\Extensions\Selenium2TestCase;

/**
 * Class to hold the special keys Unicode entities
 *
 * @see        http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/value
 */
class Keys
{
    public const NULL      = "\xEE\x80\x80";
    public const CANCEL    = "\xEE\x80\x81";
    public const HELP      = "\xEE\x80\x82";
    public const BACKSPACE = "\xEE\x80\x83";
    public const TAB       = "\xEE\x80\x84";
    public const CLEAR     = "\xEE\x80\x85";
    public const RETURN_   = "\xEE\x80\x86";
    public const ENTER     = "\xEE\x80\x87";
    public const SHIFT     = "\xEE\x80\x88";
    public const CONTROL   = "\xEE\x80\x89";
    public const ALT       = "\xEE\x80\x8A";
    public const PAUSE     = "\xEE\x80\x8B";
    public const ESCAPE    = "\xEE\x80\x8C";
    public const SPACE     = "\xEE\x80\x8D";
    public const PAGEUP    = "\xEE\x80\x8E";
    public const PAGEDOWN  = "\xEE\x80\x8F";
    public const END       = "\xEE\x80\x90";
    public const HOME      = "\xEE\x80\x91";
    public const LEFT      = "\xEE\x80\x92";
    public const UP        = "\xEE\x80\x93";
    public const RIGHT     = "\xEE\x80\x94";
    public const DOWN      = "\xEE\x80\x95";
    public const INSERT    = "\xEE\x80\x96";
    public const DELETE    = "\xEE\x80\x97";
    public const SEMICOLON = "\xEE\x80\x98";
    public const EQUALS    = "\xEE\x80\x99";
    public const NUMPAD0   = "\xEE\x80\x9A";
    public const NUMPAD1   = "\xEE\x80\x9B";
    public const NUMPAD2   = "\xEE\x80\x9C";
    public const NUMPAD3   = "\xEE\x80\x9D";
    public const NUMPAD4   = "\xEE\x80\x9E";
    public const NUMPAD5   = "\xEE\x80\x9F";
    public const NUMPAD6   = "\xEE\x80\xA0";
    public const NUMPAD7   = "\xEE\x80\xA1";
    public const NUMPAD8   = "\xEE\x80\xA2";
    public const NUMPAD9   = "\xEE\x80\xA3";
    public const MULTIPLY  = "\xEE\x80\xA4";
    public const ADD       = "\xEE\x80\xA5";
    public const SEPARATOR = "\xEE\x80\xA6";
    public const SUBTRACT  = "\xEE\x80\xA7";
    public const DECIMAL   = "\xEE\x80\xA8";
    public const DIVIDE    = "\xEE\x80\xA9";
    public const F1        = "\xEE\x80\xB1";
    public const F2        = "\xEE\x80\xB2";
    public const F3        = "\xEE\x80\xB3";
    public const F4        = "\xEE\x80\xB4";
    public const F5        = "\xEE\x80\xB5";
    public const F6        = "\xEE\x80\xB6";
    public const F7        = "\xEE\x80\xB7";
    public const F8        = "\xEE\x80\xB8";
    public const F9        = "\xEE\x80\xB9";
    public const F10       = "\xEE\x80\xBA";
    public const F11       = "\xEE\x80\xBB";
    public const F12       = "\xEE\x80\xBC";
    public const COMMAND   = "\xEE\x80\xBD";
}
