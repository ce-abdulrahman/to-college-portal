(function (w, $) {
  if (!w || !w.jQuery) return;
  if (document.body.dataset.collegesCreate === "1") return;
  document.body.dataset.collegesCreate = "1";

  const ns = ".collegesCreate";
  $(document).off(ns);

  const $form = $("#collegeForm");
  if ($form.length && !$form.data("inited")) {
    $form.data("inited", true);
    // e.g., $('#city').select2();
  }

  $(document).on("colleges:saved" + ns, function (e, json) {
    w.location.href = "/sadm/colleges";
  });

})(window, jQuery);
