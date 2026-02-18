<?php
namespace UMA\Models;

readonly class Titulacion
{
    public function __construct(
        public int $codigoPlan,
        public int $codigoCentro,
        public string $centro,
        public string $plan,
        public string $cursoImplanta,
        public string $cursoExtingue,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            codigoPlan: $data['COD_PLAN'],
            codigoCentro: $data['COD_CENTRO'],
            centro: $data['CENTRO'],
            plan: $data['PLAN'],
            cursoImplanta: $data['CURSO_IMPLANTA'],
            cursoExtingue: $data['CURSO_EXTINGUE'],
        );
    }
}
