@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('rusun-detail.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <x-adminlte-button label="Kembali" theme="dark" icon="fa fa-arrow-left" class="btn btn-sm" onClick="history.back()" />
        </x-slot>
        
        <div class="row">
            <x-adminlte-select2 name="rusun_id" label="Rusun" fgroup-class="col-md-6" :config="[
                'placeholder' => 'Pilih Rusun',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($rusuns as $rusun)
                <option value="{{$rusun->id}}" {{$rusun->id == old('rusun_id') ? 'selected' : ''}}>{{$rusun->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-input name="nama_tower" label="Nama Tower" placeholder="Nama Tower" fgroup-class="col-md-6" value="{{old('nama_tower')}}" />
            <x-adminlte-input name="jumlah_unit" label="Jumlah Unit" placeholder="Jumlah Unit" fgroup-class="col-md-2" value="{{old('jumlah_unit')}}" />
            <x-adminlte-input name="jumlah_jenis_unit" label="Jumlah Jenis Unit" placeholder="Jumlah Jenis Unit" fgroup-class="col-md-2" value="{{old('jumlah_jenis_unit')}}" />
            <x-adminlte-input name="jumlah_lantai" label="Jumlah Lantai" placeholder="Jumlah Lantai" fgroup-class="col-md-2" value="{{old('jumlah_lantai')}}" />
            <x-adminlte-input name="ukuran_paling_kecil" label="Ukuran Paling Kecil" placeholder="Ukuran Paling Kecil" fgroup-class="col-md-2" value="{{old('ukuran_paling_kecil')}}" />
            <x-adminlte-input name="ukuran_paling_besar" label="Ukuran Paling Besar" placeholder="Ukuran Paling Besar" fgroup-class="col-md-2" value="{{old('ukuran_paling_besar')}}" />
            <x-adminlte-input type="file" name="foto" label="Foto" fgroup-class="col-md-2" value="{{old('foto')}}" />
            <x-adminlte-input name="keterangan" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-12" value="{{old('keterangan')}}" />
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