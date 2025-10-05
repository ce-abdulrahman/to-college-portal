// public/assets/admin/js/pages/colleges/form.js
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

    const prov = document.getElementById('province_id');       // اختیاری
    const uni  = document.getElementById('university_id');     // پێویست

    if (!uni) return;

    const enable = (el,on=true)=> el.disabled = !on;
    const fill = (el, items, ph) => {
      el.innerHTML = `<option value="">${ph}</option>`;
      items.forEach(it => {
        const o = document.createElement('option');
        o.value = it.id;
        o.textContent = it.name;
        el.appendChild(o);
      });
    };

    // ئەگەر province هەبوو، وەبەستە بکە بە university
    if (prov) {
      if (!uni.value) enable(uni, false);
      prov.addEventListener('change', () => {
        const pid = prov.value;
        fill(uni, [], 'هەموو زانکۆكان'); enable(uni, false);
        if (!pid) return;
        fetch(`/admin/api/universities?province_id=${encodeURIComponent(pid)}`)
          .then(r => r.json())
          .then(list => { fill(uni, list, 'هەموو زانکۆكان'); enable(uni, true); });
      });
    }
  });
})();
