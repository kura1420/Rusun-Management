@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
@if (session()->has('success'))
<x-adminlte-alert theme="primary" title="Information" dismissable>
    {{session()->get('success')}}
</x-adminlte-alert>
@endif

<x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
    <x-slot name="toolsSlot">
        <a href="{{route('api-manage.create')}}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </x-slot>
    
    <x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered />
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

    $('body').on('click', '.btnTestEndpoint', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const url = $(this).attr('id');

        Swal.fire({
            title: 'Informasi',
            text: 'Test API membutuhkan waktu beberapa menit, apakah anda ingin lanjut?',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return fetch(url)
                    .then(response => {
                        return response;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        );
                    })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            const {status, statusText} = result.value;

            switch (status) {
                case 200:
                    Swal.fire(
                        'Informasi!',
                        'API sudah terkoneksi',
                        'success'
                    );                    
                    break;

                case 500:
                    Swal.fire({
                        title: 'Error',
                        text: statusText,
                    }); 
                    break;
            
                default:
                    break;
            }
        })
    });
    
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