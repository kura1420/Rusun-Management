@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('informasi-halaman.update', $row->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('informasi-halaman.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-select name="halaman_nama" id="halaman_nama" label="Halaman" placeholder="Halaman" fgroup-class="col-md-2">
                <option value="">Pilih Halaman</option>
                @foreach ($halamans as $key => $halaman)
                <option value="{{$halaman}}" {{$row->halaman_nama == $halaman ? 'selected' : ''}}>{{ucfirst($halaman)}}</option>
                @endforeach
            </x-adminlte-select>

            <x-adminlte-select name="halaman_aksi" id="halaman_aksi" label="Aksi" placeholder="Aksi" fgroup-class="col-md-2">
                <option value="">Pilih Aksi</option>
                @foreach ($aksis as $key => $aksi)
                <option value="{{$aksi}}" {{$row->halaman_aksi == $aksi ? 'selected' : ''}}>{{ucfirst($aksi)}}</option>
                @endforeach
            </x-adminlte-select>

            <x-adminlte-input name="judul" id="judul" label="Judul" placeholder="Judul" fgroup-class="col-md-8" value="{{$row->judul}}" />

            <x-adminlte-text-editor name="penjelasan" id="penjelasan" label="Penjelasan" placeholder="Penjelasan" fgroup-class="col-md-12" :config="[
                'height' => '300',
            ]">
                @php echo $row->penjelasan; @endphp
            </x-adminlte-text-editor>

            <x-adminlte-input type="file" name="file" id="file" label="File" placeholder="File" fgroup-class="col-md-12" />
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