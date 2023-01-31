@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{route('user-pengelola.update', $row->id)}}" method="post">
    @csrf
    @method('PUT')
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('user-pengelola.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-select2 name="pengelola" label="Pengelola" fgroup-class="col-md-12" :config="[
                'placeholder' => 'Pilih Pengelola',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($pengelolas as $pengelola)
                <option value="{{$pengelola->id}}" {{$pengelola->id == $row->user_mapping->reff_id || $pengelola == $pengelola->id ? 'selected' : ''}}>{{$pengelola->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-input name="name" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->name}}" />
            <x-adminlte-input name="username" label="Username" placeholder="Username" fgroup-class="col-md-6" value="{{$row->username}}" />
            <x-adminlte-input type="email" name="email" label="Email" placeholder="Email" fgroup-class="col-md-6" value="{{$row->email}}" />
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