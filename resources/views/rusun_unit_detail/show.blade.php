@extends('adminlte::page')

@section('title', $subTitle)

@section('content_header')
    <h1>
        {{$subTitle}}
        <a href="{{route('rusun-unit-detail.index')}}" class="btn btn-xs btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
    </h1>
@stop

@section('content')
<x-adminlte-card theme="primary" theme-mode="outline">
    <div class="row">
        <div class="col-12 col-sm-4">
            <h3 class="d-inline-block d-sm-none">{{$row->ukuran}}</h3>
            @if ($row->foto) 
            <div class="col-12">
                <img src="{{$row->foto}}" class="product-image">
            </div>
            @else 
            <div class="col-12">
                <img src="{{asset('images/no-image.jpg')}}" class="product-image" alt="No Image">
            </div>
            @endif
        </div>
        <div class="col-12 col-sm-8">
            <h3 class="my-3">{{$row->ukuran}}</h3>
            <p>
                <strong>Rusun:</strong> {{$row->rusuns->nama}} <br>
                <strong>Tower:</strong> {{$row->rusun_details->nama_tower}} <br>
                <strong>Jumlah:</strong> {{$row->jumlah}}
            </p>
            <p>{{$row->keterangan}}</p>
        </div>
    </div>
</x-adminlte-card>
@stop

@section('css')

@stop

@section('js')

@stop