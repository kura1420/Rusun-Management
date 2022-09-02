@extends('adminlte::page')

@section('title', $subTitle)

@section('content_header')
    <h1>
        {{$subTitle}}
        <a href="{{route('rusun-detail.index')}}" class="btn btn-xs btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
    </h1>
@stop

@section('content')
<x-adminlte-card theme="primary" theme-mode="outline">
    <div class="row">
        <div class="col-12 col-sm-4">
            <h3 class="d-inline-block d-sm-none">{{$row->nama_tower}}</h3>
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
            <h3 class="my-3">
                {{$row->nama_tower}}
                <small class="text-muted"><i>{{$row->rusuns->nama}}</i></small>
            </h3>
            
            <p>{{$row->keterangan}}</p>
            <p>
                <strong>Ukuran Paling Kecil:</strong> {{$row->ukuran_paling_kecil}} <br>
                <strong>Ukurang Paling Besar:</strong> {{$row->ukuran_paling_besar}}
            </p>
            <hr>

            <h4 class="mt-3">Total</h4>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-default text-center">
                    <input type="radio" name="color_option" id="color_option_b1" autocomplete="off" />
                    <span class="text-xl">
                        {{$row->jumlah_unit}}
                    </span>
                    <br />
                    Unit
                </label>
                <label class="btn btn-default text-center">
                    <input type="radio" name="color_option" id="color_option_b2" autocomplete="off" />
                    <span class="text-xl">
                        {{$row->jumlah_jenis_unit}}
                    </span>
                    <br />
                    Jenis
                </label>
                <label class="btn btn-default text-center">
                    <input type="radio" name="color_option" id="color_option_b2" autocomplete="off" />
                    <span class="text-xl">
                        {{$row->jumlah_lantai}}
                    </span>
                    <br />
                    Lantai
                </label>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <nav class="w-100">
            <div class="nav nav-tabs" id="product-tab" role="tablist">
                <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Unit</a>
            </div>
        </nav>
        <div class="tab-content p-3" id="nav-tabContent">
            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi vitae condimentum erat. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed posuere, purus at efficitur hendrerit, augue elit
                lacinia arcu, a eleifend sem elit et nunc. Sed rutrum vestibulum est, sit amet cursus dolor fermentum vel. Suspendisse mi nibh, congue et ante et, commodo mattis lacus. Duis varius finibus purus sed venenatis. Vivamus varius
                metus quam, id dapibus velit mattis eu. Praesent et semper risus. Vestibulum erat erat, condimentum at elit at, bibendum placerat orci. Nullam gravida velit mauris, in pellentesque urna pellentesque viverra. Nullam non
                pellentesque justo, et ultricies neque. Praesent vel metus rutrum, tempus erat a, rutrum ante. Quisque interdum efficitur nunc vitae consectetur. Suspendisse venenatis, tortor non convallis interdum, urna mi molestie eros, vel
                tempor justo lacus ac justo. Fusce id enim a erat fringilla sollicitudin ultrices vel metus.
            </div>
        </div>
    </div>
</x-adminlte-card>
@stop

@section('css')

@stop

@section('js')

@stop