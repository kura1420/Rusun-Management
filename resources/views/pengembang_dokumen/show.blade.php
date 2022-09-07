@extends('adminlte::page')

@section('title', $subTitle)

@section('content_header')
    <h1>
        {{$subTitle}}
        <x-adminlte-button label="Kembali" theme="dark" icon="fa fa-arrow-left" class="btn btn-xs" onClick="history.back()" />        
    </h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <x-adminlte-card theme="primary" theme-mode="outline" title="{{$row->pengembangs->nama}}">
            <h3 class="text-info">
                <i class="fa fa-building"></i>
                {{$row->rusuns->nama}}
            </h3>

            <br>

            <strong><i class="fas fa-book mr-1"></i> Dokumen Detail</strong>
            <p class="text-muted">{{$row->keterangan ?? '-'}}</p>

        </x-adminlte-card>
    </div>

    <div class="col-md-8">
        @if (isset($row->file) && $row->tersedia == TRUE)
        <x-adminlte-card theme="success" theme-mode="outline" title="{{$row->dokumens->nama}}">
            <embed type="application/pdf" src="{{route('pengembang-dokumen.view_file', [$row->id, $row->file])}}" width="100%" height="800"></embed>
        </x-adminlte-card>
        @else
        <x-adminlte-callout theme="danger" title="Peringatan">
            File tidak tersedia!
        </x-adminlte-callout>
        @endif
    </div>
</div>
@stop

@section('css')

@stop

@section('js')

@stop