<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Integration;

/**
 * Verifies that required assets are present in the public directory.
 * This prevents MIME type errors (404 HTML returned as JS).
 */
class AssetPresenceTest extends DataGridIntegrationTestCase
{
    /**
     * @dataProvider assetPathProvider
     */
    public function testAssetExists(string $relativePath): void
    {
        $projectRoot = static::getContainer()->getParameter('kernel.project_dir');
        $fullPath = $projectRoot . '/public/bundles/genakerdatagrid/' . $relativePath;

        self::assertFileExists($fullPath, sprintf('Asset "%s" must be installed in public directory.', $relativePath));
        self::assertIsReadable($fullPath);
    }

    public function assetPathProvider(): array
    {
        return [
            ['js/gridjs.min.js'],
            ['js/datatables.min.js'],
            ['js/tabulator.min.js'],
            ['js/ag-grid.min.js'],
            ['js/jquery.min.js'],
            ['css/gridjs-mermaid.min.css'],
            ['css/datatables.min.css'],
            ['css/tabulator.min.css'],
            ['css/ag-grid.min.css'],
            ['css/ag-theme-alpine.min.css'],
        ];
    }
}
