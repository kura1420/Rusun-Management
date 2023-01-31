@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('user.update', $row->id)}}" method="post">
    @csrf
    @method('PUT')

    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('user.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="name" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->name}}" />
            <x-adminlte-input name="username" label="Username" placeholder="Username" fgroup-class="col-md-6" value="{{$row->username}}" disabled />
            <x-adminlte-input type="email" name="email" label="Email" placeholder="Email" fgroup-class="col-md-6" value="{{$row->email}}" />
            <x-adminlte-input type="password" name="password" label="Password" placeholder="Password" fgroup-class="col-md-6" />
        </div>
            <x-adminlte-input-switch name="active" data-on-color="success" data-off-color="danger" :config="['state' => $row->active ? true : false]" />

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