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

use PHPUnit\Extensions\Selenium2TestCase\Element;
use PHPUnit\Extensions\Selenium2TestCase\ElementCriteria;

/**
 * Object representing a <select> element.
 */
class Select extends Element
{
    /**
     * @return Select
     */
    public static function fromElement(Element $element)
    {
        return new self($element->driver, $element->url);
    }

    /**
     * @return string
     */
    public function selectedLabel()
    {
        $selectedOption = $this->selectedOption();
        if ($selectedOption === null) {
            return '';
        }

        return $selectedOption->text();
    }

    /**
     * @return string
     */
    public function selectedValue()
    {
        $selectedOption = $this->selectedOption();
        if ($selectedOption === null) {
            return '';
        }

        return $selectedOption->value();
    }

    /**
     * @return string
     */
    public function selectedId()
    {
        $selectedOption = $this->selectedOption();
        if ($selectedOption === null) {
            return '';
        }

        return $selectedOption->attribute('id');
    }

    /**
     * @return array
     */
    public function selectedLabels()
    {
        $labels = [];
        foreach ($this->selectedOptions() as $option) {
            $labels[] = $option->text();
        }

        return $labels;
    }

    /**
     * @return array
     */
    public function selectedValues()
    {
        $values = [];
        foreach ($this->selectedOptions() as $option) {
            $values[] = $option->value();
        }

        return $values;
    }

    /**
     * @return array
     */
    public function selectedIds()
    {
        $id = [];
        foreach ($this->selectedOptions() as $option) {
            $values[] = $option->attribute('id');
        }

        return $id;
    }

    /**
     * @param string $label the text appearing in the option
     *
     * @return void
     */
    public function selectOptionByLabel($label)
    {
        $toSelect = $this->using('xpath')->value(sprintf(".//option[.='%s']", $label));
        $this->selectOptionByCriteria($toSelect);
    }

    /**
     * @param string $value the value attribute of the option
     *
     * @return void
     */
    public function selectOptionByValue($value)
    {
        $toSelect = $this->using('xpath')->value(sprintf(".//option[@value='%s']", $value));
        $this->selectOptionByCriteria($toSelect);
    }

    /**
     * @param ElementCriteria $localCriteria condiotions for selecting an option
     *
     * @return void
     */
    public function selectOptionByCriteria(ElementCriteria $localCriteria)
    {
        $option = $this->element($localCriteria);
        if (! $option->selected()) {
            $option->click();
        }
    }

    /**
     * @return array
     */
    public function selectOptionValues()
    {
        $options = [];
        foreach ($this->options() as $option) {
            $options[] = $option->value();
        }

        return $options;
    }

    /**
     * @return array
     */
    public function selectOptionLabels()
    {
        $options = [];
        foreach ($this->options() as $option) {
            $options[] = $option->text();
        }

        return $options;
    }

    /***
     * @return array
     */
    private function selectedOptions()
    {
        $options = [];
        foreach ($this->options() as $option) {
            if ($option->selected()) {
                $options[] = $option;
            }
        }

        return $options;
    }

    public function clearSelectedOptions()
    {
        foreach ($this->selectedOptions() as $option) {
            $option->click();
        }
    }

    private function selectedOption()
    {
        foreach ($this->options() as $option) {
            if ($option->selected()) {
                return $option;
            }
        }

        return null;
    }

    private function options()
    {
        $onlyTheOptions = $this->using('css selector')->value('option');

        return $this->elements($onlyTheOptions);
    }
}
