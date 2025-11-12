(function (w, $) {
  if (document.body.dataset.universityForm === "1") return;
  document.body.dataset.universityForm = "1";
  const ns = ".universityForm";
  $(document).off(ns);

  w.universityForm = {
    serialize() {
      const data = {};
      $("#universityForm").serializeArray().forEach(f => (data[f.name] = f.value));
      return data;
    },
    validate() {
      const name = $.trim($("[name='name']").val());
      if (!name) { alert("University name required"); return false; }
      return true;
    },
    async submit(url) {
      if (!this.validate()) return;
      const res = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-Requested-With": "XMLHttpRequest" },
        body: JSON.stringify(this.serialize())
      });
      if (res.ok) $(document).trigger("university:saved");
    }
  };

  $(document).on("submit" + ns, "#universityForm", function (e) {
    e.preventDefault();
    const action = $(this).attr("action") || "/sadm/university";
    w.universityForm.submit(action);
  });
})(window, jQuery);
