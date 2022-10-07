@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('dokumen.store')}}" method="post">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('dokumen.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="code" id="code" label="Kode" placeholder="Kode" fgroup-class="col-md-3" value="{{old('code')}}" />
            <x-adminlte-input name="nama" id="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{old('nama')}}" />

            <x-adminlte-select name="kepada" label="Kepada" placeholder="Kepada" fgroup-class="col-md-3">
                <option value="">Pilih</option>
                <option value="pengelola" {{old('kepada') == 'pengelola' ? 'selected' : ''}}>Pengelola</option>
                <option value="pengembang" {{old('kepada') == 'pengembang' ? 'selected' : ''}}>Pengembang</option>
                <option value="pemilik" {{old('kepada') == 'pemilik' ? 'selected' : ''}}>Pemilik</option>
                <option value="penghuni" {{old('kepada') == 'penghuni' ? 'selected' : ''}}>Penghuni</option>
            </x-adminlte-select>

            <x-adminlte-text-editor name="keterangan" id="keterangan" label="Keterangan" fgroup-class="col-md-12" :config="[
                'height' => '300',
                'placeholder' => 'Keterangan'
            ]">
                {{old('keterangan')}}
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