// public/assets/admin/js/pages/departments/form.js
(() => {
  "use strict";

  document.addEventListener("DOMContentLoaded", () => {
    // Bootstrap client-side validation
    document.querySelectorAll('.needs-validation').forEach(form => {
      form.addEventListener('submit', e => {
        if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        form.classList.add('was-validated');
      });
    });

    const prov = document.getElementById('province_id');
    const uni  = document.getElementById('university_id');
    const col  = document.getElementById('college_id');

    if (!prov || !uni || !col) return; // page might not have all

    const enable = (el, on = true) => el.disabled = !on;
    const fill = (el, items, ph) => {
      el.innerHTML = `<option value="">${ph}</option>`;
      items.forEach(it => {
        const o = document.createElement('option');
        o.value = it.id;
        o.textContent = it.name;
        el.appendChild(o);
      });
    };

    // Initial lock
    if (!uni.value)  enable(uni, false);
    if (!col.value)  enable(col, false);

    prov.addEventListener('change', () => {
      const pid = prov.value;
      fill(uni, [], 'هەموو زانکۆكان'); enable(uni, false);
      fill(col, [], 'هەموو کۆلێژەکان'); enable(col, false);
      if (!pid) return;

      fetch(`/admin/api/universities?province_id=${encodeURIComponent(pid)}`)
        .then(r => r.json())
        .then(list => { fill(uni, list, 'هەموو زانکۆكان'); enable(uni, true); });
    });

    uni.addEventListener('change', () => {
      const uid = uni.value;
      fill(col, [], 'هەموو کۆلێژەکان'); enable(col, false);
      if (!uid) return;

      fetch(`/admin/api/colleges?university_id=${encodeURIComponent(uid)}`)
        .then(r => r.json())
        .then(list => { fill(col, list, 'هەموو کۆلێژەکان'); enable(col, true); });
    });
  });
})();
