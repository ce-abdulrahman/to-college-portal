window.DataTableManager = (function () {
    const instances = {};
    return {
        init(cfg) {
            if (!$(cfg.selector).length) return;
            if (instances[cfg.selector]) return instances[cfg.selector];

            instances[cfg.selector] = $(cfg.selector).DataTable({
                processing:true,
                serverSide:true,
                responsive:true,
                ajax:cfg.ajax,
                columns:cfg.columns
            });
            return instances[cfg.selector];
        }
    };
})();
