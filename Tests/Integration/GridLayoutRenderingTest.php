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
        self::assertStringContainsString('formatter: (cell) => gridjs.html', $content, 'Image formatter should be present');
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

    public function testGridJsHtmlHasServerSideSorting(): void
    {
        $response = $this->request('GET', $this->url('/grid/gridjs/html'));

        self::assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        self::assertStringContainsString('sortField', $content, 'html view should have server-side sort params');
        self::assertStringContainsString('sortOrder', $content, 'html view should have server-side sort params');
    }

    public function testGridJsAjaxPaginationHasServerSideSorting(): void
    {
        $response = $this->request('GET', $this->url('/grid/gridjs/ajax-pagination'));

        self::assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        self::assertStringContainsString('sortField', $content, 'ajax_pagination view should have server-side sort params');
        self::assertStringContainsString('sortOrder', $content, 'ajax_pagination view should have server-side sort params');
    }

    public function testGridJsHtmlSyncsPaginationToUrl(): void
    {
        $response = $this->request('GET', $this->url('/grid/gridjs/html'));

        self::assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        self::assertStringContainsString('grid-url-sync.js', $content, 'html view should load URL sync script');
        self::assertStringContainsString('GridUrlSync.syncUrl', $content, 'html view should sync pagination to URL');
    }

    public function testGridJsAjaxPaginationSyncsPaginationToUrl(): void
    {
        $response = $this->request('GET', $this->url('/grid/gridjs/ajax-pagination'));

        self::assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        self::assertStringContainsString('grid-url-sync.js', $content, 'ajax_pagination view should load URL sync script');
        self::assertStringContainsString('GridUrlSync.syncUrl', $content, 'ajax_pagination view should sync pagination to URL');
    }

    public function testAgGridAjaxPaginationHasServerSidePagination(): void
    {
        $response = $this->request('GET', $this->url('/grid/ag-grid/ajax-pagination'));

        self::assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        self::assertStringContainsString('getRows', $content, 'AG Grid ajax_pagination should use server-side datasource');
        self::assertStringNotContainsString('rowData:', $content, 'AG Grid ajax_pagination should not use inline rowData');
    }

    public function testDataTableAjaxPaginationHasServerSidePagination(): void
    {
        $response = $this->request('GET', $this->url('/grid/datatable/ajax-pagination'));

        self::assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        self::assertStringContainsString('serverSide', $content, 'DataTable ajax_pagination should use server-side mode');
        self::assertStringContainsString('ajax', $content, 'DataTable ajax_pagination should use ajax');
    }

    public function testTabulatorAjaxPaginationHasServerSidePagination(): void
    {
        $response = $this->request('GET', $this->url('/grid/tabulator/ajax-pagination'));

        self::assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        self::assertStringContainsString('fetch', $content, 'Tabulator ajax_pagination should fetch data');
        self::assertStringContainsString('setData', $content, 'Tabulator ajax_pagination should use setData');
    }

    public function testGridJsHtmlHasFetchErrorHandling(): void
    {
        $response = $this->request('GET', $this->url('/grid/gridjs/html'));

        self::assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        self::assertStringContainsString('catch', $content, 'html view should handle fetch errors');
        self::assertStringContainsString('Failed to load', $content, 'html view should show error message when fetch fails');
    }

    public function testGridJsHtmlAllRenders(): void
    {
        $response = $this->request('GET', $this->url('/grid/gridjs/html-all'));

        self::assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        self::assertStringContainsString('Client-side', $content);
        self::assertStringContainsString('product-grid-container', $content);
    }

    public function testApiReferenceDocumentsDataEndpointParams(): void
    {
        $path = __DIR__ . '/../../doc/api-reference.md';
        self::assertFileExists($path);
        $content = file_get_contents($path);
        self::assertStringContainsString('p', $content, 'API reference should document p param');
        self::assertStringContainsString('sortField', $content, 'API reference should document sortField');
        self::assertStringContainsString('sortOrder', $content, 'API reference should document sortOrder');
        self::assertStringContainsString('pageSize', $content, 'API reference should document pageSize');
        self::assertStringContainsString('filter[', $content, 'API reference should document filter params');
    }
}
