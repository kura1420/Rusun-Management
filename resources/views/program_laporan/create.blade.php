@extends('adminlte::page')

@section('title', $title)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">{{$programKegiatan->nama}}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('program.index')}}">Program</a></li>
            <li class="breadcrumb-item"><a href="{{route('program-kegiatan.index', ['program_id' => $programKegiatan->program_id])}}">Kegiatan</a></li>
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
        <a href="{{route('program-laporan.index', ['program_kegiatan_id' => $programKegiatan->id])}}" class="btn btn-sm btn-dark">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </x-slot>

    <div class="row">
        <x-adminlte-input name="rusun" id="rusun" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$programKegiatan->rusun->nama}}" disabled />
        <x-adminlte-input name="program" id="program" label="Program" placeholder="Program" fgroup-class="col-md-6" value="{{$programKegiatan->program->nama}}" disabled />
        <x-adminlte-input name="kegiatan" id="kegiatan" label="Kegiatan" placeholder="Kegiatan" fgroup-class="col-md-6" value="{{$programKegiatan->nama}}" disabled />
        <x-adminlte-input name="tanggal_mulai" id="tanggal_mulai" label="Tanggal Mulai" placeholder="Tanggal Mulai" fgroup-class="col-md-2" value="{{date('d M Y', strtotime($programKegiatan->tanggal_mulai))}}" disabled />
        <x-adminlte-input name="tanggal_berakhir" id="tanggal_berakhir" label="Tanggal Berakhir" placeholder="Tanggal Berakhir" fgroup-class="col-md-2" value="{{date('d M Y', strtotime($programKegiatan->tanggal_berakhir))}}" disabled />
        <div class="form-group col-md-2">
            <label for="file">
                Lampiran
            </label>

            <div class="input-group">
                @if ($programKegiatan->file)
                <a href="{{asset('storage/program_kegiatan/' . $programKegiatan->file)}}" class="btn btn-primary btn-md" target="_blank">Lihat</a>
                @else
                Tidak Tersedia
                @endif
            </div>
        </div>
        <x-adminlte-input name="template" label="Template" placeholder="Template" fgroup-class="col-md-12" value="{{$programKegiatan->template_text}}" disabled />
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
                        <form action="{{route('program-laporan.store')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <input type="hidden" name="program_kegiatan_id" value="{{$programKegiatan->id}}">

                                <x-adminlte-input name="judul" id="judul" label="Judul" placeholder="Judul" fgroup-class="col-md-10" value="{{old('judul')}}" />
                                <x-adminlte-input-date name="tanggal" id="tanggal" label="Tanggal" placeholder="Tanggal" fgroup-class="col-md-2" :config="['format' => 'YYYY-MM-DD']" value="{{old('tanggal')}}" />

                                <x-adminlte-text-editor name="penjelasan" id="penjelasan" label="Penjelasan" fgroup-class="col-md-12" :config="[
                                    'height' => '300',
                                ]">
                                    @php echo old('penjelasan'); @endphp
                                </x-adminlte-text-editor>

                                <div class="form-group col-md-12">
                                    <x-adminlte-input type="file" name="dokumentasis[]" id="dokumentasis" label="Dokumentasi File" placeholder="Dokumentasi File" multiple>
                                        <x-slot name="bottomSlot">
                                            <small class="text-lightblue">*Maksimul file hanya 5</small>
                                        </x-slot>
                                    </x-adminlte-input>
                                </div>
                            </div>

                            <x-adminlte-button type="submit" class="btn-sm" label="Simpan" theme="primary" icon="fab fa-telegram-plane" />
                        </form>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                        @switch($programKegiatan->template)
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
var program_id = '{{$programKegiatan->program_id}}';
</script>

    @switch($programKegiatan->template)
        @case('form_pendaftaran')
            <script src="{{asset('js/program_laporan/form_pendaftaran.js')}}"></script>
            @break

        @case('polling')
            @include('program_laporan.polling')
            @break

        @default
            
    @endswitch
@stop