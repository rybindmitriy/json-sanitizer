<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler\Type;

use jetdigger\JSONHandler\Type;

class ListWithSameValuesType implements Type
{
    private Type $valuesType;

    public function __construct(Type $valuesType)
    {
        $this->valuesType = $valuesType;
    }

    public function getValuesType(): Type
    {
        return $this->valuesType;
    }
}
