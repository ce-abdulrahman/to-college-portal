(function (w, $) {
  if (document.body.dataset.provinceIndex === "1") return;
  document.body.dataset.provinceIndex = "1";
  const ns = ".provinceIndex";
  $(document).off(ns);

  const $tbl = $("#provinceTable");
  if ($tbl.length && !$tbl.data("inited")) {
    $tbl.data("inited", true);
    w.provinceDT = $tbl.DataTable({
      ajax: "/sadm/province/datatable",
      columns: [
        { data: "id", title: "#" },
        { data: "name", title: "Province" },
        { data: "population", title: "Population" },
        { data: "actions", title: "Actions", orderable: false },
      ],
    });
  }

  $(document).on("click" + ns, ".btn-add-province", () => (w.location.href = "/sadm/province/create"));
})(window, jQuery);
