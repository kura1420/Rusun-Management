@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Informasi, Syarat & Ketentuan Pendaftaran Kanidat</h3>
        
        <div class="card-tools">
            Berlaku: {{$programKegiatan->tanggal_mulai}} s/d {{$programKegiatan->tanggal_berakhir}}
        </div>
    </div>

    <div class="card-body">
        <div id="accordion">
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                            Informasi
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        @php echo $programKegiatan->informasi; @endphp
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-danger">
            <div class="card-header">
                <h4 class="card-title w-100">
                    <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                        Syarat & Ketentuan
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="collapse show" data-parent="#accordion">
                <div class="card-body">
                    @php echo $programKegiatan->syarat_ketentuan; @endphp
                </div>
            </div>
        </div>

        <div class="card card-warning">
            <div class="card-header">
                <h4 class="card-title w-100">
                    <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                        Syarat Kelengkapan Dokumen
                    </a>
                </h4>
            </div>
            <div id="collapseThree" class="collapse" data-parent="#accordion">
                <div class="card-body">
                    <ul>
                        @foreach ($programDokumens as $programDokumen)
                        <li>{{$programDokumen->nama}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

    <x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            @role('Root|Admin|Rusun|Pemda')
            <a href="{{route('program-kanidat.index', ['program_id' => $row->program->id])}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            @endrole

            @role('Pemilik|Penghuni')
            <a href="{{route('home')}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            @endrole
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="rusun" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$row->rusun->nama}}" disabled />
            <x-adminlte-input name="program" label="Program" placeholder="Program" fgroup-class="col-md-6" value="{{$row->program->nama}}" disabled />
            <x-adminlte-input name="grup_nama" label="Grup" placeholder="Grup" fgroup-class="col-md-6" value="{{$row->grup_nama}}" disabled />
            <x-adminlte-input name="grup_nama" label="Status" placeholder="Status" fgroup-class="col-md-6" value="{{$row->grup_status_text}}" disabled />
        </div>
    </x-adminlte-card>

    <x-adminlte-card theme="primary" theme-mode="outline" title="Daftar Kanidat">
        @role('Root|Admin|Rusun|Pemda')
        <x-slot name="toolsSlot">
            @if ($row->grup_status == 0)
            <x-adminlte-button label="Verifikasi Grup" class="btn-sm" theme="primary" icon="fab fa-telegram-plane" id="btnVerifGrup" value="{{$row->grup_id}}" />
            @endif
        </x-slot>
        @endrole

        <div class="table-responsive">
            <table class="table text-nowrap" id="table2">
                <thead>
                    <tr>
                        <th>Tower</th>
                        <th>Unit</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kanidats as $kanidat)
                    <tr>
                        <td>{{$kanidat->rusun_detail->nama_tower}}</td>
                        <td>{{$kanidat->rusun_unit_detail->jenis}}</td>
                        <td>{{$kanidat->pemilik_penghuni_profile->nama}}</td>
                        <td>{{$kanidat->program_jabatan->nama}}</td>
                        <td>{{$kanidat->status_text}}</td>
                        <td>{{$kanidat->dokumen}}</td>
                        <td>
                            @if ($row->grup_status == 0 && $kanidat->pemilik_penghuni_id == $pemilik_penghuni_id)
                            <a href="{{route('program-kanidat-dokumen.index', ['program_kanidat_id' => $kanidat->id])}}" class="btn btn-warning btn-sm" title="Upload Dokumen"><i class="fas fa-paste"></i> Upload Dokumen</a>
                            @endif

                            <button type="button" class="btn btn-info btn-sm btnCheckDocument" id="{{$kanidat->id}}"><i class="fas fa-tasks"></i> Cek Dokumen Persyaratan</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    <x-adminlte-modal id="modalCheckDocument" title="List Dokumen Peserta" theme="info" icon="fas fa-list" size='lg' static-backdrop v-centered>
        <strong>Nama Kanidat: </strong> <span id="kanidatName"></span> <br>
        <span class="d-none" id="sectionPenjelasan"><strong>Alasan Ditolak: </strong> <span id="penjelasan"></span></span>

        <input type="hidden" name="program_kanidat_id" id="program_kanidat_id">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Dokumen</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody id="listDokumenKanidat">
                
            </tbody>
        </table>

        <x-slot name="footerSlot">
            @role('Root|Admin|Rusun|Pemda')
            <x-adminlte-button class="mr-auto btnModal" theme="success" label="Terima" id="btnAccept" />
            <x-adminlte-button class="btnModal" theme="danger" label="Tolak" id="btnReject" />
            @endrole
        </x-slot>
    </x-adminlte-modal>
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

    const buttonViewFile = (urlViewFile) => {
        return `<a href="${urlViewFile}" class="btn btn-sm btn-info" target="_blank">View</a> `;
    }

    const updateStatusGrup = (data, id) => {
        $.ajax({
            type: "PUT",
            url: "{{url('program-kanidat')}}/status/" + id,
            data: data,
            dataType: "json",
            success: function (response) {
                Swal.fire("Terimakasih atas penilaiannya", "", "success");

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

    $('body').on('click', '.btnCheckDocument', function (e) {
        e.preventDefault();

        let id = $(this).attr('id');

        $.ajax({
            type: "GET",
            url: "{{url('program-kanidat-dokumen')}}/" + id,
            dataType: "json",
            success: function (response) {
                if (response.length > 0) {
                    $('#listDokumenKanidat').html('');
                    $('#kanidatName').text('');
                    $('#program_kanidat_id').val(null);
                    $('#sectionPenjelasan').addClass('d-none');
                    $('#penjelasan').text('');

                    let kanidatName = response[0].pemilik_penghuni_profile.nama;
                    let kanidatPenjelasan = response[0].program_kanidat.penjelasan;
                    let kanidatStatus = response[0].program_kanidat.status;
                    let kanidatGrupStatus = response[0].program_kanidat.grup_status;
                    
                    if (kanidatStatus == 1 || kanidatGrupStatus == 1 || kanidatGrupStatus == 2) {
                        $('.btnModal').addClass('d-none');
                    } else {
                        $('.btnModal').removeClass('d-none');
                    }

                    $('#kanidatName').text(kanidatName);
                    $('#program_kanidat_id').val(id);

                    if (kanidatPenjelasan) {
                        $('#sectionPenjelasan').removeClass('d-none');
                        $('#penjelasan').text(kanidatPenjelasan);
                    }

                    $.each(response, function (index, value) { 
                        var urlViewFile = '{{url("program-kanidat-dokumen")}}/view-file/' + value.id + '/' + value.file;

                        $('#listDokumenKanidat').append(`<tr>
                            <td>${value.program_dokumen.nama}</td>
                            <td>${buttonViewFile(urlViewFile)}</td>
                        </tr>`);
                    });

                    $('#modalCheckDocument').modal('show');
                } else {
                    Swal.fire('Data tidak tersedia.');
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

    @role('Root|Admin|Rusun|Pemda')
    $('#btnVerifGrup').click(function (e) { 
        e.preventDefault();

        var grup_id = $(this).val();
        let grupStatus = {
            __state: 'grup',
        }
        
        Swal.fire({
            title: "Verifikasi Grup",
            text: "Apakah anda sudah melakukan cek dokumen tiap kanidat?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Terima",
            denyButtonText: "Tolak",
        }).then((result) => {
            if (result.isConfirmed) {
                grupStatus.status = 'verif';

                updateStatusGrup(grupStatus, grup_id);
            } else if (result.isDenied) {
                grupStatus.status = 'unverif';

                updateStatusGrup(grupStatus, grup_id);
            }
        });
    });

    $('#btnAccept').click(function (e) { 
        e.preventDefault();

        Swal.fire({
            title: "Apakah anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, data sesuai!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "PUT",
                    url: "{{url('program-kanidat')}}/status/" + $('#program_kanidat_id').val(),
                    data: {
                        status: 'verif',
                        __state: 'kanidat',
                        penjelasan: null,
                    },
                    dataType: "json",
                    success: function (response) {
                        Swal.fire(
                            'Terimakasih!',
                            'Peserta berhasil di verifikasi!',
                            'success'
                        );

                        $('#modalCheckDocument').modal('hide');

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
        });
    });

    $('#btnReject').click(function (e) { 
        e.preventDefault();
        
        Swal.fire({
            title: 'Berikan alasan anda:',
            input: 'text',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            preConfirm: (penjelasan) => {
                $.ajax({
                    type: "PUT",
                    url: "{{url('program-kanidat')}}/status/" + $("#program_kanidat_id").val(),
                    data: {
                        status: "unverif",
                        __state: "kanidat",
                        penjelasan: penjelasan,
                    },
                    dataType: "json",
                    success: function (response) {
                        return true;
                    },
                    error: function (xhr) {
                        const { status, statusText, responseText, responseJSON } = xhr;

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
                    },
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        })
        .then((result) => {
                if (result.value) {
                    $('#modalCheckDocument').modal('hide');

                    window.location.reload();
                } 
                
                if (result.value == '') {
                    Swal.fire('Asalan wajib diisi.');
                }
            })
        });
    @endrole
});
</script>
@stop