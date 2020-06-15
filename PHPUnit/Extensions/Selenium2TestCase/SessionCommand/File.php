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

use BadMethodCallException;
use Exception;
use PHPUnit\Extensions\Selenium2TestCase\Command;
use PHPUnit\Extensions\Selenium2TestCase\URL;
use ZipArchive;

/**
 * Sends a file to a RC
 * Returns the FQ path to the transfered file
 */
class File extends Command
{
    /** @var */
    private static $zipArchive;

    public function __construct(string $argument, URL $url)
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

    public function httpMethod(): string
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
    protected function zipArchiveFile(string $filePath): string
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
     */
    protected function getZipArchiver(): ZipArchive
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
     */
    protected function getTmpDir(): string
    {
        return rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
}
