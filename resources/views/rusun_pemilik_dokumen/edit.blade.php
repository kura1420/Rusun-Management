@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('rusun-pemilik-dokumen.update', $row->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <x-adminlte-button label="Kembali" theme="dark" icon="fa fa-arrow-left" class="btn btn-sm" onClick="history.back()" />
        </x-slot>
        
        <div class="row">                    
            <x-adminlte-select2 name="rusun_unit_detail_id" id="rusun_unit_detail_id" label="Tower & Unit" fgroup-class="col-md-6">
                <option value=""></option>
            </x-adminlte-select2>

            <x-adminlte-select2 name="dokumen_id" label="Dokumen" fgroup-class="col-md-6" :config="[
                'placeholder' => 'Pilih Dokumen',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($dokumens as $dokumen)
                <option value="{{$dokumen->id}}" {{$dokumen->id == $row->dokumen_id ? 'selected' : ''}}>{{$dokumen->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-input type="file" name="file" label="Upload File" placeholder="Upload File" fgroup-class="col-md-6" >
                <x-slot name="bottomSlot">
                    <small class="text-lightblue">*Maksimal <strong>5MB</strong></small>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input name="keterangan" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-6" value="{{$row->keterangan}}" />
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
    $('#rusun_unit_detail_id').select2({
        placeholder: 'Pilih Tower - Unit',
        data: {{Js::from($pemilik->rusun_pemilik_groups)}}
    });
    $('#rusun_unit_detail_id').val('{{$row->rusun_unit_detail_id}}').trigger('change');
});
</script>
@stop