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

<form action="{{route('user-pemilik.update', $row->id)}}" method="post">
    @csrf
    @method('PUT')
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('user-pemilik.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-select2 name="pemilik" label="Pemilik" fgroup-class="col-md-12" readonly :config="[
                'placeholder' => 'Pilih Pemilik',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($pemiliks as $pemilik)
                <option value="{{$pemilik->id}}" {{$pemilik->id == $row->user_mapping->reff_id || $pemilik == $pemilik->id ? 'selected' : ''}}>{{$pemilik->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-input name="name" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->name}}" readonly />
            <x-adminlte-input name="username" label="Username" placeholder="Username" fgroup-class="col-md-6" value="{{$row->username}}" />
            <x-adminlte-input type="email" name="email" label="Email" placeholder="Email" fgroup-class="col-md-6" value="{{$row->email}}" />

            <div class="form-group col-md-6">
                <label for="email">
                    Password Default
                </label>

                <div class="input-group">
                    {{config('app.user_password_default', 'RusunKT@2022')}}
                </div>
            </div>
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