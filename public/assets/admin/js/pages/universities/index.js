$(document).ready(function() {
    // Initialize DataTable
    var table = $('#datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ku.json'
        },
        pageLength: $('#page-length').val(),
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"top"f>rt<"bottom"ilp><"clear">',
        columnDefs: [
            { orderable: false, targets: [6] } // Actions column
        ],
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

    // Custom search handler (search across all columns)
    $('#custom-search').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Status filter handler
    $('#filter-status').on('change', function() {
        var status = $(this).val();
        if (status === '') {
            table.columns().search('').draw();
        } else {
            // Filter by status column (index 5)
            table.column(5).search(status, true, false).draw();
        }
    });

    // Reset filters
    $('#filter-reset').on('click', function() {
        $('#filter-status').val('');
        $('#custom-search').val('');
        table.columns().search('').draw();
        table.search('').draw();
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

    // Handle delete confirmation with SweetAlert
    $(document).on('submit', 'form[onsubmit*="confirm"]', function(e) {
        e.preventDefault();
        var form = this;
        var confirmMessage = $(form).attr('onsubmit').match(/confirm\('([^']+)'\)/)[1] || 'دڵنیایت؟';
        
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
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle window resize for responsive table
    $(window).on('resize', function() {
        table.columns.adjust();
    });
});