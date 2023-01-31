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
            <a href="{{route('program-kanidat.index', ['program_id' => $program->id])}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <input type="hidden" name="rusun_id" id="rusun_id" value="{{$program->rusun_id}}">
            <input type="hidden" name="program_id" id="program_id" value="{{$program->id}}">

            <x-adminlte-input name="rusun" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$program->rusun->nama}}" disabled />
            <x-adminlte-input name="program" label="Program" placeholder="Program" fgroup-class="col-md-6" value="{{$program->nama}}" disabled />

            <x-adminlte-select2 name="grup_nama" id="grup_nama" label="Grup Nama" placeholder="Grup Nama" fgroup-class="col-md-6">
                <option value=""></option>
                <option value="new">Tambah</option>
                @foreach ($grups as $grup)
                <option value="{{$grup->grup_id}}">{{$grup->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-select2 name="rusun_detail_id" id="rusun_detail_id" label="Tower" placeholder="Tower" fgroup-class="col-md-6">
                <option value=""></option>
                @foreach ($towers as $tower)
                <option value="{{$tower->id}}">{{$tower->nama_tower}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-select2 name="pemilik_penghuni_id" id="pemilik_penghuni_id" label="Pemilik/Penghuni" placeholder="Pemilik/Penghuni" fgroup-class="col-md-6">
                <option value=""></option>
            </x-adminlte-select2>

            <x-adminlte-select2 name="program_jabatan_id" id="program_jabatan_id" label="Jabatan" placeholder="Jabatan" fgroup-class="col-md-6">
                <option value=""></option>
                @foreach ($jabatans as $jabatan)
                <option value="{{$jabatan->id}}">{{$jabatan->nama}}</option>
                @endforeach
            </x-adminlte-select2>
        </div>

        <x-slot name="footerSlot">
            <x-adminlte-button type="button" class="btn-sm" id="btnSubmit" label="Simpan" theme="primary" icon="fab fa-telegram-plane" />
        </x-slot>
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

    $('#grup_nama').select2({
        placeholder: 'Pilih',
        allowClear: true,
    });

    $('#grup_nama').on('select2:select', function (e) {
        const value = $(this).val();
        
        if (value === 'new') {
            const promptValue = prompt('Tambah List:');
            
            if (promptValue) {
                const data = {
                    id: promptValue,
                    text: promptValue,
                }
                const newOption = new Option(data.text, data.id, false, false);
                $('#grup_nama')
                    .append(newOption)
                    .trigger('change')
                    .val(promptValue);
            }
        }
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

        $.ajax({
            type: "POST",
            url: "{{route('program-kanidat.store')}}",
            data: data,
            dataType: "json",
            success: function (response) {
                window.location.href = '{{url("program-kanidat")}}/' + response + '/edit';
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
});
</script>
@stop