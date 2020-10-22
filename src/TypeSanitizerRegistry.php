<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler;

interface TypeSanitizerRegistry
{
    public function getTypeSanitizer(string $type): TypeSanitizer;
}
