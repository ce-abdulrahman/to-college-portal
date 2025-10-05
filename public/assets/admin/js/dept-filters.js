// Dependent selects + multi-filter for Departments page
window.DeptFilters = (function () {
  function q(id){ return document.getElementById(id); }
  function selectedText(el){ return (el && el.value) ? el.options[el.selectedIndex].text : ''; }
  function enable(el, on=true){ if (el) el.disabled = !on; }
  function fillSelect(el, items, placeholder){
    if (!el) return;
    el.innerHTML = `<option value="">${placeholder}</option>`;
    items.forEach(it => {
      const opt = document.createElement('option');
      opt.value = it.id; opt.textContent = it.name;
      el.appendChild(opt);
    });
  }

  function init(dt, opts = {}) {
    const NAME_COL_INDEX = opts.nameColIndex ?? 1;

    const selSystem     = q('filter-system');
    const selProvince   = q('filter-province');
    const selUniversity = q('filter-university');
    const selCollege    = q('filter-college');
    const inputFilter   = q('filter-search');
    const btnReset      = q('filter-reset');

    // Province -> Universities
    selProvince && selProvince.addEventListener('change', () => {
      const pid = selProvince.value;
      fillSelect(selUniversity, [], 'هەموو زانکۆكان'); enable(selUniversity, false);
      fillSelect(selCollege, [], 'هەموو کۆلێژەکان');  enable(selCollege, false);

      if (!pid){ applyFilters(); return; }

      fetch(`/admin/api/universities?province_id=${encodeURIComponent(pid)}`)
        .then(r => r.json())
        .then(data => { fillSelect(selUniversity, data, 'هەموو زانکۆكان'); enable(selUniversity, true); })
        .catch(() => { fillSelect(selUniversity, [], 'هەڵە ڕوویدا'); });

      applyFilters();
    });

    // University -> Colleges
    selUniversity && selUniversity.addEventListener('change', () => {
      const uid = selUniversity.value;
      fillSelect(selCollege, [], 'هەموو کۆلێژەکان'); enable(selCollege, false);

      if (!uid){ applyFilters(); return; }

      fetch(`/admin/api/colleges?university_id=${encodeURIComponent(uid)}`)
        .then(r => r.json())
        .then(data => { fillSelect(selCollege, data, 'هەموو کۆلێژەکان'); enable(selCollege, true); })
        .catch(() => { fillSelect(selCollege, [], 'هەڵە ڕوویدا'); });

      applyFilters();
    });

    function applyFilters(){
      const parts = [];
      if (selSystem && selSystem.value) parts.push(selSystem.value);
      if (selProvince && selProvince.value) parts.push(selectedText(selProvince));
      if (selUniversity && selUniversity.value) parts.push(selectedText(selUniversity));
      if (selCollege && selCollege.value) parts.push(selectedText(selCollege));
      TableKit.applyAndFilters(dt, NAME_COL_INDEX, parts);
    }

    [selSystem, selProvince, selUniversity, selCollege].forEach(el=>{
      if (el) el.addEventListener('change', applyFilters);
    });

    if (inputFilter) inputFilter.addEventListener('input', () => dt.search(inputFilter.value).draw());

    if (btnReset) btnReset.addEventListener('click', () => {
      if (selSystem) selSystem.value = '';
      if (selProvince) selProvince.value = '';
      if (selUniversity){ fillSelect(selUniversity, [], 'هەموو زانکۆكان'); enable(selUniversity, false); }
      if (selCollege){ fillSelect(selCollege, [], 'هەموو کۆلێژەکان'); enable(selCollege, false); }
      if (inputFilter) inputFilter.value = '';
      const s = document.getElementById('custom-search'); if (s) s.value = '';
      dt.search('').columns().search('').draw();
    });
  }

  return { init };
})();
