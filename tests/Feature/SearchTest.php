<?php

use function Pest\Faker\fake;

test('success response with valid search term', function (string $nombre, string $apellido_1, string $apellido_2) {
    $res = $this->api->buscar($nombre, $apellido_1, $apellido_2);
    expect($res->success)->toBeTrue();
    expect($res->data)->toBeArray()->not()->toBeEmpty();
})->with([['sixto', 'sanchez', 'merino']]);

test('success response and empty data with invalid search term', function () {
    $rand = fake()->uuid();
    $res = $this->api->buscar($rand, $rand, $rand);
    expect($res->success)->toBeTrue();
    expect($res->data)->toBeArray()->toBeEmpty();
});
