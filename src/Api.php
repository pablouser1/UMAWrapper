<?php

namespace UMA;

use UMA\Models\Response;
use UMA\Parsers\IdncToEmailParser;
use UMA\Parsers\SearchParser;

/**
 * Main Api class.
 */
class Api
{
    private Sender $sender;

    public function __construct(Options $options)
    {
        $this->sender = new Sender($options->csrfFile, $options->cache);
    }

    public function centros(): Response
    {
        return $this->sender->request(
            endpoint: '/centros/listado/',
            ttl: 604800 // 1 semana
        );
    }

    /**
     * Titulaciones a partir del id del centro
     */
    public function titulaciones(int $id): Response
    {
        return $this->sender->request("/centros/titulaciones/$id/");
    }

    /**
     * Plan a partir de su id
     */
    public function plan(int $id): Response
    {
        return $this->sender->request("/plan/$id/");
    }

    /**
     * Asignatura usando el ID de la asignatura y el ID del plan asociado
     */
    public function asignatura(int $asignatura_id, int $plan_id): Response
    {
        return $this->sender->request("/asignatura/$asignatura_id/$plan_id/");
    }

    /**
     * Profesor usando su correo electrónico
     */
    public function profesor(string $email): Response
    {
        return $this->sender->request("/profesor/$email/");
    }

    /**
     * Convierte un idnc a email haciendo scraping en la web
     */
    public function profesorWeb(string $idnc): Response
    {
        return $this->sender->request(
            endpoint: "/buscador/persona/$idnc/",
            parser: new IdncToEmailParser(),
            isJson: false
        );
    }

    /**
     * Hacer búsqueda por DUMA vía web scraping.
     */
    public function buscar(string $nombre, string $apellido_1, string $apellido_2): Response
    {
        $csrf = $this->sender->getCsrf();
        $headers = [
            "Referer: https://duma.uma.es/duma/buscador/",
        ];

        $cookies = "csrftoken=$csrf";

        return $this->sender->request(
            endpoint: '/buscador/persona/',
            parser: new SearchParser(),
            body: [
                "csrfmiddlewaretoken" => $csrf,
                "pas" => "off",
                "pdi" => "on",
                "nombre" => $nombre,
                "apellido_1" => $apellido_1,
                "apellido_2" => $apellido_2,
                "email" => "",
                "telefono" => "",
                "centro" => "",
                "departamento" => "",
                "general" => "",
            ],
            headers: $headers,
            cookies: $cookies,
            isJson: false,
            caching: false
        );
    }

    public function departamentos(string $codigo): Response
    {
        return $this->sender->request("/departamentos/listado/$codigo/");
    }

    public function personal(string $codigo): Response
    {
        return $this->sender->request("/departamentos/personal/$codigo/");
    }
}
