@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<x-adminlte-callout theme="warning" title="Perhatian">
    User masuk ke aplikasi secara otomatis dibuat ketika data melakukan singkronisasi ke API, default password yang digunakan adalah <strong>{{config('app.user_password_default', 'RusunKT@2022')}}</strong>. <br>
    Silahkan menginformasikan akses masuk ke aplikasi berdasarkan username/email dan passwordnya.
</x-adminlte-callout>

@if (session()->has('success'))
<x-adminlte-alert theme="primary" title="Information" dismissable>
    {{session()->get('success')}}
</x-adminlte-alert>
@endif

<x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
    <x-slot name="toolsSlot">
        
    </x-slot>
    
    <x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered beautify />
</x-adminlte-card>
@stop

@section('css')

@stop

@section('js')

@stop