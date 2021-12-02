<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Extensions\Selenium2TestCase\SessionCommand;

use PHPUnit\Extensions\Selenium2TestCase\Command;
use PHPUnit\Extensions\Selenium2TestCase\Element;
use PHPUnit\Extensions\Selenium2TestCase\URL;

/**
 * Changes the focus to a frame.
 */
class Frame extends Command
{
    public function __construct($id, URL $commandUrl)
    {
        $jsonParameters = [
            'id' => $this->extractId($id),
        ];

        parent::__construct($jsonParameters, $commandUrl);
    }

    /**
     * @return array
     */
    private function extractId($id)
    {
        if ($this->isElement($id)) {
            //selenium-element

            return $id->toWebDriverObject();
        }

        //html-id or null
        return $id;
    }

    private function isElement($id): bool
    {
        return $id instanceof Element;
    }

    public function httpMethod(): string
    {
        return 'POST';
    }
}
