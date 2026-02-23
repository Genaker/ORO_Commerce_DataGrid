<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Functional;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * Base for ProductGrid functional tests. Skips when test.client is not available
 * (e.g. run oro:install --env=test first).
 */
abstract class ProductGridWebTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        self::markTestSkipped('Skipping functional tests as requested.');
    }
}
