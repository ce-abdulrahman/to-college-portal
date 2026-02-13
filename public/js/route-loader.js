(function () {
  var page = (document.body.getAttribute("data-page") || "").trim();
  var view = (document.body.getAttribute("data-view") || "").trim();
  if (!page) return;

  // CSS
  var css = document.createElement("link");
  css.rel = "stylesheet";
  css.href = "/css/" + page + ".css";
  document.head.appendChild(css);

  // shared form
  var shared = document.createElement("script");
  shared.defer = true;
  shared.src = "/js/pages/" + page + "/form.js";
  document.head.appendChild(shared);

  // view-specific JS
  if (view) {
    var script = document.createElement("script");
    script.defer = true;
    script.src = "/js/pages/" + page + "/" + view + ".js";
    document.head.appendChild(script);
  }
})();
