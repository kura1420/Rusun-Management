@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<x-adminlte-card theme="primary" theme-mode="outline" title="{{$subTitle}}">
        <x-slot name="toolsSlot">
            <a href="{{route('program-kegiatan.index', ['program_id' => $row->program_id])}}" class="btn btn-sm btn-dark">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </x-slot>
        
        <div class="row">
            <x-adminlte-input name="rusun" label="Rusun" placeholder="Rusun" fgroup-class="col-md-6" value="{{$row->rusun->nama}}" disabled />
            <x-adminlte-input name="program" label="Program" placeholder="Program" fgroup-class="col-md-6" value="{{$row->program->nama}}" disabled />

            <x-adminlte-input name="nama" label="Nama" placeholder="Nama" fgroup-class="col-md-6" value="{{$row->nama}}" disabled />
            <x-adminlte-input-date name="tanggal_mulai" label="Tanggal Mulai" placeholder="Tgl. Mulai" :config="['format' => 'YYYY-MM-DD']" value="{{$row->tanggal_mulai}}" fgroup-class="col-md-2" disabled />
            <x-adminlte-input-date name="tanggal_berakhir" label="Tanggal Akhir" placeholder="Tgl. Akhir" :config="['format' => 'YYYY-MM-DD']" value="{{$row->tanggal_berakhir}}" fgroup-class="col-md-2" disabled />
            <div class="form-group col-md-2">
                <label for="file">
                    Lampiran
                </label>

                <div class="input-group">
                    @if ($row->file)
                    <a href="{{asset('storage/program_kegiatan/' . $row->file)}}" class="btn btn-primary btn-md" target="_blank">Lihat</a>
                    @else
                    Tidak Tersedia
                    @endif
                </div>
            </div>

            <x-adminlte-select name="template" label="Template" placeholder="Template" fgroup-class="col-md-12" disabled>
                <option value="">Pilih</option>
                <option value="form_pendaftaran" {{$row->template == 'form_pendaftaran' ? 'selected' : ''}}>Form Pendaftaran Kanidat</option>
                <option value="polling" {{$row->template == 'polling' ? 'selected' : ''}}>Polling</option>
                <option value="laporan" {{$row->template == 'laporan' ? 'selected' : ''}}>Laporan</option>
            </x-adminlte-select>


            <div class="form-group col-md-12">
                <label for="tanggal_berakhir">
                    Informasi
                </label>

                <div class="input-group">
                    @php echo $row->informasi; @endphp
                </div>
            </div>

            <div class="form-group col-md-12">
                <label for="tanggal_berakhir">
                    Syarat & Ketentuan
                </label>

                <div class="input-group">
                    @php echo $row->syarat_ketentuan; @endphp
                </div>
            </div>

        </div>
    </x-adminlte-card>
@stop

@section('css')

@stop

@section('js')

@stop