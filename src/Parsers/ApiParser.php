<?php

namespace UMA\Parsers;

use UMA\Models\Response;
use Throwable;

use function is_array;

class ApiParser implements IParser
{
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
        $json = json_decode($rawPayload, true);

        if (!$json) {
            return Response::failure(502, 'JSON inválido');
        }

        if (isset($json['error']) && $json['error']) {
            return Response::failure(404, $json['nombre'] ?? 'Recurso no encontrado');
        }

        if (isset($json['creditos']) && $json['creditos'] === '') {
            return Response::failure(404, 'Este plan no existe');
        }

        if (isset($json['ERROR'])) {
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
