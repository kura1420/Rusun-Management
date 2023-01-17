@extends('adminlte::page')

@section('title', $title)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{$title . ' - ' . $programKanidat->program->nama}}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('program.index')}}">Program</a></li>
                <li class="breadcrumb-item"><a href="{{route('program-kanidat.show', $programKanidat->grup_id)}}">{{$programKanidat->grup_nama}}</a></li>
                <li class="breadcrumb-item active">{{$programKanidat->pemilik_penghuni_profile->nama}}</li>
            </ol>
        </div>
    </div>
</div>

@stop

@section('content')
@if (session()->has('success'))
<x-adminlte-alert theme="primary" title="Information" dismissable>
    {{session()->get('success')}}
</x-adminlte-alert>
@endif

@if ($programKanidat->penjelasan)
<div class="callout callout-danger">
    <h5>Alasan Ditolak</h5>
    <p>{{$programKanidat->penjelasan}}</p>
</div>
@endif

@if ($programKanidat->status == 4 || $programKanidat->status == 5)
<div class="callout callout-info">
    <h5>Informasi</h5>
    <p>Anda telah di <strong>{{$programKanidat->status_text}}</strong> untuk menjadi kanidat di {{$programKanidat->grup_nama}}.</p>
    <p>Apakah anda bersedia atau tidak <x-adminlte-button label="Konfirmasi" theme="info" class="btn-xs" id="btnKonfirmasi" value="{{$programKanidat->id}}" /></p> 
</div>
@endif

<x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
    <x-slot name="toolsSlot">
        <a href="{{route('program-kanidat.show', $programKanidat->grup_id)}}" class="btn btn-sm btn-dark">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>

        <a href="{{route('program-kanidat-dokumen.create', ['program_kanidat_id' => $programKanidat->id])}}" class="btn btn-sm btn-primary @if ($programKanidat->status == 4) d-none @endif">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </x-slot>
    
    <x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered beautify />
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

    const table = $('#table2').DataTable();
    
    $('body').on('click', '.btnDelete', function (e) {
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
                        table
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

    @if ($programKanidat->status == 4 || $programKanidat->status == 5)
    $('#btnKonfirmasi').click(function (e) { 
        e.preventDefault();

        var program_kanidat_id = $(this).val();
        
        Swal.fire({
            title: "Apakah anda menerima untuk di calonkan?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Ya, saya terima",
            denyButtonText: `Tidak`,
        }).then((result) => {
            var dataKonfirmasi = {
                __state: 'pemilik_penghuni',
            }

            if (result.isConfirmed) {
                dataKonfirmasi.status = 0;

                updateStatusGrup(dataKonfirmasi, program_kanidat_id);
            } else if (result.isDenied) {
                dataKonfirmasi.status = 6

                updateStatusGrup(dataKonfirmasi, program_kanidat_id);
            }
        });
    });

    const updateStatusGrup = (data, id) => {
        $.ajax({
            type: "PUT",
            url: "{{url('program-kanidat')}}/status/" + id,
            data: data,
            dataType: "json",
            success: function (response) {
                Swal.fire("Terimakasih atas konfirmasinya", "", "success");

                window.location.reload();
            },
            error: function (xhr) {
                const {status, statusText, responseText, responseJSON} = xhr;

                switch (status) {
                    case 500:
                    case 422:
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
    @endif
});
</script>
@stop