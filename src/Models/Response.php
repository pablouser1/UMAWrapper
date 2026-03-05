<?php

namespace UMA\Models;

use UMA\Parsers\IParser;

/**
 * Represents a sanitized API response.
 *
 * @template T
 */
readonly class Response
{
    public bool $success;

    /**
     * @param T $data
     */
    public function __construct(
        public int $code,
        public mixed $data,
        public ?string $error = null,
    ) {
        $this->success = $this->code >= 200 && $this->code < 300 && $this->error === null;
    }

    /**
     * Factory for successful responses.
     */
    public static function success(int $code, mixed $data): self
    {
        return new self(
            code: $code,
            data: $data,
        );
    }

    /**
     * Factory for error responses.
     */
    public static function failure(int $code, string $error): self
    {
        return new self(
            code: $code,
            data: [],
            error: $error,
        );
    }

    /**
     * Factory method to handle the specific quirks of the UMA API.
     */
    public static function fromRaw(int $httpCode, ?string $rawPayload, IParser $parser): self
    {
        if (empty($rawPayload)) {
            return new self(code: $httpCode, data: [], error: 'Respuesta vacía');
        }

        return $parser->handle($httpCode, $rawPayload);
    }
}
