(function (w, $) {
  if (document.body.dataset.provinceForm === "1") return;
  document.body.dataset.provinceForm = "1";
  const ns = ".provinceForm";
  $(document).off(ns);

  w.provinceForm = {
    serialize() {
      const data = {};
      $("#provinceForm").serializeArray().forEach(f => (data[f.name] = f.value));
      return data;
    },
    validate() {
      const name = $.trim($("[name='name']").val());
      if (!name) { alert("Province name required"); return false; }
      return true;
    },
    async submit(url) {
      if (!this.validate()) return;
      const res = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-Requested-With": "XMLHttpRequest" },
        body: JSON.stringify(this.serialize())
      });
      if (res.ok) $(document).trigger("province:saved");
    }
  };

  $(document).on("submit" + ns, "#provinceForm", function (e) {
    e.preventDefault();
    const action = $(this).attr("action") || "/sadm/province";
    w.provinceForm.submit(action);
  });
})(window, jQuery);
