<?php

namespace UMA\Models;

use UMA\Parsers\IParser;

/**
 * Represents a sanitized API response.
 */
readonly class Response
{
    public bool $success;

    public function __construct(
        public int $code,
        public mixed $data = null,
        public ?string $error = null,
    ) {
        $this->success = $this->code >= 200 && $this->code < 300 && $this->error === null;
    }

    /**
     * Factory method to handle the specific quirks of the UMA API.
     */
    public static function fromRaw(int $httpCode, ?string $rawPayload, IParser $parser): self
    {
        if (empty($rawPayload)) {
            return new self(code: $httpCode, error: 'Respuesta vacía');
        }

        return $parser->handle($httpCode, $rawPayload);
    }
}
