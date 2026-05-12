<?php

use function Pest\Faker\fake;

test('success response with valid id', function (string $departamento) {
    $res = $this->api->departamentos($departamento);
    expect($res->success)->toBeTrue();
    expect($res->data)->toBeArray()->not()->toBeEmpty();
})->with(['Matemática Aplicada' => 'a02b84']);

test('not success response with invalid id', function () {
    $res = $this->api->departamentos(fake()->md5());
    expect($res->success)->toBeFalse();
    expect($res->code)->toBe(404);
});
