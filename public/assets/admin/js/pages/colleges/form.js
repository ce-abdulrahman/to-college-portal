// shared: create + edit
(function (w, $) {
  if (!w || !w.jQuery) return;
  if (document.body.dataset.collegesForm === "1") return;
  document.body.dataset.collegesForm = "1";

  const ns = ".collegesForm";
  $(document).off(ns);

  // CSRF (Laravel)
  function csrf() {
    const m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.getAttribute("content") : "";
  }

  w.collegesForm = {
    serialize() {
      const data = {};
      $("#collegeForm").serializeArray().forEach(f => (data[f.name] = f.value));
      return data;
    },
    validate() {
      const name = $.trim($("[name='name']").val());
      if (!name) { alert("Name is required"); return false; }
      return true;
    },
    async submit(url, method = "POST") {
      if (!this.validate()) return;
      const body = this.serialize();
      if (method !== "GET" && !body._token) body._token = csrf();

      const res = await fetch(url, {
        method,
        headers: { "X-Requested-With": "XMLHttpRequest", "Content-Type": "application/json" },
        body: JSON.stringify(body)
      });
      if (!res.ok) { alert("Save failed"); return; }
      const json = await res.json().catch(() => ({}));
      $(document).trigger("colleges:saved", [json]);
    }
  };

  $(document).on("submit" + ns, "#collegeForm", function (e) {
    e.preventDefault();
    const $f = $(this);
    let action = $f.attr("action") || "/sadm/colleges";
    const method = ($f.find('input[name="_method"]').val() || "POST").toUpperCase();
    w.collegesForm.submit(action, method);
  });

})(window, jQuery);
