<?php

use jetdigger\JSONHandler\Error;
use jetdigger\JSONHandler\JSONHandler;
use jetdigger\JSONHandler\Sanitizer;
use jetdigger\JSONHandler\Type\AssociativeArrayType;
use jetdigger\JSONHandler\Type\AssociativeArrayTypeSanitizer;
use jetdigger\JSONHandler\Type\FloatType;
use jetdigger\JSONHandler\Type\FloatTypeSanitizer;
use jetdigger\JSONHandler\Type\IntType;
use jetdigger\JSONHandler\Type\IntTypeSanitizer;
use jetdigger\JSONHandler\Type\ListWithSameValuesType;
use jetdigger\JSONHandler\Type\ListWithSameValuesTypeSanitizer;
use jetdigger\JSONHandler\Type\MobileType;
use jetdigger\JSONHandler\Type\MobileTypeSanitizer;
use jetdigger\JSONHandler\Type\StringType;
use jetdigger\JSONHandler\Type\StringTypeSanitizer;
use jetdigger\JSONHandler\TypeValidatorRegistry\InMemoryTypeSanitizerRegistry;
use PHPUnit\Framework\TestCase;

class JSONHandlerTest extends TestCase
{
    private JSONHandler $jsonHandler;

    public function testHandleAssociativeArray(): void
    {
        $result =
            $this->jsonHandler->handle(
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

        self::assertSame(
            [
                'age'             => 33,
                'balance'         => 100.8,
                'fullName'        => [
                    'firstName'  => 'Дмитрий',
                    'patronymic' => 'Андреевич',
                    'surname'    => 'Рыбин',
                ],
                'mobile'          => '79146585225',
            ],
            $result['result'],
        );
    }

    public function testHandleAssociativeArrayWithErrors(): void
    {
        $result =
            $this->jsonHandler->handle(
                '{"age":"abc","balance":"100.80","favoriteMusic":{"genres":["R&B", null, "dance"]},"fullName":{"firstName":null,"patronymic":"Андреевич","surname":"Рыбин"},"mobile":"89146585225"}',
                new AssociativeArrayType(
                    [
                        'age'            => new IntType(),
                        'balance'        => new FloatType(),
                        'favoriteMusic'  => new AssociativeArrayType(
                            [
                                'genres' => new ListWithSameValuesType(new StringType()),
                            ]
                        ),
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

        /** @var Error $error */
        $error = $result['errors'][0];

        self::assertSame('Invalid type. Expected integer', $error->message);
        self::assertSame('[age]', $error->path);

        /** @var Error $error */
        $error = $result['errors'][1];

        self::assertSame('Invalid type. Expected string', $error->message);
        self::assertSame('[favoriteMusic][genres][1]', $error->path);

        /** @var Error $error */
        $error = $result['errors'][2];

        self::assertSame('Invalid type. Expected string', $error->message);
        self::assertSame('[fullName][firstName]', $error->path);
    }

    public function testHandleInt(): void
    {
        $result = $this->jsonHandler->handle('"5"', new IntType());

        self::assertSame(5, $result['result']);
    }

    public function testHandleInvalidJSON(): void
    {
        $result = $this->jsonHandler->handle('', new IntType());

        /** @var Error $error */
        $error = $result['errors'][0];

        self::assertSame('Syntax error', $error->message);
    }

    public function testHandleInvalidInt(): void
    {
        $result = $this->jsonHandler->handle('"abc"', new IntType());

        /** @var Error $error */
        $error = $result['errors'][0];

        self::assertSame('Invalid type. Expected integer', $error->message);
    }

    public function testHandleInvalidMobile(): void
    {
        $result = $this->jsonHandler->handle('"1-800-MY-APPLE"', new MobileType());

        /** @var Error $error */
        $error = $result['errors'][0];

        self::assertSame('Invalid type. Expected mobile', $error->message);
    }

    public function testHandleListWithSameValuesType(): void
    {
        $result = $this->jsonHandler->handle('["abc", "def", "ghi"]', new ListWithSameValuesType(new StringType()));

        self::assertSame(
            [
                'abc',
                'def',
                'ghi',
            ],
            $result['result']
        );
    }

    public function testHandleListWithSameValuesTypeWithErrors(): void
    {
        $result = $this->jsonHandler->handle('[100.5, 100, "ghi"]', new ListWithSameValuesType(new StringType()));

        /** @var Error $error */
        $error = $result['errors'][0];

        self::assertSame('Invalid type. Expected string', $error->message);
        self::assertSame('[0]', $error->path);

        /** @var Error $error */
        $error = $result['errors'][1];

        self::assertSame('Invalid type. Expected string', $error->message);
        self::assertSame('[1]', $error->path);
    }

    public function testHandleMobile(): void
    {
        $result = $this->jsonHandler->handle('"+7 (914) 658 52 25"', new MobileType());

        self::assertSame('79146585225', $result['result']);
    }

    protected function setUp(): void
    {
        $sanitizer = new Sanitizer(
            new InMemoryTypeSanitizerRegistry(
                [
                    new AssociativeArrayTypeSanitizer(),
                    new FloatTypeSanitizer(),
                    new IntTypeSanitizer(),
                    new ListWithSameValuesTypeSanitizer(),
                    new MobileTypeSanitizer(),
                    new StringTypeSanitizer(),
                ]
            )
        );

        $this->jsonHandler = new JSONHandler($sanitizer);
    }
}
