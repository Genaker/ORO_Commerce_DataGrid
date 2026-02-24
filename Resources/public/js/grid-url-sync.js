/**
 * Grid URL sync - updates browser URL with pagination, sort, filter params.
 * Reusable across Grid.js, DataTable, AG Grid, Tabulator.
 */
(function(global) {
    'use strict';

    function getParams() {
        return new URLSearchParams(window.location.search);
    }

    /**
     * Update browser URL with grid params. Preserves existing params (e.g. filter) not in opts.
     * @param {Object} opts - { p, pageSize, sortField, sortOrder }
     */
    function syncUrl(opts) {
        var params = getParams();
        if (opts.p !== undefined) params.set('p', String(opts.p));
        if (opts.pageSize !== undefined) params.set('pageSize', String(opts.pageSize));
        if (opts.sortField !== undefined) params.set('sortField', opts.sortField);
        if (opts.sortOrder !== undefined) params.set('sortOrder', opts.sortOrder);
        history.replaceState({}, '', window.location.pathname + '?' + params.toString());
    }

    /**
     * Build fetch URL by merging baseUrl params with grid params.
     * @param {string} baseUrl - Data endpoint URL (may include filter params)
     * @param {Object} opts - { p, pageSize, sortField, sortOrder }
     * @returns {string} Full URL for fetch
     */
    function buildFetchUrl(baseUrl, opts) {
        var parts = baseUrl.split('?');
        var params = new URLSearchParams(parts[1] || '');
        if (opts.p !== undefined) params.set('p', String(opts.p));
        if (opts.pageSize !== undefined) params.set('pageSize', String(opts.pageSize));
        if (opts.sortField) params.set('sortField', opts.sortField);
        if (opts.sortOrder) params.set('sortOrder', opts.sortOrder);
        return parts[0] + '?' + params.toString();
    }

    global.GridUrlSync = {
        syncUrl: syncUrl,
        buildFetchUrl: buildFetchUrl,
        getParams: getParams
    };
})(typeof window !== 'undefined' ? window : this);
