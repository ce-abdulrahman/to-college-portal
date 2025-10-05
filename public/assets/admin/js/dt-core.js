// public/assets/admin/js/core/dt-core.js
(() => {
  "use strict";

  // Create and return a DataTable instance with our defaults
  window.initDataTable = function (selector, opts = {}) {
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
        paginate: { previous: 'پێشتر', next: 'دواتر' }
      }
    };
    return new DataTable(selector, Object.assign({}, defaults, opts));
  };

  // Render bootstrap-like pager into a container
  window.renderDtPager = function (dt, container) {
    const i = dt.page.info();
    const cur = i.page, total = i.pages;

    let html = `<nav aria-label="Pagination"><ul class="pagination pagination-sm mb-0">`;
    html += `<li class="page-item ${cur===0?'disabled':''}">
               <a class="page-link" href="#" data-page="${cur-1}">پێشتر</a>
             </li>`;

    const max=7; let start=Math.max(0,cur-Math.floor(max/2)); let end=Math.min(total-1,start+max-1);
    if (end-start+1<max) start=Math.max(0,end-max+1);
    for (let p=start; p<=end; p++) {
      html += `<li class="page-item ${p===cur?'active':''}">
                 <a class="page-link" href="#" data-page="${p}">${p+1}</a>
               </li>`;
    }
    html += `<li class="page-item ${cur===total-1?'disabled':''}">
               <a class="page-link" href="#" data-page="${cur+1}">دواتر</a>
             </li>`;
    html += `</ul></nav>`;

    container.innerHTML = html;
    container.querySelectorAll("[data-page]").forEach(a => {
      a.addEventListener("click", e => {
        e.preventDefault();
        const to = Number(a.dataset.page);
        if (!Number.isNaN(to) && to >= 0 && to < total) dt.page(to).draw("page");
      });
    });
  };

  // Render info text into a container
  window.renderDtInfo = function (dt, container) {
    const i = dt.page.info();
    container.textContent = i.recordsDisplay
      ? `پیشاندانی ${i.start} تا ${i.end} لە ${i.recordsDisplay}`
      : "هیچ تۆمار نییە";
  };

})();
