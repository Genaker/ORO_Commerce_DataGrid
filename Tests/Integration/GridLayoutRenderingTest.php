<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Integration;

/**
 * Asserts layout and template are rendered for grid index pages.
 */
class GridLayoutRenderingTest extends DataGridIntegrationTestCase
{
    public function testBaseUrlIsConfigured(): void
    {
        $baseUrl = $this->getBaseUrl();
        self::assertNotEmpty($baseUrl, 'TEST_BASE_URL must be set');
        self::assertStringStartsWith('http', $baseUrl, 'TEST_BASE_URL should be a valid URL');
    }

    public function testGridJsIndexRendersLayoutAndTemplate(): void
    {
        $response = $this->request('GET', $this->url('/grid/gridjs'));

        self::assertSame(200, $response->getStatusCode(), $response->getContent() ?: '');
        $content = $response->getContent();
        self::assertStringContainsString('Product Grid (Grid.js)', $content, 'Template content should be rendered');
        self::assertStringContainsString('product-grid-container', $content, 'Grid container div should be present');
        self::assertStringContainsString('gridjs.min.js', $content, 'Grid.js script should be included');
        self::assertStringContainsString('var columns = [', $content, 'Columns should be populated');
        self::assertStringContainsString('name: \'Image\'', $content, 'Image column should be present');
        self::assertStringContainsString('formatter: (cell) => cell ? gridjs.html', $content, 'Image formatter should be present');
        self::assertStringContainsString('var fieldIds = ["id","image","sku"', $content, 'Field IDs should include image');
    }

    public function testDataTableIndexRendersLayoutAndTemplate(): void
    {
        $response = $this->request('GET', $this->url('/grid/datatable'));

        self::assertSame(200, $response->getStatusCode(), $response->getContent() ?: '');
        $content = $response->getContent();
        self::assertStringContainsString('Product Grid (DataTables)', $content, 'Template content should be rendered');
        self::assertStringContainsString('product-grid', $content, 'Grid table should be present');
    }

    public function testTabulatorIndexRendersLayoutAndTemplate(): void
    {
        $response = $this->request('GET', $this->url('/grid/tabulator'));

        self::assertSame(200, $response->getStatusCode(), $response->getContent() ?: '');
        $content = $response->getContent();
        self::assertStringContainsString('Product Grid (Tabulator)', $content, 'Template content should be rendered');
        self::assertStringContainsString('tabulator.min.js', $content, 'Tabulator script should be included');
    }

    public function testAgGridIndexRendersLayoutAndTemplate(): void
    {
        $response = $this->request('GET', $this->url('/grid/ag-grid'));

        self::assertSame(200, $response->getStatusCode(), $response->getContent() ?: '');
        $content = $response->getContent();
        self::assertStringContainsString('Product Grid (AG Grid)', $content, 'Template content should be rendered');
        self::assertStringContainsString('ag-grid.min.js', $content, 'AG Grid script should be included');
    }
}
