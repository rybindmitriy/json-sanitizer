<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler\Type;

use Generator;
use jetdigger\JSONHandler\Error;
use jetdigger\JSONHandler\Sanitizer;
use jetdigger\JSONHandler\Type;
use jetdigger\JSONHandler\TypeSanitizer;

class AssociativeArrayTypeSanitizer implements TypeSanitizer
{
    public function getType(): string
    {
        return AssociativeArrayType::class;
    }

    public function sanitize(&$value, Sanitizer $sanitizer, Type $type): Generator
    {
        if (is_array($value)) {
            foreach ($value as $k => &$v) {
                /* @var AssociativeArrayType $type */
                if (isset($type->getKeys()[$k])) {
                    foreach ($sanitizer->sanitize($v, $type->getKeys()[$k]) as $error) {
                        /* @var Error $error */
                        yield $error->atIndex($k);
                    }
                }
            }

            return;
        }

        yield Error::invalidType('array');
    }
}
