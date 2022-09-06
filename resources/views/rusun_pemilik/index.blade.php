@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">  
    <x-slot name="toolsSlot">
        Last Update: {{$lastUpdate[5] ?? '-'}}
    </x-slot>

    <x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered beautify />
</x-adminlte-card>
@stop

@section('css')

@stop

@section('js')

@stop