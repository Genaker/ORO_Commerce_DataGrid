<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Integration test base using real app kernel (dev env).
 * Uses HttpKernel::handle() instead of test.client - no framework.test required.
 * Base URL is configurable via TEST_BASE_URL env var to avoid leaking real implementation domain.
 */
abstract class DataGridIntegrationTestCase extends KernelTestCase
{
    protected static $class = 'AppKernel';

    private const AUTH_USER = 'admin@example.com';
    private const AUTH_PW = 'admin';
    private const AUTH_ORGANIZATION = 1;

    protected function setUp(): void
    {
        parent::setUp();
        if (!static::$booted) {
            static::bootKernel(['environment' => 'dev']);
        }
    }

    protected static function getContainer(): ContainerInterface
    {
        if (!static::$booted) {
            static::bootKernel(['environment' => 'dev']);
        }
        return static::$kernel->getContainer();
    }

    protected function getBaseUrl(): string
    {
        $baseUrl = getenv('TEST_BASE_URL') ?: 'http://localhost';
        $baseUrl = rtrim($baseUrl, '/');
        if ($baseUrl === '') {
            throw new \RuntimeException('TEST_BASE_URL must be set and non-empty');
        }
        return $baseUrl;
    }

    protected function url(string $path): string
    {
        $path = '/' . ltrim($path, '/');
        return $this->getBaseUrl() . $path;
    }

    protected function request(string $method, string $uri, array $server = [], int $redirectLimit = 5): Response
    {
        if (str_starts_with($uri, '/') && !str_starts_with($uri, '//')) {
            $uri = $this->getBaseUrl() . $uri;
        }
        $request = Request::create($uri, $method, [], [], [], $server);
        $response = static::$kernel->handle($request);

        if ($redirectLimit > 0 && $response->isRedirection() && $response->headers->has('Location')) {
            $location = $response->headers->get('Location');
            return $this->request('GET', $location, $server, $redirectLimit - 1);
        }

        return $response;
    }

    protected function requestWithAuth(string $method, string $uri): Response
    {
        return $this->request($method, $uri, $this->getBasicAuthServer());
    }

    private function getBasicAuthServer(): array
    {
        return [
            'PHP_AUTH_USER' => self::AUTH_USER,
            'PHP_AUTH_PW' => self::AUTH_PW,
            'HTTP_PHP_AUTH_ORGANIZATION' => (string) self::AUTH_ORGANIZATION,
        ];
    }
}
