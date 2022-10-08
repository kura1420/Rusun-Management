@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{route('user-pemda.store')}}" method="post">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('user-pemda.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-select2 name="provinsi" id="provinsi" label="Provinsi" fgroup-class="col-md-6" />

            <x-adminlte-select2 name="kota" id="kota" label="Kota" fgroup-class="col-md-6" disabled />
            
            <x-adminlte-input name="name" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{old('name')}}" />
            <x-adminlte-input name="username" label="Username" placeholder="Username" fgroup-class="col-md-6" value="{{old('username')}}" />
            <x-adminlte-input type="email" name="email" label="Email" placeholder="Email" fgroup-class="col-md-6" value="{{old('email')}}" />
            <x-adminlte-input name="password" label="Password" placeholder="Password" fgroup-class="col-md-6" value="{{config('app.user_password_default', 'RusunKT@2022')}}" />
        </div>

        <x-slot name="footerSlot">
            <x-adminlte-button type="submit" class="btn-sm" label="Simpan" theme="primary" icon="fab fa-telegram-plane" />
        </x-slot>
    </x-adminlte-card>
</form>
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

    $('#provinsi').select2({
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

    $('#kota').select2({
        placeholder: 'Kota',
        allowClear: true,
        ajax: {
            url: '{{route("rest.kotas")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    province_id: $('#provinsi').val(),
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

    $('#provinsi').on('change', function (e) {
        let value = $(this).val();

        if (value) {
            $('#kota').prop('disabled', false);
        } else {
            $('#kota')
                .prop('disabled', true)
                .val('')
                .trigger('change');
        }
    });
});
</script>
@stop