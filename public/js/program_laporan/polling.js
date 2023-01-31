$(document).ready( function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });
    
    $('#listPengambilanSuara').DataTable();

    const table = $('#listKanidat').DataTable({
        columns: [
            { data: 'rusun_detail.nama_tower' },
            { data: 'rusun_unit_detail.jenis' },
            { data: 'pemilik_penghuni_profile.nama' },
            { data: 'program_jabatan.nama' },
        ],
    });

    $('body').on('click', '.btnDetailGrup', function (e) {
        let url = $(this).attr('id');

        table
            .clear()
            .draw();

        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                if (response.data.length > 0) {
                        table
                            .rows
                            .add(response.data)
                            .draw();

                        $('#modalListKanidat').modal('show');
                } else {
                    Swal.fire('Data tidak tersedia');
                }
            },
            error: function (xhr) {
                const {status, statusText, responseText, responseJSON} = xhr;

                switch (status) {
                    case 500:
                    case 419:
                    case 403:
                        Swal.fire({
                            title: statusText,
                            text: responseText,
                        });                     
                        break;
                
                    default:
                        break;
                }
            }
        });
    });
});