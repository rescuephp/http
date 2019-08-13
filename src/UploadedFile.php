<?php

declare(strict_types=1);

namespace Rescue\Http;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;
use const PHP_SAPI;
use const UPLOAD_ERR_OK;

class UploadedFile implements UploadedFileInterface
{
    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * @var int
     */
    private $size;

    /**
     * @var int
     */
    private $error;

    /**
     * @var string
     */
    private $clientFilename;

    /**
     * @var string
     */
    private $clientMediaType;

    /**
     * @var bool
     */
    private $moved = false;

    public function __construct(
        StreamInterface $stream,
        int $size = null,
        int $error = UPLOAD_ERR_OK,
        string $clientFilename = null,
        string $clientMediaType = null
    ) {
        $this->stream = $stream;
        $this->size = $size;
        $this->error = $error;
        $this->clientFilename = $clientFilename;
        $this->clientMediaType = $clientMediaType;
    }

    /**
     * @inheritDoc
     */
    public function getStream(): StreamInterface
    {
        return $this->stream;
    }

    /**
     * @inheritDoc
     */
    public function moveTo($targetPath): void
    {
        $targetPath = (string)$targetPath;
        $uri = $this->stream->getMetadata('uri');

        if ($uri === null || !is_string($uri)) {
            throw new RuntimeException('Invalid stream uri');
        }

        $this->moved = $this->isCli()
            ? rename($uri, $targetPath)
            : move_uploaded_file($uri, $targetPath);

        if ($this->moved === false) {
            throw new RuntimeException("Uploaded file could not be moved to $targetPath");
        }
    }

    /**
     * @inheritDoc
     */
    public function isMoved(): bool
    {
        return $this->moved;
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * @inheritDoc
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @inheritDoc
     */
    public function getClientFilename(): ?string
    {
        return $this->clientFilename;
    }

    /**
     * @inheritDoc
     */
    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }

    private function isCli(): bool
    {
        return PHP_SAPI === 'cli';
    }
}
