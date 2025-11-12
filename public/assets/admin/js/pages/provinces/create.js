(function (w, $) {
  if (document.body.dataset.departmentCreate === "1") return;
  document.body.dataset.departmentCreate = "1";

  $(document).on("department:saved", () => (w.location.href = "/sadm/department"));
})(window, jQuery);
