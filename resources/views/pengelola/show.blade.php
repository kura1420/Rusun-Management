@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>
        {{$title}}
        <a href="{{route('pengelola.index')}}" class="btn btn-xs btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
        <a href="{{route('pengelola.edit', $row->id)}}" class="btn btn-xs btn-warning"> <i class="fa fa-pencil-alt"></i> Edit </a>

        <div class="btn-group">
            <button type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Tambah</button>
            <button type="button" class="btn btn-xs btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
                <a class="dropdown-item" href="{{route('pengelola-kontak.create')}}?pengelola_id={{$row->id}}">Kontak</a>
                <a class="dropdown-item" href="{{route('pengelola-dokumen.create')}}?pengelola_id={{$row->id}}">Dokumen</a>
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
                {{-- {{$row->kecamatans->name}}, {{$row->desas->name ?? NULL}} <br>
                {{$row->kotas->name}}, {{$row->provinces->name}} --}}
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
                            @foreach($row->pengelola_kontaks as $pengelola_kontak)
                                <tr>
                                    <td>{{$pengelola_kontak->nama}}</td>
                                    <td>{{$pengelola_kontak->handphone}}</td>
                                    <td>{{$pengelola_kontak->email}}</td>
                                    <td>{{$pengelola_kontak->posisi}}</td>
                                    <td>
                                        <a href="{{route('pengelola-kontak.edit', $pengelola_kontak->id)}}?pengelola_id={{$row->id}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-xs btnDeleteKontak" value="{{$pengelola_kontak->id}}" id="{{route('pengelola-kontak.destroy', $pengelola_kontak->id)}}"><i class="fas fa-trash"></i> Hapus</button>
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
                            'Status',
                            ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                        ]">
                            @foreach($row->pengelola_dokumens as $pengelola_dokumen)
                                <tr>
                                    <td>{{$pengelola_dokumen->rusun->nama}}</td>
                                    <td>{{$pengelola_dokumen->dokumen->nama}}</td>
                                    <td>{{$pengelola_dokumen->tersedia ? 'Ya' : 'Tidak'}}</td>
                                    <td>{{$pengelola_dokumen->status_text}}</td>
                                    <td>
                                        <a href="{{route('pengelola-dokumen.show', $pengelola_dokumen->id)}}?pengelola_id={{$row->id}}" class="btn btn-success btn-xs" title="Show"><i class="fas fa-eye"></i> Detail</a>
                                        <a href="{{route('pengelola-dokumen.edit', $pengelola_dokumen->id)}}?pengelola_id={{$row->id}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-xs btnDeleteDokumen" value="{{$pengelola_dokumen->id}}" id="{{route('pengelola-dokumen.destroy', $pengelola_dokumen->id)}}"><i class="fas fa-trash"></i> Hapus</button>
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
});
</script>
@stop