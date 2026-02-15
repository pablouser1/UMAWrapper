<?php

namespace UMA\Parsers;

use UMA\Helpers\Document;
use UMA\Models\Response;

/**
 * Parser for IDNC -> Email converter.
 */
class IdncToEmailParser implements IParser
{
    public function handle(int $initialCode, string $rawPayload): Response
    {
        $email = '';
        $doc = Document::parse($rawPayload);

        if ($doc !== null) {
            $xpath = new \DOMXpath($doc);
            $elements = $xpath->query("/html/body/div[4]/div[2]/div[2]");
            if ($elements !== false && $elements->count() > 0) {
                $div = $elements->item(0);

                // Los correos electrónicos están separados con <br>
                // Extraemos el primero disponible
                $innerHtml = Document::innerHtml($div);

                $emailsUnfiltered = array_map(fn(string $email) => trim($email), explode('<br>', $innerHtml));
                $emails = array_filter($emailsUnfiltered, fn(string $email) => !empty($email));
                $email = $emails[0];
            }
        }

        if ($email !== '') {
            $data = (object) ['email' => $email];
            return new Response($initialCode, $data);
        }

        return new Response($initialCode, 'No se pudo procesar el documento');
    }
}
