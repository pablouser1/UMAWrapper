<?php

use UMA\Models\Plan;
use function Pest\Faker\fake;

test('success response with valid id', function (int $titulacion) {
    $res = $this->api->plan($titulacion);
    expect($res->success)->toBeTrue();
    expect($res->data)->toBeInstanceOf(Plan::class);
})->with(['ETSII - Grado Informática 2023' => 5389]);

test('not success response with invalid id', function () {
    $res = $this->api->plan(fake()->numberBetween(9999));
    expect($res->success)->toBeFalse();
});
