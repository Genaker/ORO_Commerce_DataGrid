<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Integration;

class DataTableEndpointTest extends DataGridIntegrationTestCase
{
    public function testDataEndpointReturnsJsonStructure(): void
    {
        $response = $this->request('GET', $this->url('/grid/datatable/data'));

        self::assertSame(200, $response->getStatusCode(), $response->getContent() ?: '');
        $data = json_decode($response->getContent(), true);
        self::assertIsArray($data);
        self::assertArrayHasKey('data', $data);
        self::assertArrayHasKey('total', $data);
        self::assertIsArray($data['data']);
        self::assertIsInt($data['total']);
    }

    /**
     * Asserts the endpoint is publicly accessible (no login required).
     */
    public function testDataTableDataEndpointIsPublic(): void
    {
        $response = $this->request('GET', $this->url('/grid/datatable/data'));

        self::assertSame(200, $response->getStatusCode(), 'Endpoint should be public; got redirect to login?');
        self::assertNotSame(302, $response->getStatusCode());
    }
}
