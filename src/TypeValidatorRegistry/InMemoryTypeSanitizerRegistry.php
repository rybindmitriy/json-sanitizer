<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler\TypeValidatorRegistry;

use jetdigger\JSONHandler\TypeSanitizer;
use jetdigger\JSONHandler\TypeSanitizerRegistry;
use RuntimeException;

class InMemoryTypeSanitizerRegistry implements TypeSanitizerRegistry
{
    private array $typeSanitizers = [];

    /**
     * @param TypeSanitizer[] $typeSanitizers
     */
    public function __construct(array $typeSanitizers)
    {
        foreach ($typeSanitizers as $typeSanitizer) {
            $this->typeSanitizers[$typeSanitizer->getType()] = $typeSanitizer;
        }
    }

    public function getTypeSanitizer(string $type): TypeSanitizer
    {
        if (isset($this->typeSanitizers[$type])) {
            return $this->typeSanitizers[$type];
        }

        // @todo
        throw new RuntimeException('');
    }
}
