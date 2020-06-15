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
 * Class-mapper, that converts requested special key into correspondent Unicode character
 *
 * @see        http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/value
 */
class KeysHolder
{
    private $keys = [
        'null'      => "\xEE\x80\x80",
        'cancel'    => "\xEE\x80\x81",
        'help'      => "\xEE\x80\x82",
        'backspace' => "\xEE\x80\x83",
        'tab'       => "\xEE\x80\x84",
        'clear'     => "\xEE\x80\x85",
        'return'    => "\xEE\x80\x86",
        'enter'     => "\xEE\x80\x87",
        'shift'     => "\xEE\x80\x88",
        'control'   => "\xEE\x80\x89",
        'alt'       => "\xEE\x80\x8A",
        'pause'     => "\xEE\x80\x8B",
        'escape'    => "\xEE\x80\x8C",
        'space'     => "\xEE\x80\x8D",
        'pageup'    => "\xEE\x80\x8E",
        'pagedown'  => "\xEE\x80\x8F",
        'end'       => "\xEE\x80\x90",
        'home'      => "\xEE\x80\x91",
        'left'      => "\xEE\x80\x92",
        'up'        => "\xEE\x80\x93",
        'right'     => "\xEE\x80\x94",
        'down'      => "\xEE\x80\x95",
        'insert'    => "\xEE\x80\x96",
        'delete'    => "\xEE\x80\x97",
        'semicolon' => "\xEE\x80\x98",
        'equals'    => "\xEE\x80\x99",
        'numpad0'   => "\xEE\x80\x9A",
        'numpad1'   => "\xEE\x80\x9B",
        'numpad2'   => "\xEE\x80\x9C",
        'numpad3'   => "\xEE\x80\x9D",
        'numpad4'   => "\xEE\x80\x9E",
        'numpad5'   => "\xEE\x80\x9F",
        'numpad6'   => "\xEE\x80\xA0",
        'numpad7'   => "\xEE\x80\xA1",
        'numpad8'   => "\xEE\x80\xA2",
        'numpad9'   => "\xEE\x80\xA3",
        'multiply'  => "\xEE\x80\xA4",
        'add'       => "\xEE\x80\xA5",
        'separator' => "\xEE\x80\xA6",
        'subtract'  => "\xEE\x80\xA7",
        'decimal'   => "\xEE\x80\xA8",
        'divide'    => "\xEE\x80\xA9",
        'f1'        => "\xEE\x80\xB1",
        'f2'        => "\xEE\x80\xB2",
        'f3'        => "\xEE\x80\xB3",
        'f4'        => "\xEE\x80\xB4",
        'f5'        => "\xEE\x80\xB5",
        'f6'        => "\xEE\x80\xB6",
        'f7'        => "\xEE\x80\xB7",
        'f8'        => "\xEE\x80\xB8",
        'f9'        => "\xEE\x80\xB9",
        'f10'       => "\xEE\x80\xBA",
        'f11'       => "\xEE\x80\xBB",
        'f12'       => "\xEE\x80\xBC",
        'command'   => "\xEE\x80\xBD",
    ];

    public function specialKey(string $name): string
    {
        $normalizedName = strtolower($name);

        if (! isset($this->keys[$normalizedName])) {
            throw new \PHPUnit\Extensions\Selenium2TestCase\Exception(sprintf("There is no special key '%s' defined", $name));
        }

        return $this->keys[$normalizedName];
    }
}
