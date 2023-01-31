@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('program-jabatan.store')}}" method="post">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('program-jabatan.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-select2 name="rusun_id" label="Rusun" fgroup-class="col-md-6" :config="[
                    'placeholder' => 'Pilih Rusun',
                    'allowClear' => true,
                ]">
                    <option value=""></option>
                    @foreach ($rusuns as $rusun)
                    <option value="{{$rusun->id}}" {{$rusun->id == old('rusun_id') ? 'selected' : ''}}>{{$rusun->nama}}</option>
                    @endforeach
            </x-adminlte-select2>
            <x-adminlte-input name="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{old('nama')}}" />
            <x-adminlte-input name="keterangan" id="keterangan" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-12" value="{{old('keterangan')}}" />
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