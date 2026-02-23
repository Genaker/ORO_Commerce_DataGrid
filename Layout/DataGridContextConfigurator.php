<?php

namespace Genaker\Bundle\DataGridBundle\Layout;

use Oro\Component\Layout\ContextConfiguratorInterface;
use Oro\Component\Layout\ContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Configures layout context with current request parameters.
 */
class DataGridContextConfigurator implements ContextConfiguratorInterface
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }

    #[\Override]
    public function configureContext(ContextInterface $context): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        $context->set('view', $request->query->get('view', 'index'));
    }
}
