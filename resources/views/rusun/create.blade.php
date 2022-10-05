@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<div id="showMessageError" style="display: none;">
    <x-adminlte-alert theme="warning" title="Warning" dismissable>
        <ul id="listMessageError"></ul>
    </x-adminlte-alert>
</div>

<x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
    <x-slot name="toolsSlot">
        <a href="{{route('rusun.index')}}" class="btn btn-sm btn-dark"> <i class="fa fa-arrow-left"></i> Kembali </a>
    </x-slot>

    <div class="row">
        <x-adminlte-input name="nama" id="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" />

        <x-adminlte-select2 name="province_id" id="province_id" label="Provinsi" fgroup-class="col-md-6" />

        <x-adminlte-select2 name="regencie_id" id="regencie_id" label="Kota" fgroup-class="col-md-6" disabled />

        <x-adminlte-select2 name="district_id" id="district_id" label="Kecamatan" fgroup-class="col-md-6" disabled />

        <x-adminlte-select2 name="village_id" id="village_id" label="Desa" fgroup-class="col-md-6" disabled />

        <x-adminlte-input name="alamat" id="alamat" label="Alamat" placeholder="Alamat" fgroup-class="col-md-6" />
        <x-adminlte-input name="kode_pos" id="kode_pos" label="Kodepos" placeholder="Kodepos" fgroup-class="col-md-2" />
        <x-adminlte-input name="total_tower" id="total_tower" label="Total Tower" placeholder="Total Tower" fgroup-class="col-md-2" />
        <x-adminlte-input name="total_unit" id="total_unit" label="Total Unit" placeholder="Total Unit" fgroup-class="col-md-2" />
        <x-adminlte-input name="website" id="website" label="Website" placeholder="Website" fgroup-class="col-md-3" />
        <x-adminlte-input name="facebook" id="facebook" label="Facebook" placeholder="Facebook" fgroup-class="col-md-3" />
        <x-adminlte-input name="instgram" id="instgram" label="Instgram" placeholder="Instgram" fgroup-class="col-md-3" />
        <x-adminlte-input name="email" id="email" label="Email" placeholder="Email" fgroup-class="col-md-3" />
        <x-adminlte-input name="telp" id="telp" label="Telp" placeholder="Telp" fgroup-class="col-md-2" />
        <x-adminlte-input name="latitude" id="latitude" label="Latitude" placeholder="Latitude" fgroup-class="col-md-2" />
        <x-adminlte-input name="longitude" id="longitude" label="Longitude" placeholder="Longitude" fgroup-class="col-md-2" />

        <x-adminlte-input-file name="foto_1" id="foto_1" label="Foto 1" fgroup-class="col-md-4" />
        <x-adminlte-input-file name="foto_2" id="foto_2" label="Foto 2" fgroup-class="col-md-4" />
        <x-adminlte-input-file name="foto_3" id="foto_3" label="Foto 3" fgroup-class="col-md-4" />
    </div>

    <!-- <div class="row">
        <div class="col-md-12">
            <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
            </div>

            <div class="row">
                <x-adminlte-input name="endpoint_username" id="endpoint_username" label="Username Endpoint" placeholder="Username Endpoint" fgroup-class="col-md-6" />
                <x-adminlte-input name="endpoint_password" id="endpoint_password" label="Password Endpoint" placeholder="Password Endpoint" fgroup-class="col-md-6" />

                <x-adminlte-input name="endpoint_tarif" id="endpoint_tarif" label="Endpoint Tarif" placeholder="Endpoint Tarif" fgroup-class="col-md-6" />
                <x-adminlte-input name="endpoint_outstanding" id="endpoint_outstanding" label="Endpoint Outstanding" placeholder="Endpoint Outstanding" fgroup-class="col-md-6" />
                <x-adminlte-input name="endpoint_pemilik" id="endpoint_pemilik" label="Endpoint Pemilik" placeholder="Endpoint Pemilik" fgroup-class="col-md-6" />
                <x-adminlte-input name="endpoint_penghuni" id="endpoint_penghuni" label="Endpoint Penghuni" placeholder="Endpoint Penghuni" fgroup-class="col-md-6" />
            </div>

            <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
            </div>
        </div>
    </div> -->

    <div class="row mt-4">
        <div class="col-md-12">
        <nav class="w-100">
                <div class="nav nav-tabs" id="product-tab" role="tablist">
                    <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Pengembang</a>
                    <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Pengelola</a>
                </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                    <div class="row">
                        <div class="col-md-5">
                            <x-adminlte-select2 name="pengembangs" id="pengembangs">
                                <option></option>
                            </x-adminlte-select2>
                        </div>
                        <div class="col-md-5">
                            <x-adminlte-input name="pengembang_keterangan" id="pengembang_keterangan" placeholder="Keterangan" />
                        </div>
                        <div class="col-md-2">
                            <x-adminlte-button type="button" id="btnAddPengembang" label="Tambah" class="btn-md" theme="info" icon="fas fa-plus"/>
                        </div>
                    </div>

                    <table id="tablePengembang" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Telp</th>
                                <th>Email</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                    <div class="row">
                        <div class="col-md-5">
                            <x-adminlte-select2 name="pengelolas" id="pengelolas" style="width:100%;">
                                <option></option>
                            </x-adminlte-select2>
                        </div>
                        <div class="col-md-5">
                            <x-adminlte-input name="pengelola_keterangan" id="pengelola_keterangan" placeholder="Keterangan" />
                        </div>
                        <div class="col-md-2">
                            <x-adminlte-button type="button" id="btnAddPengelola" label="Tambah" class="btn-md" theme="info" icon="fas fa-plus"/>
                        </div>
                    </div>
                
                    <table id="tablePengelola" class="table table-hover text-nowrap" style="width:100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Telp</th>
                                <th>Email</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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

    const tablePengembang = $('#tablePengembang').DataTable({
        columnDefs: [
            {
                target: 0,
                visible: false,
            },
        ],
    });

    const tablePengelola = $('#tablePengelola').DataTable({
        columnDefs: [
            {
                target: 0,
                visible: false,
            },
        ],
    });

    const btnDeletePengembang = () => `<button type="button" class="btn btn-danger btn-sm btnDeletePengembang">Hapus</button>`;
    const btnDeletePengelola = () => `<button type="button" class="btn btn-danger btn-sm btnDeletePengelola">Hapus</button>`;

    $('#pengembangs').select2({
        placeholder: 'Pilih Pengembang',
        allowClear: true,
        ajax: {
            url: '{{route("rest.pengembangs")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.nama,
                            telp: value.telp,
                            email: value.email,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#pengelolas').select2({
        placeholder: 'Pilih Pengelola',
        allowClear: true,
        ajax: {
            url: '{{route("rest.pengelolas")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.nama,
                            telp: value.telp,
                            email: value.email,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#btnAddPengelola').click(function (e) { 
        e.preventDefault();
        
        let pengelolas = $('#pengelolas').select2('data');
        const pengelola_keterangan = $('#pengelola_keterangan').val();
        
        const {
            id,
            text,
            telp,
            email,
        } = pengelolas[0];

        if (id !== '' && text !== '') {
            const tablePengelolaRows = tablePengelola
                .rows()
                .data()
                .toArray();
                
            if (tablePengelolaRows.length == 0) {
                tablePengelola
                    .row
                    .add([
                        id,
                        text,
                        telp,
                        email,
                        pengelola_keterangan,
                        btnDeletePengelola(),
                    ])
                    .draw();
            } else {
                const tablePengelolaCheck = tablePengelolaRows.filter(r => r[1] == text);
                
                if (tablePengelolaCheck.length == 0) {
                    tablePengelola
                        .row
                        .add([
                            id,
                            text,
                            telp,
                            email,
                            pengelola_keterangan,
                            btnDeletePengelola(),
                        ])
                        .draw();
                } else {
                    Swal.fire('Data sudah tersedia.');                    
                }
            }
        } else {
            Swal.fire('Harap pilih pengembang terlebih dahulu.');
        }
    });

    $('#btnAddPengembang').click(function (e) { 
        e.preventDefault();
        
        let pengembangs = $('#pengembangs').select2('data');
        const pengembang_keterangan = $('#pengembang_keterangan').val();
        
        const {
            id,
            text,
            telp,
            email,
        } = pengembangs[0];

        if (id !== '' && text !== '') {
            const tablePengembangRows = tablePengembang
                .rows()
                .data()
                .toArray();
                
            if (tablePengembangRows.length == 0) {
                tablePengembang
                    .row
                    .add([
                        id,
                        text,
                        telp,
                        email,
                        pengembang_keterangan,
                        btnDeletePengembang(),
                    ])
                    .draw();
            } else {
                const tablePengembangCheck = tablePengembangRows.filter(r => r[1] == text);
                
                if (tablePengembangCheck.length == 0) {
                    tablePengembang
                        .row
                        .add([
                            id,
                            text,
                            telp,
                            email,
                            pengembang_keterangan,
                            btnDeletePengembang(),
                        ])
                        .draw();
                } else {
                    Swal.fire('Data sudah tersedia.');                    
                }
            }
        } else {
            Swal.fire('Harap pilih pengembang terlebih dahulu.');
        }
    });

    $('body').on('click', '.btnDeletePengembang', function (e) {
        e.preventDefault();

        const columnRemovePengembang = $(this).parents('tr');

        tablePengembang
            .row(columnRemovePengembang)
            .remove()
            .draw();
    });

    $('body').on('click', '.btnDeletePengelola', function (e) {
        e.preventDefault();

        const columnRemovePengelola = $(this).parents('tr');

        tablePengelola
            .row(columnRemovePengelola)
            .remove()
            .draw();
    });

    $('#province_id').select2({
        placeholder: 'Provinsi',
        allowClear: true,
        ajax: {
            url: '{{route("rest.provinsis")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.name,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#regencie_id').select2({
        placeholder: 'Kota',
        allowClear: true,
        ajax: {
            url: '{{route("rest.kotas")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    province_id: $('#province_id').val(),
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.name,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#district_id').select2({
        placeholder: 'Kecamatan',
        allowClear: true,
        ajax: {
            url: '{{route("rest.kecamatans")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    regencie_id: $('#regencie_id').val(),
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.name,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#village_id').select2({
        placeholder: 'Desa',
        allowClear: true,
        ajax: {
            url: '{{route("rest.desas")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    district_id: $('#district_id').val(),
                }

                return query;
            },
            processResults: function (data) {
                let results = [];
                if (data.length > 0) {
                    $.each(data, function (index, value) { 
                        results.push({
                            id: value.id,
                            text: value.name,
                        });
                    });
                }

                return { results };
            }
        }
    });

    $('#province_id').on('change', function (e) {
        let value = $(this).val();

        if (value) {
            $('#regencie_id').prop('disabled', false);
        } else {
            $('#regencie_id, #district_id, #village_id')
                .prop('disabled', true)
                .val('')
                .trigger('change');
        }
    });

    $('#regencie_id').on('change', function (e) {
        let value = $(this).val();

        if (value) {
            $('#district_id').prop('disabled', false);
        } else {
            $('#district_id, #village_id')
                .prop('disabled', true)
                .val('')
                .trigger('change');
        }
    });

    $('#district_id').on('change', function (e) {
        let value = $(this).val();

        if (value) {
            $('#village_id').prop('disabled', false);
        } else {
            $('#village_id')
                .prop('disabled', true)
                .val('')
                .trigger('change');
        }
    });
    
    $('#btnSubmit').click(function (e) { 
        e.preventDefault();
        
        let $this = $(this);

        let formData = new FormData();

        formData.append('nama', $('#nama').val());
        formData.append('alamat', $('#alamat').val());
        formData.append('kode_pos', $('#kode_pos').val());
        formData.append('latitude', $('#latitude').val());
        formData.append('longitude', $('#longitude').val());
        formData.append('total_tower', $('#total_tower').val());
        formData.append('total_unit', $('#total_unit').val());
        formData.append('website', $('#website').val());
        formData.append('facebook', $('#facebook').val());
        formData.append('instgram', $('#instgram').val());
        formData.append('email', $('#email').val());
        formData.append('telp', $('#telp').val());

        formData.append('foto_1', $('#foto_1')[0].files[0] ?? '');
        formData.append('foto_2', $('#foto_2')[0].files[0] ?? '');
        formData.append('foto_3', $('#foto_3')[0].files[0] ?? '');

        formData.append('province_id', $('#province_id').val());
        formData.append('regencie_id', $('#regencie_id').val());
        formData.append('district_id', $('#district_id').val());
        formData.append('village_id', $('#village_id').val());

        // formData.append('endpoint_username', $('#endpoint_username').val());
        // formData.append('endpoint_password', $('#endpoint_password').val());
        // formData.append('endpoint_tarif', $('#endpoint_tarif').val());
        // formData.append('endpoint_outstanding', $('#endpoint_outstanding').val());
        // formData.append('endpoint_pemilik', $('#endpoint_pemilik').val());
        // formData.append('endpoint_penghuni', $('#endpoint_penghuni').val());

        formData.append('pengembangs', JSON.stringify(tablePengembang.rows().data().toArray()));
        formData.append('pengelolas', JSON.stringify(tablePengelola.rows().data().toArray()));

        $this
            .prop('disabled', true)
            .text('Loading...');
        
        $.ajax({
            type: "POST",
            url: "{{route('rusun.store')}}",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.fire({
                    title: 'Input detail rusun?',
                    text: "Langkah ini anda bisa lewati, tapi anda tidak punya detail rusun!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, input!',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.value) {
                        window.location.href = '{{route("rusun-detail.create")}}';
                    }
                });
                
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