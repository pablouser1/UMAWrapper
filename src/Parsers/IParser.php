<?php

namespace UMA\Parsers;

use UMA\Models\Response;

/**
 * Interface for UMA Response Parser
 */
interface IParser
{
    /**
     * Run parsing.
     *
     * @param int $initialCode HTTP code
     * @param string $rawPayload Original response from server
     * @return Response Sanitized response
     */
    public function handle(int $initialCode, string $rawPayload): Response;
}
