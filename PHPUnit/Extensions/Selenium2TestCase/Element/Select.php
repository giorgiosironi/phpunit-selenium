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
    public static function fromElement(Element $element): Select
    {
        return new self($element->driver, $element->url);
    }

    public function selectedLabel(): string
    {
        $selectedOption = $this->selectedOption();
        if ($selectedOption === null) {
            return '';
        }

        return $selectedOption->text();
    }

    public function selectedValue(): string
    {
        $selectedOption = $this->selectedOption();
        if ($selectedOption === null) {
            return '';
        }

        return $selectedOption->value();
    }

    public function selectedId(): string
    {
        $selectedOption = $this->selectedOption();
        if ($selectedOption === null) {
            return '';
        }

        return $selectedOption->attribute('id');
    }

    /**
     * @return Element[]
     */
    public function selectedLabels(): array
    {
        $labels = [];
        foreach ($this->selectedOptions() as $option) {
            $labels[] = $option->text();
        }

        return $labels;
    }

    public function selectedValues(): array
    {
        $values = [];
        foreach ($this->selectedOptions() as $option) {
            $values[] = $option->value();
        }

        return $values;
    }

    public function selectedIds(): array
    {
        $id = [];
        foreach ($this->selectedOptions() as $option) {
            $values[] = $option->attribute('id');
        }

        return $id;
    }

    /**
     * @param string $label the text appearing in the option
     */
    public function selectOptionByLabel(string $label): void
    {
        $toSelect = $this->using('xpath')->value(sprintf(".//option[.='%s']", $label));
        $this->selectOptionByCriteria($toSelect);
    }

    /**
     * @param string $value the value attribute of the option
     */
    public function selectOptionByValue(string $value): void
    {
        $toSelect = $this->using('xpath')->value(sprintf(".//option[@value='%s']", $value));
        $this->selectOptionByCriteria($toSelect);
    }

    /**
     * @param ElementCriteria $localCriteria condiotions for selecting an option
     */
    public function selectOptionByCriteria(ElementCriteria $localCriteria): void
    {
        $option = $this->element($localCriteria);
        if (! $option->selected()) {
            $option->click();
        }
    }

    /**
     * @return Element[]
     */
    public function selectOptionValues(): array
    {
        $options = [];
        foreach ($this->options() as $option) {
            $options[] = $option->value();
        }

        return $options;
    }

    /**
     * @return Element[]
     */
    public function selectOptionLabels(): array
    {
        $options = [];
        foreach ($this->options() as $option) {
            $options[] = $option->text();
        }

        return $options;
    }

    /**
     * @return Element[]
     */
    private function selectedOptions(): array
    {
        $options = [];
        foreach ($this->options() as $option) {
            if ($option->selected()) {
                $options[] = $option;
            }
        }

        return $options;
    }

    public function clearSelectedOptions(): void
    {
        foreach ($this->selectedOptions() as $option) {
            $option->click();
        }
    }

    private function selectedOption(): ?Element
    {
        foreach ($this->options() as $option) {
            if ($option->selected()) {
                return $option;
            }
        }

        return null;
    }

    /**
     * @return Element[]
     */
    private function options(): array
    {
        $onlyTheOptions = $this->using('css selector')->value('option');

        return $this->elements($onlyTheOptions);
    }
}
