<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler;

class Sanitizer
{
    private TypeSanitizerRegistry $typeValidatorRegistry;

    public function __construct(TypeSanitizerRegistry $typeValidatorRegistry)
    {
        $this->typeValidatorRegistry = $typeValidatorRegistry;
    }

    public function sanitize(&$value, Type $type): array
    {
        $errors =
            $this->typeValidatorRegistry
                ->getTypeSanitizer(get_class($type))
                ->sanitize($value, $this, $type);

        return iterator_to_array($errors, false);
    }
}
