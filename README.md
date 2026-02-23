# Genaker Data Grid Bundle for OroCommerce

**Generic Data Grid Module | Interactive Tables | High-Performance Grids | Grid.js | DataTables | Tabulator | AG Grid | Product Sample**

---

## Installation

### 1. Install via Composer
Add the bundle to your OroCommerce project:

```bash
composer require genaker/data-grid-bundle
```

### 2. Initialize Assets
Ensure the JavaScript and CSS libraries are correctly symlinked to your public directory:

```bash
php bin/console assets:install --relative
```

### 3. Clear Cache
Warm up the cache to register the new services and layout data providers:

```bash
php bin/console cache:clear
```

The bundle uses **OroPlatform's auto-registration** (via `bundles.yml`), so no manual kernel updates are required.

---

## Overview

![Data Grid on Category Page](./Resources/images/category-page-data-grid.png)

The **Genaker Data Grid Bundle** is a generic, high-performance **OroCommerce extension** for building interactive data grids. It provides a unified, reusable framework for rendering entity data (Products, Orders, etc.) using four industry-leading libraries: **Grid.js**, **DataTables**, **Tabulator**, and **AG Grid**. Includes a **Product sample** to get started quickly.

Built with **OroPlatform 6.1 best practices**, this bundle ensures your admin interfaces are fast, themeable, and fully testable, with zero external CDN dependencies.

### Key Features & Capabilities

- **🚀 Multi-Library Support** — Seamlessly switch between Grid.js, DataTables, Tabulator, or AG Grid.
- **🖼️ Image Rendering** — Built-in support for product thumbnail rendering using Oro's `AttachmentManager`.
- **🔍 Advanced Filtering & Sorting** — Dynamic server-side filtering and multi-column sorting across all grid types.
- **🔄 Oro Layout Integration** — Uses the **Layout Data Provider** pattern for clean, cacheable UI components.
- **🏗️ Reusable Architecture** — Generic builders and providers that work with any Doctrine entity.
- **⌛ Smooth UX** — Integrated CSS preloaders and progressive enhancement for HTML-sourced grids.
- **📦 Self-Contained Assets** — All JS/CSS libraries are bundled locally for maximum reliability and speed.

---

## Table of Contents

- [Live Demo Routes](#live-demo-routes)
- [Architecture & Reusable Classes](#architecture--reusable-classes)
- [How to Add a New Grid](#adding-a-new-grid)
- [Asset Management](#asset-management)
- [Quality Assurance & Testing](#quality-assurance--testing)
- [Developer Guide](#developer-guide)

---

## Live Demo Routes

Each library is exposed through a set of demo routes. These routes are set to `PUBLIC_ACCESS` in development for easy testing.

| Library | Base URL | Data Endpoint (JSON) | Variants | JS Size (Minified) | JS Size (Gzipped) | vs. Oro DataGrid |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Grid.js** | `/grid/gridjs` | `/grid/gridjs/data` | `/json`, `/html`, `/ajax`, `/ajax-pagination` | **~52 KB** | **~17 KB** | **-93% lighter** |
| **DataTables** | `/grid/datatable` | `/grid/datatable/data` | `/json`, `/html`, `/ajax`, `/ajax-pagination` | **~86 KB** | **~30 KB** | **-88% lighter** |
| **Tabulator** | `/grid/tabulator` | `/grid/tabulator/data` | `/json`, `/html`, `/ajax`, `/ajax-pagination` | **~383 KB** | **~87 KB** | **-65% lighter** |
| **Oro DataGrid** | *Standard* | *Standard* | *Standard* | **~1.2 MB** | **~250 KB** | *Baseline* |
| **AG Grid** | `/grid/ag-grid` | `/grid/ag-grid/data` | `/json`, `/html`, `/ajax`, `/ajax-pagination` | **~1.7 MB** | **~358 KB** | **+43% heavier** |

*Note: Sizes are estimated based on Gzip compression, which is standard for modern web servers. The Oro DataGrid baseline is an estimate of the combined gzipped payload of its many individual JS components (as seen in the screenshot).*

---

## Architecture & Reusable Classes

The bundle follows a decoupled architecture, making it easy to extend.

| Class | Responsibility |
| :--- | :--- |
| **GridBuilder** | Fluent API for configuring entities, fields, processors, and default sorting. |
| **GenericGridDataProvider** | The core engine that handles Doctrine query building, filtering, and pagination. |
| **DataGridLayoutDataProvider** | **(Best Practice)** Supplies grid blocks to Oro Layout via the `data["data_grid"]` alias. |
| **ImageProcessor** | Automatically retrieves and resizes product images for grid display. |
| **DataProcessorInterface** | Interface for custom cell formatters (Dates, Statuses, Prices). |

---

## Adding a New Grid

To create a new grid for an entity like `Order`, use the `GridBuilder`:

```php
$config = (new GridBuilder($doctrine))
    ->setEntity(Order::class)
    ->setFields([
        'id' => 'ID', 
        'identifier' => 'Order #', 
        'status' => 'Status'
    ])
    ->addProcessor('status', new StatusProcessor())
    ->setDefaultSort('createdAt', 'desc')
    ->build();

$dataProvider = new GenericGridDataProvider($doctrine, $config);
```

For a detailed walkthrough, see the [How to Add a Block and Test It](./docs/HOW_TO_ADD_BLOCK_AND_TEST.md) guide.

---

## Asset Management

Assets are managed via Symfony's asset system. To ensure relative symlinks work correctly across Docker/Warden environments, always install using:

```bash
warden shell -c "php bin/console assets:install --relative"
```

Assets are deployed to `public/bundles/genakerdatagrid/`.

---

## Quality Assurance & Testing

The bundle includes a comprehensive test suite covering unit logic, API endpoints, and layout rendering.

### Run All Tests
```bash
warden shell -c "php bin/phpunit -c phpunit-datagrid.xml.dist src/Genaker/Bundle/DataGridBundle/Tests/"
```

### Key Test Suites
- **AssetPresenceTest** — Verifies that JS/CSS files are correctly installed and readable.
- **GridLayoutRenderingTest** — Ensures Twig templates and Oro Layouts render with correct data.
- **DataGridLayoutDataProviderTest** — Validates the logic for route-to-grid mapping.

---

## Developer Guide

For more in-depth information on extending this bundle, refer to the following documentation:

- [How to Add and Test Custom Layout Blocks](./docs/HOW_TO_ADD_BLOCK_AND_TEST.md)
- [OroPlatform 6.1 Architecture Principles](https://doc.oroinc.com/backend/architecture/framework/architecture-principles/)

---

## Related Keywords

*OroCommerce* • *Product Grid* • *Data Grid* • *B2B eCommerce* • *Grid.js* • *DataTables* • *Tabulator* • *AG Grid* • *Symfony 6.4* • *Doctrine ORM* • *Admin Dashboard*
