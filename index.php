<?php

declare(strict_types=1);

use jetdigger\JSONHandler\JSONHandler;
use jetdigger\JSONHandler\Sanitizer;
use jetdigger\JSONHandler\Type\AssociativeArrayType;
use jetdigger\JSONHandler\Type\AssociativeArrayTypeSanitizer;
use jetdigger\JSONHandler\Type\FloatType;
use jetdigger\JSONHandler\Type\FloatTypeSanitizer;
use jetdigger\JSONHandler\Type\IntType;
use jetdigger\JSONHandler\Type\IntTypeSanitizer;
use jetdigger\JSONHandler\Type\MobileType;
use jetdigger\JSONHandler\Type\MobileTypeSanitizer;
use jetdigger\JSONHandler\Type\StringType;
use jetdigger\JSONHandler\Type\StringTypeSanitizer;
use jetdigger\JSONHandler\TypeValidatorRegistry\InMemoryTypeSanitizerRegistry;

require 'vendor/autoload.php';

$sanitizer = new Sanitizer(
    new InMemoryTypeSanitizerRegistry(
        [
            new AssociativeArrayTypeSanitizer(),
            new FloatTypeSanitizer(),
            new IntTypeSanitizer(),
            new MobileTypeSanitizer(),
            new StringTypeSanitizer(),
        ]
    )
);

$jsonHandler = new JSONHandler($sanitizer);

$result =
    $jsonHandler->handle(
        '{"age":"33","balance":"100.80","fullName":{"firstName":"Дмитрий","patronymic":"Андреевич","surname":"Рыбин"},"mobile":"89146585225"}',
        new AssociativeArrayType(
            [
                'age'      => new IntType(),
                'balance'  => new FloatType(),
                'fullName' => new AssociativeArrayType(
                    [
                        'firstName'  => new StringType(),
                        'patronymic' => new StringType(),
                        'surname'    => new StringType(),
                    ]
                ),
                'mobile'          => new MobileType(),
            ]
        )
    );

print_r($result);
