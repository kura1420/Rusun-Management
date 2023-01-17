@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('program-kanidat-dokumen.update', $row->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('program-kanidat-dokumen.index', ['program_kanidat_id' => $row->program_kanidat_id])}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <input type="hidden" name="program_kanidat_id" id="program_kanidat_id" value="{{$row->program_kanidat_id}}" />
            <x-adminlte-select2 name="program_dokumen_id" label="Dokumen" fgroup-class="col-md-6" :config="[
                    'placeholder' => 'Pilih',
                    'allowClear' => true,
                ]">
                    <option value=""></option>
                    @foreach ($programDokumens as $programDokumen)
                    <option value="{{$programDokumen->id}}" {{$programDokumen->id == $row->program_dokumen_id ? 'selected' : ''}}>{{$programDokumen->nama}}</option>
                    @endforeach
            </x-adminlte-select2>
            <x-adminlte-input type="file" name="file" label="File" placeholder="File" fgroup-class="col-md-6" />
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