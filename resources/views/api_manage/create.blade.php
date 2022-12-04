@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('api-manage.store')}}" method="post">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('api-manage.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-select2 name="reff_id" id="reff_id" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6"
                :config="[
                    'placeholder' => 'Pilih Rusun',
                    'allowClear' => TRUE
                ]"
            >
                <option value=""></option>
                @foreach ($rusuns as $key => $rusun)
                <option value="{{$rusun->id}}" {{old('reff_id') == $rusun->id ? 'selected' : ''}}>{{$rusun->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-input name="username" id="username" label="Username" placeholder="Username" fgroup-class="col-md-3" value="{{old('username')}}" />
            <x-adminlte-input name="password" id="password" label="Password" placeholder="Password" fgroup-class="col-md-3" value="{{old('password')}}" />

            <x-adminlte-select name="table_rusun_details" id="table_rusun_details" label="Data Sinkronisasi" placeholder="Data Sinkronisasi" fgroup-class="col-md-3">
                <option value="rusun_details" {{old('table_rusun_details') == 'rusun_details' ? 'selected' : ''}}>Tower</option>
            </x-adminlte-select>

            <x-adminlte-input name="endpoint_rusun_details" id="endpoint_rusun_details" label="Endpoint" placeholder="Endpoint" fgroup-class="col-md-4" value="{{old('endpoint_rusun_details')}}" />
            <x-adminlte-input name="keterangan_rusun_details" id="keterangan_rusun_details" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-5" value="{{old('keterangan_rusun_details')}}" />

            <x-adminlte-select name="table_rusun_tarifs" id="table_rusun_tarifs" label="Data Sinkronisasi" placeholder="Data Sinkronisasi" fgroup-class="col-md-3">
                <option value="rusun_tarifs" {{old('table_rusun_tarifs') == 'rusun_tarifs' ? 'selected' : ''}}>Tarif</option>
            </x-adminlte-select>

            <x-adminlte-input name="endpoint_rusun_tarifs" id="endpoint_rusun_tarifs" label="Endpoint" placeholder="Endpoint" fgroup-class="col-md-4" value="{{old('endpoint_rusun_tarifs')}}" />
            <x-adminlte-input name="keterangan_rusun_tarifs" id="keterangan_rusun_tarifs" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-5" value="{{old('keterangan_rusun_tarifs')}}" />

            <x-adminlte-select name="table_rusun_outstanding_penghunis" id="table_rusun_outstanding_penghunis" label="Data Sinkronisasi" placeholder="Data Sinkronisasi" fgroup-class="col-md-3">
                <option value="rusun_outstanding_penghunis" {{old('table_rusun_outstanding_penghunis') == 'rusun_outstanding_penghunis' ? 'selected' : ''}}>Outstanding Penghuni</option>
            </x-adminlte-select>

            <x-adminlte-input name="endpoint_rusun_outstanding_penghunis" id="endpoint_rusun_outstanding_penghunis" label="Endpoint" placeholder="Endpoint" fgroup-class="col-md-4" value="{{old('endpoint_rusun_outstanding_penghunis')}}" />
            <x-adminlte-input name="keterangan_rusun_outstanding_penghunis" id="keterangan_rusun_outstanding_penghunis" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-5" value="{{old('keterangan_rusun_outstanding_penghunis')}}" />

            <x-adminlte-select name="table_rusun_pemiliks" id="table_rusun_pemiliks" label="Data Sinkronisasi" placeholder="Data Sinkronisasi" fgroup-class="col-md-3">
                <option value="rusun_pemiliks" {{old('table_rusun_pemiliks') == 'rusun_pemiliks' ? 'selected' : ''}}>Pemilik & Penghuni</option>
            </x-adminlte-select>

            <x-adminlte-input name="endpoint_rusun_pemiliks" id="endpoint_rusun_pemiliks" label="Endpoint" placeholder="Endpoint" fgroup-class="col-md-4" value="{{old('endpoint_rusun_pemiliks')}}" />
            <x-adminlte-input name="keterangan_rusun_pemiliks" id="keterangan_rusun_pemiliks" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-5" value="{{old('keterangan_rusun_pemiliks')}}" />
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

@stop