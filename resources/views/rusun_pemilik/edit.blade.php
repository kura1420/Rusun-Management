@extends('adminlte::page')

@section('title', $subTitle)

@section('content_header')
    <h1>
        {{$subTitle}}
        <a href="{{route('rusun-pemilik.index')}}" class="btn btn-xs btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
    </h1>
@stop

@section('content')
<x-adminlte-card theme="primary" theme-mode="outline">
    <div class="row">
        <div class="col-12 col-sm-4">
            <h3 class="d-inline-block d-sm-none">{{$row->rusun_unit_details->ukuran}}</h3>
            @if ($row->rusun_unit_details->foto) 
            <div class="col-12">
                <img src="{{$row->rusun_unit_details->foto}}" class="product-image">
            </div>
            @else 
            <div class="col-12">
                <img src="{{asset('images/no-image.jpg')}}" class="product-image" alt="No Image">
            </div>
            @endif
        </div>
        <div class="col-12 col-sm-8">
            <h3 class="my-3">{{$row->rusun_unit_details->ukuran}}</h3>
            <p>
                <strong>Rusun:</strong> {{$row->rusuns->nama}} <br>
                <strong>Tower:</strong> {{$row->rusun_details->nama_tower}} <br>
                <strong>BAST:</strong> {{$row->bast}} <br>
                <strong>Status:</strong> {{$row->status_text}} <br>
                <strong>Alasan Ditolak: </strong> <br> {{$row->alasan}}
            </p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <nav class="w-100">
                <div class="nav nav-tabs" id="product-tab" role="tablist">
                    <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Pemilik</a>
                    <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Penghuni</a>
                    <a class="nav-item nav-link" id="product-fasilitas-tab" data-toggle="tab" href="#product-fasilitas" role="tab" aria-controls="product-fasilitas" aria-selected="false">Dokumen</a>
                </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                    @if (isset($row->pemiliks))
                    <div class="row">
                        <x-adminlte-input name="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-4" value="{{$row->pemiliks->nama}}" readonly />
                        <x-adminlte-input type="email" name="email" label="Email" placeholder="Email" fgroup-class="col-md-4" value="{{$row->pemiliks->email}}" readonly />
                        <x-adminlte-input name="phone" label="Phone" placeholder="Phone" fgroup-class="col-md-4" value="{{$row->pemiliks->phone}}" readonly />
                        <x-adminlte-input name="identitas_nomor" label="Identitas Nomor" placeholder="Identitas Nomor" fgroup-class="col-md-4" value="{{$row->pemiliks->identitas_nomor}}" readonly />
                        <x-adminlte-input name="identitas_tipe" label="Identitas Nomor" placeholder="Identitas Nomor" fgroup-class="col-md-4" value="{{$row->pemiliks->identitas_tipe_text}}" readonly />

                        <div class="form-group col-md-4">
                            <label for="identitas_file">
                                Identitas File
                            </label>

                            <div class="input-group">
                                @if ($row->pemiliks->identitas_file)
                                <a href="{{route('pemilik.view_file', [$row->pemiliks->id, $row->pemiliks->identitas_file])}}" class="btn btn-danger btn-block" data-toggle="lightbox">
                                    <b><i class="fa fa-eye"></i> Lihat</b>
                                </a>
                                @else
                                    Tidak Tersedia
                                @endif
                            </div>
                        </div>
                    </div>  
                    @else
                        Tidak Tersedia
                    @endif
                </div>
                <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                    @if (isset($row->rusun_penghuni))
                    <div class="row">
                        <x-adminlte-input name="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->rusun_penghuni->nama}}" readonly />
                        <x-adminlte-input type="email" name="email" label="Email" placeholder="Email" fgroup-class="col-md-3" value="{{$row->rusun_penghuni->email}}" readonly />
                        <x-adminlte-input name="phone" label="Phone" placeholder="Phone" fgroup-class="col-md-3" value="{{$row->rusun_penghuni->phone}}" readonly />
                        <x-adminlte-select name="status" label="Status" placeholder="Status" fgroup-class="col-md-2" readonly>
                            <option value="SW" {{$row->rusun_penghuni->status == 'SW' ? 'selected' : ''}}>Sewa</option>
                            <option value="TSW" {{$row->rusun_penghuni->status == 'TSW' ? 'selected' : ''}}>Tidak Sewa</option>
                            <option value="KS" {{$row->rusun_penghuni->status == 'KS' ? 'selected' : ''}}>Kosong</option>
                        </x-adminlte-select>
                        <x-adminlte-input name="identitas_nomor" label="Identitas Nomor" placeholder="Identitas Nomor" fgroup-class="col-md-2" value="{{$row->rusun_penghuni->identitas_nomor}}" readonly />
                        <x-adminlte-select name="identitas_tipe" label="Identitas Tipe" placeholder="Identitas Tipe" fgroup-class="col-md-2" readonly>
                            <option value="KTP" {{$row->rusun_penghuni->identitas_tipe == 'KTP' ? 'selected' : ''}}>KTP</option>
                            <option value="PASSPORT" {{$row->rusun_penghuni->identitas_tipe == 'PASSPORT' ? 'selected' : ''}}>PASSPORT</option>
                        </x-adminlte-select>
                        <x-adminlte-input-date name="tanggal_masuk" id="tanggal_masuk" label="Tanggal Masuk" placeholder="Tanggal Masuk" fgroup-class="col-md-2" :config="['format' => 'YYYY-MM-DD']" value="{{$row->rusun_penghuni->tanggal_masuk}}" disabled />
                        <x-adminlte-input-date name="tanggal_keluar" id="tanggal_keluar" label="Tanggal Keluar" placeholder="Tanggal Keluar" fgroup-class="col-md-2" :config="['format' => 'YYYY-MM-DD']" value="{{$row->rusun_penghuni->tanggal_keluar}}" disabled />

                        <div class="form-group col-md-2">
                            <label for="identitas_file">
                                Identitas File
                            </label>

                            <div class="input-group">
                                @if ($row->rusun_penghuni->identitas_file)
                                <a href="{{route('rusun-penghuni.view_file', [$row->rusun_penghuni->id, $row->rusun_penghuni->identitas_file])}}" class="btn btn-danger btn-block" data-toggle="lightbox">
                                    <b><i class="fa fa-file-alt"></i> ID File</b>
                                </a>
                                @else
                                    Tidak Tersedia
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                        Tidak Tersedia
                    @endif
                </div>
                <div class="tab-pane fade" id="product-fasilitas" role="tabpanel" aria-labelledby="product-fasilitas-tab">
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
                            </td>
                        </tr>
                        @endforeach                     
                    </x-adminlte-datatable>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footerSlot">
        <x-adminlte-button type="button" id="btnVerif" class="btn-sm" label="Verifikasi" theme="primary" icon="fab fa-telegram-plane" />
    </x-slot>
</x-adminlte-card>

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

    $('#btnVerif').on('click', function () {
        Swal.fire({
            title: "Verifikasi Pemilik?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Terima",
            denyButtonText: "Tolak",
        }).then((result) => {
            if (result.isConfirmed) {
                var params = {
                    status: 'verif',
                }

                updateData(params);
            } else if (result.isDenied) {
                Swal.fire({
                    title: "Harap berikan alasannya",
                    input: "text",
                    showCancelButton: true,
                    confirmButtonText: "Kirim",
                    showLoaderOnConfirm: true,
                    preConfirm: (alasan) => {
                        if (alasan) {
                            var params = {
                                status: 'unverif',
                                alasan: alasan,
                            }

                            updateData(params);
                        } else {
                            Swal.fire('Kotak alasan tidak boleh kosong.');
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading(),
                }).then((result) => {
                    const {isConfirmed, value} = result;

                    if (isConfirmed == true && value !== '') {
                        Swal.fire("Terimakasih konfirmasinya!", "", "success");
                    }
                });
            }
        });
    });

    const updateData = params => {
        $.ajax({
            type: "PUT",
            url: "{{route('rusun-pemilik.update', $row->id)}}",
            data: params,
            dataType: "json",
            success: function (response) {
                Swal.fire("Terimakasih konfirmasinya!", "", "success");

                window.location.reload();
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
</script>
@stop