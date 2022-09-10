@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('rusun-pembayaran-ipl.store')}}" method="post">
    @csrf

    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <x-adminlte-button label="Kembali" theme="dark" icon="fa fa-arrow-left" class="btn btn-sm" onClick="history.back()" />
        </x-slot>
        
        <div class="row">        
            <input type="hidden" name="pemilik_id" value="{{$pemilik_id}}">  

            <x-adminlte-input-switch name="pemilik_bayar" data-on-text="Ya" data-off-text="Tidak" label="Pemilik Bayar" />

            <x-adminlte-select2 name="rusun_unit_detail_id" id="rusun_unit_detail_id" label="Penghuni Rusun" fgroup-class="col-md-6">
                <option value=""></option>
            </x-adminlte-select2>
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
        placeholder: 'Pilih Penghuni - Rusun',
        data: {{Js::from($rusunPenghuniToIPL)}}
    });
});
</script>
@stop