<?php

declare(strict_types=1);

namespace Rescue\Http;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

use const PHP_SAPI;
use const UPLOAD_ERR_OK;

class UploadedFile implements UploadedFileInterface
{
    private StreamInterface $stream;

    private ?int $size;

    private int $error;

    private ?string $clientFilename;

    private ?string $clientMediaType;

    private bool $moved = false;

    /**
     * @var resource|null
     */
    private $file;

    /**
     * UploadedFile constructor.
     * @param StreamInterface|resource|string $stream
     * @param int|null $size
     * @param int $error
     * @param string|null $clientFilename
     * @param string|null $clientMediaType
     */
    public function __construct(
        StreamInterface $stream,
        int $size = null,
        int $error = UPLOAD_ERR_OK,
        string $clientFilename = null,
        string $clientMediaType = null
    ) {
        $this->setStream($stream);
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

        if ($this->file !== null) {
            $this->moved = $this->isCli()
                ? rename($uri, $targetPath)
                : move_uploaded_file($uri, $targetPath);
        }

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

    /**
     * @param StreamInterface|resource|string $stream
     */
    private function setStream($stream): void
    {
        if (is_string($stream)) {
            $this->file = $stream;
        } elseif (is_resource($stream)) {
            $this->stream = new Stream($stream);
        } elseif ($stream instanceof StreamInterface) {
            $this->stream = $stream;
        } else {
            throw new InvalidArgumentException('Invalid stream or file');
        }
    }
}
