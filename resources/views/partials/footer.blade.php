<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Global AJAX setup
    if (typeof $ !== 'undefined') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
    }

    // Confirm logout with SweetAlert
    function confirmLogout() {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm')?.submit();
            }
        });
    }

    // Sortable table functionality
    document.querySelectorAll('.table-sortable').forEach(table => {
        const headers = table.querySelectorAll('thead th:not(.no-sort)');
        const tbody = table.querySelector('tbody');

        headers.forEach((header, index) => {
            header.addEventListener('click', () => {
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const isAsc = header.classList.contains('sort-asc');

                // Reset all headers
                headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));

                // Sort rows
                rows.sort((a, b) => {
                    const aVal = a.cells[index]?.textContent.trim() || '';
                    const bVal = b.cells[index]?.textContent.trim() || '';

                    // Try numeric sort first
                    const aNum = parseFloat(aVal.replace(/[^0-9.-]/g, ''));
                    const bNum = parseFloat(bVal.replace(/[^0-9.-]/g, ''));

                    if (!isNaN(aNum) && !isNaN(bNum)) {
                        return isAsc ? bNum - aNum : aNum - bNum;
                    }

                    // Fallback to string sort
                    return isAsc
                        ? bVal.localeCompare(aVal, 'id')
                        : aVal.localeCompare(bVal, 'id');
                });

                // Update header class
                header.classList.add(isAsc ? 'sort-desc' : 'sort-asc');

                // Re-append sorted rows
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });
</script>