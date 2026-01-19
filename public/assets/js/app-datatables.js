(function ($) {
    "use strict";

    $(document).ready(function () {

        const page = $('body').data('page');
        const view = $('body').data('view');

        // ==============================
        // USERS INDEX
        // ==============================
        if (page === 'users' && view === 'index') {
            initDataTable({
                selector: '#main-table',
                ajax: '/admin/users/data',
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'status' },
                    { data: 'action', orderable: false, searchable: false }
                ]
            });
        }

        // ==============================
        // UNIVERSITIES INDEX
        // ==============================
        if (page === 'universities' && view === 'index') {
            initDataTable({
                selector: '#main-table',
                ajax: '/admin/universities/data',
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'province' },
                    { data: 'status' },
                    { data: 'action', orderable: false }
                ]
            });
        }

        // ==============================
        // COLLEGES INDEX
        // ==============================
        if (page === 'colleges' && view === 'index') {
            initDataTable({
                selector: '#main-table',
                ajax: '/admin/colleges/data',
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'university' },
                    { data: 'status' },
                    { data: 'action', orderable: false }
                ]
            });
        }

    });

    // ==============================
    // GLOBAL DATATABLE FUNCTION
    // ==============================
    function initDataTable(config) {
        if (!$(config.selector).length) return;

        $(config.selector).DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searchDelay: 500,

            ajax: {
                url: config.ajax,
                type: 'GET'
            },

            columns: config.columns,

            language: {
                search: "گەڕان:",
                lengthMenu: "نیشاندانی _MENU_ تۆمار",
                zeroRecords: "هیچ تۆمارێک نەدۆزرایەوە",
                info: "نیشاندانی _START_ بۆ _END_ لە _TOTAL_",
                infoEmpty: "هیچ زانیاری نیە",
                processing: "چاوەڕوان بە..."
            }
        });
    }

})(jQuery);
