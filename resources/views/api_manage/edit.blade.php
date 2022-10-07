@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('api-manage.update', $row->id)}}" method="post">
    @csrf
    @method('PUT')
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
                <option value="{{$rusun->id}}" {{$row->reff_id == $rusun->id ? 'selected' : ''}}>{{$rusun->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-select name="table" id="table" label="Data Singkronisasi" placeholder="Data Singkronisasi" fgroup-class="col-md-6">
                <option value="">Pilih</option>
                <option value="rusun_details" {{$row->table == 'rusun_details' ? 'selected' : ''}}>Tower</option>
                <option value="rusun_tarifs" {{$row->table == 'rusun_tarifs' ? 'selected' : ''}}>Tarif</option>
                <option value="rusun_outstanding_penghunis" {{$row->table == 'rusun_outstanding_penghunis' ? 'selected' : ''}}>Outstanding Penghuni</option>
                <option value="rusun_pemiliks" {{$row->table == 'rusun_pemiliks' ? 'selected' : ''}}>Pemilik</option>
                <option value="rusun_penghunis" {{$row->table == 'rusun_penghunis' ? 'selected' : ''}}>Penghuni</option>
            </x-adminlte-select>

            <x-adminlte-input name="username" id="username" label="Username" placeholder="Username" fgroup-class="col-md-6" value="{{$row->username}}" />
            <x-adminlte-input name="password" id="password" label="Password" placeholder="Password" fgroup-class="col-md-6" value="{{$row->password}}" />
            <x-adminlte-input name="endpoint" id="endpoint" label="Endpoint" placeholder="Endpoint" fgroup-class="col-md-12" value="{{$row->endpoint}}" />
            <x-adminlte-input name="keterangan" id="keterangan" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-12" value="{{$row->keterangan}}" />
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