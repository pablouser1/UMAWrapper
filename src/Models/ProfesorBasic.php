<?php

namespace UMA\Models;

readonly class ProfesorBasic
{
    public function __construct(
        public string $nombre,
        public string $email,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            email: $data['email'],
        );
    }
}
