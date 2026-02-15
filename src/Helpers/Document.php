<?php

namespace UMA\Helpers;

/**
 * Helper for handling HTML.
 */
class Document
{
    /**
     * Parse HTML document.
     */
    public static function parse(string $html): ?\DOMDocument
    {
        $doc = new \DOMDocument();
        $success = @$doc->loadHTML($html);
        return $success ? $doc : null;
    }

    /**
     * Replicate innerHtml from Javascript.
     */
    public static function innerHtml(\DOMNode $element): string
    {
        $innerHTML = '';
        $children = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }
}
