<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Integration;

class GridDataEndpointTest extends DataGridIntegrationTestCase
{
    public function testGridJsDataEndpointReturnsJsonStructure(): void
    {
        $response = $this->request('GET', $this->url('/grid/gridjs/data'));

        self::assertSame(200, $response->getStatusCode(), $response->getContent() ?: '');
        $data = json_decode($response->getContent(), true);
        self::assertIsArray($data);
        self::assertArrayHasKey('data', $data);
        self::assertArrayHasKey('total', $data);
        self::assertIsArray($data['data']);
        self::assertIsInt($data['total']);
    }

    public function testGridJsDataEndpointReturnsValidJson(): void
    {
        $response = $this->request('GET', $this->url('/grid/gridjs/data'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('application/json', $response->headers->get('Content-Type'));
        $data = json_decode($response->getContent(), true);
        self::assertNotNull($data, 'Response should be valid JSON');
        self::assertGreaterThanOrEqual(0, $data['total']);
    }

    /**
     * Asserts the endpoint is publicly accessible (no login required).
     * Unauthenticated request must return 200, not 302 redirect to login.
     */
    public function testGridJsDataEndpointIsPublic(): void
    {
        $response = $this->request('GET', $this->url('/grid/gridjs/data'));

        self::assertSame(200, $response->getStatusCode(), 'Endpoint should be public; got redirect to login? ' . $response->getContent());
        self::assertNotSame(302, $response->getStatusCode(), 'Should not redirect to login when unauthenticated');
    }
}
