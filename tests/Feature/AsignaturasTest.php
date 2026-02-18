<?php

use UMA\Models\Asignatura;
use function Pest\Faker\fake;

test('success response with valid id', function (int $asignatura, int $titulacion) {
    $res = $this->api->asignatura($asignatura, $titulacion);
    expect($res->success)->toBeTrue();
    expect($res->data)->toBeInstanceOf(Asignatura::class);
})->with(['ETSII - Grado Informática 2023 - Cálculo para la Computación' => [55147, 5389]]);

test('not success response with invalid id', function () {
    $res = $this->api->asignatura(fake()->numberBetween(9999), fake()->numberBetween(9999));
    expect($res->success)->toBeFalse();
});
