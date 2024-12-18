$(document).ready(function() {
    // Initialize DataTables
    $('#anggotaTable').DataTable();
    
    // Navigation
    $('.nav-link').click(function(e) {
        e.preventDefault();
        const tableId = $(this).data('table');
        $('.table-section').addClass('d-none');
        $(`#${tableId}-content`).removeClass('d-none');
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });

    // Add Anggota
    $('#submitAddAnggota').click(function() {
        const formData = $('#addAnggotaForm').serialize();
        $.ajax({
            url: 'api/anggota/create.php',
            method: 'POST',
            data: formData,
            success: function(response) {
                if(response.success) {
                    $('#addAnggotaModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan sistem');
            }
        });
    });

    // Delete Anggota
    $('.delete-anggota').click(function() {
        if(confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
            const id = $(this).data('id');
            $.ajax({
                url: 'api/anggota/delete.php',
                method: 'POST',
                data: { id: id },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan sistem');
                }
            });
        }
    });

    // Edit Anggota
    $('.edit-anggota').click(function() {
        const id = $(this).data('id');
        // Load anggota data and show edit modal
        $.ajax({
            url: 'api/anggota/read_single.php',
            method: 'GET',
            data: { id: id },
            success: function(response) {
                // Fill the edit form with the data
                // Show the edit modal
            }
        });
    });
});