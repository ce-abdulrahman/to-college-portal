function resetSelect($el) {
    $el.html('<option>---</option>').prop('disabled',true);
}

function fillSelect($el,data) {
    data.forEach(i=>{
        $el.append(`<option value="${i.id}">${i.name}</option>`);
    });
    $el.prop('disabled',false);
}

function startLoading($el) {
    $el.prop('disabled',true)
       .parent().addClass('select-loading');
}

function stopLoading($el) {
    $el.prop('disabled',false)
       .parent().removeClass('select-loading');
}
