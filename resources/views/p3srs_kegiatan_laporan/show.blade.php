@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title . ' ' .$row->p3srs_kegiatans->nama}}</h1>
@stop

@section('content')
@if (session()->has('success'))
<x-adminlte-alert theme="primary" title="Information" dismissable>
    {{session()->get('success')}}
</x-adminlte-alert>
@endif

<x-adminlte-card theme="primary" theme-mode="outline" title="{{$row->rusuns->nama}}">
    <x-slot name="toolsSlot">
        @if (! $row->status)
        <a href="{{route('p3srs-kegiatan-laporan.create')}}?p3srs_kegiatan_jadwal_id={{$row->id}}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> Buat Laporan
        </a>
        @endif

        <a href="{{route('p3srs-kegiatan-laporan.index')}}" class="btn btn-sm btn-dark">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </x-slot>

    <div class="col-md-12">
        <div class="timeline">
            <div class="time-label">
                <span class="bg-red">{{date('d M Y', strtotime($row->tanggal))}}</span>
            </div>

            <div>
                <i class="fas fa-pencil-alt bg-blue"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock"></i> {{$row->created_at}}</span>
                    <h3 class="timeline-header"><a href="{{route('p3srs-jadwal.show', $row->id)}}" target="_blank">{{$row->p3srs_kegiatans->nama}}</a></h3>
                    <div class="timeline-body">
                        @php echo $row->keterangan; @endphp 
                        <i class="fa fa-map-marker-alt"></i> {{$row->lokasi}} &nbsp;
                        <i class="fa fa-calendar-day"></i> {{$row->tanggal_format}}
                    </div>
                </div>
            </div>

            @foreach ($row->p3srs_kegiatan_laporans as $p3srs_kegiatan_laporan)
            <div id="timeline_{{$p3srs_kegiatan_laporan->id}}">
                <i class="fas fa-spinner bg-warning"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock"></i> {{$p3srs_kegiatan_laporan->created_at}}</span>
                    <h3 class="timeline-header"><a href="#">{{$p3srs_kegiatan_laporan->judul}}</a></h3>
                    <div class="timeline-body">
                        @php echo $p3srs_kegiatan_laporan->penjelasan; @endphp

                        @if (count($p3srs_kegiatan_laporan->p3srs_kegiatan_dokumentasis)>0)
                            @foreach ($p3srs_kegiatan_laporan->p3srs_kegiatan_dokumentasis as $p3srs_kegiatan_dokumentasi) 
                                @switch($p3srs_kegiatan_dokumentasi->type)
                                    @case('jpg')
                                    @case('jpeg')
                                    @case('png')
                                        <a href="{{route('p3srs-kegiatan-laporan.dokumentasiViewFile', [$p3srs_kegiatan_dokumentasi->id, $p3srs_kegiatan_dokumentasi->filename])}}" data-toggle="lightbox">
                                            <img src="{{route('p3srs-kegiatan-laporan.dokumentasiViewFile', [$p3srs_kegiatan_dokumentasi->id, $p3srs_kegiatan_dokumentasi->filename])}}" class="img-thumbnail" style="width:10%;" />
                                        </a>
                                        @break
                                
                                    @case('pdf')
                                        <a href="{{route('p3srs-kegiatan-laporan.dokumentasiViewFile', [$p3srs_kegiatan_dokumentasi->id, $p3srs_kegiatan_dokumentasi->filename])}}" target="_blank"> 
                                            <img src="{{asset('images/pdf.png')}}" alt="" class="img-thumbnail" style="width:10%;">
                                        </a>
                                        @break
                                
                                    @default
                                        Default case...
                                @endswitch
                            @endforeach
                        @endif
                    </div>

                    @if (! $row->status)
                    <div class="timeline-footer">
                        <a href="{{route('p3srs-kegiatan-laporan.edit', $p3srs_kegiatan_laporan->id)}}" class="btn btn-info btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm btnDelete" value="{{$p3srs_kegiatan_laporan->id}}" id="{{route('p3srs-kegiatan-laporan.destroy', $p3srs_kegiatan_laporan->id)}}">Hapus</button>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach

            @if ($row->status)
            <div>
                <i class="fas fa-check bg-success"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock"></i> {{$groupTerpilih->updated_at}}</span>
                    <h3 class="timeline-header card-success card-outline">Grup <a href="javascript:void(0)" id="btnModalTerpilih">{{$groupTerpilih->grup_nama}} </a> Telah Terpilih</h3>
                </div>
            </div>
            @else
            <div>
                <i class="fas fa-clock bg-dark"></i>
            </div>
            @endif
            
        </div>
    </div>

    <x-slot name="footerSlot">
        @if (! $row->status)
        <x-adminlte-button type="button" id="btnVerifikasi" label="Verifikasi" theme="success" icon="fas fa-tasks" class="btn-sm" />
        @endif

        <button type="button" class="btn btn-warning float-right" id="btnModal"><i class="fa fa-file"></i> Cek Keterangan Kegiatan</button>
    </x-slot>

</x-adminlte-card>

<x-adminlte-modal id="modalKeterangan" title="{{$row->p3srs_kegiatans->nama}}" theme="purple" size='lg' scrollable static-backdrop v-centered>
    @php echo $row->p3srs_kegiatans->keterangan; @endphp
</x-adminlte-modal>

<x-adminlte-modal id="modalVerifikasi" title="Verifikasi Kegiatan" theme="success" size='lg' scrollable static-backdrop v-centered>
    <div class="row">
        <div class="col-md-2">
            <strong>Kegiatan: </strong>
        </div>
        <div class="col-md-10">
            {{$row->p3srs_kegiatans->nama}}
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <strong>Grup Terpilih: </strong>
        </div>
        <div class="col-md-10">
            <select name="terpilih" id="terpilih" class="form-control input-sm">
                <option value="">Pilih</option>
                @foreach ($groupKanidats as $key => $groupKanidat) 
                <option value="{{$groupKanidat->grup_id}}">{{$groupKanidat->grup_nama}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="direct-chat-text mt-2">
        @php echo $row->p3srs_kegiatans->keterangan; @endphp
    </div>

    <x-slot name="footerSlot">
        <x-adminlte-button id="btnAccept" class="mr-auto" theme="success" label="Terima"/>
        <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>

<x-adminlte-modal id="modalTerpilih" title="Group Terpilih" theme="primary" size='lg' static-backdrop v-centered>
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 10px;">#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Unit</th>
                <th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @if ($terpilihs)
                @foreach ($terpilihs as $key => $terpilih)
                <tr>
                    <td>{{$loop->iteration}}.</td>
                    <td>{{$terpilih->profile->nama}}</td>
                    <td>{{$terpilih->profile->email}}</td>
                    <td>{{$terpilih->profile->phone}}</td>
                    <td>{{$terpilih->profile->unit}}</td>
                    <td>{{$terpilih->p3srs_jabatans->nama}}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
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

    $('#btnModalTerpilih').click(function (e) { 
        e.preventDefault();
        
        $('#modalTerpilih').modal('show');
    });

    $('#btnAccept').click(function (e) { 
        e.preventDefault();
        
        let terpilih = $('#terpilih').val();

        if (terpilih) {
            $.ajax({
                type: "POST",
                url: "{{route('p3srs-jadwal.groupTerpilih')}}",
                data: {
                    id: '{{$row->id}}',
                    terpilih: terpilih
                },
                dataType: "json",
                success: function (response) {
                    Swal.fire(
                        'Informasi!',
                        'Kegiatan sudah terselesaikan.',
                        'success'
                    );

                    window.location.reload();
                },
                error: function (xhr) {
                    const {responseJSON, status, statusText} = xhr;

                    switch (status) {
                        case 500:
                        case 419:
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
        } else {
            Swal.fire('Silahkan pilih group kanidat terlebih dahulu');
        }
    });

    $('#btnVerifikasi').click(function (e) { 
        e.preventDefault();
        
        $('#modalVerifikasi').modal('show');
    });

    $('#btnModal').click(function (e) { 
        e.preventDefault();
        
        $('#modalKeterangan').modal('show');
    });

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    $('body').on('click', '.btnDelete', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const url = $(this).attr('id');

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
                        $('#timeline_' + value).remove();          
    
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