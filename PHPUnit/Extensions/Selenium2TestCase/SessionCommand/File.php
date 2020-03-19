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

namespace PHPUnit\Extensions\Selenium2TestCase\SessionCommand;

use BadMethodCallException;
use Exception;
use PHPUnit\Extensions\Selenium2TestCase\Command;
use PHPUnit\Extensions\Selenium2TestCase\URL;
use ZipArchive;

/**
 * Sends a file to a RC
 * Returns the FQ path to the transfered file
 *
 * @link       http://www.phpunit.de/
 */
class File extends Command
{
    /** @var */
    private static $zipArchive;

    public function __construct($argument, URL $url)
    {
        if (! is_file($argument)) {
            throw new BadMethodCallException(sprintf('No such file: %s', $argument));
        }

        $zipfilePath = $this->zipArchiveFile($argument);
        $contents    = file_get_contents($zipfilePath);

        if ($contents === false) {
            throw new Exception(sprintf('Unable to read generated zip file: %s', $zipfilePath));
        }

        $file = base64_encode($contents);

        parent::__construct(['file' => $file], $url);

        unlink($zipfilePath);
    }

    public function httpMethod()
    {
        return 'POST';
    }

    /**
     * Creates a zip archive with the given file
     *
     * @param   string $filePath FQ path to file
     *
     * @return  string              Generated zip file
     */
    protected function zipArchiveFile($filePath)
    {
        // file MUST be readable
        if (! is_readable($filePath)) {
            throw new Exception(sprintf('Unable to read %s', $filePath));
        } // if !file_data

        $filenameHash = sha1(time() . $filePath);
        $tmpDir       = $this->getTmpDir();
        $zipFilename  = sprintf('%s%s.zip', $tmpDir, $filenameHash);
        $zip          = $this->getZipArchiver();

        if ($zip->open($zipFilename, ZipArchive::CREATE) === false) {
            throw new Exception(sprintf('Unable to create zip archive: %s', $zipFilename));
        }

        $zip->addFile($filePath, basename($filePath));
        $zip->close();

        return $zipFilename;
    }

    /**
     * Returns a runtime instance of a ZipArchive
     *
     * @return ZipArchive
     */
    protected function getZipArchiver()
    {
        // create ZipArchive if necessary
        if (! static::$zipArchive) {
            static::$zipArchive = new ZipArchive();
        }

        return static::$zipArchive;
    }

    /**
     * Calls sys_get_temp_dir and ensures that it has a trailing slash
     * ( behavior varies across systems )
     *
     * @return string
     */
    protected function getTmpDir()
    {
        return rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
}
