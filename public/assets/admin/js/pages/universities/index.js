(function (w, $) {
  if (document.body.dataset.universityIndex === "1") return;
  document.body.dataset.universityIndex = "1";
  const ns = ".universityIndex";
  $(document).off(ns);

  const $tbl = $("#universityTable");
  if ($tbl.length && !$tbl.data("inited")) {
    $tbl.data("inited", true);
    w.universityDT = $tbl.DataTable({
      ajax: "/sadm/university/datatable",
      columns: [
        { data: "id", title: "#" },
        { data: "name", title: "University" },
        { data: "province", title: "Province" },
        { data: "actions", title: "Actions", orderable: false },
      ],
    });
  }

  $(document).on("click" + ns, ".btn-add-university", () => (w.location.href = "/sadm/university/create"));
})(window, jQuery);
