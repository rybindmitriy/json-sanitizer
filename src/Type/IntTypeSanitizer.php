<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler\Type;

use Generator;
use jetdigger\JSONHandler\Error;
use jetdigger\JSONHandler\Sanitizer;
use jetdigger\JSONHandler\Type;
use jetdigger\JSONHandler\TypeSanitizer;

class IntTypeSanitizer implements TypeSanitizer
{
    public function getType(): string
    {
        return IntType::class;
    }

    public function sanitize(&$value, Sanitizer $sanitizer, Type $type): Generator
    {
        if (
            is_int($value)
            || (is_string($value) && preg_match('~^\d+$~', $value))
        ) {
            $value = (int) $value;

            return;
        }

        yield Error::invalidType('integer');
    }
}
