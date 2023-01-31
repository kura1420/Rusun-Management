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

<form action="{{route('p3srs-kegiatan-laporan.store')}}?p3srs_kegiatan_jadwal_id={{$p3srs_kegiatan_jadwal->id}}" method="post" enctype="multipart/form-data">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('p3srs-kegiatan-laporan.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="rusun" id="rusun" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$p3srs_kegiatan_jadwal->rusuns->nama}}" disabled />
            <x-adminlte-input name="kegiatan" id="kegiatan" label="Kegiatan" placeholder="Kegiatan" fgroup-class="col-md-4" value="{{$p3srs_kegiatan_jadwal->p3srs_kegiatans->nama}}" disabled />
            <x-adminlte-input name="tanggal_kegiatan" id="tanggal_kegiatan" label="Tanggal Kegiatan" placeholder="Tanggal Kegiatan" fgroup-class="col-md-2" value="{{date('d M Y', strtotime($p3srs_kegiatan_jadwal->tanggal))}}" disabled />

            <x-adminlte-input name="judul" id="judul" label="Judul" placeholder="Judul" fgroup-class="col-md-10" value="{{old('judul')}}" />
            <x-adminlte-input-date name="tanggal" id="tanggal" label="Tanggal" placeholder="Tanggal" fgroup-class="col-md-2" :config="['format' => 'YYYY-MM-DD']" value="{{old('tanggal')}}" />

            <x-adminlte-text-editor name="penjelasan" id="penjelasan" label="Penjelasan" fgroup-class="col-md-12" :config="[
                'height' => '300',
            ]">
                @php echo old('penjelasan'); @endphp
            </x-adminlte-text-editor>

            <div class="form-group col-md-12">
                <x-adminlte-input type="file" name="dokumentasis[]" id="dokumentasis" label="Dokumentasi File" placeholder="Dokumentasi File" multiple>
                    <x-slot name="bottomSlot">
                        <small class="text-lightblue">*Maksimul file hanya 5</small>
                    </x-slot>
                </x-adminlte-input>
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