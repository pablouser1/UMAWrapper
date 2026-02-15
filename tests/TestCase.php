<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use UMA\Api;
use UMA\Options;

abstract class TestCase extends BaseTestCase
{
    protected Api $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = new Api(new Options());
    }
}
