<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler\Type;

use jetdigger\JSONHandler\Type;

class AssociativeArrayType implements Type
{
    private array $keys;

    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    public function getKeys(): array
    {
        return $this->keys;
    }
}
