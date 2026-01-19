$(document).ready(function() {
    // Initialize DataTable
    var table = $('#datatable').DataTable({
        language: {
            url: '/assets/admin/js/datatables-ku.json'
        },
        pageLength: $('#page-length').val(),
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"top"f>rt<"bottom"ilp><"clear">',
        initComplete: function() {
            updateTableInfo();
        },
        drawCallback: function() {
            updateTableInfo();
        }
    });

    // Page length change handler
    $('#page-length').on('change', function() {
        table.page.len($(this).val()).draw();
    });

    // Custom search handler
    $('#custom-search').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Update table info display
    function updateTableInfo() {
        var info = table.page.info();
        var start = info.start + 1;
        var end = info.end;
        var total = info.recordsTotal;
        var filtered = info.recordsDisplay;
        
        var infoText = filtered === total 
            ? `نیشاندان ${start} بۆ ${end} لە کۆی ${total}`
            : `نیشاندان ${start} بۆ ${end} لە کۆی ${filtered} (فیلتەرکراو لە ${total})`;
        
        $('#dt-info').html(`<i class="fa-solid fa-table me-1"></i> ${infoText}`);
    }

    // Handle delete confirmation with sweetalert
    $(document).on('submit', 'form[onsubmit="return confirm(\'دڵنیایت؟\');"]', function(e) {
        e.preventDefault();
        var form = this;
        
        Swal.fire({
            title: 'دڵنیایت؟',
            text: 'ئەم کردارە گەڕانەوەی نیە!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'بەڵێ، بسڕەوە!',
            cancelButtonText: 'نەخێر',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Handle window resize for responsive table
    $(window).on('resize', function() {
        table.columns.adjust();
    });
});