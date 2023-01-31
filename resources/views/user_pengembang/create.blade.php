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

<form action="{{route('user-pengembang.store')}}" method="post">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('user-pengembang.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-select2 name="pengembang" label="Pengembang" fgroup-class="col-md-12" :config="[
                'placeholder' => 'Pilih Pengembang',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($pengembangs as $pengembang)
                <option value="{{$pengembang->id}}" {{$pengembang->id == old('pengembang') || $pengembang == $pengembang->id ? 'selected' : ''}}>{{$pengembang->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-input name="name" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{old('name')}}" />
            <x-adminlte-input name="username" label="Username" placeholder="Username" fgroup-class="col-md-6" value="{{old('username')}}" />
            <x-adminlte-input type="email" name="email" label="Email" placeholder="Email" fgroup-class="col-md-6" value="{{old('email')}}" />
            <x-adminlte-input name="password" label="Password" placeholder="Password" fgroup-class="col-md-6" value="{{config('app.user_password_default', 'RusunKita')}}" />
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