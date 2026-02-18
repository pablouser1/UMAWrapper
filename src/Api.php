<?php

namespace UMA;

use UMA\Models\Asignatura;
use UMA\Models\Centro;
use UMA\Models\Departamento;
use UMA\Models\Personal;
use UMA\Models\Plan;
use UMA\Models\Profesor;
use UMA\Models\Response;
use UMA\Models\SearchResult;
use UMA\Models\Titulacion;
use UMA\Parsers\ApiParser;
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

    /**
     * @return Response<Centro[]>
     */
    public function centros(): Response
    {
        return $this->sender->request(
            endpoint: '/centros/listado/',
            parser: new ApiParser(Centro::class),
            ttl: 604800 // 1 semana
        );
    }

    /**
     * Titulaciones a partir de código de centro.
     *
     * @return Response<Titulacion[]>
     */
    public function titulaciones(int $centro_id): Response
    {
        return $this->sender->request(
            endpoint: "/centros/titulaciones/$centro_id/",
            parser: new ApiParser(Titulacion::class)
        );
    }

    /**
     * Plan a partir de id de titulación.
     *
     * @return Response<Plan>
     */
    public function plan(int $id): Response
    {
        return $this->sender->request(
            endpoint: "/plan/$id/",
            parser: new ApiParser(Plan::class, isCollection: false),
        );
    }

    /**
     * Asignatura usando el ID de la asignatura y el ID del plan asociado.
     *
     * @return Response<Asignatura>
     */
    public function asignatura(int $asignatura_id, int $plan_id): Response
    {
        return $this->sender->request(
            endpoint: "/asignatura/$asignatura_id/$plan_id/",
            parser: new ApiParser(Asignatura::class, isCollection: false)
        );
    }

    /**
     * Profesor usando su correo electrónico.
     *
     * @return Response<Profesor>
     */
    public function profesor(string $email): Response
    {
        return $this->sender->request(
            endpoint: "/profesor/$email/",
            parser: new ApiParser(Profesor::class, isCollection: false)
        );
    }

    /**
     * Convierte un idnc a email haciendo scraping en la web
     *
     * @return Response<string>
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
     *
     * @return Response<SearchResult>
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

    /**
     * @return Response<Departamento[]>
     */
    public function departamentos(string $codigo): Response
    {
        return $this->sender->request(
            endpoint: "/departamentos/listado/$codigo/",
            parser: new ApiParser(Departamento::class)
        );
    }

    /**
     * @return Response<Personal[]>
     */
    public function personal(string $codigo): Response
    {
        return $this->sender->request(
            endpoint: "/departamentos/personal/$codigo/",
            parser: new ApiParser(Personal::class)
        );
    }
}
