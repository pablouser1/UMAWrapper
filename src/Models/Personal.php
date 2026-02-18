<?php
namespace UMA\Models;

readonly class Personal
{
    public function __construct(
        public string $displayName,
        public string $irisMailMainAddress,
        /** @var string[] */
        public array $telephoneNumber,
        public string $jpegPhoto,
        public string $apellidos,
        /** @var string[] */
        public array $mailsInstitucionales,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            displayName: $data['displayName'],
            irisMailMainAddress: $data['irisMailMainAddress'],
            telephoneNumber: $data['telephoneNumber'],
            jpegPhoto: $data['jpegPhoto'],
            apellidos: $data['apellidos'],
            mailsInstitucionales: $data['mails_institucionales'],
        );
    }
}
