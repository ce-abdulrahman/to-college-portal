(() => {
  "use strict";

  document.addEventListener("DOMContentLoaded", () => {
    // Init DT (دڵنیابە window.initDataTable لە app-core.js دابین کراوە)
    const dt = window.initDataTable('#datatable', {
      columnDefs: [
        { targets: -1, orderable: false } // کردار
      ],
      language: {
        zeroRecords: 'هیچ داتا نییە',
        info: 'پیشاندانی _START_ تا _END_ لە _TOTAL_',
        infoEmpty: 'هیچ تۆمار نییە',
        paginate: { previous: 'پێشتر', next: 'دواتر' }
      }
    });

    // External search & length
    const search = document.getElementById('custom-search');
    const lenSel = document.getElementById('page-length');
    search?.addEventListener('input', () => dt.search(search.value).draw());
    lenSel?.addEventListener('change', () => dt.page.len(Number(lenSel.value)).draw());

    // Info + Pager
    const info = document.getElementById('dt-info');
    const pager = document.getElementById('dt-pager');
    const redraw = () => {
      if (info)  window.renderDtInfo(dt, info);
      if (pager) window.renderDtPager(dt, pager);
    };
    dt.on('draw', redraw); redraw();

    // Status filter (row-level)
    const selStat = document.getElementById('filter-status');
    const btnReset = document.getElementById('filter-reset');

    function applyFilters() {
      const st = selStat?.value || '';
      dt.rows().every(function () {
        const n = this.node();
        const okS = !st || n.dataset.status === st;
        okS ? n.classList.remove('d-none') : n.classList.add('d-none');
      });
      dt.draw(false);
    }

    selStat?.addEventListener('change', applyFilters);

    btnReset?.addEventListener('click', () => {
      if (selStat) selStat.value = '';
      if (search)  search.value = '';
      dt.rows().every(function(){ this.node().classList.remove('d-none'); });
      dt.search('').columns().search('').draw();
    });
  });
})();
