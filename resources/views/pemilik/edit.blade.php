@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('pemilik.update', $row->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('pemilik.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->nama}}" />
            <x-adminlte-input type="email" name="email" label="Email" placeholder="Email" fgroup-class="col-md-6" value="{{$row->email}}" />
            <x-adminlte-input name="phone" label="Phone" placeholder="Phone" fgroup-class="col-md-6" value="{{$row->phone}}" />
            <x-adminlte-input name="identitas_nomor" label="Identitas Nomor" placeholder="Identitas Nomor" fgroup-class="col-md-2" value="{{$row->identitas_nomor}}" />
            <x-adminlte-select name="identitas_tipe" label="Identitas Tipe" placeholder="Identitas Tipe" fgroup-class="col-md-2">
                <option value="KTP" {{$row->identitas_tipe == 'KTP' ? 'selected' : ''}}>KTP</option>
                <option value="PASSPORT" {{$row->identitas_tipe == 'PASSPORT' ? 'selected' : ''}}>PASSPORT</option>
            </x-adminlte-select>

            <x-adminlte-input type="file" name="identitas_file" label="Identitas File" placeholder="Identitas File" fgroup-class="col-md-2" >
                <x-slot name="bottomSlot">
                    <small class="text-lightblue">*Maksimal <strong>5MB</strong></small>
                </x-slot>
            </x-adminlte-input>
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