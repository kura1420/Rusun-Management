@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<x-adminlte-callout theme="warning" title="Perhatian">
    Sebelum sync manual untuk bagian <strong>Outstanding Penghuni</strong> harap melakukan sync <strong>Pemilik & Penghuni</strong> terlebih dahulu, agar data Pemilik & Penghuni terupdate.
</x-adminlte-callout>

@if (session()->has('success'))
<x-adminlte-alert theme="primary" title="Information" dismissable>
    {{session()->get('success')}}
</x-adminlte-alert>
@endif

<x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
    <x-slot name="toolsSlot">
        <a href="{{route('api-manage.create')}}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </x-slot>
    
    <table class="table text-nowrap" id="table2">
        <thead>
            <tr>
                <th></th>
                <th>Rusun</th>
                <th>Username</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</x-adminlte-card>
@stop

@section('css')
<style>
table.dataTable td.dt-control {
  text-align: center;
  cursor: pointer;
}
table.dataTable td.dt-control:before {
  height: 1em;
  width: 1em;
  margin-top: -9px;
  display: inline-block;
  color: white;
  border: 0.15em solid white;
  border-radius: 1em;
  box-shadow: 0 0 0.2em #444;
  box-sizing: content-box;
  text-align: center;
  text-indent: 0 !important;
  font-family: "Courier New", Courier, monospace;
  line-height: 1em;
  content: "+";
  background-color: #31b131;
}
table.dataTable tr.dt-hasChild td.dt-control:before {
  content: "-";
  background-color: #d33333;
}
</style>
@stop

@section('js')
<script>
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const table = $('#table2').DataTable({
        processing: true,
        ajax: '{{route("api-manage.list-data")}}',
        columns: [
            {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
            },
            { data: 'reff_id.nama' },
            { data: 'username' },
            { data: 'password' },
            {
                data: 'id',
                render: function (data, type, row, meta) {
                    let urlAPIManage = '{{url("api-manage")}}';
                    let btnEdit = `<a href="${urlAPIManage}/${row.id}/edit" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>`;
                    let btnDestroy = `<button type="button" class="btn btn-danger btn-sm btnDelete" value="${row.id}" id="${urlAPIManage}/${row.id}"><i class="fas fa-trash"></i> Hapus</button>`;

                    return btnEdit + ' ' + btnDestroy;
                }
            },
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
        if (d.childs.length > 0) {
            d.childs.forEach(e => {
                let urlAPIManage = '{{url("api-manage")}}';
                let buttonSyncManual = `<button type="button" class="btn btn-success btn-sm btnSyncManual" value="${e.id}" id="${urlAPIManage}/${e.id}/sync-manual"><i class="fas fa-plane"></i> Proses</button>`;

                tbody.push(`<tr>
                    <td>${e.table}</td>
                    <td>${e.keterangan ?? ''}</td>
                    <td>${e.last_sync ?? ''}</td>
                    <td>
                        ${buttonSyncManual}
                    </td>
                </tr>`);
            });
        } else {
            tbody.push('<tr><td colspan="4" style="text-align:center;"><strong>Data tidak tersedia</strong></td></tr>');
        }

        return (
            `<table class="table table-hover table-bordered text-md-nowrap">
                <thead>
                    <tr>
                        <th>Table</th>
                        <th>Keterangan</th>
                        <th>Terakhir Diperbarui</th>
                        <th style="width:50px;">Sync Manual</th>
                    </tr>
                </thead>
                <tbody>
                ${tbody.join('', )}
                </tbody>
            </table>`
        );
    }

    $('body').on('click', '.btnSyncManual', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const url = $(this).attr('id');

        Swal.fire({
            title: 'Informasi',
            text: 'Sync API membutuhkan waktu beberapa menit, apakah anda ingin lanjut?',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return fetch(url)
                    .then(response => {
                        return response;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        );
                    })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            const {status, statusText} = result.value;

            switch (status) {
                case 200:
                    Swal.fire(
                        'Informasi!',
                        'Data sudah diperbarui',
                        'success'
                    );
                    break;

                case 422:
                    Swal.fire({
                        title: 'Error',
                        text: 'Data pemilik/penghuni tidak ditemukan',
                    });
                    break;

                case 500:
                case 419:
                case 403:
                    Swal.fire({
                        title: 'Error',
                        text: statusText,
                    }); 
                    break;
            
                default:
                    break;
            }
        })
    });
    
    $('body').on('click', '.btnDelete', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const url = $(this).attr('id');
        const columnRemove = $(this).parents('tr');

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Ingin menghapus data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {
                        id: value,
                    },
                    dataType: "json",
                    success: function (response) {
                        table
                            .row(columnRemove)
                            .remove()
                            .draw();                  

                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        
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
            }
        });
    });
});
</script>
@stop