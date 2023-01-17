@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<div class="alert alert-danger mb-10" role="alert" id="showMessageError" style="display: none;">
    <h4 class="alert-heading">Peringatan!</h4>
    <ul id="listMessageError"></ul>
</div>

    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            @role('Root|Admin|Rusun|Pemda')
            <a href="{{route('program-kanidat.index', ['program_id' => $row->program->id])}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            @endrole

            @role('Pemilik|Penghuni')
            <a href="{{route('program-kanidat.show', $row->grup_id)}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            @endrole
        </x-slot>
        
        <div class="row">
            <input type="hidden" name="rusun_id" id="rusun_id" value="{{$row->rusun_id}}">
            <input type="hidden" name="program_id" id="program_id" value="{{$row->program_id}}">
            <input type="hidden" name="grup_id" id="grup_id" value="{{$row->grup_id}}">

            <x-adminlte-input name="rusun" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$row->rusun->nama}}" disabled />
            <x-adminlte-input name="program" label="Program" placeholder="Program" fgroup-class="col-md-6" value="{{$row->program->nama}}" disabled />
            <x-adminlte-input name="grup_nama" label="Grup Nama" placeholder="Grup Nama" fgroup-class="col-md-6" value="{{$row->grup_nama}}" disabled />

            <x-adminlte-select2 name="rusun_detail_id" id="rusun_detail_id" label="Tower" placeholder="Tower" fgroup-class="col-md-6">
                <option value=""></option>
                @foreach ($towers as $tower)
                <option value="{{$tower->id}}">{{$tower->nama_tower}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-select2 name="pemilik_penghuni_id" id="pemilik_penghuni_id" label="Pemilik/Penghuni" placeholder="Pemilik/Penghuni" fgroup-class="col-md-6">
                <option value=""></option>
            </x-adminlte-select2>

            <x-adminlte-select2 name="program_jabatan_id" id="program_jabatan_id" label="Jabatan" placeholder="Jabatan" fgroup-class="col-md-4">
                <option value=""></option>
                @foreach ($jabatans as $jabatan)
                <option value="{{$jabatan->id}}">{{$jabatan->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <div class="form-group col-md-2">
                <label for="pemilik_penghuni_id" class="text-white">
                    -
                </label>
                
                <div class="input-group">
                    <x-adminlte-button type="button" class="btn-sm" id="btnSubmit" label="Simpan" theme="primary" icon="fab fa-telegram-plane" />
                </div>
            </div>

        </div>
    </x-adminlte-card>

    <x-adminlte-card theme="primary" theme-mode="outline" title="Daftar Kanidat">
        <table class="table text-nowrap" id="table2">
            <thead>
                <tr>
                    <th>Tower</th>
                    <th>Unit</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </x-adminlte-card>
@stop

@section('css')

@stop

@section('js')
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const table = $('#table2').DataTable({
        processing: true,
        ajax: '{{route("program-kanidat.show-detail", [$row->program_id, $row->grup_id])}}',
        columns: [
            { data: 'rusun_detail.nama_tower' },
            { data: 'rusun_unit_detail.jenis' },
            { data: 'pemilik_penghuni_profile.nama' },
            { data: 'program_jabatan.nama' },
            {
                data: 'id',
                render: function (data, type, row, meta) {
                    let url = '{{url("program-kanidat")}}';
                    let btnDestroy = `<button type="button" class="btn btn-danger btn-sm btnDelete" value="${row.id}" id="${url}/${row.id}"><i class="fas fa-trash"></i> Hapus</button>`;

                    let pemilikPenghuniLogin = '{{$pemilikPenghuniLogin}}';
                    console.log(row.pemilik_penghuni_id, pemilikPenghuniLogin);
                    if (row.pemilik_penghuni_id == pemilikPenghuniLogin) {
                        return '-';
                    } else {
                        return btnDestroy;
                    }
                }
            },
        ],
    });

    $('#rusun_detail_id').select2({
        placeholder: 'Pilih',
        allowClear: true,
    });

    $('#pemilik_penghuni_id').select2({
        placeholder: 'Pilih',
        allowClear: true,
        disabled: true,
        ajax: {
            url: "{{route('rusun-penghuni.list-data')}}",
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    rusun_id: $('#rusun_id').val(),
                    program_id: $('#program_id').val(),
                    rusun_detail_id: $('#rusun_detail_id').val(),
                };

                return query;
            },
            processResults: function (data) {
                let results = [];

                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.nama + ' - ' + value.unit_name,
                            rusun_unit_detail_id: value.unit_id,
                            apakah_pemilik: value.apakah_pemilik,
                        });
                    });
                }

                return {results};
            }
        },
    });

    $('#program_jabatan_id').select2({
        placeholder: 'Pilih',
        allowClear: true,
    });

    $('#rusun_detail_id').on('change', function (e) {
        const value = $(this).val();

        if (value) {
            $('#pemilik_penghuni_id').attr('disabled', false);
        } else {
            $('#pemilik_penghuni_id').attr('disabled', true);
        }

        $('#pemilik_penghuni_id').val(null).trigger('change');
    });

    $('#btnSubmit').click(function (e) { 
        e.preventDefault();
        
        let data = {
            rusun_id: $('#rusun_id').val(),
            program_id: $('#program_id').val(),
            grup_nama: $('#grup_nama').val(),
            rusun_detail_id: $('#rusun_detail_id').val(),
            pemilik_penghuni_id: $('#pemilik_penghuni_id').val(),
            program_jabatan_id: $('#program_jabatan_id').val(),
        };

        let {id, text, apakah_pemilik, rusun_unit_detail_id} = $('#pemilik_penghuni_id').select2('data')[0];

        if (id !== '' && text !== '') {
            data.rusun_unit_detail_id = rusun_unit_detail_id;
            data.apakah_pemilik = apakah_pemilik;
        }

        @role('Pemilik|Penghuni')
        data.__state = 'mendaftarkan';
        @endrole

        $.ajax({
            type: "POST",
            url: "{{route('program-kanidat.store')}}",
            data: data,
            dataType: "json",
            success: function (response) {
                table.ajax.reload();

                $('#pemilik_penghuni_id').val(null).trigger('change');
                $('#program_jabatan_id').val(null).trigger('change');

                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data berhasil di simpan',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function (xhr) {
                const {status, statusText, responseText, responseJSON} = xhr;

                switch (status) {
                    case 422:
                        $('#showMessageError').hide();
                        $('#listMessageError').html('');

                        const errors = responseJSON.errors;

                        $.each(errors, function (index, value) { 
                            $('<li/>')
                                .text(value[0])
                                .appendTo('#listMessageError');
                        });
                        
                        $('#showMessageError').show();
                        break;

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