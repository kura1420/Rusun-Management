@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
@if (session()->has('success'))
<x-adminlte-alert theme="primary" title="Information" dismissable>
    {{session()->get('success')}}
</x-adminlte-alert>
@endif

<form action="{{route('faq.store')}}" method="post">
    @csrf
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('faq.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="judul" id="judul" label="Judul" placeholder="Judul" fgroup-class="col-md-12" value="{{old('judul')}}" />

            {{-- <x-adminlte-select2 id="kata_kunci" name="kata_kunci[]" label="Kata Kunci" fgroup-class="col-md-6"
                :config="[
                    'placeholder' => 'Masukkan kata kunci...',
                    'allowClear' => true,    
                    'tags' => true,
                ]" multiple>
            </x-adminlte-select2> --}}

            <x-adminlte-text-editor name="penjelasan" id="penjelasan" label="Penjelasan" fgroup-class="col-md-12" :config="[
                'height' => '300',
                'placeholder' => 'Penjelasan',
            ]">
                {{old('penjelasan')}}
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