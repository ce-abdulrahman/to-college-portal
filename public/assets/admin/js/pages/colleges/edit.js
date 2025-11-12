(function (w, $) {
  if (!w || !w.jQuery) return;
  if (document.body.dataset.collegesEdit === "1") return;
  document.body.dataset.collegesEdit = "1";

  const ns = ".collegesEdit";
  $(document).off(ns);

  const $form = $("#collegeForm");
  const id = $form.data("id");

  (async function preload() {
    if (!id) return;
    const res = await fetch("/sadm/colleges/" + id, { headers: { "X-Requested-With": "XMLHttpRequest" } });
    if (!res.ok) return;
    const data = await res.json().catch(() => ({}));
    Object.entries(data || {}).forEach(([k, v]) => {
      const $el = $form.find("[name='" + k + "']");
      if ($el.is(":checkbox")) $el.prop("checked", !!v);
      else $el.val(v);
    });
  })();

  $(document).on("colleges:saved" + ns, function (e, json) {
    w.location.href = "/sadm/colleges";
  });

})(window, jQuery);
