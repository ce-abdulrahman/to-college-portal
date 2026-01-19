JSRouter.register('department', function (view) {

    if (view === 'index') initIndex();
    if (view === 'create' || view === 'edit') initForm();
    if (view === 'show') initShow();

});

/* index */
function initIndex() {
    DataTableManager.init({
        selector:'#departments-table',
        ajax:'/admin/departments/data',
        columns:[
            {data:'id'},
            {data:'name'},
            {data:'university'},
            {data:'status'},
            {data:'action',orderable:false}
        ]
    });
}

/* create & edit */
function initForm() {
    const $province   = $('#province_id');
    const $university = $('#university_id');
    const $college    = $('#college_id');

    $province.on('change',function(){
        resetSelect($university);
        resetSelect($college);
        if (!this.value) return;

        startLoading($university);
        $.get(API_UNI,{province_id:this.value})
            .done(data=>fillSelect($university,data))
            .always(()=>stopLoading($university));
    });

    $university.on('change',function(){
        resetSelect($college);
        if (!this.value) return;

        startLoading($college);
        $.get(API_COLLS,{university_id:this.value})
            .done(data=>fillSelect($college,data))
            .always(()=>stopLoading($college));
    });

    MapManager.init({
        id:'map-department',
        center:[36.19,44.01],
        lat:'#lat',
        lng:'#lng'
    });
}

/* show */
function initShow() {
    if (typeof DEPARTMENT_LAT === 'undefined') return;
    MapManager.init({
        id:'map-department',
        center:[DEPARTMENT_LAT,DEPARTMENT_LNG],
        zoom:14
    });
}
