# Adding a New Grid

This guide shows how to create a grid for a custom entity (e.g. Order).

## 1. Create a Data Provider

Use GridBuilder to build a GridConfig, then wrap it in GenericGridDataProvider:

```php
use Genaker\Bundle\DataGridBundle\Builder\GridBuilder;
use Genaker\Bundle\DataGridBundle\Provider\GenericGridDataProvider;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DateProcessor;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\StatusProcessor;
use Oro\Bundle\OrderBundle\Entity\Order;

$config = (new GridBuilder($doctrine))
    ->setEntity(Order::class)
    ->setFields([
        'id' => 'ID',
        'identifier' => 'Order #',
        'status' => 'Status',
        'createdAt' => 'Created',
    ])
    ->addProcessor('createdAt', new DateProcessor())
    ->addProcessor('status', new StatusProcessor())
    ->setDefaultSort('createdAt', 'desc')
    ->build();

$dataProvider = new GenericGridDataProvider($doctrine, $config);
```

## 2. Create a Block

Create a block that extends GenericGridBlock:

```php
use Genaker\Bundle\DataGridBundle\Block\GenericGridBlock;
use Genaker\Bundle\DataGridBundle\Api\GridDataProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderGridBlock extends GenericGridBlock
{
    public function __construct(
        GridDataProviderInterface $dataProvider,
        RequestStack $requestStack,
        UrlGeneratorInterface $router,
        string $dataRouteName,
    ) {
        $dataUrl = $router->generate($dataRouteName);
        parent::__construct($dataProvider, $requestStack, [
            'id' => 'ID',
            'identifier' => 'Order #',
            'status' => 'Status',
            'createdAt' => 'Created',
        ], $dataUrl);
    }
}
```

## 3. Register Services

In Resources/config/services.yml add your provider and block, then wire them to a new controller.

## 4. Add Controller and Routes

Create a controller similar to GridJsController, inject your block, and define routes for index, data, json, html, ajax, ajax-pagination.

## 5. Extend Layout Data Provider

Update ProductGridLayoutDataProvider to recognize your new route prefix and return the correct block and grid type.

## 6. Add Templates

Copy the _embed templates from GridJs (or your chosen library) and adjust field names and labels for your entity.
