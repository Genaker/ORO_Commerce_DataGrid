# API Reference

## Data Endpoint (JSON API)

The grid data endpoints (e.g. `/grid/gridjs/data`, `/grid/ag-grid/data`) accept these query parameters:

| Parameter | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `p` | int | 1 | Page number (1-based). Alias: `page` |
| `pageSize` | int | 10 | Rows per page. Alias: `size` (Tabulator) |
| `sortField` | string | — | Field name to sort by |
| `sortOrder` | string | — | `asc` or `desc` |
| `filter[field]` | string | — | Filter value for a field (e.g. `filter[sku]=ABC`) |

**Response format:**
```json
{
  "data": [ { "id": 1, "sku": "ABC", ... } ],
  "total": 42
}
```

---

## GridDataProviderInterface

```php
interface GridDataProviderInterface
{
    /**
     * @param array<string, string> $fields Field name => label
     * @param array<string, mixed>  $filters
     * @return array{data: array, total: int, timeSql?: float}
     */
    public function getJsonGridData(
        array $fields,
        array $filters,
        int $page,
        int $pageSize,
        ?string $sortField,
        ?string $sortOrder
    ): array;
}
```

## GenericGridBlock

| Method | Returns | Description |
| :--- | :--- | :--- |
| `getFields()` | `array<string, string>` | Field name => label |
| `getFieldsNames()` | `list<string>` | Ordered field names |
| `getGridJsonData()` | `array{data, total, timeSql?}` | Grid data for current request |
| `getGridJsonDataWithLimit(int $limit)` | `array{data, total, timeSql?}` | Load up to `$limit` rows for client-side processing (html-all / infinity mode) |
| `getFilterValues()` | `array<string, mixed>` | Current filter values from query |
| `getDataUrl()` | `string` | Data endpoint URL with filter query params |

## GridBuilder

| Method | Returns | Description |
| :--- | :--- | :--- |
| `setEntity(string $entityClass)` | `self` | Doctrine entity class |
| `setFields(array $fields)` | `self` | Field name => label |
| `addProcessor(string $field, DataProcessorInterface $processor)` | `self` | Cell processor for field |
| `setDefaultSort(string $field, string $order)` | `self` | Default sort (asc/desc) |
| `build()` | `GridConfig` | Build config |

## DataProcessorInterface

```php
interface DataProcessorInterface
{
    /**
     * @param array<string, mixed> $row Full row (may include 'entity')
     */
    public function process(string $field, mixed $value, array $row): mixed;
}
```

## Built-in Processors

| Class | Use Case |
| :--- | :--- |
| `DefaultProcessor` | Pass-through, null → '' |
| `DateProcessor` | Format `DateTimeInterface` (default `Y-m-d`) |
| `StatusProcessor` | Format status (object with `getId()` or scalar) |
| `ImageProcessor` | Product images via `AttachmentManager`, `ProductImageHelper` |

## ProductGridLayoutDataProvider

| Method | Returns | Description |
| :--- | :--- | :--- |
| `getGridBlock(?string $routeName)` | `GenericGridBlock` | Block for current route |
| `getGridType(?string $routeName)` | `string` | `GridJs`, `DataTable`, `Tabulator`, or `AgGrid` |
