@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('pengelola-dokumen.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <x-adminlte-button label="Kembali" theme="dark" icon="fa fa-arrow-left" class="btn btn-sm" onClick="history.back()" />
        </x-slot>
        
        <div class="row">
            <input type="hidden" name="redirect_to" value="{{$pengelola_id}}">
            
            <x-adminlte-select2 name="pengelola_id" label="Pengelola" fgroup-class="col-md-4" readonly :config="[
                'placeholder' => 'Pilih Pengelola',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($pengelolas as $pengelola)
                <option value="{{$pengelola->id}}" {{$pengelola->id == old('pengelola_id') || $pengelola_id == $pengelola->id ? 'selected' : ''}}>{{$pengelola->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-select2 name="rusun_id" label="Rusun" fgroup-class="col-md-4" :config="[
                'placeholder' => 'Pilih Rusun',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($rusunPengelolas as $rusunPengelola)
                <option value="{{$rusunPengelola->rusun_id}}" {{$rusunPengelola->rusun_id == old('rusun_id') ? 'selected' : ''}}>{{$rusunPengelola->rusun->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-select2 name="dokumen_id" label="Dokumen" fgroup-class="col-md-4" :config="[
                'placeholder' => 'Pilih Dokumen',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($dokumens as $dokumen)
                <option value="{{$dokumen->id}}" {{$dokumen->id == old('dokumen_id') ? 'selected' : ''}}>{{$dokumen->nama}}</option>
                @endforeach
            </x-adminlte-select2>
            
            <x-adminlte-input type="file" name="file" label="File" placeholder="File" fgroup-class="col-md-6" value="{{old('file')}}">
                <x-slot name="bottomSlot">
                    <small class="text-lightblue">*Hanya file <strong>PDF</strong> dan Maksimal <strong>5MB</strong></small>
                </x-slot>
            </x-adminlte-input>
            
            <x-adminlte-input name="keterangan" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-6" value="{{old('keterangan')}}" />
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