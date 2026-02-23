<?php

namespace Genaker\Bundle\DataGridBundle\Controller;

use Genaker\Bundle\DataGridBundle\Block\GenericGridBlock;
use Oro\Bundle\LayoutBundle\Attribute\Layout;
use Oro\Bundle\SecurityBundle\Attribute\AclAncestor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/ag-grid', name: 'genaker_data_grid_ag_grid_')]
class AgGridController extends AbstractController
{
    public function __construct(
        private readonly GenericGridBlock $block,
    ) {
    }

    #[Route(path: '', name: 'index', methods: ['GET'])]
    #[Layout(action: 'genaker_ag_grid_layout', vars: ['view'])]
    #[AclAncestor('oro_product_view')]
    public function index(): array
    {
        return ['view' => 'index'];
    }

    #[Route(path: '/data', name: 'data', methods: ['GET'])]
    public function data(): JsonResponse
    {
        return new JsonResponse($this->block->getGridJsonData());
    }

    #[Route(path: '/json', name: 'json', methods: ['GET'])]
    #[Layout(action: 'genaker_ag_grid_layout', vars: ['view'])]
    #[AclAncestor('oro_product_view')]
    public function jsonPage(): array
    {
        return ['view' => 'json'];
    }

    #[Route(path: '/html', name: 'html', methods: ['GET'])]
    #[Layout(action: 'genaker_ag_grid_layout', vars: ['view'])]
    #[AclAncestor('oro_product_view')]
    public function html(): array
    {
        return ['view' => 'html'];
    }

    #[Route(path: '/ajax', name: 'ajax', methods: ['GET'])]
    #[Layout(action: 'genaker_ag_grid_layout', vars: ['view'])]
    #[AclAncestor('oro_product_view')]
    public function ajax(): array
    {
        return ['view' => 'ajax'];
    }

    #[Route(path: '/ajax-pagination', name: 'ajax_pagination', methods: ['GET'])]
    #[Layout(action: 'genaker_ag_grid_layout', vars: ['view'])]
    #[AclAncestor('oro_product_view')]
    public function ajaxPagination(): array
    {
        return ['view' => 'ajax_pagination'];
    }
}
