<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler;

class Error
{
    public string $message;

    public string $path;

    public function __construct(string $message, string $path = '')
    {
        $this->message = $message;
        $this->path    = $path;
    }

    public static function invalidType(string $expectedType): self
    {
        return new self(
            sprintf('Invalid type. Expected %s', $expectedType)
        );
    }

    public function atIndex($index): self
    {
        $error       = clone $this;
        $error->path = sprintf('[%s]%s', $index, $this->path);

        return $error;
    }
}
