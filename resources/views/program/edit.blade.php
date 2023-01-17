@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('program.update', $row->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('program.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-select2 name="rusun_id" label="Rusun" fgroup-class="col-md-6" :config="[
                    'placeholder' => 'Pilih Rusun',
                    'allowClear' => true,
                ]">
                    <option value=""></option>
                    @foreach ($rusuns as $rusun)
                    <option value="{{$rusun->id}}" {{$rusun->id == $row->rusun_id ? 'selected' : ''}}>{{$rusun->nama}}</option>
                    @endforeach
            </x-adminlte-select2>

            <x-adminlte-select2 name="tahun" label="Tahun Awal Jabatan" fgroup-class="col-md-3" :config="[
                    'placeholder' => 'Pilih Tahun',
                    'allowClear' => true,
                ]">
                    <option value=""></option>
                    @foreach ($tahuns as $tahun)
                    <option value="{{$tahun}}" {{$tahun == $row->tahun ? 'selected' : ''}}>{{$tahun}}</option>
                    @endforeach
            </x-adminlte-select2>

            <x-adminlte-input type="number" name="periode" id="periode" label="Periode Jabatan (Tahun)" placeholder="Periode Jabatan (Tahun)" fgroup-class="col-md-3" value="{{$row->periode}}" />

            <x-adminlte-input name="nama" id="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->nama}}" />
            
            <x-adminlte-input type="file" name="file" label="File" placeholder="File" fgroup-class="col-md-5" value="{{old('file')}}">
                <x-slot name="bottomSlot">
                    <small class="text-lightblue">*Hanya file <strong>PDF</strong> dan Maksimal <strong>5MB</strong></small>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input-switch name="publish" label="Publish" data-on-color="success" data-off-color="danger" />
            
            <x-adminlte-text-editor name="keterangan" id="keterangan" label="Keterangan" fgroup-class="col-md-12" :config="[
                'height' => '300',
                'placeholder' => 'Keterangan',
            ]">
                @php echo $row->keterangan; @endphp
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