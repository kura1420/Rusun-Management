@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
    <x-slot name="toolsSlot">
        <x-adminlte-button label="Kembali" theme="dark" icon="fa fa-arrow-left" class="btn btn-sm" onClick="history.back()" />
    </x-slot>

    <div class="row">
        <x-adminlte-select2 name="rusun_id" id="rusun_id" label="Rusun" fgroup-class="col-md-6">
            <option value=""></option>
            @foreach ($rusuns as $rusun)
            <option value="{{$rusun->id}}" {{$row->rusun_id == $rusun->id ? 'selected' : ''}}>{{$rusun->nama}}</option>
            @endforeach
        </x-adminlte-select2>

        <x-adminlte-select2 name="rusun_detail_id" id="rusun_detail_id" label="Tower" fgroup-class="col-md-6">
            @if($row->rusun_details)
            <option value="{{$row->rusun_details->id}}">{{$row->rusun_details->nama_tower}}</option>
            @endif
        </x-adminlte-select2>

        <x-adminlte-select2 name="nama" id="nama" label="Fasilitas" fgroup-class="col-md-3" :config="[
            'placeholder' => 'Pilih Fasilitas',
            'allowClear' => true,
        ]">
            <option value=""></option>
            <option value="new">Tambah Fasilitas</option>
            @foreach ($namas as $nama)
            <option value="{{$nama->nama}}" {{$row->nama == $nama->nama ? 'selected' : ''}}>{{$nama->nama}}</option>
            @endforeach
        </x-adminlte-select2>

        <x-adminlte-input name="keterangan" id="keterangan" label="Keterangan" placeholder="Keterangan" fgroup-class="col-md-5" value="{{$row->keterangan}}" />
        <x-adminlte-input name="jumlah" id="jumlah" label="Jumlah" placeholder="Jumlah" fgroup-class="col-md-2" value="{{$row->jumlah}}" />
        <x-adminlte-input-file name="foto" id="foto" label="Foto" fgroup-class="col-md-2" />
    </div>

    <x-slot name="footerSlot">
        <x-adminlte-button type="button" id="btnSubmit" class="btn-sm" label="Simpan" theme="primary" />
    </x-slot>
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

    $('#rusun_id').select2({
        placeholder: 'Rusun',
        allowClear: true,
    });

    $('#rusun_detail_id').select2({
        placeholder: 'Tower',
        allowClear: true,
        ajax: {
            url: '{{route("rest.rusun_details")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    rusun_id: $('#rusun_id').val(),
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.nama_tower,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#rusun_id').on('change', function (e) {
        let value = $(this).val();

        if (value) {
            $('#rusun_detail_id').prop('disabled', false);
        } else {
            $('#rusun_detail_id')
                .prop('disabled', true)
                .val('')
                .trigger('change');
        }
    });

    $('#nama').on('select2:select', function (e) {
        const value = $(this).val();
        
        if (value === 'new') {
            const promptValue = prompt('Tambah Fasilitas:');
            
            if (promptValue) {
                const data = {
                    id: promptValue,
                    text: promptValue,
                }

                const newOption = new Option(data.text, data.id, false, false);

                $('#nama')
                    .append(newOption)
                    .trigger('change')
                    .val(promptValue);
            }
        }
    });
    
    $('#btnSubmit').click(function (e) { 
        e.preventDefault();
        
        let $this = $(this);

        let formData = new FormData();

        formData.append('nama', $('#nama').val());
        formData.append('jumlah', $('#jumlah').val());
        formData.append('keterangan', $('#keterangan').val());
        formData.append('foto', $('#foto')[0].files[0] ?? '');
        formData.append('rusun_id', $('#rusun_id').val());
        formData.append('rusun_detail_id', $('#rusun_detail_id').val());

        $this
            .prop('disabled', true)
            .text('Loading...');
        
        $.ajax({
            type: "POST",
            url: "{{route('rusun-fasilitas.updateAsStore', $row->id)}}",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.fire(
                    'Informasi!',
                    'Data berhasil di perbarui!',
                    'success'
                );
                
                $this
                    .prop('disabled', false)
                    .text('Simpan');
            },
            error: function (xhr) {
                const {responseJSON, status, statusText} = xhr;

                switch (status) {
                    case 422:
                        const errors = responseJSON.errors;

                        $('#showMessageError').hide();

                        $('#listMessageError').html('');

                        $.each(errors, function (index, value) { 
                            $('<li/>')
                                .text(value[0])
                                .appendTo('#listMessageError');
                        });
                        
                        $('#showMessageError').show();
                        break;

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
                
                $this
                    .prop('disabled', false)
                    .text('Simpan');
            }
        });
    });
});
</script>
@stop