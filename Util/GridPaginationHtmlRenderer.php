<?php

namespace Genaker\Bundle\DataGridBundle\Util;

/**
 * Renders pagination HTML without Twig dependency.
 */
final class GridPaginationHtmlRenderer
{
    public static function render(int $total, int $page, int $pageSize): string
    {
        $totalPages = max(1, (int) ceil($total / $pageSize));
        $from = ($page - 1) * $pageSize + 1;
        $to = min($page * $pageSize, $total);

        $pages = self::pageNumbers($page, $totalPages);

        $html = '<div class="grid-pagination">';
        $html .= '<div class="grid-pagination-summary">Showing ' . $from . ' to ' . $to . ' of ' . $total . ' results</div>';
        $html .= '<div class="grid-pagination-pages">';
        $html .= self::btn('Previous', $page - 1, false, $page <= 1);
        foreach ($pages as $p) {
            if ($p === '...') {
                $html .= '<button type="button" class="grid-pagination-spread" disabled>...</button>';
            } else {
                $html .= self::btn((string) $p, $p, $p === $page, false);
            }
        }
        $html .= self::btn('Next', $page + 1, false, $page >= $totalPages);
        $html .= '</div></div>';

        return $html;
    }

    /**
     * @return array<int|string>
     */
    private static function pageNumbers(int $cur, int $total): array
    {
        if ($total <= 7) {
            return range(1, $total);
        }
        $pages = [1];
        if ($cur > 3) {
            $pages[] = '...';
        }
        $start = max(2, $cur - 1);
        $end = min($total - 1, $cur + 1);
        if ($start <= $end) {
            $pages = array_merge($pages, range($start, $end));
        }
        if ($cur < $total - 2) {
            $pages[] = '...';
        }
        $pages[] = $total;

        return $pages;
    }

    private static function btn(string $label, int $targetPage, bool $isCurrent, bool $disabled): string
    {
        $class = 'grid-pagination-btn' . ($isCurrent ? ' grid-pagination-current' : '');
        $disabledAttr = ($disabled || $isCurrent) ? ' disabled' : '';

        return sprintf(
            '<button type="button" class="%s" data-page="%d"%s>%s</button>',
            $class,
            $targetPage,
            $disabledAttr,
            htmlspecialchars($label)
        );
    }
}
