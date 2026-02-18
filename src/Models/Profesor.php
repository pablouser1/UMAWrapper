<?php
namespace UMA\Models;

readonly class Profesor
{
    public function __construct(
        public string $nombre,
        public string $email,
        /** @var string[] */
        public array $telefono,
        /** @var Tutoria[] */
        public array $tutorias,
        /** @var array<array<Departamento>> */
        public array $departamentos,
        public string $jpegPhoto,
        public string $idnc,
        /** @var string[] */
        public array $mailsInstitucionales,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            email: $data['email'],
            telefono: $data['telefono'],
            tutorias: array_map(fn ($tutoria) => Tutoria::fromArray($tutoria), $data['tutorias']),
            departamentos: array_map(
                fn ($temp) => array_map(fn ($departamento) => Departamento::fromArray($departamento), $temp),
                $data['departamentos'],
            ),
            jpegPhoto: $data['jpegPhoto'],
            idnc: $data['idnc'],
            mailsInstitucionales: $data['mails_institucionales'],
        );
    }
}
