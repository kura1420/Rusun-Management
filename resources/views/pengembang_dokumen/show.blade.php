@extends('adminlte::page')

@section('title', $subTitle)

@section('content_header')
    <h1>
        {{$subTitle}}

        @role('Root|Admin|Pengelola')
        <x-adminlte-button label="Kembali" theme="dark" icon="fa fa-arrow-left" class="btn btn-xs" onClick="history.back()" />        
        @endrole
    </h1>
@stop

@section('content')
@if (session()->has('success'))
<x-adminlte-alert theme="primary" title="Information" dismissable>
    {{session()->get('success')}}
</x-adminlte-alert>
@endif

<div class="row">
    <div class="col-md-4">
        <x-adminlte-card theme="primary" theme-mode="outline" title="{{$row->pengembangs->nama}}">
            <h3 class="text-info">
                <i class="fa fa-building"></i>
                {{$row->rusuns->nama}}
            </h3>

            <br>

            <strong><i class="fas fa-book mr-1"></i>Detail</strong>
            <p class="text-muted">{{$row->keterangan ?? '-'}}</p>

            <strong><i class="fas fa-clock mr-1"></i> Status</strong>
            <p class="text-muted">{{$row->status_text}}</p>

            @if ($row->status == 2)
            <strong><i class="fas fa-pencil-alt mr-1"></i> Penjelasan Dari Verifikator</strong>
            <p class="text-muted">{{$row->keterangan_ditolak}}</p>
            @endif

        </x-adminlte-card>

        @can('Verif Dokumen')
        <x-adminlte-card theme="danger" theme-mode="outline" title="Verifikasi Dokumen">
            <form action="{{route('pengembang-dokumen.verif', $row->id)}}" method="post">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-adminlte-textarea name="keterangan_ditolak" id="keterangan_ditolak" rows=5 placeholder="Keterangan Verifikasi Dokumen"/>

            <x-adminlte-select name="status" id="status">
                <option value="2">Ditolak</option>
                <option value="1">Diterima</option>
            </x-adminlte-select>

            <x-adminlte-button type="submit" label="Button" class="btn-block" theme="danger" label="Verif" />

            </form>
        </x-adminlte-card>
        @endcan
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