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
        <a href="{{route('pengembang.index')}}" class="btn btn-sm btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
    </x-slot>

    <div class="row">
        <x-adminlte-input name="nama" id="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->nama}}" />

        {{-- <x-adminlte-select2 name="province_id" id="province_id" label="Provinsi" fgroup-class="col-md-6">
            @if($row->provinces)
            <option value="{{$row->provinces->id}}">{{$row->provinces->name}}</option>
            @endif
        </x-adminlte-select2>

        <x-adminlte-select2 name="regencie_id" id="regencie_id" label="Kota" fgroup-class="col-md-6">
            @if($row->kotas)
            <option value="{{$row->kotas->id}}">{{$row->kotas->name}}</option>
            @endif
        </x-adminlte-select2>

        <x-adminlte-select2 name="district_id" id="district_id" label="Kecamatan" fgroup-class="col-md-6">
            @if($row->kecamatans)
            <option value="{{$row->kecamatans->id}}">{{$row->kecamatans->name}}</option>
            @endif
        </x-adminlte-select2>

        <x-adminlte-select2 name="village_id" id="village_id" label="Kelurahan" fgroup-class="col-md-6">
            @if($row->desas)
            <option value="{{$row->desas->id}}">{{$row->desas->name}}</option>
            @endif
        </x-adminlte-select2> --}}

        <x-adminlte-input name="alamat" id="alamat" label="Alamat" placeholder="Alamat" fgroup-class="col-md-6" value="{{$row->alamat}}" />
        <x-adminlte-input name="telp" id="telp" label="Telp" placeholder="Telp" fgroup-class="col-md-6" value="{{$row->telp}}" />
        <x-adminlte-input name="email" id="email" label="Email" placeholder="Email" fgroup-class="col-md-6" value="{{$row->email}}" />
        <x-adminlte-input name="website" id="website" label="Website" placeholder="Website" fgroup-class="col-md-6" value="{{$row->website}}" />
        <x-adminlte-input name="keterangan" id="keterangan" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-6" value="{{$row->keterangan}}" />
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

    $('#province_id').select2({
        placeholder: 'Provinsi',
        allowClear: true,
        ajax: {
            url: '{{route("rest.provinsis")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.name,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#regencie_id').select2({
        placeholder: 'Kota',
        allowClear: true,
        ajax: {
            url: '{{route("rest.kotas")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    province_id: $('#province_id').val(),
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.name,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#district_id').select2({
        placeholder: 'Kecamatan',
        allowClear: true,
        ajax: {
            url: '{{route("rest.kecamatans")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    regencie_id: $('#regencie_id').val(),
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.name,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#village_id').select2({
        placeholder: 'Kelurahan',
        allowClear: true,
        ajax: {
            url: '{{route("rest.desas")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    district_id: $('#district_id').val(),
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.name,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#province_id').on('change', function (e) {
        let value = $(this).val();

        if (value) {
            $('#regencie_id').prop('disabled', false);
        } else {
            $('#regencie_id, #district_id, #village_id')
                .prop('disabled', true)
                .val('')
                .trigger('change');
        }
    });

    $('#regencie_id').on('change', function (e) {
        let value = $(this).val();

        if (value) {
            $('#district_id').prop('disabled', false);
        } else {
            $('#district_id, #village_id')
                .prop('disabled', true)
                .val('')
                .trigger('change');
        }
    });

    $('#district_id').on('change', function (e) {
        let value = $(this).val();

        if (value) {
            $('#village_id').prop('disabled', false);
        } else {
            $('#village_id')
                .prop('disabled', true)
                .val('')
                .trigger('change');
        }
    });
    
    $('#btnSubmit').click(function (e) { 
        e.preventDefault();
        
        let $this = $(this);

        $this
            .prop('disabled', true)
            .text('Loading...');
        
        const data = {
            nama: $('#nama').val(),
            alamat: $('#alamat').val(),
            telp: $('#telp').val(),
            email: $('#email').val(),
            website: $('#website').val(),
            keterangan: $('#keterangan').val(),
            sebagai: $('#sebagai').val(),
            province_id: $('#province_id').val(),
            regencie_id: $('#regencie_id').val(),
            district_id: $('#district_id').val(),
            village_id: $('#village_id').val(),
        }; 
        
        $.ajax({
            type: "PUT",
            url: "{{route('pengembang.update', $row->id)}}",
            data: data,
            dataType: "json",
            success: function (response) {  
                Swal.fire(
                    'Informasi!',
                    'Data berhasil diperbarui!',
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