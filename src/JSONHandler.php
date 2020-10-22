<?php

declare(strict_types=1);

namespace jetdigger\JSONHandler;

use JsonException;

class JSONHandler
{
    private Sanitizer $sanitizer;

    public function __construct(Sanitizer $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    public function handle(string $json, Type $type): array
    {
        $result = [
            'errors' => [],
            'result' => [],
        ];

        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            $result['errors'] = $this->sanitizer->sanitize($data, $type);

            if (0 === count($result['errors'])) {
                $result['result'] = $data;
            }
        } catch (JsonException $exception) {
            $result['errors'][] = new Error($exception->getMessage());
        }

        return $result;
    }
}
