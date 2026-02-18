<?php
namespace UMA\Models;

readonly class AsignaturaBasic
{
    public function __construct(
        public int $codigo,
        public string $nombre,
        public int $curso,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            codigo: $data['codigo'] ?? $data['cod_asig'],
            nombre: $data['nombre'],
            curso: $data['curso'],
        );
    }
}
