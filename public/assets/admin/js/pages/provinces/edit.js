(function (w, $) {
  if (document.body.dataset.departmentEdit === "1") return;
  document.body.dataset.departmentEdit = "1";

  const id = $("#departmentForm").data("id");
  (async function preload() {
    const res = await fetch("/sadm/department/" + id, { headers: { "X-Requested-With": "XMLHttpRequest" } });
    if (!res.ok) return;
    const data = await res.json().catch(() => ({}));
    Object.entries(data).forEach(([k, v]) => $("[name='" + k + "']").val(v));
  })();
})(window, jQuery);
