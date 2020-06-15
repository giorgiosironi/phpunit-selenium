<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase\Element;

use PHPUnit\Extensions\Selenium2TestCase\CommandsHolder;
use PHPUnit\Extensions\Selenium2TestCase\Element;
use PHPUnit\Extensions\Selenium2TestCase\ElementCriteria;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Provides access to /element and /elements commands
 */
abstract class Accessor extends CommandsHolder
{
    /**
     * @param string $value e.g. 'container'
     */
    public function byClassName(string $value): Element
    {
        return $this->by('class name', $value);
    }

    /**
     * @param string $value e.g. 'div.container'
     */
    public function byCssSelector(string $value): Element
    {
        return $this->by('css selector', $value);
    }

    /**
     * @param string $value e.g. 'uniqueId'
     */
    public function byId(string $value): Element
    {
        return $this->by('id', $value);
    }

    /**
     * @param string $value e.g. 'Link text'
     */
    public function byLinkText(string $value): Element
    {
        return $this->by('link text', $value);
    }

    /**
     * @param string $value e.g. 'Link te'
     */
    public function byPartialLinkText(string $value): Element
    {
        return $this->by('partial link text', $value);
    }

    /**
     * @param string $value e.g. 'email_address'
     */
    public function byName(string $value): Element
    {
        return $this->by('name', $value);
    }

    /**
     * @param string $value e.g. 'body'
     */
    public function byTag(string $value): Element
    {
        return $this->by('tag name', $value);
    }

    /**
     * @param string $value e.g. '/div[@attribute="value"]'
     */
    public function byXPath(string $value): Element
    {
        return $this->by('xpath', $value);
    }

    public function element(ElementCriteria $criteria): Element
    {
        $value = $this->postCommand('element', $criteria);

        return Element::fromResponseValue($value, $this->getSessionUrl()->descend('element'), $this->driver);
    }

    /**
     * @return Element[]
     */
    public function elements(ElementCriteria $criteria): array
    {
        $values   = $this->postCommand('elements', $criteria);
        $elements = [];
        foreach ($values as $value) {
            $elements[] = Element::fromResponseValue($value, $this->getSessionUrl()->descend('element'), $this->driver);
        }

        return $elements;
    }

    public function using(string $strategy): ElementCriteria
    {
        return new ElementCriteria($strategy);
    }

    abstract protected function getSessionUrl(): URL;

    /**
     * @param string $strategy supported by JsonWireProtocol element/ command
     */
    private function by(string $strategy, string $value): Element
    {
        return $this->element($this->using($strategy)->value($value));
    }
}
