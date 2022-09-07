@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>
        {{$title}}
        <a href="{{route('pengembang.index')}}" class="btn btn-xs btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
        <div class="btn-group">
            <button type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Tambah</button>
            <button type="button" class="btn btn-xs btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
                <a class="dropdown-item" href="{{route('pengembang-kontak.create')}}?pengembang_id={{$row->id}}">Kontak</a>
                <a class="dropdown-item" href="{{route('pengembang-dokumen.create')}}?pengembang_id={{$row->id}}">Dokumen</a>
            </div>
        </div>
    </h1>
@stop

@section('content')
@if (session()->has('success'))
<x-adminlte-alert theme="primary" title="Information" dismissable>
    {{session()->get('success')}}
</x-adminlte-alert>
@endif

<div class="row">
    <div class="col-md-4">
        <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
            <h3 class="text-primary">{{$row->nama}}</h3>
            <p class="text-muted">{{$row->keterangan}}</p>

            <br>

            <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
            <p class="text-muted">
                {{$row->alamat}} <br>
                {{$row->kecamatans->name}}, {{$row->desas->name ?? NULL}} <br>
                {{$row->kotas->name}}, {{$row->provinces->name}}
            </p>

            <strong><i class="fas fa-phone mr-1"></i> Telp</strong>
            <p class="text-muted">{{$row->telp ?? '-'}}</p>

            <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
            <p class="text-muted">{{$row->email ?? '-'}}</p>

            <strong><i class="fas fa-globe-asia mr-1"></i> Website</strong>
            <p class="text-muted">{{$row->website ?? '-'}}</p>
        </x-adminlte-card>
    </div>

    <div class="col-md-8">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Dokumen</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                        <x-adminlte-datatable id="table1" :heads="[
                            'Nama',
                            'Handphone',
                            'Email',
                            'Posisi',
                            ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                        ]">
                            @foreach($row->pengembang_kontaks as $pengembang_kontak)
                                <tr>
                                    <td>{{$pengembang_kontak->nama}}</td>
                                    <td>{{$pengembang_kontak->handphone}}</td>
                                    <td>{{$pengembang_kontak->email}}</td>
                                    <td>{{$pengembang_kontak->posisi}}</td>
                                    <td>
                                        <a href="{{route('pengembang-kontak.edit', $pengembang_kontak->id)}}?pengembang_id={{$row->id}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-xs btnDeleteKontak" value="{{$pengembang_kontak->id}}" id="{{route('pengembang-kontak.destroy', $pengembang_kontak->id)}}"><i class="fas fa-trash"></i> Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                        <x-adminlte-datatable id="table2" :heads="[
                            'Rusun',
                            'Dokumen',
                            'Tersedia',
                            'Keterangan',
                            ['label' => 'Aksi', 'no-export' => true, 'width' => 20],
                        ]">
                            @foreach($row->pengembang_dokumens as $pengembang_dokumen)
                                <tr>
                                    <td>{{$pengembang_dokumen->rusun->nama}}</td>
                                    <td>{{$pengembang_dokumen->dokumen->nama}}</td>
                                    <td>{{$pengembang_dokumen->tersedia ? 'Ya' : 'Tidak'}}</td>
                                    <td>{{$pengembang_dokumen->keterangan}}</td>
                                    <td>
                                        <a href="{{route('pengembang-dokumen.show', $pengembang_dokumen->id)}}?pengembang_id={{$row->id}}" class="btn btn-success btn-xs" title="Show"><i class="fas fa-eye"></i> Detail</a>
                                        <a href="{{route('pengembang-dokumen.edit', $pengembang_dokumen->id)}}?pengembang_id={{$row->id}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-xs btnDeleteDokumen" value="{{$pengembang_dokumen->id}}" id="{{route('pengembang-dokumen.destroy', $pengembang_dokumen->id)}}"><i class="fas fa-trash"></i> Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

    const tableKontak = $('#table1').DataTable();
    const tableDokumen = $('#table2').DataTable();
    
    $('body').on('click', '.btnDeleteKontak', function (e) {
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
                        tableKontak
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


    $('body').on('click', '.btnDeleteDokumen', function (e) {
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
                        tableDokumen
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