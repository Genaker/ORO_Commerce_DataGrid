<?php

namespace Genaker\Bundle\DataGridBundle\Provider;

use Doctrine\DBAL\Connection;

/**
 * Sample Product grid data provider using raw SQL queries.
 * Extends RawSqlGridDataProvider with Product table configuration.
 */
class SampleProductSqlDataProvider extends RawSqlGridDataProvider
{
    public function __construct(Connection $connection)
    {
        parent::__construct($connection, 'oro_product', 'sku', 'asc');
    }
}
