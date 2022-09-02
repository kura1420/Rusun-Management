@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<form action="{{route('pengelola-kontak.store')}}" method="post">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <x-adminlte-button label="Kembali" theme="dark" icon="fa fa-arrow-left" class="btn btn-sm" onClick="history.back()" />
        </x-slot>
        
        <div class="row">
            <input type="hidden" name="redirect_to" value="{{$pengelola_id}}">
            
            <x-adminlte-select2 name="pengelola_id" label="Pengelola" fgroup-class="col-md-6" :config="[
                'placeholder' => 'Pilih Pengelola',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($pengelolas as $pengelola)
                <option value="{{$pengelola->id}}" {{$pengelola->id == old('pengelola_id') || $pengelola_id == $pengelola->id ? 'selected' : ''}}>{{$pengelola->nama}}</option>
                @endforeach
            </x-adminlte-select2>

            <x-adminlte-input name="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{old('nama')}}" />
            <x-adminlte-input name="handphone" label="Handphone" placeholder="Handphone" fgroup-class="col-md-6" value="{{old('handphone')}}" />
            <x-adminlte-input type="email" name="email" label="Email" placeholder="Email" fgroup-class="col-md-6" value="{{old('email')}}" />

            <x-adminlte-select2 name="posisi" id="posisi" label="Posisi" fgroup-class="col-md-6" :config="[
                'placeholder' => 'Pilih Posisi',
                'allowClear' => true,
            ]">
                <option value=""></option>
                <option value="new">Tambah Posisi</option>
                @foreach ($posisis as $posisi)
                <option value="{{$posisi->posisi}}" {{$posisi->posisi == old('posisi') ? 'selected' : ''}}>{{$posisi->posisi}}</option>
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