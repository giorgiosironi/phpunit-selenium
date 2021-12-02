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
 *
 * @link       http://www.phpunit.de/
 */

namespace PHPUnit\Extensions\Selenium2TestCase\Element;

use PHPUnit\Extensions\Selenium2TestCase\Element;
use PHPUnit\Extensions\Selenium2TestCase\ElementCriteria;

/**
 * Object representing a <select> element.
 *
 * @link       http://www.phpunit.de/
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
