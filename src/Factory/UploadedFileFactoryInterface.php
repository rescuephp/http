<?php

namespace Rescue\Http\Factory;

use InvalidArgumentException;
use Rescue\Http\UploadedFileInterface;
use const UPLOAD_ERR_OK;

interface UploadedFileFactoryInterface
{
    /**
     * Create a new uploaded file.
     *
     * If a string is used to create the file, a temporary resource will be
     * created with the content of the string.
     *
     * If a size is not provided it will be determined by checking the size of
     * the file.
     *
     * @see http://php.net/manual/features.file-upload.post-method.php
     * @see http://php.net/manual/features.file-upload.errors.php
     *
     * @param string|resource $file
     * @param int $size in bytes
     * @param int $error PHP file upload error
     * @param string $clientFilename
     * @param string $clientMediaType
     *
     * @return UploadedFileInterface
     *
     * @throws InvalidArgumentException
     *  If the file resource is not readable.
     */
    public function createUploadedFile(
        $file,
        int $size = null,
        int $error = UPLOAD_ERR_OK,
        string $clientFilename = null,
        string $clientMediaType = null
    ): UploadedFileInterface;

    /**
     * @param array $files
     * @return UploadedFileInterface[]
     */
    public function createFromArray(array $files): array;
}
