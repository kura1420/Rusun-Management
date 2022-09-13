@extends('adminlte::page')

@section('title', $subTitle)

@section('content_header')
    <h1>
        {{$subTitle}}
        <a href="{{route('p3srs-jadwal.index')}}" class="btn btn-xs btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
        <div class="btn-group">
            <button type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Tambah</button>
            <button type="button" class="btn btn-xs btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
                <a class="dropdown-item" href="{{route('p3srs-kegiatan-kanidat.create')}}?p3srs_jadwal_id={{$row->id}}">Kanidat</a>
                <a class="dropdown-item" href="{{route('p3srs-kegiatan-anggota.create')}}?p3srs_jadwal_id={{$row->id}}">Anggota</a>
            </div>
        </div>
    </h1>
@stop

@section('content')
<x-adminlte-card theme="primary" theme-mode="outline" title="{{$row->p3srs_kegiatans->nama}}">
    <div class="row">
        <div class="col-md-12">
            @php echo $row->keterangan; @endphp

            <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                    <strong>Rusun:</strong> {{$row->rusuns->nama}}
                </li>
                <li class="list-group-item">
                    <strong>Tanggal:</strong> {{$row->tanggal}}
                </li>
                <li class="list-group-item">
                    <strong>Lokasi:</strong> {{$row->lokasi}}
                </li>
            </ul>

            <div class="row">
                
            </div>
        </div>
    </div>
</x-adminlte-card>


<x-adminlte-card theme="primary" theme-mode="outline" title="Kanidat">
    <div class="row">
        @foreach ($groupBys as $key => $values)        
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <div class="card-title">{{$values['text']}}</div>
                    <div class="card-tools">
                        <a href="{{route('p3srs-kegiatan-kanidat.edit', $values['id'])}}?p3srs_jadwal_id={{$row->id}}" class="btn btn-sm btn-warning text-dark"><strong><i class="fa fa-pencil-alt"></i> Edit</strong></a>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tower</th>
                                <th>Unit</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($values['childrens'] as $value) 
                            <tr>
                                <td>{{$loop->iteration}}.</td>
                                <td>{{$value['profile']->nama_tower}}</td>
                                <td>{{$value['profile']->ukuran}}</td>
                                <td>{{$value['profile']->nama}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-adminlte-card>

@if (count($row->p3srs_kegiatan_anggotas)>0)
<x-adminlte-card theme="primary" theme-mode="outline" title="Peserta" id="cardTableAnggota">
    <x-slot name="toolsSlot">
        <x-adminlte-button type="button" label="Hapus" theme="danger" class="btn-sm btnDeleteAnggota" id="{{route('p3srs-kegiatan-anggota.destroy', $row->id)}}" value="{{$row->id}}" />
    </x-slot>

    <div class="row table-responsive">
        <table class="table table-hover table-bordered text-nowrap" id="tableAnggota">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tower</th>
                    <th>Unit</th>
                    <th>Nama</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($row->p3srs_kegiatan_anggotas as $key => $p3srs_kegiatan_anggota)
                <tr>
                    <td>{{$loop->iteration}}.</td>
                    <td>{{$p3srs_kegiatan_anggota->profile->nama_tower}}</td>
                    <td>{{$p3srs_kegiatan_anggota->profile->ukuran}}</td>
                    <td>{{$p3srs_kegiatan_anggota->profile->nama}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-adminlte-card>
@endif
@stop

@section('css')

@stop

@section('js')
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    $('#tableAnggota').DataTable();
    
    $('body').on('click', '.btnDeleteAnggota', function (e) {
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
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        
                        $('#cardTableAnggota').remove();
                    },
                    error: function (xhr) {
                        const {responseJSON, status, statusText} = xhr;

                        switch (status) {
                            case 500:
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
            }
        });
    });
});
</script>
@stop