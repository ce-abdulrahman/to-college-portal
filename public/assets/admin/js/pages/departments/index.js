// public/assets/admin/js/pages/departments/index.js
(() => {
  "use strict";

  document.addEventListener("DOMContentLoaded", () => {
    // 1) Init DT
    const dt = window.initDataTable('#datatable');

    // 2) External search & page length
    const customSearch = document.getElementById('custom-search');
    const lengthSel    = document.getElementById('page-length');
    if (customSearch) customSearch.addEventListener('input', () => dt.search(customSearch.value).draw());
    if (lengthSel) lengthSel.addEventListener('change', () => dt.page.len(Number(lengthSel.value)).draw());

    // 3) Info + Pager
    const infoBox = document.getElementById('dt-info');
    const pager   = document.getElementById('dt-pager');
    const rerender = () => {
      if (infoBox) window.renderDtInfo(dt, infoBox);
      if (pager)   window.renderDtPager(dt, pager);
    };
    dt.on('draw', rerender); rerender();

    // 4) Filters (Province -> University -> College + System)
    const $ = id => document.getElementById(id);
    const selSystem = $('filter-system'),
          selProv   = $('filter-province'),
          selUni    = $('filter-university'),
          selCol    = $('filter-college'),
          txtFilter = $('filter-search'),
          btnReset  = $('filter-reset');

    const enable = (el, on = true) => el && (el.disabled = !on);
    const fill   = (el, items, ph) => {
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
      fill(selCol, [], 'هەموو کۆلێژەکان'); enable(selCol, false);
      if (!pid) { applyFilters(); return; }

      fetch(`/admin/api/universities?province_id=${encodeURIComponent(pid)}`)
        .then(r => r.json())
        .then(list => { fill(selUni, list, 'هەموو زانکۆكان'); enable(selUni, true); })
        .catch(() => fill(selUni, [], 'هەڵە ڕوویدا'));

      applyFilters();
    });

    // University -> Colleges
    selUni?.addEventListener('change', () => {
      const uid = selUni.value;
      fill(selCol, [], 'هەموو کۆلێژەکان'); enable(selCol, false);
      if (!uid) { applyFilters(); return; }

      fetch(`/admin/api/colleges?university_id=${encodeURIComponent(uid)}`)
        .then(r => r.json())
        .then(list => { fill(selCol, list, 'هەموو کۆلێژەکان'); enable(selCol, true); })
        .catch(() => fill(selCol, [], 'هەڵە ڕوویدا'));

      applyFilters();
    });

    // Apply filters: hide/show rows by data-* attributes (ID-based exact match)
    function applyFilters() {
      const sys = selSystem?.value?.trim() || '';
      const pid = selProv?.value || '';
      const uid = selUni?.value || '';
      const cid = selCol?.value || '';

      dt.rows().every(function () {
        const n = this.node();
        const okSys = !sys || n.dataset.system === sys;
        const okProv = !pid || n.dataset.provinceId === pid;
        const okUni  = !uid || n.dataset.universityId === uid;
        const okCol  = !cid || n.dataset.collegeId === cid;
        (okSys && okProv && okUni && okCol) ? n.classList.remove('d-none') : n.classList.add('d-none');
      });
      dt.draw(false);
    }

    [selSystem, selProv, selUni, selCol].forEach(el => el?.addEventListener('change', applyFilters));
    txtFilter?.addEventListener('input', () => dt.search(txtFilter.value).draw());

    // Reset all
    btnReset?.addEventListener('click', () => {
      if (selSystem) selSystem.value = '';
      if (selProv)   selProv.value   = '';
      fill(selUni, [], 'هەموو زانکۆكان'); enable(selUni, false);
      fill(selCol, [], 'هەموو کۆلێژەکان'); enable(selCol, false);
      if (txtFilter) txtFilter.value = '';
      if (customSearch) customSearch.value = '';

      dt.rows().every(function(){ this.node().classList.remove('d-none'); });
      dt.search('').columns().search('').draw();
    });

  });
})();
