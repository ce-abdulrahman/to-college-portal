window.JSRouter = (function () {
    const routes = {};
    return {
        register(page, handler) {
            routes[page] = handler;
        },
        dispatch(page, view) {
            if (routes[page]) routes[page](view);
        }
    };
})();
