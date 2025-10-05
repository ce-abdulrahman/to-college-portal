// public/assets/admin/js/pages/colleges/index.js
(() => {
  "use strict";

  document.addEventListener("DOMContentLoaded", () => {
    // Init DataTable (v2)
    const dt = window.initDataTable('#datatable');

    // External search & page length
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

    // Filters
    const $ = id => document.getElementById(id);
    const selProv = $('filter-province');
    const selUni  = $('filter-university');
    const selStat = $('filter-status');
    const btnReset = $('filter-reset');

    const enable = (el, on=true)=> el && (el.disabled = !on);
    const fill = (el, items, ph) => {
      if (!el) return;
      el.innerHTML = `<option value="">${ph}</option>`;
      items.forEach(it => {
        const o = document.createElement('option');
        o.value = it.id;
        o.textContent = it.name;
        el.appendChild(o);
      });
    };

    // Province -> Universities
    selProv?.addEventListener('change', () => {
      const pid = selProv.value;
      fill(selUni, [], 'هەموو زانکۆكان'); enable(selUni, false);

      if (pid) {
        fetch(`/admin/api/universities?province_id=${encodeURIComponent(pid)}`)
          .then(r => r.json())
          .then(list => { fill(selUni, list, 'هەموو زانکۆكان'); enable(selUni, true); })
          .catch(() => fill(selUni, [], 'هەڵە ڕوویدا'));
      }
      applyFilters();
    });

    // Apply (ID-based exact matching)
    function applyFilters() {
      const pid = selProv?.value || '';
      const uid = selUni?.value  || '';
      const st  = selStat?.value || '';

      dt.rows().every(function () {
        const n = this.node();
        const okP = !pid || n.dataset.provinceId === pid;
        const okU = !uid || n.dataset.universityId === uid;
        const okS = !st  || n.dataset.status === st;
        (okP && okU && okS) ? n.classList.remove('d-none') : n.classList.add('d-none');
      });
      dt.draw(false);
    }

    [selProv, selUni, selStat].forEach(el => el?.addEventListener('change', applyFilters));

    // Reset
    btnReset?.addEventListener('click', () => {
      if (selProv) selProv.value = '';
      if (selUni)  { fill(selUni, [], 'هەموو زانکۆكان'); enable(selUni, false); }
      if (selStat) selStat.value = '';
      if (search)  search.value = '';

      dt.rows().every(function(){ this.node().classList.remove('d-none'); });
      dt.search('').columns().search('').draw();
    });
  });
})();
