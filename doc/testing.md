# Testing

## Running Tests

```bash
warden shell -c "php bin/phpunit -c phpunit-datagrid.xml.dist src/Genaker/Bundle/DataGridBundle/Tests/"
```

Or without Warden:

```bash
php bin/phpunit -c phpunit-datagrid.xml.dist src/Genaker/Bundle/DataGridBundle/Tests/
```

## Test Structure

| Directory | Purpose |
| :--- | :--- |
| Tests/Unit/ | Unit tests for blocks, providers, builders, processors, controllers |
| Tests/Integration/ | Integration tests using real kernel, HTTP requests, layout rendering |
| Tests/Functional/ | Functional tests (currently skipped) |

## Key Test Suites

- AssetPresenceTest — Verifies JS/CSS assets exist in public/bundles/genakerproductgrid/
- GridLayoutRenderingTest — Asserts layout and templates render for each grid type
- ProductGridLayoutDataProviderTest — Validates route-to-grid mapping
- GridDataEndpointTest, AgGridEndpointTest, etc. — Data endpoint JSON structure
- ProductGridDataProviderIntegrationTest — Full data provider with real DB

## Integration Test Base

DataGridIntegrationTestCase uses KernelTestCase and HttpKernel::handle() for requests. No test.client or framework.test config is required. Tests run against the dev environment.

## PHPUnit Config

Use phpunit-datagrid.xml.dist in the project root. Ensure it includes the bundle test path and required bootstrap.
