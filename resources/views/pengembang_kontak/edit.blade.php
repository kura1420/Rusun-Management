@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('pengembang-kontak.update', $row->id)}}" method="post">
    @csrf
    @method('PUT')
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <x-adminlte-button label="Kembali" theme="dark" icon="fa fa-arrow-left" class="btn btn-sm" onClick="history.back()" />
        </x-slot>
        
        <div class="row">
            <input type="hidden" name="redirect_to" value="{{$pengembang_id}}">
            
            <x-adminlte-select2 name="pengembang_id" label="Pengembang" fgroup-class="col-md-6" readonly :config="[
                'placeholder' => 'Pilih Pengembang',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($pengembangs as $pengembang)
                <option value="{{$pengembang->id}}" {{$pengembang->id == $row->pengembang_id || $pengembang_id == $row->pengembang_id ? 'selected' : ''}}>{{$pengembang->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-input name="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->nama}}" />
            <x-adminlte-input name="handphone" label="Handphone" placeholder="Handphone" fgroup-class="col-md-6" value="{{$row->handphone}}" />
            <x-adminlte-input type="email" name="email" label="Email" placeholder="Email" fgroup-class="col-md-6" value="{{$row->email}}" />

            <x-adminlte-select2 name="posisi" id="posisi" label="Posisi" fgroup-class="col-md-6" :config="[
                'placeholder' => 'Pilih Posisi',
                'allowClear' => true,
            ]">
                <option value=""></option>
                <option value="new">Tambah Posisi</option>
                @foreach ($posisis as $posisi)
                <option value="{{$posisi->posisi}}" {{$posisi->posisi == $row->posisi ? 'selected' : ''}}>{{$posisi->posisi}}</option>
                @endforeach
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
$(function () {
    $('#posisi').on('select2:select', function (e) {
        const value = $(this).val();
        
        if (value === 'new') {
            const promptValue = prompt('Tambah Posisi:');
            
            if (promptValue) {
                const data = {
                    id: promptValue,
                    text: promptValue,
                }

                const newOption = new Option(data.text, data.id, false, false);

                $('#posisi')
                    .append(newOption)
                    .trigger('change')
                    .val(promptValue);
            }
        }
    });
});
</script>
@stop