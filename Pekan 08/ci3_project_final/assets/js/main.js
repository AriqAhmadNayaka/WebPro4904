$(document).ready(function () {

    /* ---- Sidebar Toggle ---- */
    const $sidebar     = $('#sidebar');
    const $mainWrapper = $('#mainWrapper');
    const $overlay     = $('#sidebarOverlay');

    $('#toggleSidebar').on('click', function () {
        if ($(window).width() <= 768) {
            $sidebar.toggleClass('show');
            $overlay.toggleClass('show');
        } else {
            if ($sidebar.hasClass('collapsed')) {
                $sidebar.removeClass('collapsed');
                $mainWrapper.css('margin-left', '250px');
            } else {
                $sidebar.addClass('collapsed');
                $mainWrapper.css('margin-left', '0');
            }
        }
    });

    $overlay.on('click', function () {
        $sidebar.removeClass('show');
        $overlay.removeClass('show');
    });

    /* ---- DataTables ---- */
    if ($.fn.DataTable) {
        $('table.datatable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                paginate: { previous: "&#8249;", next: "&#8250;" },
                zeroRecords: "Data tidak ditemukan"
            },
            responsive: true,
            pageLength: 10,
        });
    }

    /* ---- Konfirmasi Hapus (setara onclick confirm di pekan8) ---- */
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        Swal.fire({
            title: 'Hapus Proyek?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) window.location.href = url;
        });
    });

    /* ---- Auto-dismiss alert ---- */
    setTimeout(() => $('.alert').fadeOut('slow'), 4000);

    /* ---- Tooltips Bootstrap ---- */
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    /* ---- Counter animasi stat cards ---- */
    document.querySelectorAll('.stat-value[data-count]').forEach(el => {
        const target = parseInt(el.getAttribute('data-count'));
        let current  = 0;
        const step   = Math.ceil(target / 40);
        const timer  = setInterval(() => {
            current += step;
            if (current >= target) { current = target; clearInterval(timer); }
            el.textContent = current;
        }, 30);
    });

});
