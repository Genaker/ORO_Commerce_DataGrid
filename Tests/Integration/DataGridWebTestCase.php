<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Integration;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * Base for ProductGrid integration tests.
 */
abstract class ProductGridWebTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->initClient([], $this->generateBasicAuthHeader());
    }
}
