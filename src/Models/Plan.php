<?php

namespace UMA\Models;

readonly class Plan
{
    public function __construct(
        public int $duracion,
        public int $creditos,
        /** @var AsignaturaBasic[] */
        public array $asignaturas,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            duracion: $data['duracion'],
            creditos: $data['creditos'],
            asignaturas: array_map(fn($asignatura) => AsignaturaBasic::fromArray($asignatura), $data['asignaturas']),
        );
    }
}
