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

use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\GenericPost;

/**
 * Object representing a browser window.
 *
 * @method array size(array $size = null) Window size as array('width' => $x, 'height' => $y)
 * @method array position(array $position = null) Window position as array('x' => $x, 'y' => $y)
 * @method array maximize() Maximize window
 */
class Window extends CommandsHolder
{
    /**
     * @return array    class names
     */
    protected function initCommands()
    {
        return [
            'size' => StateCommand::class,
            'position' => StateCommand::class,
            'maximize' => GenericPost::class,
        ];
    }
}
