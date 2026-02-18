<?php
namespace UMA\Models;

readonly class Centro
{
    public function __construct(
        public string $dn,
        public string $nombre,
        public string $codigo,
        public string $alfilws,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            dn: $data['dn'],
            nombre: $data['nombre'],
            codigo: $data['codigo'],
            alfilws: $data['alfilws'],
        );
    }
}
