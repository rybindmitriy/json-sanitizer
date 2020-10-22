<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler\Type;

use Generator;
use jetdigger\JSONHandler\Error;
use jetdigger\JSONHandler\Sanitizer;
use jetdigger\JSONHandler\Type;
use jetdigger\JSONHandler\TypeSanitizer;

class FloatTypeSanitizer implements TypeSanitizer
{
    public function getType(): string
    {
        return FloatType::class;
    }

    public function sanitize(&$value, Sanitizer $sanitizer, Type $type): Generator
    {
        if (
            is_float($value)
            || (is_string($value) && preg_match('~^\d+(?:\.\d+)?$~', $value))
        ) {
            $value = (float) $value;

            return;
        }

        yield Error::invalidType('float');
    }
}
