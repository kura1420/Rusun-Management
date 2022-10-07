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
        <div class="col-md-12">
            <nav class="w-100">
                <div class="nav nav-tabs" id="product-tab" role="tablist">
                    <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Unit</a>
                </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                    <x-adminlte-datatable id="tableUnit" :heads="[
                            'Tower',
                            'Jenis',
                            'Jumlah',
                            'Keterangan',
                            ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                        ]">
                            @foreach($row->rusun_unit_details as $rusun_unit_detail)
                                <tr>
                                    <td>{{$rusun_unit_detail->rusun_details->nama_tower}}</td>
                                    <td>{{$rusun_unit_detail->jenis}}</td>
                                    <td>{{$rusun_unit_detail->jumlah}}</td>
                                    <td>{{$rusun_unit_detail->keterangan}}</td>
                                    <td>
                                        <a href="{{route('rusun-unit-detail.show', $rusun_unit_detail->id)}}?rusun_id={{$row->rusun_id}}" class="btn btn-success btn-xs" title="Show"><i class="fas fa-eye"></i> Detail</a>
                                        <a href="{{route('rusun-unit-detail.edit', $rusun_unit_detail->id)}}?rusun_id={{$row->rusun_id}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <!-- <button type="button" class="btn btn-danger btn-xs btnDeleteUnit" value="{{$rusun_unit_detail->id}}" id="{{route('rusun-unit-detail.destroy', $rusun_unit_detail->id)}}"><i class="fas fa-trash"></i> Hapus</button>                 -->
                                    </td>
                                </tr>
                            @endforeach
                    </x-adminlte-datatable>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });
    const tableUnit = $('#tableUnit').DataTable();

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
                        const {responseJSON, status, statusText} = xhr;

                        switch (status) {
                            case 500:
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
            }
        });
    });
});
</script>
@stop