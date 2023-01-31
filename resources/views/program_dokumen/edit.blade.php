@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('program-dokumen.update', $row->id)}}" method="post">
    @csrf
    @method('PUT')
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('program-dokumen.index', ['program_id' => $row->program->id])}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <input type="hidden" name="rusun_id" id="rusun_id" value="{{$row->program->rusun_id}}">
            <input type="hidden" name="program_id" id="program_id" value="{{$row->program->id}}">

            <x-adminlte-input name="rusun" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$row->program->rusun->nama}}" disabled />
            <x-adminlte-input name="program" label="Program" placeholder="Program" fgroup-class="col-md-6" value="{{$row->program->nama}}" disabled />

            <x-adminlte-input name="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->nama}}" />
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