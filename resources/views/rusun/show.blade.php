@extends('adminlte::page')

@section('title', $subTitle)

@section('content_header')
    <h1>
        {{$subTitle}}
        <a href="{{route('rusun.index')}}" class="btn btn-xs btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
        <a href="{{route('rusun.edit', $row->id)}}" class="btn btn-xs btn-warning"><i class="fa fa-pencil-alt"></i> Edit</a>

        <div class="btn-group">
            <button type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Tambah</button>
            <button type="button" class="btn btn-xs btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
                {{-- <a class="dropdown-item" href="{{route('rusun-detail.create')}}?rusun_id={{$row->id}}">Detail</a> --}}
                {{-- <a class="dropdown-item" href="{{route('rusun-unit-detail.create')}}?rusun_id={{$row->id}}">Unit</a> --}}
                <a class="dropdown-item" href="{{route('rusun-fasilitas.create')}}?rusun_id={{$row->id}}">Fasilitas</a>
            </div>
        </div>
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
                    <a class="nav-item nav-link" id="pengembang-tab" data-toggle="tab" href="#pengembang" role="tab" aria-controls="pengembang" aria-selected="false">Pengembang</a>
                    <a class="nav-item nav-link" id="pengelola-tab" data-toggle="tab" href="#pengelola" role="tab" aria-controls="pengelola" aria-selected="false">Pengelola</a>
                    <a class="nav-item nav-link" id="tarif-tab" data-toggle="tab" href="#tarif" role="tab" aria-controls="tarif" aria-selected="false">Tarif</a>
                    <a class="nav-item nav-link" id="outstanding-tab" data-toggle="tab" href="#outstanding" role="tab" aria-controls="outstanding" aria-selected="false">Outstanding Pemilik/Penghuni</a>
                </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                    <x-adminlte-datatable id="tableTower" :heads="[
                            'Nama Tower',
                            'Jml. Unit',
                            // 'Jml. Jenis',
                            // 'Jml. Lantai',
                            // ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                        ]">
                            @foreach($row->rusun_details as $rusun_detail)
                                <tr>
                                    <td>{{$rusun_detail->nama_tower}}</td>
                                    <td>{{$rusun_detail->jumlah_unit}}</td>
                                    <!-- <td>{{$rusun_detail->jumlah_jenis_unit}}</td>
                                    <td>{{$rusun_detail->jumlah_lantai}}</td>
                                    <td>
                                        <a href="{{route('rusun-detail.show', $rusun_detail->id)}}?rusun_id={{$row->id}}" class="btn btn-success btn-xs" title="Show"><i class="fas fa-eye"></i> Detail</a>
                                        <a href="{{route('rusun-detail.edit', $rusun_detail->id)}}?rusun_id={{$row->id}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-xs btnDeleteTower" value="{{$rusun_detail->id}}" id="{{route('rusun-detail.destroy', $rusun_detail->id)}}"><i class="fas fa-trash"></i> Hapus</button>                                         
                                    </td>-->
                                </tr>
                            @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                    <x-adminlte-datatable id="tableUnit" :heads="[
                            'Tower',
                            'Jenis',
                            // 'Ukuran',
                            // 'Jumlah',
                            // 'Keterangan',
                            // ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                        ]">
                            @foreach($row->rusun_unit_details as $rusun_unit_detail)
                                <tr>
                                    <td>{{$rusun_unit_detail->rusun_details->nama_tower}}</td>
                                    <td>{{$rusun_unit_detail->jenis}}</td>
                                    <!-- <td>{{$rusun_unit_detail->ukuran}}</td> -->
                                    <!-- <td>{{$rusun_unit_detail->jumlah}}</td> -->
                                    <!-- <td>{{$rusun_unit_detail->keterangan}}</td> -->
                                    <!-- <td>
                                        <a href="{{route('rusun-unit-detail.show', $rusun_unit_detail->id)}}?rusun_id={{$row->id}}" class="btn btn-success btn-xs" title="Show"><i class="fas fa-eye"></i> Detail</a>
                                        <a href="{{route('rusun-unit-detail.edit', $rusun_unit_detail->id)}}?rusun_id={{$row->id}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-xs btnDeleteUnit" value="{{$rusun_unit_detail->id}}" id="{{route('rusun-unit-detail.destroy', $rusun_unit_detail->id)}}"><i class="fas fa-trash"></i> Hapus</button>                                         
                                    </td> -->
                                </tr>
                            @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane fade" id="product-fasilitas" role="tabpanel" aria-labelledby="product-fasilitas-tab">
                    <x-adminlte-datatable id="tableFasilitas" :heads="[
                            'Tower',
                            'Nama',
                            'Jumlah',
                            'Keterangan',
                            ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                        ]">
                            @foreach($row->rusun_fasilitas as $rusun_fasilitas)
                                <tr>
                                    <td>{{$rusun_fasilitas->rusun_details->nama_tower ?? NULL}}</td>
                                    <td>{{$rusun_fasilitas->nama}}</td>
                                    <td>{{$rusun_fasilitas->jumlah}}</td>
                                    <td>{{$rusun_fasilitas->keterangan}}</td>
                                    <td>
                                        <a href="{{route('rusun-fasilitas.show', $rusun_fasilitas->id)}}?rusun_id={{$row->id}}" class="btn btn-success btn-xs" title="Show"><i class="fas fa-eye"></i> Detail</a>
                                        <a href="{{route('rusun-fasilitas.edit', $rusun_fasilitas->id)}}?rusun_id={{$row->id}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-xs btnDeleteFasilitas" value="{{$rusun_fasilitas->id}}" id="{{route('rusun-fasilitas.destroy', $rusun_fasilitas->id)}}"><i class="fas fa-trash"></i> Hapus</button>                                        
                                    </td>
                                </tr>
                            @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane fade" id="pengembang" role="tabpanel" aria-labelledby="pengembang-tab">
                    <x-adminlte-datatable id="tablePengembang" :heads="[
                            'Nama',
                            'Telp',
                            'Email',
                            'Keterangan',
                            ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                        ]">
                            @foreach($row->rusun_pengembangs as $rusun_pengembang)
                                <tr>
                                    <td>{{$rusun_pengembang->nama}}</td>
                                    <td>{{$rusun_pengembang->telp}}</td>
                                    <td>{{$rusun_pengembang->email}}</td>
                                    <td>{{$rusun_pengembang->keterangan}}</td>
                                    <td>@php echo $rusun_pengembang->aksi; @endphp</td>
                                </tr>
                            @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane fade" id="pengelola" role="tabpanel" aria-labelledby="pengelola-tab">
                    <x-adminlte-datatable id="tablePengelola" :heads="[
                            'Nama',
                            'Telp',
                            'Email',
                            'Keterangan',
                            ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                        ]">
                            @foreach($row->rusun_pengelolas as $rusun_pengelola)
                                <tr>
                                    <td>{{$rusun_pengelola->nama}}</td>
                                    <td>{{$rusun_pengelola->telp}}</td>
                                    <td>{{$rusun_pengelola->email}}</td>
                                    <td>{{$rusun_pengelola->keterangan}}</td>
                                    <td>@php echo $rusun_pengelola->aksi; @endphp</td>
                                </tr>
                            @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane fade" id="tarif" role="tabpanel" aria-labelledby="tarif-tab">
                    <x-adminlte-datatable id="tableTarif" :heads="[
                            'Item',
                            'Tarif',
                        ]">
                            @foreach($row->rusun_tarifs as $rusun_tarif)
                                <tr>
                                    <td>{{$rusun_tarif->item}}</td>
                                    <td>{{$rusun_tarif->tarif_format}}</td>
                                </tr>
                            @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane fade" id="outstanding" role="tabpanel" aria-labelledby="outstanding-tab">
                    <x-adminlte-datatable id="tableOutstanding" :heads="[
                            'Penghuni',
                            'Tower',
                            'Unit',
                            'Total',
                        ]">
                            @foreach($row->rusun_outstanding_penghunis as $rusun_outstanding_penghuni)
                                <tr>
                                    <td>{{$rusun_outstanding_penghuni->pemilik_penghuni_text}}</td>
                                    <td>{{$rusun_outstanding_penghuni->rusun_details->nama_tower}}</td>
                                    <td>{{$rusun_outstanding_penghuni->rusun_unit_details->jenis ?? '-'}}</td>
                                    <td>{{$rusun_outstanding_penghuni->total_format}}</td>
                                </tr>
                            @endforeach
                    </x-adminlte-datatable>
                </div>
            </div>
        </div>
    </div>
</x-adminlte-card>

<x-adminlte-modal id="modalPengembang" title="Dokumen Pengembang" theme="purple"
    icon="fas fa-file-alt" size='lg' static-backdrop v-centered>
    <div class="table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Dokumen</th>
                    <th>Status</th>
                    <th>Lihat</th>
                </tr>
            </thead>
            <tbody id="listDokumenPengembang">
                
            </tbody>
        </table>
    </div>
</x-adminlte-modal>

<x-adminlte-modal id="modalPengelola" title="Dokumen Pengelola" theme="purple"
    icon="fas fa-file-alt" size='lg' static-backdrop v-centered>
    <div class="table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Dokumen</th>
                    <th>Status</th>
                    <th>Lihat</th>
                </tr>
            </thead>
            <tbody id="listDokumenPengelola">
                
            </tbody>
        </table>
    </div>
</x-adminlte-modal>
@stop

@section('css')

@stop

@section('js')
<script>
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    $('.product-image-thumb').on('click', function () {
        var $image_element = $(this).find('img')
        $('.product-image').prop('src', $image_element.attr('src'))
        $('.product-image-thumb.active').removeClass('active')
        $(this).addClass('active')
    });

    const tableTower = $('#tableTower').DataTable();
    const tableUnit = $('#tableUnit').DataTable();
    const tableFasilitas = $('#tableFasilitas').DataTable();

    const buttonViewFile = (urlDocument, urlShow) => {
        return `<a href="${urlDocument}" class="btn btn-sm btn-info" target="_blank">Dokumen</a> 
            <a href="${urlShow}" class="btn btn-sm btn-success" target="_blank">Detail</a>`;
    }

    $('body').on('click', '.btnDeleteTower', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const url = $(this).attr('id');
        const columnRemove = $(this).parents('tr');

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Ingin menghapus data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {
                        id: value,
                    },
                    dataType: "json",
                    success: function (response) {
                        tableTower
                            .row(columnRemove)
                            .remove()
                            .draw();                  

                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        
                    },
                    error: function (xhr) {
                        const {status, statusText, responseText, responseJSON} = xhr;

                        switch (status) {
                            case 500:
                            case 419:
                            case 403:
                                Swal.fire({
                                    title: statusText,
                                    text: responseText,
                                });                        
                                break;
                        
                            default:
                                break;
                        }
                    }
                });
            }
        });
    });

    $('body').on('click', '.btnDeleteUnit', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const url = $(this).attr('id');
        const columnRemove = $(this).parents('tr');

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Ingin menghapus data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {
                        id: value,
                    },
                    dataType: "json",
                    success: function (response) {
                        tableUnit
                            .row(columnRemove)
                            .remove()
                            .draw();                  

                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        
                    },
                    error: function (xhr) {
                        const {status, statusText, responseText, responseJSON} = xhr;

                        switch (status) {
                            case 500:
                            case 419:
                            case 403:
                                Swal.fire({
                                    title: statusText,
                                    text: responseText,
                                });                        
                                break;
                        
                            default:
                                break;
                        }
                    }
                });
            }
        });
    });

    $('body').on('click', '.btnDeleteFasilitas', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const url = $(this).attr('id');
        const columnRemove = $(this).parents('tr');

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Ingin menghapus data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {
                        id: value,
                    },
                    dataType: "json",
                    success: function (response) {
                        tableFasilitas
                            .row(columnRemove)
                            .remove()
                            .draw();                  

                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        
                    },
                    error: function (xhr) {
                        const {status, statusText, responseText, responseJSON} = xhr;

                        switch (status) {
                            case 500:
                            case 419:
                            case 403:
                                Swal.fire({
                                    title: statusText,
                                    text: responseText,
                                });                        
                                break;
                        
                            default:
                                break;
                        }
                    }
                });
            }
        });
    });

    $('body').on('click', '.btnModalPengembang', function (e) {
        e.preventDefault();

        const dokumen_pengembang_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "{{route('rusun.pengembangDokumen', $row->id)}}",
            data: {
                pengembang_id: dokumen_pengembang_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.length > 0) {
                    $('#listDokumenPengembang').html('');

                    $.each(response, function (index, value) {
                        var urlShow = '{{url("pengembang-dokumen")}}/' + value.id;

                        $('#listDokumenPengembang').append(`<tr>
                            <td>${value.dokumens.nama}</td>
                            <td>${value.status}</td>
                            <td>${buttonViewFile(value.file, urlShow)}</td>
                        </tr>`);
                    });

                    $('#modalPengembang').modal('show');
                } else {
                    Swal.fire('Data tidak tersedia.');
                }
            },
            error: function (xhr) {
                const {status, statusText, responseText, responseJSON} = xhr;

                switch (status) {
                    case 500:
case 419:
case 403:
                        Swal.fire({
                            title: 'Error',
                            text: statusText,
                        });             
                        break;
                    
                    default:
                        break;
                }
            }
        });
    });

    $('body').on('click', '.btnModalPengelola', function (e) {
        e.preventDefault();

        const dokumen_pengelola_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "{{route('rusun.pengelolaDokumen', $row->id)}}",
            data: {
                pengelola_id: dokumen_pengelola_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.length > 0) {
                    $('#listDokumenPengelola').html('');

                    $.each(response, function (index, value) { 
                        var urlShow = '{{route("pengelola-dokumen.index")}}/' + value.id;

                        $('#listDokumenPengelola').append(`<tr>
                            <td>${value.dokumens.nama}</td>
                            <td>${value.status}</td>
                            <td>${buttonViewFile(value.file, urlShow)}</td>
                        </tr>`);
                    });

                    $('#modalPengelola').modal('show');
                } else {
                    Swal.fire('Data tidak tersedia.');
                }
            },
            error: function (xhr) {
                const {status, statusText, responseText, responseJSON} = xhr;

                switch (status) {
                    case 500:
case 419:
case 403:
                        Swal.fire({
                            title: 'Error',
                            text: statusText,
                        });             
                        break;
                    
                    default:
                        break;
                }
            }
        });
    });
});
</script>
@stop