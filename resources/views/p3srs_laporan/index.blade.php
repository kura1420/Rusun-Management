@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
@if (session()->has('success'))
<x-adminlte-alert theme="primary" title="Information" dismissable>
    {{session()->get('success')}}
</x-adminlte-alert>
@endif

<x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">    
    <x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered />
</x-adminlte-card>
@stop

@section('css')

@stop

@section('js')

@stop