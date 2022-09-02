@extends('adminlte::page')

@section('title', $subTitle)

@section('content_header')
    <h1>
        {{$subTitle}}
        <a href="{{route('rusun.index')}}" class="btn btn-xs btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
    </h1>
@stop

@section('content')
<x-adminlte-card theme="primary" theme-mode="outline">
    <div class="row">
        <div class="col-12 col-sm-4">
            <h3 class="d-inline-block d-sm-none">{{$row->nama}}</h3>
            @if (count($fotos)>0) 
            <div class="col-12">
                <img src="{{$row->foto_1}}" class="product-image">
            </div>
            <div class="col-md-12 product-image-thumbs"> 
                <div class="product-image-thumb active"><img src="{{$row->foto_1}}"></div>
                <div class="product-image-thumb"><img src="{{$row->foto_2}}"></div>
                <div class="product-image-thumb"><img src="{{$row->foto_3}}"></div>
            </div>
            @else 
            <div class="col-12">
                <img src="{{asset('images/no-image.jpg')}}" class="product-image" alt="No Image">
            </div>
            @endif
        </div>
        <div class="col-12 col-sm-8">
            <h3 class="my-3">{{$row->nama}}</h3>
            <p>
                {{$row->alamat}}. {{$row->kecamatans->name}}, {{$row->desas->name ?? NULL}}  @if ($row->kode_pos) KODEPOS: {{$row->kode_pos}} @endif. {{$row->kotas->name}}, {{$row->provinces->name}}
            </p>
            <hr>

            <h4 class="mt-3">Total</h4>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-default text-center">
                    <input type="radio" name="color_option" id="color_option_b1" autocomplete="off" />
                    <span class="text-xl">
                        {{$row->total_tower}}
                    </span>
                    <br />
                    Tower
                </label>
                <label class="btn btn-default text-center">
                    <input type="radio" name="color_option" id="color_option_b2" autocomplete="off" />
                    <span class="text-xl">
                        {{$row->total_unit}}
                    </span>
                    <br />
                    Unit
                </label>
            </div>

            <div class="mt-4">
                @if ($row->website)
                <a href="{{$row->website}}" target="_blank" class="btn btn-info btn-lg btn-flat">
                    <i class="fas fa-globe fa-lg mr-2"></i>
                    Website Official
                </a>
                @endif

                @if ($row->telp)
                <a href="telp:{{$row->telp}}" class="btn btn-default btn-lg btn-flat">
                    <i class="fas fa-phone fa-lg mr-2"></i>
                    Hotline: {{$row->telp}}
                </a>
                @endif
            </div>

            <div class="mt-4 product-share">
                @if ($row->facebook)
                <a href="{{$row->facebook}}" class="text-primary">
                    <i class="fab fa-facebook-square fa-2x"></i>
                </a>
                @endif

                @if ($row->instgram)
                <a href="{{$row->instgram}}" class="text-pink">
                    <i class="fab fa-instagram-square fa-2x"></i>
                </a>
                @endif

                @if ($row->email)
                <a href="mailto:{{$row->email}}" class="text-gray">
                    <i class="fas fa-envelope-square fa-2x"></i>
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <nav class="w-100">
                <div class="nav nav-tabs" id="product-tab" role="tablist">
                    <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Tower</a>
                    <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Unit</a>
                    <a class="nav-item nav-link" id="product-fasilitas-tab" data-toggle="tab" href="#product-fasilitas" role="tab" aria-controls="product-fasilitas" aria-selected="false">Fasilitas</a>
                </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                    <x-adminlte-datatable id="table1" :heads="[
                            'Nama Tower',
                            'Jml. Unit',
                            'Jml. Jenis',
                            'Jml. Lantai',
                            ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                        ]">
                            @foreach($row->rusun_details as $rusun_detail)
                                <tr>
                                    <td>{{$rusun_detail->nama_tower}}</td>
                                    <td>{{$rusun_detail->jumlah_unit}}</td>
                                    <td>{{$rusun_detail->jumlah_jenis_unit}}</td>
                                    <td>{{$rusun_detail->jumlah_lantai}}</td>
                                    <td>
                                        <a href="{{route('rusun-detail.show', $rusun_detail->id)}}?pengelola_id={{$row->id}}" class="btn btn-success btn-xs" title="Show"><i class="fas fa-eye"></i> Detail</a>
                                        <a href="{{route('rusun-detail.edit', $rusun_detail->id)}}?pengelola_id={{$row->id}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-xs btnDeleteDokumen" value="{{$rusun_detail->id}}" id="{{route('rusun-detail.destroy', $rusun_detail->id)}}"><i class="fas fa-trash"></i> Hapus</button>                                        
                                    </td>
                                </tr>
                            @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                    <x-adminlte-datatable id="table1" :heads="[
                            'Nama Tower',
                            'Jml. Unit',
                            'Jml. Jenis',
                            'Jml. Lantai',
                            ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                        ]">
                            @foreach($row->rusun_details as $rusun_detail)
                                <tr>
                                    <td>{{$rusun_detail->nama_tower}}</td>
                                    <td>{{$rusun_detail->jumlah_unit}}</td>
                                    <td>{{$rusun_detail->jumlah_jenis_unit}}</td>
                                    <td>{{$rusun_detail->jumlah_lantai}}</td>
                                    <td>
                                        <a href="{{route('rusun-detail.show', $rusun_detail->id)}}?pengelola_id={{$row->id}}" class="btn btn-success btn-xs" title="Show"><i class="fas fa-eye"></i> Detail</a>
                                        <a href="{{route('rusun-detail.edit', $rusun_detail->id)}}?pengelola_id={{$row->id}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-xs btnDeleteDokumen" value="{{$rusun_detail->id}}" id="{{route('rusun-detail.destroy', $rusun_detail->id)}}"><i class="fas fa-trash"></i> Hapus</button>                                        
                                    </td>
                                </tr>
                            @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane fade" id="product-fasilitas" role="tabpanel" aria-labelledby="product-fasilitas-tab">
                    fasilitas
                </div>
            </div>
        </div>
    </div>
</x-adminlte-card>
@stop

@section('css')

@stop

@section('js')
<script>
$(function () {
    $('.product-image-thumb').on('click', function () {
        var $image_element = $(this).find('img')
        $('.product-image').prop('src', $image_element.attr('src'))
        $('.product-image-thumb.active').removeClass('active')
        $(this).addClass('active')
    });
});
</script>
@stop