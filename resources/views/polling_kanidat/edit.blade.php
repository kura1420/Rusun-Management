@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
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

<form action="{{route('polling-kanidat.update', $row->program_id)}}" method="post">
    @csrf
    @method('PUT')
    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('program.index')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="rusun_nama" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$row->rusun->nama}}" disabled />
            <x-adminlte-input name="program_nama" label="Program" placeholder="Program" fgroup-class="col-md-6" value="{{$row->program->nama}}" disabled />

            <x-adminlte-select name="grup_id" label="Grup" placeholder="Grup" fgroup-class="col-md-12">
                <option value="">Pilih</option>
                @foreach ($grups as $grup)
                <option value="{{$grup->grup_id}}">{{$grup->grup_nama}}</option>
                @endforeach
            </x-adminlte-select>
        </div>

        <x-slot name="footerSlot">
            <x-adminlte-button type="submit" class="btn-sm" label="Simpan" theme="primary" icon="fab fa-telegram-plane" />
        </x-slot>
    </x-adminlte-card>
</form>

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
});
</script>
@stop