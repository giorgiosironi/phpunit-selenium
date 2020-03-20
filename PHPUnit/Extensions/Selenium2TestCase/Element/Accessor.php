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
     *
     * @return Element
     */
    public function byClassName($value)
    {
        return $this->by('class name', $value);
    }

    /**
     * @param string $value e.g. 'div.container'
     *
     * @return Element
     */
    public function byCssSelector($value)
    {
        return $this->by('css selector', $value);
    }

    /**
     * @param string $value e.g. 'uniqueId'
     *
     * @return Element
     */
    public function byId($value)
    {
        return $this->by('id', $value);
    }

    /**
     * @param string $value e.g. 'Link text'
     *
     * @return Element
     */
    public function byLinkText($value)
    {
        return $this->by('link text', $value);
    }

    /**
     * @param string $value e.g. 'Link te'
     *
     * @return Element
     */
    public function byPartialLinkText($value)
    {
        return $this->by('partial link text', $value);
    }

    /**
     * @param string $value e.g. 'email_address'
     *
     * @return Element
     */
    public function byName($value)
    {
        return $this->by('name', $value);
    }

    /**
     * @param string $value e.g. 'body'
     *
     * @return Element
     */
    public function byTag($value)
    {
        return $this->by('tag name', $value);
    }

    /**
     * @param string $value e.g. '/div[@attribute="value"]'
     *
     * @return Element
     */
    public function byXPath($value)
    {
        return $this->by('xpath', $value);
    }

    /**
     * @return Element
     */
    public function element(ElementCriteria $criteria)
    {
        $value = $this->postCommand('element', $criteria);

        return Element::fromResponseValue($value, $this->getSessionUrl()->descend('element'), $this->driver);
    }

    /**
     * @return Element[]
     */
    public function elements(ElementCriteria $criteria)
    {
        $values   = $this->postCommand('elements', $criteria);
        $elements = [];
        foreach ($values as $value) {
            $elements[] = Element::fromResponseValue($value, $this->getSessionUrl()->descend('element'), $this->driver);
        }

        return $elements;
    }

    /**
     * @param string $strategy
     *
     * @return ElementCriteria
     */
    public function using($strategy)
    {
        return new ElementCriteria($strategy);
    }

    /**
     * @return URL
     */
    abstract protected function getSessionUrl();

    /**
     * @param string $strategy supported by JsonWireProtocol element/ command
     * @param string $value
     *
     * @return Element
     */
    private function by($strategy, $value)
    {
        return $this->element($this->using($strategy)->value($value));
    }
}
