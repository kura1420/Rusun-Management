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
        <a href="{{route('p3srs-jadwal.index')}}" class="btn btn-sm btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
    </x-slot>

    <div class="row">
        <x-adminlte-select2
            name="rusun_id"
            id="rusun_id"
            label="Rusun"
            fgroup-class="col-md-6"
            :config="[
                'placeholder' => 'Pilih Rusun',
                'allowClear' => true,
            ]"
        >
            <option value=""></option>
            @foreach ($rusuns as $rusun)
            <option value="{{$rusun->id}}">{{$rusun->nama}}</option>
            @endforeach
        </x-adminlte-select2>

        <x-adminlte-select2
            name="p3srs_kegiatan_id"
            id="p3srs_kegiatan_id"
            label="Kegiatan"
            fgroup-class="col-md-6"
            :config="[
                'placeholder' => 'Pilih Kegiatan',
                'allowClear' => true,
            ]"
        >
            <option value=""></option>
            <option value="new">Tambah Kegiatan</option>
            @foreach ($kegiatans as $kegiatan)
            <option value="{{$kegiatan->id}}">{{$kegiatan->nama}}</option>
            @endforeach
        </x-adminlte-select2>

        <x-adminlte-input-date name="tanggal" id="tanggal" label="Tanggal" placeholder="Tanggal" fgroup-class="col-md-2" :config="['format' => 'YYYY-MM-DD']" />
        <x-adminlte-input name="lokasi" id="lokasi" label="Lokasi" placeholder="Lokasi" fgroup-class="col-md-10" />

        <x-adminlte-text-editor
            name="keterangan"
            id="keterangan"
            label="Keterangan"
            fgroup-class="col-md-12"
            :config="[
                'height' => '300',
            ]"
        />
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
    
    $('#p3srs_kegiatan_id').on('select2:select', function (e) {
        const value = $(this).val();
        
        if (value === 'new') {
            const promptValue = prompt('Tambah Posisi:');
            
            if (promptValue) {
                const data = {
                    id: promptValue,
                    text: promptValue,
                }

                const newOption = new Option(data.text, data.id, false, false);

                $('#p3srs_kegiatan_id')
                    .append(newOption)
                    .trigger('change')
                    .val(promptValue);
            }
        }
    });

    $('#btnSubmit').click(function (e) { 
        e.preventDefault();
        
        let $this = $(this);

        $this
            .prop('disabled', true)
            .text('Loading...');
        
        const data = {
            tanggal: $('#tanggal').val(),
            status: $('#status').val(),
            lokasi: $('#lokasi').val(),
            keterangan: $('#keterangan').val(),
            p3srs_kegiatan_id: $('#p3srs_kegiatan_id').val(),
            rusun_id: $('#rusun_id').val(),
        }; 
        
        $.ajax({
            type: "POST",
            url: "{{route('p3srs-jadwal.store')}}",
            data: data,
            dataType: "json",
            success: function (response) {
                Swal.fire(
                    'Informasi!',
                    'Data berhasil simpan!',
                    'success'
                );
                
                $this
                    .prop('disabled', false)
                    .text('Simpan');
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