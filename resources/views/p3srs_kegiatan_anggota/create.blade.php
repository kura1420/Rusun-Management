@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
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

        <div class="col-md-12">
            <table id="tableAnggota" class="table table-hover text-nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID Warga</th>
                        <th>Nama</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wargas as $key => $warga)
                    <tr>
                        <td>{{$warga['id']}}</td>
                        <td>{{$warga['text']}}</td>
                        <td>{{$warga['phone']}}</td>
                        <td>{{$warga['email']}}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btnDeleteAnggota">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <x-slot name="footerSlot">
        <x-adminlte-button type="button" id="btnSubmit" class="btn-sm" label="Simpan" theme="primary" />
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

    const tableAnggota = $('#tableAnggota').DataTable({
        columnDefs: [
            {
                target: 0,
                visible: false,
            },
        ],
    });

    $('body').on('click', '.btnDeleteAnggota', function (e) {
        e.preventDefault();

        const columnRemoveAnggota = $(this).parents('tr');

        tableAnggota
            .row(columnRemoveAnggota)
            .remove()
            .draw();
    });

    $('#btnSubmit').click(function (e) { 
        e.preventDefault();
        
        let $this = $(this);

        $this
            .prop('disabled', true)
            .text('Loading...');
        
        const data = {
            p3srs_kegiatan_jadwal_id: $('#p3srs_kegiatan_jadwal_id').val(),
            wargas: JSON.stringify(tableAnggota.rows().data().toArray()),
        }; 
        
        $.ajax({
            type: "POST",
            url: "{{route('p3srs-kegiatan-anggota.store')}}",
            data: data,
            dataType: "json",
            success: function (response) {
                Swal.fire(
                    'Informasi!',
                    'Data berhasil simpan!',
                    'success'
                );

                window.location.href = '{{route("p3srs-jadwal.show", $p3srsKegiatanJadwal->id)}}';
            },
            error: function (xhr) {
                const {status, statusText, responseText, responseJSON} = xhr;

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
case 403:
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