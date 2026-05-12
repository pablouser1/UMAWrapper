<?php

namespace UMA\Parsers;

use UMA\Models\Response;
use Throwable;

use function is_array;

class ApiParser implements IParser
{
    private const string DEFAULT_NOT_FOUND = 'Recurso no encontrado';

    /**
     * @param class-string $dtoClass The FQCN of the DTO
     * @param bool $isCollection Return array of $dtoClass
     */
    public function __construct(
        private string $dtoClass,
        private bool $isCollection = true,
    ) {}

    public function handle(int $initialCode, string $rawPayload): Response
    {
        // API always returns 200,
        // Only way to figure out real status code is from error messages (or lack thereof)

        if ($rawPayload === '""' || $rawPayload === '[]') {
            // 404 if response is "" or [], applies to the mayority of endpoints
            return Response::failure(404, self::DEFAULT_NOT_FOUND);
        }

        $json = json_decode($rawPayload, true);

        if (!$json) {
            return Response::failure(502, 'JSON inválido');
        }

        if (isset($json['error']) && $json['error']) {
            return Response::failure(404, $json['nombre'] ?? self::DEFAULT_NOT_FOUND);
        }

        if (isset($json['creditos']) && $json['creditos'] === '') {
            // 404 on /plan endpoint
            return Response::failure(404, 'Este plan no existe');
        }

        if (isset($json['ERROR'])) {
            // Some endpoints do show an error
            if ($json['ERROR'] === 'SQL Result set after last row') {
                return Response::failure(404, $json['ERROR']);
            }

            return Response::failure(502, $json['ERROR']);
        }

        // -- Mapping -- //
        try {
            if ($this->isCollection && is_array($json)) {
                $mapped = array_map(fn($item) => $this->dtoClass::fromArray($item), $json);
                return Response::success($initialCode, $mapped);
            }

            return Response::success($initialCode, $this->dtoClass::fromArray($json));
        } catch (Throwable $e) {
            return Response::failure(500, error: 'Mapping Error: ' . $e->getMessage());
        }
    }
}
