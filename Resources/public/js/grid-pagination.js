/**
 * Shared grid pagination - reusable across Grid.js, DataTable, AG Grid, Tabulator.
 * Renders custom pagination UI. Supports Twig-rendered HTML or opts.paginationHtml from backend.
 *
 * Usage:
 *   GridPagination.render('grid-pagination', total, page, pageSize, function(n) { ... });
 *   GridPagination.render('grid-pagination', total, page, pageSize, onPageChange, { paginationHtml: html });
 *   GridPagination.render('grid-pagination', total, page, pageSize, onPageChange, { showCustomPagination: false });
 *
 * Options (6th param): showCustomPagination (default true), paginationHtml (from backend)
 */
(function(global) {
    'use strict';

    var handlers = {};

    function attachDelegate(containerId, onPageChange) {
        handlers[containerId] = onPageChange;
        var el = document.getElementById(containerId);
        if (!el || el._gridPaginationDelegate) return;
        el._gridPaginationDelegate = true;
        el.addEventListener('click', function(e) {
            var btn = e.target.closest('[data-page]');
            if (btn && !btn.disabled) {
                var h = handlers[containerId];
                if (h) h(parseInt(btn.dataset.page, 10));
            }
        });
    }

    function pageNumbers(cur, total) {
        if (total <= 7) {
            var arr = [];
            for (var i = 1; i <= total; i++) arr.push(i);
            return arr;
        }
        var pages = [1];
        if (cur > 3) pages.push('...');
        for (var i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) pages.push(i);
        if (cur < total - 2) pages.push('...');
        pages.push(total);
        return pages;
    }

    function renderPagination(containerId, total, page, pageSize, onPageChange, opts) {
        opts = opts || {};
        if (opts.showCustomPagination === false) return;
        var el = document.getElementById(containerId);
        if (!el) return;

        if (opts.paginationHtml) {
            el.innerHTML = opts.paginationHtml;
        } else if (el.querySelector('.grid-pagination')) {
            attachDelegate(containerId, onPageChange);
            return;
        } else {
            var totalPages = Math.max(1, Math.ceil(total / pageSize));
            var nav = document.createElement('div');
            nav.className = 'grid-pagination';
            var summary = document.createElement('div');
            summary.className = 'grid-pagination-summary';
            var from = (page - 1) * pageSize + 1;
            var to = Math.min(page * pageSize, total);
            summary.textContent = 'Showing ' + from + ' to ' + to + ' of ' + total + ' results';
            nav.appendChild(summary);
            var pagesDiv = document.createElement('div');
            pagesDiv.className = 'grid-pagination-pages';

            function btn(label, targetPage, isCurrent, disabled) {
                var b = document.createElement('button');
                b.textContent = label;
                b.setAttribute('data-page', targetPage);
                b.className = 'grid-pagination-btn' + (isCurrent ? ' grid-pagination-current' : '');
                if (disabled || isCurrent) b.setAttribute('disabled', '');
                return b;
            }

            pagesDiv.appendChild(btn('Previous', page - 1, false, page <= 1));
            pageNumbers(page, totalPages).forEach(function(p) {
                if (p === '...') {
                    var s = document.createElement('button');
                    s.textContent = '...';
                    s.disabled = true;
                    s.className = 'grid-pagination-spread';
                    pagesDiv.appendChild(s);
                } else {
                    pagesDiv.appendChild(btn(String(p), p, p === page, false));
                }
            });
            pagesDiv.appendChild(btn('Next', page + 1, false, page >= totalPages));
            nav.appendChild(pagesDiv);
            el.innerHTML = '';
            el.appendChild(nav);
        }
        attachDelegate(containerId, onPageChange);
    }

    global.GridPagination = {
        pageNumbers: pageNumbers,
        render: renderPagination
    };
})(typeof window !== 'undefined' ? window : this);
