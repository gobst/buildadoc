<?php
/**
 * This file is part of BuildADoc.
 *
 * (c) Guido Obst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types = 1);

namespace Service\File;

use Collection\FileCollection;
use Contract\Service\File\FileServiceInterface;
use Dto\Common\File;
use Ramsey\Collection\Exception\NoSuchElementException;
use Service\File\Filter\FileNameFilter;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class FileService implements FileServiceInterface
{
    private Filesystem $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * Find all files in a directory and add these to an FileCollection.
     *
     * @param string         $directory    Path of the directory to be searched
     * @param FileCollection $files        The FileCollection that should be filled
     * @param array          $excludeFiles Excluded files. For example: $excludeFiles[0] = 'test.php'
     * @param string         $extension    Find only files with the given extension (optional)(default:'php')
     *
     * @throws InvalidArgumentException
     */
    public function getAllFilesWithinDir(
        string $directory,
        FileCollection $files,
        array $excludeFiles = [],
        string $extension = 'php'
    ): FileCollection {
        Assert::stringNotEmpty($directory);
        Assert::stringNotEmpty($extension);
        if (is_dir($directory)) {
            $scanResult = scandir($directory);
            foreach ($scanResult as $value) {
                $path = $directory . $value;
                if (is_file($path)) {
                    $rfile = pathinfo($path);
                    if ($this->isAllowedFile($rfile, $extension, $excludeFiles) && !empty($rfile['extension'])) {
                        $filePath = $rfile['dirname'] . '/' . $rfile['basename'];
                        $fileSize = filesize($filePath);

                        Assert::stringNotEmpty($rfile['filename']);
                        Assert::stringNotEmpty($filePath);
                        Assert::stringNotEmpty($rfile['basename']);
                        Assert::stringNotEmpty($rfile['dirname']);
                        Assert::positiveInteger($fileSize);

                        $file = File::create(
                            $rfile['filename'],
                            $filePath,
                            $rfile['basename'],
                            $rfile['dirname'],
                            $fileSize
                        )->withExtension($rfile['extension']);

                        $files->add($file);
                    }
                } elseif ($this->isValidDirectory($path, $value)) {
                    $files = $this->getAllFilesWithinDir($path . '/', $files, $excludeFiles, $extension);
                }
            }
        }

        return $files;
    }

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     */
    public function dumpFile(string $filename, string $content): void
    {
        Assert::stringNotEmpty($filename);
        $this->filesystem->dumpFile($filename, $content);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function isAllowedFile(array $file, string $allowedFileExtension, array $excludeFiles): bool
    {
        Assert::notEmpty($file['extension']);

        return $this->isAllowedFileExtension($file['extension'], $allowedFileExtension)
            && $this->isExcludedFile($file['basename'], $excludeFiles) === false;
    }

    private function isAllowedFileExtension(string $extension, string $allowedExtension): bool
    {
        return $extension === $allowedExtension;
    }

    private function isExcludedFile(string $file, array $excludedFiles): bool
    {
        return in_array($file, $excludedFiles);
    }

    private function isValidDirectory($path, $directory): bool
    {
        return is_dir($path) && $directory !== '.' && $directory !== '..';
    }

    /**
     * @throws InvalidArgumentException
     * @throws NoSuchElementException
     */
    public function getSingleFile(string $phpFile, FileCollection $files): File
    {
        Assert::stringNotEmpty($phpFile);

        return $files->filter([new FileNameFilter($phpFile), 'hasFileName'])->first();
    }
}
