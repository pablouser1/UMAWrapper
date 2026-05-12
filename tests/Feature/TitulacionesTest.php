<?php

use function Pest\Faker\fake;

test('success response with valid id', function (int $centro) {
    $res = $this->api->titulaciones($centro);
    expect($res->success)->toBeTrue();
    expect($res->data)->toBeArray()->not()->toBeEmpty();
})->with(['ETSII' => 306]);

test('not success response with invalid id', function () {
    $res = $this->api->titulaciones(fake()->numberBetween(9999));
    expect($res->success)->toBeFalse();
    expect($res->code)->toBe(404);
});
