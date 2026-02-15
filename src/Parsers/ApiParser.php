<?php

namespace UMA\Parsers;

use UMA\Models\Response;

/**
 * Parser for all JSON-based endpoints.
 */
class ApiParser implements IParser
{
    public function handle(int $initialCode, string $rawPayload): Response
    {
        $json = json_decode($rawPayload);
        if ($json === null) {
            return new Response(code: 502, error: 'JSON inválido');
        }

        if (empty($json)) {
            return new Response(code: 502, error: 'Cuerpo vacío');
        }

        if (isset($json->error) && $json->error) {
            return new Response(404, error: $json->nombre ?? 'Recurso no encontrado');
        }

        if (isset($json->creditos) && $json->creditos === '') {
            return new Response(404, error: 'Este plan no existe');
        }

        if (isset($json->ERROR)) {
            return new Response(502, error: $json->ERROR);
        }

        return new Response($initialCode, data: $json);
    }
}
