<?php

use function Pest\Faker\fake;

test('success response with valid id', function (string $personal) {
    $res = $this->api->personal($personal);
    expect($res->success)->toBeTrue();
    expect($res->data)->toBeArray()->not()->toBeEmpty();
})->with(['Matemática Aplicada - Área' => 'a02b84c06d01']);

test('not success response with invalid id', function () {
    $res = $this->api->personal(fake()->md5());
    expect($res->success)->toBeFalse();
    expect($res->code)->toBe(404);
});
