<?php

namespace UMA\Parsers;

use UMA\Helpers\Document;
use UMA\Models\Response;

/**
 * Parser for DUMA search.
 */
class SearchParser implements IParser
{
    public function handle(int $initialCode, string $rawPayload): Response
    {
        if ($initialCode !== 200) {
            return new Response($initialCode, null, 'Error al buscar profesor');
        }

        $results = [];
        $doc = Document::parse(html: $rawPayload);
        if ($doc !== null) {
            // Get all h4 in the doc
            $h4s = $doc->getElementsByTagName('h4');
            foreach ($h4s as $h4) {
                // Take second child (a)
                $a = $h4->childNodes->item(2);
                $url = $a?->attributes?->getNamedItem('href')?->nodeValue;
                if ($url) {
                    $results[] = (object) [
                        'name' => mb_convert_encoding(
                            string: $a->textContent,
                            to_encoding: 'ISO-8859-1'
                        ), // Sin el mb_convert_encoding los caractéres especiales salen mal
                        'idnc' => basename($url)
                    ];
                }
            }
        }

        return new Response($initialCode, $results);
    }
}
