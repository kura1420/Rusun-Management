$(document).ready( function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const table = $('#table2').DataTable({
        processing: true,
        ajax: {
            url: _url + '/program-kanidat/list/data',
            data: {
                program_id: program_id,
            },
        },
        columns: [
            {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
            },
            { data: 'grup_nama' },
            { data: 'grup_status' },
            { data: 'total' },
        ],
    });

    $('#table2 tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
 
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('dt-hasChild');
        } else {
            row.child(format(row.data())).show();
            tr.addClass('dt-hasChild');
        }
    });

    var format = d => {
        let tbody = [];
        if (d.members.length > 0) {
            d.members.forEach(e => {
                tbody.push(`<tr>
                    <td>${e.rusun_detail.nama_tower}</td>
                    <td>${e.rusun_unit_detail.jenis}</td>
                    <td>${e.profile.nama}</td>
                    <td>${e.program_jabatan.nama}</td>
                    <td>${e.status}</td>
                    <td>${e.dokumen}</td>
                </tr>`);
            });
        } else {
            tbody.push('<tr><td colspan="4" style="text-align:center;"><strong>Data tidak tersedia</strong></td></tr>');
        }

        return (
            `<table class="table table-hover table-bordered text-md-nowrap">
                <thead>
                    <tr>
                        <th>Tower</th>
                        <th>Unit</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                ${tbody.join('', )}
                </tbody>
            </table>`
        );
    }
});