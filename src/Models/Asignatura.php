<?php

namespace UMA\Models;

readonly class Asignatura
{
    public function __construct(
        public string $nombre,
        public int $cursoAcad,
        public int $curso,
        public int $cuatrimestre,
        public int $codAsig,
        public int $creditos,
        public int $creditosT,
        public int $creditosP,
        public string $tipo,
        public Persona $coordinador,
        /** @var Grupo[] */
        public array $grupos,
        public string $programa,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            cursoAcad: $data['cursoAcad'],
            curso: $data['curso'],
            cuatrimestre: $data['cuatrimestre'],
            codAsig: $data['cod_asig'],
            creditos: $data['creditos'],
            creditosT: $data['creditosT'],
            creditosP: $data['creditosP'],
            tipo: $data['tipo'],
            coordinador: Persona::fromArray($data['coordinador']),
            grupos: array_map(fn($grupo) => Grupo::fromArray($grupo), $data['grupos']),
            programa: $data['programa'],
        );
    }
}
