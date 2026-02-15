<?php

namespace UMA;

use Psr\SimpleCache\CacheInterface;

/**
 * Options available for the Api class.
 */
class Options
{
    public readonly string $csrfFile;
    public readonly ?CacheInterface $cache;

    public function __construct(
        ?string $csrfFile = null,
        ?CacheInterface $cache = null,
    ) {
        $this->csrfFile = $csrfFile ?? sys_get_temp_dir() . '/uma_csrf.txt';
        $this->cache = $cache;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            csrfFile: $data['csrfFile'] ?? null,
            cache: $data['cache'] ?? null,
        );
    }
}
