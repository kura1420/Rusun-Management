@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<x-adminlte-callout theme="info" title="Informasi">
    Grup yang akan di simpan adalah sesuai dengan yang ada di field. Bila anda ganti tanpa menekan tombol "Simpan", maka data nama grup terakhir yang akan digunakan. <br>
    Pastikan daftar kanidat dan nama grup terlebih dahulu lalu tekan Simpan. Setelah itu anda dapat membuat grup baru lagi.
</x-adminlte-callout>

<div id="showMessageError" style="display: none;">
    <x-adminlte-alert theme="warning" title="Warning" dismissable>
        <ul id="listMessageError"></ul>
    </x-adminlte-alert>
</div>

<x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
    <x-slot name="toolsSlot">
        <a href="{{route('p3srs-jadwal.show', $p3srsKegiatanJadwal->id)}}" class="btn btn-sm btn-dark"><i class="fa fa-arrow-left"></i> Kembali</a>
    </x-slot>

    <div class="row">
        <input type="hidden" name="p3srs_kegiatan_jadwal_id" id="p3srs_kegiatan_jadwal_id" value="{{$p3srsKegiatanJadwal->id}}">
        <x-adminlte-input name="rusun_id" id="rusun_id" label="Rusun" placeholder="Rusun" fgroup-class="col-md-2" value="{{$p3srsKegiatanJadwal->rusuns->nama}}" readonly />
        <x-adminlte-input name="p3srs_kegiatan_id" id="p3srs_kegiatan_id" label="Kegaiatan" placeholder="Kegaiatan" fgroup-class="col-md-10" value="{{$p3srsKegiatanJadwal->p3srs_kegiatans->nama}}" readonly />

        <x-adminlte-input name="grup_nama" id="grup_nama" placeholder="Grup" fgroup-class="col-md-2" value="{{$row[0]->grup_nama}}" />
        <x-adminlte-select2 name="warga" id="warga" fgroup-class="col-md-4">
            <option value=""></option>
        </x-adminlte-select2>

        <x-adminlte-select2
            name="p3srs_jabatan_id"
            id="p3srs_jabatan_id"
            fgroup-class="col-md-4"
            :config="[
                'placeholder' => 'Pilih Jabatan',
                'allowClear' => true,
            ]"
        >
            <option value=""></option>
            @foreach ($p3srsJabatans as $p3srsJabatan)
            <option value="{{$p3srsJabatan->id}}">{{$p3srsJabatan->nama}}</option>
            @endforeach
        </x-adminlte-select2>
        
        <div class="col-md-2">
            <x-adminlte-button type="button" id="btnAddKanidat" label="Tambah" class="btn-md" theme="info" icon="fas fa-plus"/>
        </div>

        <div class="col-md-12">
            <table id="tableKanidat" class="table table-hover text-nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID Warga</th>
                        <th>ID Jabatan</th>
                        <th>Nama</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Sebagai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($row as $key => $r)
                    <tr>
                        <td>{{$r->apakah_pemilik ? $r->pemilik_penghuni_id . '=pmk' : $r->pemilik_penghuni_id . '=pgh'}}</td>
                        <td>{{$r->p3srs_jabatan_id}}</td>
                        <td>{{$r->profile->nama}}</td>
                        <td>{{$r->profile->phone}}</td>
                        <td>{{$r->profile->email}}</td>
                        <td>{{$r->p3srs_jabatans->nama}}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btnDeleteKanidat" id="{{route('p3srs-kegiatan-kanidat.destroy', $r->id)}}" value="{{$r->id}}">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <x-slot name="footerSlot">
        <x-adminlte-button type="button" id="btnSubmit" class="btn-sm" label="Simpan" theme="primary" />
        <x-adminlte-button type="button" value="{{$row[0]->grup_id}}" id="{{route('p3srs-kegiatan-kanidat.destroyGroup', $row[0]->grup_id)}}" class="btn-sm float-right btnDeleteGroup" label="Hapus Grup" theme="warning" />
    </x-slot>
</x-adminlte-card>
@stop

@section('css')

@stop

@section('js')
<script>
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    $('#warga').select2({
        placeholder: 'Pilih Warga',
        allowClear: true,
        data: {{Js::from($wargas)}}
    });

    const tableKanidat = $('#tableKanidat').DataTable({
        columnDefs: [
            {
                target: 0,
                visible: false,
            },
            {
                target: 1,
                visible: false,
            },
        ],
    });

    const btnDeleteKanidat = () => `<button type="button" class="btn btn-danger btn-sm btnDeleteKanidat">Hapus</button>`;

    $('#btnAddKanidat').click(function (e) { 
        e.preventDefault();
        
        let warga = $('#warga').select2('data');
        const p3srs_jabatan_id = $('#p3srs_jabatan_data').val();
        const p3srs_jabatan_data = $('#p3srs_jabatan_id').select2('data');
        
        const {
            id,
            text,
            phone,
            email,            
        } = warga[0];

        if (id !== '' && text !== '' && p3srs_jabatan_id !== '') {
            const tableKanidatRows = tableKanidat
                .rows()
                .data()
                .toArray();
                
            if (tableKanidatRows.length == 0) {
                tableKanidat
                    .row
                    .add([
                        id,
                        p3srs_jabatan_data[0].id,
                        text,
                        phone,
                        email,
                        p3srs_jabatan_data[0].text,
                        btnDeleteKanidat(),
                    ])
                    .draw();
            } else {
                const tableKanidatCheck = tableKanidatRows.filter(r => r[2] == text);
                
                if (tableKanidatCheck.length == 0) {
                    tableKanidat
                        .row
                        .add([
                            id,
                            p3srs_jabatan_data[0].id,
                            text,
                            phone,
                            email,
                            p3srs_jabatan_data[0].text,
                            btnDeleteKanidat(),
                        ])
                        .draw();
                } else {
                    Swal.fire('Data sudah tersedia.');                    
                }
            }
        } else {
            Swal.fire('Harap pilih warga & jabatan terlebih dahulu.');
        }
    });

    $('body').on('click', '.btnDeleteKanidat', function (e) {
        e.preventDefault();

        const columnRemoveKanidat = $(this).parents('tr');
        const url = $(this).attr('id');
        const value = $(this).val();

        if (url !== '' && value !== '') {
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
                            tableKanidat
                                .row(columnRemoveKanidat)
                                .remove()
                                .draw();              

                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            );
                            
                        },
                        error: function (xhr) {
                            const {responseJSON, status, statusText} = xhr;

                            switch (status) {
                                case 500:
                                case 403:
                                    Swal.fire({
                                        title: 'Error',
                                        text: statusText,
                                    });                        
                                    break;
                            
                                default:
                                    break;
                            }
                        }
                    });
                }
            });
        } else {
            tableKanidat
                .row(columnRemoveKanidat)
                .remove()
                .draw();            
        }
    });

    $('body').on('click', '.btnDeleteGroup', function (e) {
        e.preventDefault();

        const url = $(this).attr('id');
        const value = $(this).val();

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
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );                        
                
                        window.location.href = '{{route("p3srs-jadwal.show", $p3srsKegiatanJadwal->id)}}';
                    },
                    error: function (xhr) {
                        const {responseJSON, status, statusText} = xhr;

                        switch (status) {
                            case 500:
                            case 403:
                            Swal.fire({
                                title: 'Error',
                                text: statusText,
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

    $('#btnSubmit').click(function (e) { 
        e.preventDefault();
        
        let $this = $(this);

        $this
            .prop('disabled', true)
            .text('Loading...');
        
        const data = {
            grup_nama: $('#grup_nama').val(),
            p3srs_kegiatan_jadwal_id: $('#p3srs_kegiatan_jadwal_id').val(),
            wargas: JSON.stringify(tableKanidat.rows().data().toArray()),
        };
        
        $.ajax({
            type: "PUT",
            url: "{{route('p3srs-kegiatan-kanidat.update', $row[0]->grup_id)}}",
            data: data,
            dataType: "json",
            success: function (response) {
                Swal.fire(
                    'Informasi!',
                    'Data berhasil perbarui!',
                    'success'
                );
            },
            error: function (xhr) {
                const {responseJSON, status, statusText} = xhr;

                switch (status) {
                    case 422:
                        const errors = responseJSON.errors;

                        $('#showMessageError').hide();

                        $('#listMessageError').html('');

                        $.each(errors, function (index, value) { 
                            $('<li/>')
                                .text(value[0])
                                .appendTo('#listMessageError');
                        });
                        
                        $('#showMessageError').show();
                        break;

                    case 500:
                    case 419:
                        Swal.fire({
                            title: 'Error',
                            text: statusText,
                        });                        
                        break;
                
                    default:
                        break;
                }
                
                $this
                    .prop('disabled', false)
                    .text('Simpan');
            }
        });
    });
});
</script>
@stop