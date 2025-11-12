(function (w, $) {
  if (!w || !w.jQuery) return;
  if (document.body.dataset.collegesIndex === "1") return;
  document.body.dataset.collegesIndex = "1";

  const ns = ".collegesIndex";
  $(document).off(ns);

  const $tbl = $("#collegesTable");
  if ($tbl.length && !$tbl.data("inited")) {
    $tbl.data("inited", true);
    const dt = $tbl.DataTable({
      processing: true,
      serverSide: true,
      ajax: "/sadm/colleges/datatable",
      columns: [
        { data: "id", title: "#" },
        { data: "name", title: "Name" },
        { data: "city", title: "City" },
        { data: "actions", title: "Actions", orderable: false, searchable: false }
      ],
      order: [[0, "desc"]]
    });
    w.dt = w.dt || dt;
  }

  $(document).on("click" + ns, ".btn-add-college", function () {
    w.location.href = "/sadm/colleges/create";
  });

  $(document).on("click" + ns, ".btn-edit", function () {
    const id = $(this).data("id");
    w.location.href = "/sadm/colleges/" + id + "/edit";
  });

  $(document).on("click" + ns, ".btn-delete", async function () {
    const id = $(this).data("id");
    if (!confirm("Delete this college?")) return;
    const res = await fetch("/sadm/colleges/" + id, {
      method: "POST",
      headers: { "Content-Type": "application/json", "X-Requested-With": "XMLHttpRequest" },
      body: JSON.stringify({ _method: "DELETE", _token: $('meta[name="csrf-token"]').attr('content') })
    });
    if (res.ok && w.dt) w.dt.ajax.reload(null, false);
  });

})(window, jQuery);
