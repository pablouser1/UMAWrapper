<?php

test('success response', function () {
    $res = $this->api->centros();
    expect($res->success)->toBeTrue();
    expect($res->data)->toBeArray()->not()->toBeEmpty();
});
