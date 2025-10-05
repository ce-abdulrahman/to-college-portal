// public/assets/admin/js/core/app-core.js
(() => {
  "use strict";

  // Enable Bootstrap tooltips (safe if none exist)
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
      if (window.bootstrap?.Tooltip) new bootstrap.Tooltip(el);
    });

    // Auto show toasts if available
    document.querySelectorAll(".toast").forEach(el => {
      if (window.bootstrap?.Toast) new bootstrap.Toast(el).show();
    });
  });
})();
