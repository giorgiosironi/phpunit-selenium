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

/**
 * Gets or posts an attribute from/to the session (title, alert text, etc.)
 */
class Location extends GenericAttribute
{
    public function __construct($location, URL $commandUrl)
    {
        if ($location !== null) {
            $jsonParameters = ['location' => $location];
        } else {
            $jsonParameters = null;
        }

        parent::__construct($jsonParameters, $commandUrl);
    }

    public function httpMethod(): string
    {
        if ($this->jsonParameters) {
            return 'POST';
        }

        return 'GET';
    }
}
