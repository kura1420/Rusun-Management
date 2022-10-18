@extends('komplain.main')

@section('komplain_content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Buat Tanggapan Untuk Komplain <a href="{{route('komplain.show', [$row->id, 'status=' . $status])}}">{{$row->kode}}</a> </h3>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{route('komplain.tanggapiStore', $row->id)}}" method="post" enctype="multipart/form-data">
        @csrf
    <div class="card-body">
        <div class="form-group">
            <x-adminlte-input name="rusun_id" id="rusun_id" placeholder="Rusun" value="{{$row->rusun->nama}}" disabled />
        </div>
        <div class="form-group">
            <x-adminlte-input name="pengelola_id" id="pengelola_id" placeholder="Pengelola" value="{{$row->pengelola->nama}}" disabled />
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-9">
                    <x-adminlte-input name="judul" id="judul" placeholder="Judul" value="{{$row->judul}}" disabled />
                </div>
                <div class="col-md-3">
                    <x-adminlte-select name="tingkat" id="tingkat" disabled>
                        <option value="">Pilih Tingkat</option>
                        <option value="3" {{$row->tingkat == 3 ? 'selected' : ''}}>High</option>
                        <option value="2" {{$row->tingkat == 2 ? 'selected' : ''}}>Medium</option>
                        <option value="1" {{$row->tingkat == 1 ? 'selected' : ''}}>Low</option>
                    </x-adminlte-select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <x-adminlte-text-editor name="penjelasan" id="penjelasan" :config="[
                'height' => '300',
                'placeholder' => 'Penjelasan'
            ]">
                {{old('penjelasan')}}
            </x-adminlte-text-editor>
        </div>
        <div class="form-group">
            <div class="btn btn-default btn-file">
                <i class="fas fa-paperclip"></i> Attachment
                <input type="file" name="attachments[]" id="attachments" multiple />
            </div>
            <p class="help-block">Max. 5MB</p>
        </div>
    </div>

    <div class="card-footer">
        <div class="float-right">
            <button type="submit" id="btnSubmit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send</button>
        </div>
    </div>
    </form>
</div>
@endsection

@section('komplain_js')

@endsection