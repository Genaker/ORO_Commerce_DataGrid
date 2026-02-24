# Full-Featured Grid Checklist

## Implemented

### Backend
- [x] **Pagination** — `p`, `pageSize` params; server-side in providers
- [x] **Sorting** — `sortField`, `sortOrder` params; GenericGridDataProvider, RawSqlGridDataProvider
- [x] **Filtering** — `filter[field]=value`; form in `_filters.html.twig`; getDataUrl includes filters
- [x] **Total count** — in `getJsonGridData` response; `getTotalCount()` method
- [x] **Data processors** — DateProcessor, StatusProcessor, ImageProcessor
- [x] **Multiple providers** — GenericGridDataProvider (ORM), RawSqlGridDataProvider (SQL)

### Grid.js
- [x] **html** — Server-side pagination (`?p=`), total display
- [x] **ajax** — Server-side pagination, server-side sorting
- [x] **ajax_pagination** — Server-side pagination, total display
- [x] **index, json** — Client-side pagination (initial data)

### AG Grid, DataTable, Tabulator
- [x] **Pagination** — Client-side (loads page 1 or all data)
- [x] **Sorting** — Client-side (on loaded data)
- [x] **Filter form** — Shared `_filters.html.twig`; submit reloads with filter params

### UX
- [x] **Placeholder for missing images**
- [x] **Row height** — 90px for image column (AG Grid)
- [x] **Preloader** — Some views (ajax)

---

## Missing / Incomplete

### Server-side features (AG Grid, DataTable, Tabulator)
- [ ] **AG Grid server-side pagination** — Currently fetches once; no `?p=` on page change
- [ ] **DataTable server-side** — Uses client-side only
- [ ] **Tabulator server-side** — Uses client-side only

### Grid.js
- [ ] **html server-side sorting** — No `sort.server.url`; only pagination is server-side
- [ ] **ajax_pagination server-side sorting** — Same; add sort config

### URL / browser
- [ ] **URL sync on pagination** — Clicking page 2 does not update browser URL to `?p=2` (no `history.pushState`)

### Filter form
- [ ] **Filter params in data URL** — getDataUrl adds filters, but AJAX requests may not preserve them when pagination/sort changes (check if dataUrl base includes filters)

### Advanced features
- [ ] **Export** — CSV/Excel download
- [ ] **Column visibility** — Show/hide columns
- [ ] **Loading state** — Skeleton or spinner when fetching (Grid.js ajax has preloader; others vary)
- [ ] **Error handling** — User feedback when data fetch fails
- [ ] **Accessibility** — ARIA labels, keyboard navigation

### Documentation
- [ ] **API params** — Document `p`, `pageSize`, `sortField`, `sortOrder`, `filter[field]` in README

---

## Priority order

1. **Grid.js html + ajax_pagination** — Add server-side sorting (quick win)
2. **URL sync** — `history.pushState` when pagination changes
3. **AG Grid / DataTable / Tabulator** — Server-side pagination if needed for large datasets
4. **Export** — If required by product
5. **Error handling** — Basic UX improvement
