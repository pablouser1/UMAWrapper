<?php
namespace UMA\Models;

readonly class Tutoria
{
    public function __construct(
        public string $nif,
        public int $cursoAcad,
        public string $periodo,
        public string $diaSemana,
        public string $horaDesde,
        public string $horaHasta,
        public string $nombreDespacho,
        public string $nombreCentro,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nif: $data['NIF'],
            cursoAcad: $data['CURSO_ACAD'],
            periodo: $data['PERIODO'],
            diaSemana: $data['DIA_SEMANA'],
            horaDesde: $data['HORA_DESDE'],
            horaHasta: $data['HORA_HASTA'],
            nombreDespacho: $data['NOMBRE_DESPACHO'],
            nombreCentro: $data['NOMBRE_CENTRO'],
        );
    }
}
