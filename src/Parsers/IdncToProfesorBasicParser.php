<?php

namespace UMA\Parsers;

use UMA\Helpers\Document;
use UMA\Models\ProfesorBasic;
use UMA\Models\Response;
use DOMXpath;

/**
 * Parser for IDNC -> ProfesorBasic converter.
 */
class IdncToProfesorBasicParser implements IParser
{
    public function handle(int $initialCode, string $rawPayload): Response
    {
        $name = '';
        $email = '';
        $doc = Document::parse($rawPayload);

        if ($doc !== null) {
            $xpath = new DOMXpath($doc);
            // Name
            $container = $xpath->query('/html/body/div[3]/h1/img');
            if ($container !== false && $container->count() > 0) {
                $img = $container->item(0);
                $name = $img->getAttribute('title');
            }

            // Email
            $elements = $xpath->query('/html/body/div[4]/div[2]/div[2]');
            if ($elements !== false && $elements->count() > 0) {
                $div = $elements->item(0);

                // Los correos electrónicos están separados con <br>
                // Extraemos el primero disponible
                $innerHtml = Document::innerHtml($div);

                $emailsUnfiltered = array_map('trim', explode('<br>', $innerHtml));
                $emails = array_filter($emailsUnfiltered, fn(string $email) => !empty($email));
                $email = $emails[0];
            }
        }

        if ($name !== '' && $email !== '') {
            return Response::success($initialCode, new ProfesorBasic($name, $email));
        }

        return Response::failure($initialCode, 'No se pudo procesar el documento');
    }
}
