@extends('adminlte::page')

@section('title', $title)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>
                {{$title}}
                <small>{{$subTitle}}</small>
            </h1>
        </div>
        <div class="col-sm-6"></div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md">
        <x-adminlte-info-box title="Total Suara Masuk" text="{{$pollingKanidatCount}}" icon="fas fa-lg fa-download" icon-theme="purple" />
    </div>
    <div class="col-md">
        <x-adminlte-info-box title="{{$totalPemilikPenghuni}}" text="Total Penghuni Diluar Kanidat" icon="fas fa-lg fa-users text-primary" theme="gradient-primary" icon-theme="white"/>
    </div>
</div>

<div class="card card-outline card-danger">
    <div class="card-header">
        <h5 class="card-title">
            Data terkahir update: <strong>{{$pollingKanidat->waktu ?? 'Tidak Tersedia'}}</strong>
        </h5>
    </div>
    <div class="card-footer">
        <div class="row">
            @forelse ($grups as $grup)
            <div class="col-md">
                <div class="description-block border-right">
                    <h3>{{$grup->total_suara_percent}} %</h3>
                    <h1>{{$grup->total_suara}}</h1>
                    <span class="description-text text-bold">
                        <a href="javascript:void(0)" class="btnDetailGrup" id="{{route('program-kanidat.show-detail', [$grup->program_id, $grup->grup_id])}}">{{$grup->grup_nama}}</a>
                    </span>
                </div>
            </div>
            @empty
            <div class="col-md">
                <div class="description-block border-right">
                    <h5 class="description-header">Data tidak tersedia</h5>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

@role('Pemilik|Penghuni')
<div class="card">
    <div class="card-body">
        Anda belum memilih dari calon kanidat yang tersedia <x-adminlte-button label="Tentukan pilihan anda" theme="primary" icon="fas fa-check"/>
    </div>
</div>
@endrole

<x-adminlte-card theme="danger" title="Daftar Yang Sudah Memberikan Suara" theme-mode="outline">
    <div class="table-responsive">
        <table class="table table-striped" id="listPengambilanSuara">
            <thead>
                <tr>
                    <th style="width: 10px;">#</th>
                    <th>Nama</th>
                    <th>Telp</th>
                    <th>Email</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pollingKanidats as $key => $pollingKanidat)
                <tr>
                    <td>{{$loop->iteration}}.</td>
                    <td>{{$pollingKanidat->pemilik_penghuni_memilih_profile->nama}}</td>
                    <td>{{$pollingKanidat->pemilik_penghuni_memilih_profile->telp}}</td>
                    <td>{{$pollingKanidat->pemilik_penghuni_memilih_profile->email}}</td>
                    <td>{{$pollingKanidat->waktu}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-adminlte-card>

<x-adminlte-modal id="modalListKanidat" title="List Kanidat" theme="success" size="lg" v-centered static-backdrop scrollable>
    <div class="table-responsive">
        <table class="table table-hover text-nowrap" id="listKanidat">
            <thead>
                <tr>
                    <th>Tower</th>
                    <th>Unit</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</x-adminlte-modal>

@role('Pemilik|Penghuni')
<x-adminlte-modal id="modalMemilih" title="Tentukan Pilihan Anda" theme="danger" v-centered static-backdrop scrollable>
    <x-adminlte-select name="grup_id" id="grup_id" placeholder="Grup Kanidat">
        <option value="">- Grup Kanidat -</option>
        @foreach ($grups as $grup)
        <option value="{{$grup->grup_id}}">{{$grup->grup_nama}}</option>
        @endforeach
    </x-adminlte-select>

    <x-slot name="footerSlot">
        <x-adminlte-button class="mr-auto" theme="success" label="Pilih Kanidat" id="btnChooseCandidat" />
        <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" />
    </x-slot>
</x-adminlte-modal>
@endrole
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

    $('#listPengambilanSuara').DataTable();

    const table = $('#listKanidat').DataTable({
        columns: [
            { data: 'rusun_detail.nama_tower' },
            { data: 'rusun_unit_detail.jenis' },
            { data: 'pemilik_penghuni_profile.nama' },
            { data: 'program_jabatan.nama' },
        ],
    });

    $('body').on('click', '.btnDetailGrup', function (e) {
        let url = $(this).attr('id');

        table
            .clear()
            .draw();

        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                if (response.data.length > 0) {
                        table
                            .rows
                            .add(response.data)
                            .draw();

                        $('#modalListKanidat').modal('show');
                } else {
                    Swal.fire('Data tidak tersedia');
                }
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
    });

    @role('Pemilik|Penghuni')
    $('#btnChooseCandidat').on('click', function () {
        const grup_id = $('#grup_id').val();
                
        if (grup_id) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Setelah memilih tidak dapat di kembalikan kembali.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, saya yakin!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('polling-kanidat.store')}}",
                        data: {
                            grup_id: grup_id,
                        },
                        dataType: "json",
                        success: function (response) {
                            Swal.fire("Informasi!", "Terimakasih suara anda sudah masuk.", "success");

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
        } else {
            Swal.fire('Anda belum memilih');
        }
    });
    @endrole
});
</script>
@stop