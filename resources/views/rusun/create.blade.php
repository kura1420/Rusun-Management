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
        <a href="{{route('rusun.index')}}" class="btn btn-sm btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
    </x-slot>

    <div class="row">
        <x-adminlte-input name="nama" id="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" />

        <x-adminlte-select2 name="province_id" id="province_id" label="Provinsi" fgroup-class="col-md-6" />

        <x-adminlte-select2 name="regencie_id" id="regencie_id" label="Kota" fgroup-class="col-md-6" disabled />

        <x-adminlte-select2 name="district_id" id="district_id" label="Kecamatan" fgroup-class="col-md-6" disabled />

        <x-adminlte-select2 name="village_id" id="village_id" label="Desa" fgroup-class="col-md-6" disabled />

        <x-adminlte-input name="alamat" id="alamat" label="Alamat" placeholder="Alamat" fgroup-class="col-md-6" />
        <x-adminlte-input name="kode_pos" id="kode_pos" label="Kodepos" placeholder="Kodepos" fgroup-class="col-md-2" />
        <x-adminlte-input name="total_tower" id="total_tower" label="Total Tower" placeholder="Total Tower" fgroup-class="col-md-2" />
        <x-adminlte-input name="total_unit" id="total_unit" label="Total Unit" placeholder="Total Unit" fgroup-class="col-md-2" />
        <x-adminlte-input name="website" id="website" label="Website" placeholder="Website" fgroup-class="col-md-3" />
        <x-adminlte-input name="facebook" id="facebook" label="Facebook" placeholder="Facebook" fgroup-class="col-md-3" />
        <x-adminlte-input name="instgram" id="instgram" label="Instgram" placeholder="Instgram" fgroup-class="col-md-3" />
        <x-adminlte-input name="email" id="email" label="Email" placeholder="Email" fgroup-class="col-md-3" />
        <x-adminlte-input name="telp" id="telp" label="Telp" placeholder="Telp" fgroup-class="col-md-2" />
        <x-adminlte-input name="latitude" id="latitude" label="Latitude" placeholder="Latitude" fgroup-class="col-md-2" />
        <x-adminlte-input name="longitude" id="longitude" label="Longitude" placeholder="Longitude" fgroup-class="col-md-2" />

        <x-adminlte-input-file name="foto_1" id="foto_1" label="Foto 1" fgroup-class="col-md-6" />
        <x-adminlte-input-file name="foto_2" id="foto_2" label="Foto 2" fgroup-class="col-md-6" />
        <x-adminlte-input-file name="foto_3" id="foto_3" label="Foto 3" fgroup-class="col-md-6" />
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
        placeholder: 'Desa',
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

        let formData = new FormData();

        formData.append('nama', $('#nama').val());
        formData.append('alamat', $('#alamat').val());
        formData.append('kode_pos', $('#kode_pos').val());
        formData.append('latitude', $('#latitude').val());
        formData.append('longitude', $('#longitude').val());
        formData.append('total_tower', $('#total_tower').val());
        formData.append('total_unit', $('#total_unit').val());
        formData.append('website', $('#website').val());
        formData.append('facebook', $('#facebook').val());
        formData.append('instgram', $('#instgram').val());
        formData.append('email', $('#email').val());
        formData.append('telp', $('#telp').val());

        formData.append('foto_1', $('#foto_1')[0].files[0] ?? '');
        formData.append('foto_2', $('#foto_2')[0].files[0] ?? '');
        formData.append('foto_3', $('#foto_3')[0].files[0] ?? '');

        formData.append('province_id', $('#province_id').val());
        formData.append('regencie_id', $('#regencie_id').val());
        formData.append('district_id', $('#district_id').val());
        formData.append('village_id', $('#village_id').val());

        $this
            .prop('disabled', true)
            .text('Loading...');
        
        $.ajax({
            type: "POST",
            url: "{{route('rusun.store')}}",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.fire({
                    title: 'Input detail rusun?',
                    text: "Langkah ini anda bisa lewati, tapi anda tidak punya detail rusun!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, input!',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.value) {
                        window.location.href = '{{route("rusun-detail.create")}}';
                    }
                });
                
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