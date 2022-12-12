@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>
        {{$subTitle}}
        <a href="{{route('rusun-penghuni.index')}}" class="btn btn-xs btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
        <div class="btn-group">
            <button type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Tambah</button>
            <button type="button" class="btn btn-xs btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
                <a class="dropdown-item" href="{{route('rusun-penghuni-dokumen.create')}}?rusun_penghuni_id={{$row->id}}">Dokumen</a>
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
    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <h3 class="profile-username text-center">{{$row->nama}}</h3>
                <p class="text-center"><a href="mailto:{{$row->email}}">{{$row->email}}</a></p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item"><b>Identitas Nomor</b> <a class="float-right">{{$row->identitas_nomor}}</a></li>
                    <li class="list-group-item"><b>Identitas Jenis</b> <a class="float-right">{{$row->identitas_tipe}}</a></li>

                    <li class="list-group-item"><b>Rusun</b> <a class="float-right">{{$row->rusuns->nama}}</a></li>
                    <li class="list-group-item"><b>Tower</b> <a class="float-right">{{$row->rusun_details->nama_tower}}</a></li>
                    <li class="list-group-item"><b>Unit</b> <a class="float-right">{{$row->rusun_unit_details->jenis}}</a></li>
                    <li class="list-group-item"><b>Status</b> <a class="float-right">{{$row->status_label}}</a></li>
                    
                    <li class="list-group-item">
                        <b>IPL Dibayar Oleh</b> 
                        
                        @if ($row->rusun_pembayaran_ipls)
                                @if ($row->rusun_pembayaran_ipls->pemilik_bayar) 
                                    <a class="float-right">Pemilik</a>
                                @else 
                                    <a class="float-right">Penghuni</a> 
                                @endif
                        @else
                            -
                        @endif
                    </li>
                </ul>

                <div class="row">
                    <div class="col-md-6">
                        @if ($row->identitas_file)
                        <a href="{{route('rusun-penghuni.view_file', [$row->id, $row->identitas_file])}}" class="btn btn-danger btn-block" data-toggle="lightbox"><b><i class="fa fa-file-alt"></i> ID File</b></a>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <a href="tel:{{$row->phone}}" class="btn btn-warning btn-block"><b><i class="fa fa-phone"></i> {{$row->phone}}</b></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-dokumen-tab" data-toggle="pill" href="#custom-tabs-one-dokumen" role="tab" aria-controls="custom-tabs-one-dokumen" aria-selected="false">Dokumen</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-dokumen" role="tabpanel" aria-labelledby="custom-tabs-one-dokumen-tab">
                        <x-adminlte-datatable id="tableDokumen" :heads="[
                                'Dokumen',
                                'Keterangan',
                                ['label' => 'Aksi', 'no-export' => true, 'width' => 20],
                            ]">
                            @foreach ($row->rusun_penghuni_dokumens as $rusun_penghuni_dokumen)
                            <tr>
                                <td>{{$rusun_penghuni_dokumen->dokumens->nama}}</td>
                                <td>{{$rusun_penghuni_dokumen->keterangan}}</td>
                                <td>
                                    <button type="button" id="{{route('rusun-penghuni-dokumen.show', $rusun_penghuni_dokumen->id)}}" class="btn btn-success btn-xs btnDokumenView"><i class="fas fa-eye"></i> View</button>
                                    <a href="{{route('rusun-penghuni-dokumen.edit', $rusun_penghuni_dokumen->id)}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    <button type="button" class="btn btn-danger btn-xs btnDeleteDokumen" value="{{$rusun_penghuni_dokumen->id}}" id="{{route('rusun-penghuni-dokumen.destroy', $rusun_penghuni_dokumen->id)}}"><i class="fas fa-trash"></i> Hapus</button>                                                                            
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

<x-adminlte-modal id="modalDokumen" title="Dokumen Pemilik" theme="purple" icon="fas fa-file-alt" size='lg' v-centered static-backdrop scrollable>
    <embed type="application/pdf" id="modalViewDokumen" width="100%" height="500"></embed>

    <x-slot name="footerSlot">
        <x-adminlte-button type="button" theme="danger" id="btnModalViewOnTap" label="View On Tap"/>
    </x-slot>
</x-adminlte-modal>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" integrity="sha512-Velp0ebMKjcd9RiCoaHhLXkR1sFoCCWXNp6w4zj1hfMifYB5441C+sKeBl/T/Ka6NjBiRfBBQRaQq65ekYz3UQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js" integrity="sha512-YibiFIKqwi6sZFfPm5HNHQYemJwFbyyYHjrr3UT+VobMt/YBo1kBxgui5RWc4C3B4RJMYCdCAJkbXHt+irKfSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
    
    const tableDokumen = $('#tableDokumen').DataTable();    

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

    $('body').on('click', '.btnIPLView', function (e) {
        e.preventDefault();

        alert('TODO: show history IPL');
    });

    $('body').on('click', '.btnDokumenView', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const url = $(this).attr('id');
        
        $('#btnModalViewOnTap').val(url);
        $('#modalViewDokumen').attr('src', url);
        $('#modalDokumen').modal('show');
    });

    $('#modalDokumen').on('hidden.bs.modal', function () {
        $('#btnModalViewOnTap').val('');
    });

    $('#btnModalViewOnTap').click(function (e) { 
        e.preventDefault();
        
        const url_file = $(this).val();

        window.open(url_file, '_blank');
    });
});
</script>
@stop