<?php
namespace UMA\Models;

readonly class Departamento
{
    public function __construct(
        public string $dn,
        public string $nombre,
        public string $codigo,
        public int $nodes,
        public bool $gps,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            dn: $data['dn'],
            nombre: $data['nombre'],
            codigo: $data['codigo'],
            nodes: $data['nodes'],
            gps: $data['gps'] ?? false,
        );
    }
}
