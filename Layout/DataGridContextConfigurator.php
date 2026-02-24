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

        $view = $request->query->get('view');
        if ($view === null) {
            $route = $request->attributes->get('_route', '');
            if (str_contains($route, '_ajax_pagination')) {
                $view = 'ajax_pagination';
            } elseif (str_contains($route, '_html_all')) {
                $view = 'html_all';
            } elseif (str_contains($route, '_html')) {
                $view = 'html';
            } elseif (str_contains($route, '_ajax')) {
                $view = 'ajax';
            } elseif (str_contains($route, '_json')) {
                $view = 'json';
            } else {
                $view = 'index';
            }
        }
        $context->set('view', $view);
    }
}
