@extends('adminlte::page')

@section('title', $title)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">{{$row->program_kegiatan->nama}}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('program.index')}}">Program</a></li>
            <li class="breadcrumb-item"><a href="{{route('program-kegiatan.index', ['program_id' => $row->program_id])}}">Kegiatan</a></li>
            <li class="breadcrumb-item active">Laporan</li>
        </ol>
    </div>
</div>
@stop

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<x-adminlte-card theme="primary" theme-mode="outline" title="Informasi">
    <x-slot name="toolsSlot">
        <a href="{{route('program-laporan.index', ['program_kegiatan_id' => $row->program_kegiatan_id])}}" class="btn btn-sm btn-dark">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </x-slot>

    <div class="row">
        <x-adminlte-input name="rusun" id="rusun" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$row->rusun->nama}}" disabled />
        <x-adminlte-input name="program" id="program" label="Program" placeholder="Program" fgroup-class="col-md-6" value="{{$row->program->nama}}" disabled />
        <x-adminlte-input name="kegiatan" id="kegiatan" label="Kegiatan" placeholder="Kegiatan" fgroup-class="col-md-6" value="{{$row->program_kegiatan->nama}}" disabled />
        <x-adminlte-input name="tanggal_mulai" id="tanggal_mulai" label="Tanggal Mulai" placeholder="Tanggal Mulai" fgroup-class="col-md-2" value="{{date('d M Y', strtotime($row->program_kegiatan->tanggal_mulai))}}" disabled />
        <x-adminlte-input name="tanggal_berakhir" id="tanggal_berakhir" label="Tanggal Berakhir" placeholder="Tanggal Berakhir" fgroup-class="col-md-2" value="{{date('d M Y', strtotime($row->program_kegiatan->tanggal_berakhir))}}" disabled />
        <div class="form-group col-md-2">
            <label for="file">
                Lampiran
            </label>

            <div class="input-group">
                @if ($row->program_kegiatan->file)
                <a href="{{asset('storage/program_kegiatan/' . $row->program_kegiatan->file)}}" class="btn btn-primary btn-md" target="_blank">Lihat</a>
                @else
                Tidak Tersedia
                @endif
            </div>
        </div>
        <x-adminlte-input name="template" label="Template" placeholder="Template" fgroup-class="col-md-12" value="{{$row->program_kegiatan->template_text}}" disabled />
    </div>
</x-adminlte-card>

<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">{{$subTitle}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Detail</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                        <form action="{{route('program-laporan.update', $row->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <x-adminlte-input name="judul" id="judul" label="Judul" placeholder="Judul" fgroup-class="col-md-10" value="{{$row->judul}}" disabled />
                                <x-adminlte-input-date name="tanggal" id="tanggal" label="Tanggal" placeholder="Tanggal" fgroup-class="col-md-2" :config="['format' => 'YYYY-MM-DD']" value="{{$row->tanggal}}" disabled />

                                <div class="form-group col-md-12">
                                    <label for="penjelasan">
                                        Penjelasan
                                    </label>

                                    <div class="input-group">
                                        @php echo $row->penjelasan; @endphp
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <strong>File dokumentasi:</strong>
                                    <ul>
                                        @foreach ($row->program_laporan_dokumens as $key => $program_laporan_dokumen) 
                                        <li>
                                            <a href="{{route('program-laporan.view-file', [$program_laporan_dokumen->id, $program_laporan_dokumen->filename])}}" target="_blank">View {{ $loop->iteration }}</a>
                                            <button type="button" class="btn btn-icon text-danger btnDelete" id="{{route('program-laporan.dokumentasi-delete', $program_laporan_dokumen->id)}}" value="{{$program_laporan_dokumen->id}}"><i class="fa fa-trash-alt"></i></button>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                        @switch($row->program_kegiatan->template)
                            @case('form_pendaftaran')
                                @include('program_laporan.form_pendaftaran')
                                @break
                        
                            @case('polling')
                                @include('program_laporan.polling')
                                @break
                        
                            @default
                                No Defined
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
table.dataTable td.dt-control {
  text-align: center;
  cursor: pointer;
}
table.dataTable td.dt-control:before {
  height: 1em;
  width: 1em;
  margin-top: -9px;
  display: inline-block;
  color: white;
  border: 0.15em solid white;
  border-radius: 1em;
  box-shadow: 0 0 0.2em #444;
  box-sizing: content-box;
  text-align: center;
  text-indent: 0 !important;
  font-family: "Courier New", Courier, monospace;
  line-height: 1em;
  content: "+";
  background-color: #31b131;
}
table.dataTable tr.dt-hasChild td.dt-control:before {
  content: "-";
  background-color: #d33333;
}
</style>
@stop

@section('js')
<script>
var program_id = '{{$row->program_id}}';
</script>

    @switch($row->program_kegiatan->template)
        @case('form_pendaftaran')
            <script src="{{asset('js/program_laporan/form_pendaftaran.js')}}"></script>
            @break

        @case('polling')
            <script src="{{asset('js/program_laporan/polling.js')}}"></script>
            @break

        @default
            
    @endswitch
@stop