// public/assets/admin/js/table-kit.js
(function () {
  'use strict';

  // Helper: init DataTable with our defaults
  function initDataTable(selector, opts = {}) {
    const defaults = {
      layout: { topStart: null, topEnd: null, bottomStart: null, bottomEnd: null },
      paging: true,
      ordering: true,
      autoWidth: false,
      pageLength: 10,
      lengthChange: false,
      language: {
        zeroRecords: 'هیچ داتا نییە',
        info: 'پیشاندانی _START_ تا _END_ لە _TOTAL_',
        infoEmpty: 'هیچ تۆمار نییە',
        paginate: { previous: 'پێشتر', next: 'دواتر' },
      },
    };
    return new DataTable(selector, Object.assign({}, defaults, opts));
  }

  // Helper: render info (custom)
  function renderDtInfo(dt, el) {
    const i = dt.page.info();
    el.textContent = i.recordsDisplay
      ? `پیشاندانی ${i.start} تا ${i.end} لە ${i.recordsDisplay}`
      : 'هیچ تۆمار نییە';
  }

  // Helper: render pager (custom)
  function renderDtPager(dt, el) {
    const i = dt.page.info();
    const cur = i.page;
    const total = i.pages;

    let html = `<nav aria-label="Pagination"><ul class="pagination pagination-sm mb-0">`;
    html += `<li class="page-item ${cur === 0 ? 'disabled' : ''}">
               <a class="page-link" href="#" data-page="${cur - 1}">پێشتر</a>
             </li>`;

    const max = 7;
    let start = Math.max(0, cur - Math.floor(max / 2));
    let end = Math.min(total - 1, start + max - 1);
    if (end - start + 1 < max) start = Math.max(0, end - max + 1);

    for (let p = start; p <= end; p++) {
      html += `<li class="page-item ${p === cur ? 'active' : ''}">
                 <a class="page-link" href="#" data-page="${p}">${p + 1}</a>
               </li>`;
    }

    html += `<li class="page-item ${cur === total - 1 ? 'disabled' : ''}">
               <a class="page-link" href="#" data-page="${cur + 1}">دواتر</a>
             </li>`;
    html += `</ul></nav>`;

    el.innerHTML = html;
    el.querySelectorAll('[data-page]').forEach(a => {
      a.addEventListener('click', (e) => {
        e.preventDefault();
        const to = Number(a.getAttribute('data-page'));
        if (!Number.isNaN(to) && to >= 0 && to < total) dt.page(to).draw('page');
      });
    });
  }

  // Expose globally
  window.initDataTable = initDataTable;
  window.renderDtInfo = renderDtInfo;
  window.renderDtPager = renderDtPager;

})();
