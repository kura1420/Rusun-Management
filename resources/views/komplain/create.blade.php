@extends('komplain.main')

@section('komplain_content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Buat Pesan Baru</h3>
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

    <form action="{{route('komplain.store')}}" method="post" enctype="multipart/form-data">
        @csrf
    <div class="card-body">
        <div class="form-group">
            <x-adminlte-select2 name="rusun_id" :config="[
                'placeholder' => 'Pilih Rusun',
                'allowClear' => true,
            ]">
                <option value=""></option>
                @foreach ($rusuns as $key => $rusun)
                <option value="{{$rusun->id}}" {{old('rusun_id') == $rusun->id ? 'selected' : ''}}>{{$rusun->nama}}</option>
                @endforeach
            </x-adminlte-select2>
        </div>
        <div class="form-group">
            <x-adminlte-select2 name="pengelola_id" id="pengelola_id">
                <option value=""></option>
            </x-adminlte-select2>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-9">
                    <x-adminlte-input name="judul" id="judul" placeholder="Judul" value="{{old('judul')}}" />
                </div>
                <div class="col-md-3">
                    <x-adminlte-select name="tingkat" id="tingkat">
                        <option value="">Pilih Tingkat</option>
                        <option value="3" {{old('tingkat') == 3 ? 'selected' : ''}}>High</option>
                        <option value="2" {{old('tingkat') == 2 ? 'selected' : ''}}>Medium</option>
                        <option value="1" {{old('tingkat') == 1 ? 'selected' : ''}}>Low</option>
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
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    $('#rusun_id').change(function (e) { 
        e.preventDefault();
        
        let value = $(this).val();

        if (!value) {
            $('#pengelola_id').val(null).trigger('change');
        }
    });

    $('#pengelola_id').select2({
        placeholder: 'Pilih Pengelola',
        allowClear: true,
        ajax: {
            url: '{{route("pengelola.apiList")}}',
            dataType: 'json',
            data: function (params) {
                var query = {
                    rusun_id: $("#rusun_id").val(),
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
                            text: value.nama
                        });
                    });
                }

                return { results };
            }
        }
    });
});
</script>
@endsection