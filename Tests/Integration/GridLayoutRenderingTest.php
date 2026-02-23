<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Integration;

/**
 * Asserts layout and template are rendered for grid index pages.
 */
class GridLayoutRenderingTest extends DataGridIntegrationTestCase
{
    public function testGridJsIndexRendersLayoutAndTemplate(): void
    {
        $response = $this->request('GET', 'https://app.peigenesis.test/grid/gridjs');

        self::assertSame(200, $response->getStatusCode(), $response->getContent() ?: '');
        $content = $response->getContent();
        self::assertStringContainsString('Product Grid (Grid.js)', $content, 'Template content should be rendered');
        self::assertStringContainsString('product-grid-container', $content, 'Grid container div should be present');
        self::assertStringContainsString('gridjs.min.js', $content, 'Grid.js script should be included');
        self::assertStringContainsString('var columns = [', $content, 'Columns should be populated');
        self::assertStringContainsString('name: \'Image\'', $content, 'Image column should be present');
        self::assertStringContainsString('formatter: (cell) => cell ? gridjs.html', $content, 'Image formatter should be present');
        self::assertStringContainsString('var fieldIds = ["id","image","sku"', $content, 'Field IDs should include image');
        self::assertStringNotContainsString('Failed to resolve the context variables', $content, 'Should not have layout resolution errors');
        self::assertStringNotContainsString('Neither the property "getGridJsonData"', $content, 'Should not have property access errors');
    }

    public function testDataTableIndexRendersLayoutAndTemplate(): void
    {
        $response = $this->request('GET', 'https://app.peigenesis.test/grid/datatable');

        self::assertSame(200, $response->getStatusCode(), $response->getContent() ?: '');
        $content = $response->getContent();
        self::assertStringContainsString('Product Grid (DataTables)', $content, 'Template content should be rendered');
        self::assertStringContainsString('product-grid', $content, 'Grid table should be present');
    }

    public function testTabulatorIndexRendersLayoutAndTemplate(): void
    {
        $response = $this->request('GET', 'https://app.peigenesis.test/grid/tabulator');

        self::assertSame(200, $response->getStatusCode(), $response->getContent() ?: '');
        $content = $response->getContent();
        self::assertStringContainsString('Product Grid (Tabulator)', $content, 'Template content should be rendered');
        self::assertStringContainsString('tabulator.min.js', $content, 'Tabulator script should be included');
    }

    public function testAgGridIndexRendersLayoutAndTemplate(): void
    {
        $response = $this->request('GET', 'https://app.peigenesis.test/grid/ag-grid');

        self::assertSame(200, $response->getStatusCode(), $response->getContent() ?: '');
        $content = $response->getContent();
        self::assertStringContainsString('Product Grid (AG Grid)', $content, 'Template content should be rendered');
        self::assertStringContainsString('ag-grid.min.js', $content, 'AG Grid script should be included');
    }
}
