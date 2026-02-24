# Genaker Product Grid Bundle — Documentation

Documentation for the Product Grid Bundle extension for OroCommerce.

## Contents

| Document | Description |
| :--- | :--- |
| [Installation](installation.md) | Install via Composer, assets, and configuration |
| [Architecture](architecture.md) | Components, data flow, and layout integration |
| [Adding a New Grid](adding-a-new-grid.md) | Create a grid for a custom entity (Order, etc.) |
| [Testing](testing.md) | Run tests and test structure |
| [API Reference](api-reference.md) | Key classes and interfaces |

## Quick Links

- **Demo routes**: `/grid/gridjs`, `/grid/datatable`, `/grid/tabulator`, `/grid/ag-grid`
- **Data endpoints**: `/grid/{type}/data` (JSON)
- **Variants**: `/json`, `/html`, `/ajax`, `/ajax-pagination`, `/html-all` (infinity mode) per grid type
- **Infinity mode demo**: `/grid/gridjs/html-all` — loads up to 1000 records, client-side sort/search/pagination
