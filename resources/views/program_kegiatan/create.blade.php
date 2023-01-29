@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('program-kegiatan.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('program-kegiatan.index', ['program_id' => $program->id])}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <input type="hidden" name="rusun_id" id="rusun_id" value="{{$program->rusun_id}}">
            <input type="hidden" name="program_id" id="program_id" value="{{$program->id}}">

            <x-adminlte-input name="rusun" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$program->rusun->nama}}" disabled />
            <x-adminlte-input name="program" label="Program" placeholder="Program" fgroup-class="col-md-6" value="{{$program->nama}}" disabled />

            <x-adminlte-input name="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{old('nama')}}" />
            <x-adminlte-input-date name="tanggal_mulai" label="Tanggal Mulai" placeholder="Tgl. Mulai" :config="['format' => 'YYYY-MM-DD']" fgroup-class="col-md-2" value="{{old('tanggal_mulai')}}" />
            <x-adminlte-input-date name="tanggal_berakhir" label="Tanggal Akhir" placeholder="Tgl. Akhir" :config="['format' => 'YYYY-MM-DD']" fgroup-class="col-md-2" value="{{old('tanggal_berakhir')}}" />
            <x-adminlte-input type="file" name="file" label="Lampiran" placeholder="Lampiran" fgroup-class="col-md-2" />

            <x-adminlte-select name="template" label="Template" placeholder="Template" fgroup-class="col-md-12">
                <option value="">Pilih</option>
                <option value="form_pendaftaran" {{old('template') == 'form_pendaftaran' ? 'selected' : ''}}>Form Pendaftaran Kanidat</option>
                <option value="polling" {{old('template') == 'polling' ? 'selected' : ''}}>Polling</option>
                <option value="penetapan_hasil_pemilihan" {{old('template') == 'penetapan_hasil_pemilihan' ? 'selected' : ''}}>Penetapan Hasil Pemilihan</option>
            </x-adminlte-select>

            <x-adminlte-text-editor name="informasi" id="informasi" label="Informasi" fgroup-class="col-md-12" :config="[
                'height' => '300',
                'placeholder' => 'Informasi',
            ]">
                {{old('informasi')}}
            </x-adminlte-text-editor>

            <x-adminlte-text-editor name="syarat_ketentuan" id="syarat_ketentuan" label="Syarat & Ketentuan" fgroup-class="col-md-12" :config="[
                'height' => '300',
                'placeholder' => 'Syarat & Ketentuan',
            ]">
                {{old('syarat_ketentuan')}}
            </x-adminlte-text-editor>
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