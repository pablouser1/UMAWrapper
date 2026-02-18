<?php

namespace UMA;

use Composer\InstalledVersions;
use Psr\SimpleCache\CacheInterface;
use UMA\Models\Response;
use UMA\Parsers\IParser;

/**
 * Handle HTTP requests.
 */
class Sender
{
    private const string BASE_API = "https://duma.uma.es/api/appuma";
    private const string BASE_WEB = "https://duma.uma.es/duma";
    private const string CACHE_PREFIX = 'uma_';
    private const int TTL = 86400;
    private string $csrfFile;
    private ?CacheInterface $cache;

    public function __construct(string $csrfFile, ?CacheInterface $cache)
    {
        $this->csrfFile = $csrfFile;
        $this->cache = $cache;
    }

    public function request(
        string $endpoint,
        IParser $parser,
        array $body = [],
        array $headers = [],
        string $cookies = '',
        bool $isJson = true,
        bool $caching = true,
        int $ttl = self::TTL
    ): Response {
        $cacheEnabled = $caching && $this->hasCache();
        $cacheKey = $cacheEnabled ? $this->buildCacheKey($endpoint) : null;
        if ($cacheEnabled) {
            $cached = $this->cache->get($cacheKey);
            if ($cached !== null) {
                return Response::success(200, $cached);
            }
        }

        $res = $this->send($endpoint, $parser, $body, $headers, $cookies, $isJson);

        if ($res->success && $cacheEnabled) {
            $this->cache->set($cacheKey, $res->data, $ttl);
        }

        return $res;
    }

    /**
     * Fetch CSRF token used for web forms.
     */
    public function getCsrf(): ?string
    {
        // Get csrf token if it already exists
        if (is_file($this->csrfFile)) {
            return file_get_contents($this->csrfFile);
        }

        $ch = curl_init(self::BASE_WEB . '/buscador/persona/');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_USERAGENT => $this->getUserAgent(),
        ]);

        $result = curl_exec($ch);

        if ($result !== false) {
            // Extract cookies
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
            $cookies = [];
            foreach ($matches[1] as $item) {
                parse_str($item, $cookie);
                $cookies = array_merge($cookies, $cookie);
            }

            // Write csrf token to tmp
            if (isset($cookies['csrftoken'])) {
                file_put_contents($this->csrfFile, $cookies['csrftoken']);
                return $cookies['csrftoken'];
            }
        }

        return null;
    }

    private function send(
        string $endpoint,
        IParser $parser,
        array $body,
        array $headers,
        string $cookies,
        bool $isJson
    ): Response {
        $base = $isJson ? self::BASE_API : self::BASE_WEB;

        $options = [
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->getUserAgent(),
        ];

        $url = $base . $endpoint;

        if (!empty($body)) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = http_build_query($body);
        }

        if (!empty($headers)) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        if ($cookies !== '') {
            $options[CURLOPT_COOKIE] = $cookies;
        }

        $ch = curl_init($url);

        curl_setopt_array($ch, $options);

        $data = curl_exec($ch);
        $error = curl_errno($ch);
        $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        if (!$error) {
            // Request sent
            return Response::fromRaw($code, $data, $parser);
        }

        return Response::failure(502, 'Network error');
    }

    private function getUserAgent(): string
    {
        $root = InstalledVersions::getRootPackage();
        $pkgName = $root['name'];
        $projectName = explode('/', $pkgName)[1];
        $version = $root['version'];
        return "$projectName/{$version} (https://github.com/$pkgName)";
    }

    private function buildCacheKey(string $endpoint): string
    {
        return self::CACHE_PREFIX . md5($endpoint);
    }

    private function hasCache(): bool
    {
        return $this->cache !== null;
    }
}
