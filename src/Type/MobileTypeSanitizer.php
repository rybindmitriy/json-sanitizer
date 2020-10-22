<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler\Type;

use Generator;
use jetdigger\JSONHandler\Error;
use jetdigger\JSONHandler\Sanitizer;
use jetdigger\JSONHandler\Type;
use jetdigger\JSONHandler\TypeSanitizer;

class MobileTypeSanitizer implements TypeSanitizer
{
    public function getType(): string
    {
        return MobileType::class;
    }

    public function sanitize(&$value, Sanitizer $sanitizer, Type $type): Generator
    {
        if (is_string($value)) {
            $clearMobile = preg_replace('~\D~', '', $value);
            $clearMobile = preg_replace('~^[78]?(9\d{9})$~', '$1', $clearMobile);

            if (10 === strlen($clearMobile)) {
                $value = "7{$clearMobile}";

                return;
            }
        }

        yield Error::invalidType('mobile');
    }
}
