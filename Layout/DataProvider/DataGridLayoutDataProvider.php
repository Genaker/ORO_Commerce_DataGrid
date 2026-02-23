<?php

namespace Genaker\Bundle\DataGridBundle\Layout\DataProvider;

use Genaker\Bundle\DataGridBundle\Block\GenericGridBlock;

/**
 * Layout data provider exposing grid blocks for layout use.
 */
class DataGridLayoutDataProvider
{
    public function __construct(
        private readonly GenericGridBlock $gridJsBlock,
        private readonly GenericGridBlock $dataTableBlock,
        private readonly GenericGridBlock $tabulatorBlock,
        private readonly GenericGridBlock $agGridBlock,
    ) {
    }

    public function getGridJsBlock(): GenericGridBlock
    {
        return $this->gridJsBlock;
    }

    public function getDataTableBlock(): GenericGridBlock
    {
        return $this->dataTableBlock;
    }

    public function getTabulatorBlock(): GenericGridBlock
    {
        return $this->tabulatorBlock;
    }

    public function getAgGridBlock(): GenericGridBlock
    {
        return $this->agGridBlock;
    }
}
