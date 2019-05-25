<?php

namespace Rescue\Http\Factory;

use InvalidArgumentException;
use Rescue\Http\UploadedFile;
use Rescue\Http\UploadedFileInterface;
use function is_array;
use function is_resource;
use function is_string;
use const UPLOAD_ERR_OK;

class UploadedFileFactory implements UploadedFileFactoryInterface
{
    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    public function __construct(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    /**
     * @inheritDoc
     */
    public function createFromArray(array $files): array
    {
        $uploadedFiles = [];

        foreach ($files as $key => $params) {
            $fileData = [];
            foreach ($params as $paramName => $paramValue) {
                if (is_array($paramValue)) {
                    foreach ($paramValue as $paramKey => $value) {
                        $fileData[$paramKey][$paramName] = $value;
                    }
                }
            }

            if (empty($fileData)) {
                $uploadedFiles[] = $this->createByFileData($params);
            } else {
                foreach ($fileData as $data) {
                    $uploadedFiles[] = $this->createByFileData($data);
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * @inheritDoc
     */
    public function createUploadedFile(
        $file,
        int $size = null,
        int $error = UPLOAD_ERR_OK,
        string $clientFilename = null,
        string $clientMediaType = null
    ): UploadedFileInterface {
        if (is_resource($file)) {
            $stream = $this->streamFactory->createStreamFromResource($file);
        } elseif (is_string($file)) {
            $stream = $this->streamFactory->createStreamFromFile($file);
        } else {
            throw new InvalidArgumentException('Invalid file type: string or resource allowed');
        }

        return new UploadedFile(
            $stream,
            $size,
            $error,
            $clientFilename,
            $clientMediaType
        );
    }


    private function createByFileData(array $data): UploadedFileInterface
    {
        return $this->createUploadedFile(
            $data['tmp_name'],
            $data['size'] ?? null,
            $data['error'] ?? UPLOAD_ERR_OK,
            $data['name'] ?? null,
            $data['type'] ?? null
        );
    }
}
