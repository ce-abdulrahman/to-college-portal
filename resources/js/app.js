import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
$(function(){
    const page = document.body.dataset.page;
    const view = document.body.dataset.view;
    if (page && view) JSRouter.dispatch(page,view);
});
