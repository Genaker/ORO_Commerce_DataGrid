# Installation

## Requirements

- PHP 8.4+
- OroCommerce 6.1
- Composer

## Steps

### 1. Install via Composer

```bash
composer require genaker/product-grid-bundle
```

### 2. Install Assets

```bash
php bin/console assets:install --relative
```

For Docker/Warden:

```bash
warden shell -c "php bin/console assets:install --relative"
```

Assets are deployed to `public/bundles/genakerproductgrid/`.

### 3. Clear Cache

```bash
php bin/console cache:clear
```

## Auto-Registration

The bundle registers itself via `Resources/config/oro/bundles.yml`. No manual kernel configuration is required.

## Security

Demo routes under `/grid/*` use `PUBLIC_ACCESS` in `Resources/config/oro/app.yml`. Adjust for production if needed.
