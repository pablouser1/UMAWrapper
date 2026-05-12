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
        $doc = Document::parse($rawPayload);
        if ($doc === null) {
            return Response::failure(502, 'No se pudo procesar el documento');
        }

        $xpath = new DOMXpath($doc);
        // Check idnc exists
        $alerts = $xpath->query("//div[@class='alert-danger']");
        if (!$alerts || $alerts->count() > 0) {
            return Response::failure(404, 'Profesor no encontrado');
        }

        // Name
        $container = $xpath->query('/html/body/div[3]/h1/img');
        if (!$container || $container->count() === 0) {
            return Response::failure(502, 'No se pudo extrar nombre');
        }

        $img = $container->item(0);
        $name = $img->getAttribute('title');

        // Email
        $elements = $xpath->query('/html/body/div[4]/div[2]/div[2]');
        if (!$elements || $elements->count() === 0) {
            return Response::failure(502, 'No se pudo extrar correo');
        }

        $div = $elements->item(0);

        // Los correos electrónicos están separados con <br>
        // Extraemos el primero disponible
        $innerHtml = Document::innerHtml($div);

        $emailsUnfiltered = array_map('trim', explode('<br>', $innerHtml));
        $emails = array_filter($emailsUnfiltered, fn(string $email) => !empty($email));
        $email = $emails[0];

        return Response::success($initialCode, new ProfesorBasic($name, $email));
    }
}
