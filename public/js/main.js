// assets/admin/js/main.js
(function() {
    // Load order is critical
    const CORE_MODULES = [
        'app-core.js',
        'dt-core.js',
        'forms-validate.js',
        'dept-filters.js',
        'drawer.js',
        'dashboard-charts.js'
    ];
    
    const PAGE_SPECIFIC = {
        'index': 'index.js',
        'create': 'create.js',
        'edit': 'edit.js'
    };
    
    // Initialize based on page
    function initPage() {
        const page = document.body.dataset.page;
        const view = document.body.dataset.view;
        
        // Run core initialization
        Admin.Core.initTooltips();
        
        // Run page-specific initialization
        if (PAGE_SPECIFIC[view]) {
            // Dynamically load or check if already initialized
            const initFn = window[`init${view.charAt(0).toUpperCase() + view.slice(1)}`];
            if (initFn && typeof initFn === 'function') {
                initFn(page, view);
            }
        }
    }
    
    // Wait for DOM and all scripts
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPage);
    } else {
        initPage();
    }
})();