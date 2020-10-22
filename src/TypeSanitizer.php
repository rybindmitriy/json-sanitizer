<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler;

use Generator;

interface TypeSanitizer
{
    public function getType(): string;

    public function sanitize(&$value, Sanitizer $sanitizer, Type $type): Generator;
}
