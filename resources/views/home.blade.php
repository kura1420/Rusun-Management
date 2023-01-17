@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        @forelse ($programs as $program)
        <div class="card card-outline card-primary card-widget collapsed-card">
            <div class="card-header">
                <div class="user-block">
                    <img class="img-circle" src="{{asset('logo.png')}}" alt="User Image" />
                    <span class="username">Program:<a href="#">{{$program->nama}}</a></span>
                    <span class="description">Publish - {{$program->publish_at}}</span>
                </div>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <p>@php echo $program->keterangan; @endphp</p>

                <div class="btn-group">
                    <button type="button" class="btn btn-default text-primary btn-sm"><i class="fas fa-share"></i> Share</button>
                    <button type="button" class="btn btn-default text-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        @foreach ($program->shareds as $key => $shared)
                        <a class="dropdown-item" href="{{$shared}}">{{ucfirst($key)}}</a>
                        @endforeach
                    </div>
                </div>
                
                <a href="{{asset('storage/program/' . $program->file)}}" target="_blank" class="btn btn-default btn-sm text-danger"><i class="fas fa-file"></i> Lampiran</a>
                <a href="{{route('blog.program-show', $program->slug)}}" class="btn btn-default btn-sm text-info"><i class="fas fa-book-reader"></i> Lebih lengkap</a>
            </div>

            <div class="card-footer">
                <strong>Daftar Kegiatan:</strong>
            </div>

            @foreach ($program->program_kegiatans as $program_kegiatan)
            <div class="card-footer card-comments">
                <div class="card-comment">
                    <div class="comment-text">
                        <span class="username">
                            {{$program_kegiatan->nama}}
                            <span class="text-muted float-right">Waktu: {{$program_kegiatan->tanggal_mulai}} s/d {{$program_kegiatan->tanggal_berakhir}}</span>
                        </span>
                        @php echo \Str::limit($program_kegiatan->informasi, 130); @endphp
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @empty
        <div class="callout callout-info">
            <h5>Program & Kegiatan</h5>
            <p>Tidak Tersedia Saat Ini</p>
        </div>
        @endforelse

        <div class="card card-outline card-danger">
            <div class="card-header border-transparent">
                <h3 class="card-title">Daftar Keluhan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Penghuni</th>
                                <th>Tingkat</th>
                                <th>Waktu Dibuat/Diperbaui</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $ticket)
                            <tr>
                                <td>
                                    <a href="{{route('komplain.show', [$ticket->id, 'status' => 'noreply'])}}">{{$ticket->kode}}</a>
                                </td>
                                <td>{{$ticket->user->name}}</td>
                                <td>
                                    @switch($ticket->tingkat)
                                        @case(1)
                                            <span class="badge badge-info">Low</span>
                                            @break
                                    
                                        @case(2)
                                            <span class="badge badge-warning">Medium</span>
                                            @break

                                        @case(3)
                                            <span class="badge badge-danger">Tinggi</span>
                                            @break
                                    
                                        @default
                                            Default case...
                                    @endswitch
                                    
                                </td>
                                <td>{{$ticket->created_at}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center"><strong>Data tidak tersedia</strong></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer clearfix">
                <a href="{{route('komplain.index', ['status' => 'noreply'])}}" class="btn btn-sm btn-secondary float-right">Lihat Semua</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-gradient-success">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="far fa-calendar-alt"></i>
                    Kalender
                </h3>
            </div>

            <div class="card-body pt-0">
                <div id="calendar" style="width: 100%;"></div>
            </div>
        </div>

        @foreach ($programs as $program)
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">{{$program->nama}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                    @foreach ($program->grups as $grup)
                    <li class="item">
                        <div class="product-info">
                            <a href="{{route('program-kanidat.show', $grup->grup_id)}}" class="product-title">{{$grup->grup_nama}} <span class="badge badge-warning float-right">{{$program->program_kanidats()->count()}} Peserta</span></a>
                            <span class="product-description">
                                {{$grup->grup_status_text}}
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="card-footer text-center">
                <a href="{{route('program-kanidat.index', ['program_id' => $grup->program_id])}}" class="uppercase">Lihat Semua</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@stop

@section('css')

@stop

@section('js')
<script src="{{ asset('js/share.js') }}"></script>
<script>
$(document).ready(function () {
    $("#calendar").datetimepicker({ format: "L", inline: true });
});
</script>
@stop