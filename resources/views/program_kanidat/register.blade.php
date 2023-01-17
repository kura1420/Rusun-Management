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
            
            <x-adminlte-input name="grup_nama" label="Grup Nama" placeholder="Grup Nama" fgroup-class="col-md-6" />

            <x-adminlte-select2 name="rusun_detail_id" id="rusun_detail_id" label="Tower" placeholder="Tower" fgroup-class="col-md-6">
                @foreach ($towers as $tower)
                <option value="{{$tower->id}}">{{$tower->nama_tower}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-select2 name="pemilik_penghuni_id" id="pemilik_penghuni_id" label="Pemilik/Penghuni" placeholder="Pemilik/Penghuni" fgroup-class="col-md-6">
                <option value="{{$pemilikPenghuni['id']}}">{{$pemilikPenghuni['nama']}}</option>
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

    $('#program_jabatan_id').select2({
        placeholder: 'Pilih',
        allowClear: true,
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
            rusun_unit_detail_id: '{{$rusun_unit_detail_id}}',
            apakah_pemilik: '{{$apakah_pemilik}}',
        };

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