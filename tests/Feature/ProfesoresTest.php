<?php

use function Pest\Faker\fake;

test('success response with valid id', function (string $correo) {
    $res = $this->api->profesor($correo);
    expect($res->success)->toBeTrue();
    expect($res->data)->toBeObject();
})->with(['Sixto' => 'sixto@uma.es']);

test('not success response with invalid email', function () {
    $res = $this->api->profesor(fake()->safeEmail());
    expect($res->success)->toBeFalse();
});

test('can translate idnc to email with valid idnc', function (string $idnc) {
    $res = $this->api->profesorWeb($idnc);
    expect($res->success)->toBeTrue();
    expect($res->data)->toBeObject();
})->with(['Sixto' => 'ce4ca780-b501-45b7-9443-5c5acd4cacd3']);

test('cannot translate idnc to email with invalid idnc', function () {
    $res = $this->api->profesorWeb(fake()->uuid());
    expect($res->success)->toBeFalse();
});
