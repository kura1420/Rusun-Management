@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>
        {{$subTitle}}
        <a href="{{route('pemilik.index')}}" class="btn btn-xs btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
        <div class="btn-group">
            <button type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Tambah</button>
            <button type="button" class="btn btn-xs btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
                <a class="dropdown-item" href="{{route('rusun-pemilik-dokumen.create')}}?pemilik_id={{$row->id}}">Dokumen</a>
                <a class="dropdown-item" href="{{route('rusun-pembayaran-ipl.create')}}?pemilik_id={{$row->id}}">Pembayaran IPL</a>
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
                </ul>

                <div class="row">
                    <div class="col-md-6">
                        @if ($row->identitas_file)
                        <a href="{{route('pemilik.view_file', [$row->id, $row->identitas_file])}}" class="btn btn-danger btn-block" data-toggle="lightbox">
                            <b><i class="fa fa-file-alt"></i> ID File</b>
                        </a>
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
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Tower & Unit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-dokumen-tab" data-toggle="pill" href="#custom-tabs-one-dokumen" role="tab" aria-controls="custom-tabs-one-dokumen" aria-selected="false">Dokumen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Penghuni</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Pembayaran IPL</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                        <x-adminlte-datatable id="tableRusun" :heads="[
                                'Rusun',
                                'Tower',
                                'Unit Ukuran',
                                ['label' => 'Aksi', 'no-export' => true, 'width' => 20],
                            ]">
                            @foreach ($row->rusun_pemiliks as $rusun_pemilik)
                            <tr>
                                <td>{{$rusun_pemilik->rusuns->nama}}</td>
                                <td>{{$rusun_pemilik->rusun_details->nama_tower}}</td>
                                <td>{{$rusun_pemilik->rusun_unit_details->ukuran}}</td>
                                <td>
                                    <button type="button" id="" class="btn btn-success btn-xs btnRusunView"><i class="fas fa-eye"></i> View</button>
                                </td>
                            </tr>
                            @endforeach
                        </x-adminlte-datatable>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-dokumen" role="tabpanel" aria-labelledby="custom-tabs-one-dokumen-tab">
                        <x-adminlte-datatable id="tableDokumen" :heads="[
                                'Rusun',
                                'Tower',
                                'Unit Ukuran',
                                'Dokumen',
                                'Keterangan',
                                ['label' => 'Aksi', 'no-export' => true, 'width' => 20],
                            ]">
                            @foreach ($row->rusun_pemilik_dokumens as $rusun_pemilik_dokumen)
                            <tr>
                                <td>{{$rusun_pemilik_dokumen->rusuns->nama}}</td>
                                <td>{{$rusun_pemilik_dokumen->rusun_details->nama_tower}}</td>
                                <td>{{$rusun_pemilik_dokumen->rusun_unit_details->ukuran}}</td>
                                <td>{{$rusun_pemilik_dokumen->dokumens->nama}}</td>
                                <td>{{$rusun_pemilik_dokumen->keterangan}}</td>
                                <td>
                                    <button type="button" id="{{route('rusun-pemilik-dokumen.show', $rusun_pemilik_dokumen->id)}}" class="btn btn-success btn-xs btnDokumenView"><i class="fas fa-eye"></i> View</button>
                                    <a href="{{route('rusun-pemilik-dokumen.edit', $rusun_pemilik_dokumen->id)}}" class="btn btn-info btn-xs" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    <button type="button" class="btn btn-danger btn-xs btnDeleteDokumen" value="{{$rusun_pemilik_dokumen->id}}" id="{{route('rusun-pemilik-dokumen.destroy', $rusun_pemilik_dokumen->id)}}"><i class="fas fa-trash"></i> Hapus</button>                                                                            
                                </td>
                            </tr>
                            @endforeach                            
                        </x-adminlte-datatable>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                        <x-adminlte-datatable id="tablePenghuni" :heads="[
                                'Rusun',
                                'Tower',
                                'Unit Ukuran',
                                'Nama',
                                'Phone',
                                'Status',
                                'Tgl. Masuk',
                                'Tgl. Keluar',
                                ['label' => 'Aksi', 'no-export' => true, 'width' => 20],
                            ]">
                            @foreach ($row->rusun_penghunis as $rusun_penghuni) 
                            <tr>
                                <td>{{$rusun_penghuni->rusuns->nama}}</td>
                                <td>{{$rusun_penghuni->rusun_details->nama_tower}}</td>
                                <td>{{$rusun_penghuni->rusun_unit_details->ukuran}}</td>
                                <td>{{$rusun_penghuni->nama}}</td>
                                <td>{{$rusun_penghuni->phone}}</td>
                                <td>{{$rusun_penghuni->status_label}}</td>
                                <td>{{$rusun_penghuni->tanggal_masuk}}</td>
                                <td>{{$rusun_penghuni->tanggal_keluar}}</td>
                                <td>
                                    <button type="button" id="" class="btn btn-success btn-xs btnRusunPenghuniView"><i class="fas fa-eye"></i> View</button>
                                </td>
                            </tr>
                            @endforeach
                        </x-adminlte-datatable>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                        <x-adminlte-datatable id="tablePembayaranIPL" :heads="[
                                'Rusun',
                                'Tower',
                                'Unit Ukuran',
                                'Nama',
                                'Phone',
                                ['label' => 'Aksi', 'no-export' => true, 'width' => 20],
                            ]">
                            @foreach ($row->rusun_pembayaran_ipls as $rusun_pembayaran_ipl) 
                            <tr>
                                <td>{{$rusun_pembayaran_ipl->rusuns->nama}}</td>
                                <td>{{$rusun_pembayaran_ipl->rusun_details->nama_tower}}</td>
                                <td>{{$rusun_pembayaran_ipl->rusun_unit_details->ukuran}}</td>
                                <td>{{$rusun_pembayaran_ipl->kepada->nama}}</td>
                                <td>{{$rusun_pembayaran_ipl->kepada->phone}}</td>
                                <td>
                                    <button type="button" id="" class="btn btn-success btn-xs btnIPLView"><i class="fas fa-eye"></i> View</button>
                                    <a href="{{route('rusun-pembayaran-ipl.edit', $rusun_pembayaran_ipl->id)}}" class="btn btn-warning btn-xs"><i class="fa fa-pencil-alt"></i> Edit</a>
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

    $('body').on('click', '.btnRusunView', function (e) {
        e.preventDefault();

        alert('TODO: show SC & DKK');
    });

    $('body').on('click', '.btnRusunPenghuniView', function (e) {
        e.preventDefault();

        alert('TODO: show profile penghuni');
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