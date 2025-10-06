(() => {
  "use strict";
  document.addEventListener("DOMContentLoaded", () => {
    const dt = window.initDataTable('#datatable');

    // External search + length
    const customSearch = document.getElementById('custom-search');
    const lengthSel    = document.getElementById('page-length');
    customSearch?.addEventListener('input', () => dt.search(customSearch.value).draw());
    lengthSel?.addEventListener('change', () => dt.page.len(Number(lengthSel.value)).draw());

    // Info + Pager
    const infoBox = document.getElementById('dt-info');
    const pager   = document.getElementById('dt-pager');
    const rerender = () => {
      infoBox && window.renderDtInfo(dt, infoBox);
      pager   && window.renderDtPager(dt, pager);
    };
    dt.on('draw', rerender); rerender();

    // Filters
    const $ = id => document.getElementById(id);
    const selSystem = $('filter-system'),
          selProv   = $('filter-province'),
          selUni    = $('filter-university'),
          selCol    = $('filter-college'),
          txtFilter = $('filter-search'),
          btnReset  = $('filter-reset');

    const enable = (el, on = true) => el && (el.disabled = !on);
    const fill   = (el, list, ph) => {
      if (!el) return;
      el.innerHTML = `<option value="">${ph}</option>`;
      list.forEach(({id, name}) => {
        const o = document.createElement('option');
        o.value = id; o.textContent = name;
        el.appendChild(o);
      });
    };

    // Province -> Universities
    selProv?.addEventListener('change', () => {
      const pid = selProv.value;
      fill(selUni, [], 'هەموو زانکۆكان'); enable(selUni, false);
      fill(selCol, [], 'هەموو کۆلێژەکان'); enable(selCol, false);
      if (!pid) { applyFilters(); return; }

      fetch(`/api/v1/lookups/universities?province_id=${encodeURIComponent(pid)}`, {
        headers: { 'Accept': 'application/json' }
        })
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

      fetch(`/api/v1/lookups/colleges?university_id=${encodeURIComponent(uid)}`, {
        headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(list => { fill(selCol, list, 'هەموو کۆلێژەکان'); enable(selCol, true); })
        .catch(() => fill(selCol, [], 'هەڵە ڕوویدا'));

      applyFilters();
    });

    // Apply filters by row dataset
    function applyFilters() {
      const sys = selSystem?.value?.trim() || '';
      const pid = selProv?.value || '';
      const uid = selUni?.value || '';
      const cid = selCol?.value || '';

      dt.rows().every(function () {
        const n = this.node();
        const okSys  = !sys || n.dataset.system === sys;
        const okProv = !pid || n.dataset.provinceId === pid;
        const okUni  = !uid || n.dataset.universityId === uid;
        const okCol  = !cid || n.dataset.collegeId === cid;
        (okSys && okProv && okUni && okCol) ? n.classList.remove('d-none') : n.classList.add('d-none');
      });
      dt.draw(false);
    }

    [selSystem, selProv, selUni, selCol].forEach(el => el?.addEventListener('change', applyFilters));
    txtFilter?.addEventListener('input', () => dt.search(txtFilter.value).draw());

    // Reset
    btnReset?.addEventListener('click', () => {
      selSystem && (selSystem.value = '');
      selProv   && (selProv.value   = '');
      fill(selUni, [], 'هەموو زانکۆكان'); enable(selUni, false);
      fill(selCol, [], 'هەموو کۆلێژەکان'); enable(selCol, false);
      txtFilter && (txtFilter.value = '');
      customSearch && (customSearch.value = '');

      dt.rows().every(function(){ this.node().classList.remove('d-none'); });
      dt.search('').columns().search('').draw();
    });

  });
})();
