<?php

namespace Rescue\Http;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use function is_resource;

class Stream implements StreamInterface
{
    /** @var array Hash of readable and writable stream types */
    private const READ_WRITE_HASH = [
        'read' => [
            'r' => true,
            'w+' => true,
            'r+' => true,
            'x+' => true,
            'c+' => true,
            'rb' => true,
            'w+b' => true,
            'r+b' => true,
            'x+b' => true,
            'c+b' => true,
            'rt' => true,
            'w+t' => true,
            'r+t' => true,
            'x+t' => true,
            'c+t' => true,
            'a+' => true,
        ],
        'write' => [
            'w' => true,
            'w+' => true,
            'rw' => true,
            'r+' => true,
            'x+' => true,
            'c+' => true,
            'wb' => true,
            'w+b' => true,
            'r+b' => true,
            'x+b' => true,
            'c+b' => true,
            'w+t' => true,
            'r+t' => true,
            'x+t' => true,
            'c+t' => true,
            'a' => true,
            'a+' => true,
        ],
    ];

    /**
     * @var resource
     */
    private $stream;

    /**
     * @var bool
     */
    private $readable;

    /**
     * @var bool
     */
    private $writable;

    /**
     * @var bool
     */
    private $seekable;

    /**
     * @var int
     */
    private $size;

    /**
     * Stream constructor.
     * @param resource|bool $stream
     *
     * @throws InvalidArgumentException
     */
    public function __construct($stream = null)
    {
        if ($stream === null) {
            $stream = fopen('php://temp', 'rb+');
        }

        if (!is_resource($stream)) {
            throw new InvalidArgumentException('Stream must be resource');
        }

        $this->stream = $stream;
        $meta = stream_get_meta_data($this->stream);
        $this->seekable = $meta['seekable'];
        $this->readable = isset(self::READ_WRITE_HASH['read'][$meta['mode']]);
        $this->writable = isset(self::READ_WRITE_HASH['write'][$meta['mode']]);
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        if ($this->stream !== null) {
            if (is_resource($this->stream) !== null) {
                fclose($this->stream);
            }

            $this->detach();
        }
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        if ($this->stream === null) {
            return null;
        }

        $result = $this->stream;
        $this->stream = null;

        $this->size = null;
        $this->readable = $this->writable = $this->seekable = false;

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        try {
            return $this->getContents();
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * @inheritDoc
     */
    public function getContents(): string
    {
        if ($this->stream === null) {
            throw new RuntimeException('Stream is detached');
        }

        $this->seek(0);
        $contents = stream_get_contents($this->stream);

        if ($contents === false) {
            throw new RuntimeException('Unable to read stream contents');
        }

        return $contents;
    }

    /**
     * @inheritDoc
     */
    public function seek(int $offset, int $whence = 0): void
    {
        if ($this->stream === null) {
            throw new RuntimeException('Stream is detached');
        }

        if (!$this->seekable) {
            throw new RuntimeException('Stream is not seekable');
        }

        if (fseek($this->stream, $offset, $whence) === -1) {
            $message = "Unable to seek to stream position $offset with whence "
                . var_export($whence, true);

            throw new RuntimeException($message);
        }
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ?int
    {
        if ($this->size !== null) {
            return $this->size;
        }

        if ($this->stream === null) {
            return null;
        }

        $info = fstat($this->stream);

        return $this->size = ($info['size'] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function tell(): int
    {
        if ($this->stream === null) {
            throw new RuntimeException('Stream is detached');
        }

        $result = ftell($this->stream);

        if ($result === false) {
            throw new RuntimeException('Unable to determine stream position');
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function eof(): bool
    {
        if ($this->stream === null) {
            throw new RuntimeException('Stream is detached');
        }

        return feof($this->stream);
    }

    /**
     * @inheritDoc
     */
    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * @inheritDoc
     */
    public function write(string $string): int
    {
        if ($this->stream === null) {
            throw new RuntimeException('Stream is detached');
        }

        if (!$this->writable) {
            throw new RuntimeException('Cannot write to a non-writable stream');
        }

        $this->size = null;

        $result = fwrite($this->stream, $string);

        if ($result === false) {
            throw new RuntimeException('Unable to write to stream');
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * @inheritDoc
     */
    public function read(int $length): string
    {
        if ($this->stream === null) {
            throw new RuntimeException('Stream is detached');
        }

        if (!$this->readable) {
            throw new RuntimeException('Cannot read from non-readable stream');
        }

        if ($length < 0) {
            throw new RuntimeException('Length parameter cannot be negative');
        }

        if ($length === 0) {
            return '';
        }

        $string = fread($this->stream, $length);

        if ($string === false) {
            throw new RuntimeException('Unable to read from stream');
        }

        return $string;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(string $key = null)
    {
        if ($this->stream === null) {
            return $key ? null : [];
        }

        $meta = stream_get_meta_data($this->stream);

        return $key
            ? ($meta[$key] ?? null)
            : $meta;
    }
}
