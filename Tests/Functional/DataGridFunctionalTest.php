<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Functional;

/**
 * @dbIsolationPerTest
 */
class ProductGridFunctionalTest extends ProductGridWebTestCase
{

    public function testGridJsIndexReturns200(): void
    {
        $this->client->request('GET', '/grid/gridjs');
        self::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGridJsDataReturnsJson(): void
    {
        $this->client->request('GET', '/grid/gridjs/data');
        $response = $this->client->getResponse();
        self::assertSame(200, $response->getStatusCode());
        self::assertStringContainsString('"data"', $response->getContent());
        self::assertStringContainsString('"total"', $response->getContent());
    }

    public function testDataTableIndexReturns200(): void
    {
        $this->client->request('GET', '/grid/datatable');
        self::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testTabulatorIndexReturns200(): void
    {
        $this->client->request('GET', '/grid/tabulator');
        self::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAgGridIndexReturns200(): void
    {
        $this->client->request('GET', '/grid/ag-grid');
        self::assertSame(200, $this->client->getResponse()->getStatusCode());
    }
}
