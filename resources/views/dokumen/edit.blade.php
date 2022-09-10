@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('dokumen.update', $row->id)}}" method="post">
    @csrf
    @method('PUT')
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('dokumen.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="code" id="code" label="Kode" placeholder="Kode" fgroup-class="col-md-3" value="{{$row->code}}" />
            <x-adminlte-input name="nama" id="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->nama}}" />

            <x-adminlte-select name="kepada" label="Kepada" placeholder="Kepada" fgroup-class="col-md-3">
                <option value="">Pilih</option>
                <option value="pengelola" {{$row->kepada == 'pengelola' ? 'selected' : ''}}>Pengelola</option>
                <option value="pengembang" {{$row->kepada == 'pengembang' ? 'selected' : ''}}>Pengembang</option>
                <option value="pemilik" {{$row->kepada == 'pemilik' ? 'selected' : ''}}>Pemilik</option>
                <option value="penghuni" {{$row->kepada == 'penghuni' ? 'selected' : ''}}>Penghuni</option>
            </x-adminlte-select>

            <x-adminlte-text-editor name="keterangan" id="keterangan" fgroup-class="col-md-12" :config="[
                'height' => '300',
            ]">
                {{$row->keterangan}}
            </x-adminlte-text-editor>
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