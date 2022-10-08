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

<form action="{{route('p3srs-kegiatan-laporan.update', $row->id)}}?p3srs_kegiatan_jadwal_id={{$row->p3srs_kegiatan_jadwal_id}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('p3srs-kegiatan-laporan.show', $row->p3srs_kegiatan_jadwal_id)}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="rusun" id="rusun" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$row->rusuns->nama}}" disabled />
            <x-adminlte-input name="kegiatan" id="kegiatan" label="Kegiatan" placeholder="Kegiatan" fgroup-class="col-md-4" value="{{$row->p3srs_kegiatans->nama}}" disabled />
            <x-adminlte-input name="tanggal_kegiatan" id="tanggal_kegiatan" label="Tanggal Kegiatan" placeholder="Tanggal Kegiatan" fgroup-class="col-md-2" value="{{date('d M Y', strtotime($row->p3srs_kegiatan_jadwals->tanggal))}}" disabled />

            <x-adminlte-input name="judul" id="judul" label="Judul" placeholder="Judul" fgroup-class="col-md-10" value="{{$row->judul}}" />
            <x-adminlte-input-date name="tanggal" id="tanggal" label="Tanggal" placeholder="Tanggal" fgroup-class="col-md-2" :config="['format' => 'YYYY-MM-DD']" value="{{$row->tanggal}}" />

            <x-adminlte-text-editor name="penjelasan" id="penjelasan" label="Penjelasan" fgroup-class="col-md-12" :config="[
                'height' => '300',
            ]">
                @php echo $row->penjelasan; @endphp
            </x-adminlte-text-editor>

            <div class="form-group col-md-12">
                <x-adminlte-input type="file" name="dokumentasis[]" id="dokumentasis" label="Dokumentasi File" placeholder="Dokumentasi File" fgroup-class="col-md-12" multiple>
                    <x-slot name="bottomSlot">
                        <small class="text-lightblue">*Maksimul file hanya 5</small>
                    </x-slot>
                </x-adminlte-input>

                <br><br>

                <strong>File yang sudah di upload:</strong>
                <ul>
                    @foreach ($row->p3srs_kegiatan_dokumentasis as $key => $p3srs_kegiatan_dokumentasi) 
                    <li>
                        <a href="{{route('p3srs-kegiatan-laporan.dokumentasiViewFile', [$p3srs_kegiatan_dokumentasi->id, $p3srs_kegiatan_dokumentasi->filename])}}" target="_blank">View {{ $loop->iteration }}</a>
                    </li>
                    @endforeach
                </ul>
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