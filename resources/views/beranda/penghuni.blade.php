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

            @if ($program->form_pendaftaran)
                @if ($program->undangan)
                <a href="{{$program->undangan}}" class="btn btn-default btn-sm text-secondary"><i class="fas fa-envelope-open-text"></i> Undangan</a>
                @endif

                @if ($program->register)
                <a href="{{route('program-kanidat.register', $program->id)}}" class="btn btn-default btn-sm text-secondary"><i class="fas fa-pencil-alt"></i> Daftarkan Tim Anda</a>
                @endif
            @endif

            @if ($program->polling_result)
                <a href="{{route('polling-kanidat.index', ['program_id' => $program->id])}}" class="btn btn-success btn-sm"><i class="fas fa-tasks"></i> Hasil Pemilihan</a>
            @endif
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

        @forelse ($programTeams as $key => $programTeam)
            @if($programTeam)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$programTeam->nama}}</h3>
                    <div class="card-tools">
                        <span class="badge badge-danger">{{$programTeam->team_count}} Member</span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <ul class="users-list clearfix">
                        @forelse ($programTeam->teams as $team)
                        <li>
                            <img src="{{asset('images/blank.png')}}" alt="{{$team->profile->nama}}" />
                            <a class="users-list-name" href="#">{{$team->profile->nama}}</a>
                            <span class="users-list-date">{{$team->status_text}}</span>
                        </li>
                        @empty
                        <li>Tidak tersedia</li>
                        @endforelse
                    </ul>
                </div>

                @if ($programTeam->teams)
                <div class="card-footer text-center">
                    <a href="{{route('program-kanidat.show', $programTeam->teams[$key]->grup_id)}}">{{$programTeam->teams[$key]->grup_nama}}</a>
                </div>
                @endif
            </div>
            @endif
        @empty

        @endforelse
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