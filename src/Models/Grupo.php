<?php

namespace UMA\Models;

readonly class Grupo
{
    public function __construct(
        public string $nombre,
        public string $aula,
        /** @var Persona[] */
        public array $profesores,
        public string $horarios,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            aula: $data['aula'],
            profesores: array_map(fn($profesor) => Persona::fromArray($profesor), $data['profesores']),
            horarios: $data['horarios'],
        );
    }
}
