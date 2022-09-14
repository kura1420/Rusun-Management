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
            <x-adminlte-input name="keterangan" id="keterangan" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-6" value="{{$row->keterangan}}" disabled />
            <x-adminlte-input name="username" id="username" label="Username" placeholder="Username" fgroup-class="col-md-6" value="{{$row->username}}" />
            <x-adminlte-input name="password" id="password" label="Password" placeholder="Password" fgroup-class="col-md-6" value="{{$row->password}}" />
            <x-adminlte-input name="endpoint" id="endpoint" label="Endpoint" placeholder="Endpoint" fgroup-class="col-md-6" value="{{$row->endpoint}}" />
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