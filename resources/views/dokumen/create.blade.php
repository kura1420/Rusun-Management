@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('dokumen.store')}}" method="post">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('dokumen.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="singkatan" id="singkatan" label="Singkatan" placeholder="Singkatan" fgroup-class="col-md-3" value="{{old('singkatan')}}" />
            <x-adminlte-input name="nama" id="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{old('nama')}}" />

            <div class="form-group col-md-3">
                <label for="kepada">
                    Ditujukan Kepada
                </label>

                <div class="input-group">
                    <select id="kepada" name="kepada[]" class="form-control" placeholder="Ditujukan Kepada" multiple>
                        <option value="pengelola">Pengelola</option>
                        <option value="pengembang">Pengembang</option>
                        <option value="pemilik">Pemilik</option>
                        <option value="penghuni">Penghuni</option>
                    </select>
                </div>
            </div>

            <x-adminlte-text-editor name="keterangan" id="keterangan" label="Keterangan" fgroup-class="col-md-12" :config="[
                'height' => '300',
                'placeholder' => 'Keterangan'
            ]">
                {{old('keterangan')}}
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
<script>
$(document).ready(function () {
    $('#kepada').select2();
});
</script>
@stop