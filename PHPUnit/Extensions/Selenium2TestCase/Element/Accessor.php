<?php

/**
 * Provides access to /element and /elements commands
 */
abstract class PHPUnit_Extensions_Selenium2TestCase_Element_Accessor
    extends PHPUnit_Extensions_Selenium2TestCase_CommandsHolder
{

    /**
     * @param string $value     e.g. 'container'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byClassName($value)
    {
        return $this->by('class name', $value);
    }

    /**
     * @param string $value     e.g. 'div.container'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byCssSelector($value)
    {
        return $this->by('css selector', $value);
    }

    /**
     * @param string $value     e.g. 'uniqueId'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byId($value)
    {
        return $this->by('id', $value);
    }

    /**
     * @param string $value     e.g. 'email_address'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byName($value)
    {
        return $this->by('name', $value);
    }

    /**
     * @param string $value     e.g. '/div[@attribute="value"]'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byXPath($value)
    {
        return $this->by('xpath', $value);
    }

    /**
     * @param string $value     e.g. 'Link text'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byLinkText($value)
    {
        return $this->by('link text', $value);
    }

    /**
     * @param string $value     e.g. 'body'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byTag($value)
    {
        return $this->by('tag name', $value);
    }

    /**
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function element(PHPUnit_Extensions_Selenium2TestCase_ElementCriteria $criteria)
    {
        $value = $this->postCommand('element', $criteria);
        return PHPUnit_Extensions_Selenium2TestCase_Element::fromResponseValue(
                $value, $this->getSessionUrl()->descend('element'), $this->driver);
    }

    /**
     * @return array    instances of PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function elements(PHPUnit_Extensions_Selenium2TestCase_ElementCriteria $criteria)
    {
        $values = $this->postCommand('elements', $criteria);
        $elements = array();
        foreach ($values as $value) {
            $elements[] =
                PHPUnit_Extensions_Selenium2TestCase_Element::fromResponseValue(
                    $value, $this->getSessionUrl()->descend('element'), $this->driver);
        }
        return $elements;
    }

    /**
     * @param string $strategy
     * @return PHPUnit_Extensions_Selenium2TestCase_ElementCriteria
     */
    public function using($strategy)
    {
        return new PHPUnit_Extensions_Selenium2TestCase_ElementCriteria($strategy);
    }

    /**
     * @return PHPUnit_Extensions_Selenium2TestCase_URL
     */
    protected abstract function getSessionUrl();

    /**
     * @param string $strategy     supported by JsonWireProtocol element/ command
     * @param string $value
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    private function by($strategy, $value)
    {
        return $this->element($this->using($strategy)->value($value));
    }

}
