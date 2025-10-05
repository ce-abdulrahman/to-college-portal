// DataTables helpers (v2)
window.TableKit = (function () {
  function initDataTable({
    table = '#datatable',
    externalSearch = '#custom-search',
    pageLengthSel = '#page-length',
    infoBox = '#dt-info',
    pagerBox = '#dt-pager',
    language = {}
  } = {}) {
    const dt = new DataTable(table, {
      layout: { topStart: null, topEnd: null, bottomStart: null, bottomEnd: null },
      paging: true,
      ordering: true,
      autoWidth: false,
      pageLength: 10,
      lengthChange: false,
      language: Object.assign({
        zeroRecords: 'هیچ داتا نییە',
        info: 'پیشاندانی _START_ تا _END_ لە _TOTAL_',
        infoEmpty: 'هیچ تۆمار نییە',
        paginate: { previous: 'پێشتر', next: 'دواتر' },
      }, language)
    });

    // external search
    const s = document.querySelector(externalSearch);
    if (s) s.addEventListener('input', () => dt.search(s.value).draw());

    // external page length
    const len = document.querySelector(pageLengthSel);
    if (len) len.addEventListener('change', () => dt.page.len(Number(len.value)).draw());

    // info + pager
    const infoEl = document.querySelector(infoBox);
    const pagerEl = document.querySelector(pagerBox);

    function renderInfo() {
      if (!infoEl) return;
      const i = dt.page.info();
      infoEl.textContent = i.recordsDisplay
        ? `پیشاندانی ${i.start} تا ${i.end} لە ${i.recordsDisplay}`
        : 'هیچ تۆمار نییە';
    }

    function renderPager() {
      if (!pagerEl) return;
      const i = dt.page.info();
      const cur = i.page, total = i.pages;
      let html = `<nav aria-label="Pagination"><ul class="pagination pagination-sm mb-0">`;
      html += `<li class="page-item ${cur===0?'disabled':''}">
        <a class="page-link" href="#" data-page="${cur-1}">پێشتر</a></li>`;
      const max = 7;
      let start = Math.max(0, cur - Math.floor(max / 2));
      let end = Math.min(total - 1, start + max - 1);
      if (end - start + 1 < max) start = Math.max(0, end - max + 1);
      for (let p = start; p <= end; p++) {
        html += `<li class="page-item ${p===cur?'active':''}">
          <a class="page-link" href="#" data-page="${p}">${p+1}</a></li>`;
      }
      html += `<li class="page-item ${cur===total-1?'disabled':''}">
        <a class="page-link" href="#" data-page="${cur+1}">دواتر</a></li>`;
      html += `</ul></nav>`;
      pagerEl.innerHTML = html;
      pagerEl.querySelectorAll('[data-page]').forEach(el => {
        el.addEventListener('click', (e) => {
          e.preventDefault();
          const to = Number(el.getAttribute('data-page'));
          if (!Number.isNaN(to) && to >= 0 && to < total) dt.page(to).draw('page');
        });
      });
    }

    dt.on('draw', () => { renderInfo(); renderPager(); });
    renderInfo(); renderPager();

    return dt;
  }

  function applyAndFilters(dt, colIndex, parts) {
    const esc = s => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const rx = (parts && parts.length) ? parts.map(p => `(?=.*${esc(p)})`).join('') + '.*' : '';
    dt.column(colIndex).search(rx, { regex: true, smart: false }).draw();
  }

  return { initDataTable, applyAndFilters };
})();
