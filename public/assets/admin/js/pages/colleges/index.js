// index.js
$(function () {
    let table = $.fn.dataTable.isDataTable('#collegesTable')
        ? $('#collegesTable').DataTable()
        : $('#collegesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ku.json'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            dom: '<"top"f>rt<"bottom"ilp><"clear">',
            columnDefs: [
                { orderable: false, targets: [6] }
            ],
            initComplete: function () {
                updateTableInfo();
                const triggers = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                triggers.map(el => new bootstrap.Tooltip(el));
            },
            drawCallback: function () {
                updateTableInfo();
            }
        });

    setupEventHandlers();

    function setupEventHandlers() {
        $('#page-length').on('change', function () {
            table.page.len($(this).val()).draw();
        });

        $('#custom-search').on('keyup', function () {
            table.search(this.value).draw();
        });

        $('#filter-status').on('change', function () {
            const status = $(this).val();
            if (!status) {
                table.column(5).search('').draw();
            } else {
                table.column(5).search('^' + status + '$', true, false).draw();
            }
        });

        $('#filter-province').on('change', function () {
            const provinceId = $(this).val();
            const $uni = $('#filter-university');

            if (!provinceId) {
                $uni.val('').prop('disabled', true);
                table.column(2).search('').draw();
                table.column(3).search('').draw();
            } else {
                fetchUniversities(provinceId);
                table.column(2).search(provinceId).draw();
            }
        });

        $('#filter-university').on('change', function () {
            const universityId = $(this).val();
            table.column(3).search(universityId || '').draw();
        });

        $('#filter-reset').on('click', function () {
            $('#filter-province').val('');
            $('#filter-university').val('').prop('disabled', true);
            $('#filter-status').val('');
            $('#custom-search').val('');
            $('#page-length').val('10');

            table.columns().search('');
            table.search('');
            table.page.len(10).draw();
        });

        $(document).on('submit', 'form[onsubmit*="confirm"]', function (e) {
            e.preventDefault();

            const form = this;
            const match = $(form).attr('onsubmit').match(/confirm\('([^']+)'\)/);
            const confirmMessage = (match && match[1]) || 'دڵنیایت؟';

            Swal.fire({
                title: confirmMessage,
                text: 'ئەم کردارە گەڕانەوەی نیە!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'بەڵێ، بسڕەوە!',
                cancelButtonText: 'نەخێر',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });

        $(window).on('resize', function () {
            setTimeout(() => table.columns.adjust(), 100);
        });
    }

    function updateTableInfo() {
        const info = table.page.info();
        const start = info.start + 1;
        const end = info.end;
        const total = info.recordsTotal;
        const filtered = info.recordsDisplay;

        const infoText = filtered === total
            ? `نیشاندان ${start} بۆ ${end} لە کۆی ${total}`
            : `نیشاندان ${start} بۆ ${end} لە کۆی ${filtered} (فیلتەرکراو لە ${total})`;

        $('#dt-info').html(infoText);
    }

    function fetchUniversities(provinceId) {
        if (!provinceId) return;

        const $uni = $('#filter-university');
        $uni.prop('disabled', true).text('چاوەروان بکە...');

        $.ajax({
            url: window.UNI_API || '/api/universities/by-province/' + provinceId,
            method: 'GET',
            data: { province_id: provinceId },
            success: function (response) {
                let options = '<option value="">' + 'هەموو زانکۆكان' + '</option>';

                if (response.data && response.data.length) {
                    $.each(response.data, function (_, university) {
                        options += `<option value="${university.id}">${university.name}</option>`;
                    });
                }

                $uni.html(options).prop('disabled', false);
            },
            error: function () {
                $uni.text('هەڵە لە وەرگرتن').prop('disabled', false);
                showToast('هەڵە', 'نەتوانرا زانکۆکان وەربگیرێت', 'error');
            }
        });
    }

    function showToast(title, message, type) {
        if (typeof Swal === 'undefined') return;

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: toast => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: type,
            title,
            text: message
        });
    }
});
