# Architecture

## Overview

The bundle uses a layered architecture:

1. Controllers handle HTTP requests and delegate to Layout
2. Layout Data Provider resolves the correct grid block by route
3. Blocks provide data and configuration to templates
4. Data Providers fetch and format entity data
5. Templates render grid HTML/JS per library

## Key Classes

- **ProductGridLayoutDataProvider** — Maps route name to grid block and type
- **GenericGridBlock** — Base block with getGridJsonData, getFields, getFilterValues, getDataUrl
- **ProductGridBlock** — Product-specific block with default Product fields
- **GenericGridDataProvider** — Doctrine ORM provider with filters, sort, processors
- **ProductGridDataProvider** — Product provider with ImageProcessor, DateProcessor, StatusProcessor
- **GridBuilder** — Fluent API for GridConfig
- **GridConfig** — DTO for entity, fields, processors, default sort
- **DataProcessorInterface** — Cell formatter interface

## Route-to-Grid Mapping

- genaker_product_grid_datatable_* -> DataTables
- genaker_product_grid_tabulator_* -> Tabulator
- genaker_product_grid_ag_grid_* -> AG Grid
- Default -> Grid.js

## Template Structure

- layouts/default/genaker_product_grid/layout.yml
- layouts/default/genaker_product_grid/layout.html.twig
- GridJs, DataTable, Tabulator, AgGrid each have a views/ folder with templates: index, json, html, html_all, ajax, ajax_pagination
- _filters.html.twig, _page_size_selector.html.twig are shared (in views/)

## HTML-All (Infinity Mode)

The **html-all** view uses `getGridJsonDataWithLimit(1000)` to load up to 1000 records in one request. All sort, search, and pagination run client-side—no server round-trips. Available at `/grid/gridjs/html-all`, `/grid/datatable/html-all`, etc.
