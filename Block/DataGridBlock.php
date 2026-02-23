<?php

namespace Genaker\Bundle\DataGridBundle\Block;

use Genaker\Bundle\DataGridBundle\Api\GridDataProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Data grid block (generic, used with sample providers).
 */
class DataGridBlock extends GenericGridBlock
{
    private const DEFAULT_FIELDS = [
        'id' => 'ID',
        'image' => 'Image',
        'sku' => 'SKU',
        'denormalizedDefaultName' => 'Name',
        'status' => 'Status',
        'type' => 'Type',
        'createdAt' => 'Created',
    ];

    /**
     * @param array<string, string> $defaultFields
     */
    public function __construct(
        GridDataProviderInterface $dataProvider,
        RequestStack $requestStack,
        UrlGeneratorInterface $router,
        string $dataRouteName,
        array $defaultFields = self::DEFAULT_FIELDS,
    ) {
        $dataUrl = $router->generate($dataRouteName);
        parent::__construct($dataProvider, $requestStack, $defaultFields, $dataUrl);
    }
}
