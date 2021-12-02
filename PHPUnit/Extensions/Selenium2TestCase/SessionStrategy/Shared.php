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
 */

namespace PHPUnit\Extensions\Selenium2TestCase\SessionStrategy;

use PHPUnit\Extensions\Selenium2TestCase\Session;
use PHPUnit\Extensions\Selenium2TestCase\SessionStrategy;

/**
 * Keeps a Session object shared between test runs to save time.
 */
class Shared implements SessionStrategy
{
    private $original;
    private $session;
    private $mainWindow;
    private $lastTestWasNotSuccessful = false;
    private $keepSessionOnFailure;

    public function __construct(SessionStrategy $originalStrategy, $keepSessionOnFailure)
    {
        $this->original             = $originalStrategy;
        $this->keepSessionOnFailure = $keepSessionOnFailure;
    }

    public function session(array $parameters)
    {
        if ($this->lastTestWasNotSuccessful && ! $this->keepSessionOnFailure) {
            if ($this->session !== null) {
                $this->session->stop();
                $this->session = null;
            }

            $this->lastTestWasNotSuccessful = false;
        }

        if ($this->session === null) {
            $this->session    = $this->original->session($parameters);
            $this->mainWindow = $this->session->windowHandle();
        } else {
            $this->session->window($this->mainWindow);
        }

        return $this->session;
    }

    public function notSuccessfulTest()
    {
        $this->lastTestWasNotSuccessful = true;
    }

    public function endOfTest(?Session $session = null)
    {
    }
}
