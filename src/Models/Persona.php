<?php

namespace UMA\Models;

readonly class Persona
{
    public function __construct(
        public string $dni,
        public string $email,
        public string $nombre,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            dni: $data['dni'],
            email: $data['email'],
            nombre: $data['nombre'],
        );
    }
}
