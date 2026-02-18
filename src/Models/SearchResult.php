<?php
namespace UMA\Models;

readonly class SearchResult
{
    public function __construct(
        public string $nombre,
        public string $idnc,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            idnc: $data['idnc'],
        );
    }
}
