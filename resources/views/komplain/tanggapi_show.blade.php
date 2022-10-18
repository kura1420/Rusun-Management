@extends('komplain.main')

@section('komplain_content')
<div class="card card-primary card-outline" id="printArea">
    <div class="card-header">
        <h3 class="card-title">Jawaban</h3>
        <div class="float-right">
            <button type="button" onclick="window.history.back()" class="btn btn-sm btn-secondary"><i class="fas fa-angle-left"></i> Kembali</button>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="mailbox-read-info">
            <h5>{{$row->komplain->judul}}</h5>
            <h6>
                Yang Menjawab: <a href="#" class="__cf_email__">{{$row->user->name}}</a> <span class="mailbox-read-time float-right">{{$row->created_at}}</span>
            </h6>
        </div>

        <div class="mailbox-controls with-border text-center">
            <div class="btn-group">
                <a href="{{route('komplain.tanggapiKembali', [$row->id, 'status=reply'])}}" class="btn btn-default btn-sm" title="Reply">
                    <i class="fas fa-reply"></i>
                </a>

                <button type="button" class="btn btn-default btn-sm btnMelihat" data-container="body" title="Show">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <button type="button" class="btn btn-default btn-sm" title="Print" onclick="printKeluhan('printArea')">
                <i class="fas fa-print"></i>
            </button>
        </div>

        <div class="mailbox-read-message">
            @php echo $row->penjelasan; @endphp
        </div>
    </div>

    <div class="card-footer bg-white">
        <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
            @foreach ($row->komplain_files as $key => $komplain_file)
                @switch($komplain_file->tipe)
                    @case('pdf')
                        <li>
                            <span class="mailbox-attachment-icon"><i class="far fa-file-pdf text-danger"></i></span>
                            <div class="mailbox-attachment-info">
                                <a href="{{route('komplain.view_file', [$komplain_file->id, $komplain_file->filename, 'type=preview'])}}" class="mailbox-attachment-name" target="_blank"><i class="fas fa-paperclip"></i> {{$komplain_file->filename}}</a>
                                <span class="mailbox-attachment-size clearfix mt-1">
                                    <a href="{{route('komplain.view_file', [$komplain_file->id, $komplain_file->filename, 'type=download'])}}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                                </span>
                            </div>
                        </li>
                        @break
                
                    @case('png')
                    @case('jpg')
                    @case('jpeg')
                        <li>
                            <span class="mailbox-attachment-icon has-img"><img src="{{route('komplain.view_file', [$komplain_file->id, $komplain_file->filename, 'type=preview'])}}" alt="Attachment" /></span>
                            <div class="mailbox-attachment-info">
                                <a href="{{route('komplain.view_file', [$komplain_file->id, $komplain_file->filename, 'type=preview'])}}" class="mailbox-attachment-name" target="_blank"><i class="fas fa-camera"></i> {{$komplain_file->filename}}</a>
                                <span class="mailbox-attachment-size clearfix mt-1">
                                    <a href="{{route('komplain.view_file', [$komplain_file->id, $komplain_file->filename, 'type=download'])}}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                                </span>
                            </div>
                        </li>
                        @break
                
                    @default
                        Default case...
                @endswitch
            
            
            @endforeach
        </ul>
    </div>

    <div class="card-footer">
        <div class="float-right">
            <a href="{{route('komplain.tanggapiKembali', [$row->id, 'status=reply'])}}" class="btn btn-default" title="Reply">
                <i class="fas fa-reply"></i> Tanggapi
            </a>
            <button type="button" class="btn btn-default btnMelihat"><i class="fas fa-eye"></i> Yang Melihat</button>
        </div>
        <button type="button" class="btn btn-default" onclick="printKeluhan('printArea')"><i class="fas fa-print"></i> Print</button>
    </div>
</div>

<x-adminlte-card theme="success" title="Penjelasan Yang Ditanggapi" theme-mode="outline">
    Yang menjelaskan sebelumnya: <strong>{{$parent->user->name}}</strong> <br>

    <div class="direct-chat-text">
        @php echo $parent->penjelasan; @endphp
    </div>

    <div class="card-footer bg-white">
        <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
            @foreach ($parent->komplain_files as $key => $komplain_file)
                @switch($komplain_file->tipe)
                    @case('pdf')
                        <li>
                            <span class="mailbox-attachment-icon"><i class="far fa-file-pdf text-danger"></i></span>
                            <div class="mailbox-attachment-info">
                                <a href="{{route('komplain.view_file', [$komplain_file->id, $komplain_file->filename, 'type=preview'])}}" class="mailbox-attachment-name" target="_blank"><i class="fas fa-paperclip"></i> {{$komplain_file->filename}}</a>
                                <span class="mailbox-attachment-size clearfix mt-1">
                                    <a href="{{route('komplain.view_file', [$komplain_file->id, $komplain_file->filename, 'type=download'])}}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                                </span>
                            </div>
                        </li>
                        @break
                
                    @case('png')
                    @case('jpg')
                    @case('jpeg')
                        <li>
                            <span class="mailbox-attachment-icon has-img"><img src="{{route('komplain.view_file', [$komplain_file->id, $komplain_file->filename, 'type=preview'])}}" alt="Attachment" /></span>
                            <div class="mailbox-attachment-info">
                                <a href="{{route('komplain.view_file', [$komplain_file->id, $komplain_file->filename, 'type=preview'])}}" class="mailbox-attachment-name" target="_blank"><i class="fas fa-camera"></i> {{$komplain_file->filename}}</a>
                                <span class="mailbox-attachment-size clearfix mt-1">
                                    <a href="{{route('komplain.view_file', [$komplain_file->id, $komplain_file->filename, 'type=download'])}}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                                </span>
                            </div>
                        </li>
                        @break
                
                    @default
                        Default case...
                @endswitch
            
            
            @endforeach
        </ul>
    </div>
</x-adminlte-card>

<x-adminlte-modal id="modalUserView" title="User Melihat" theme="secondary" size='lg' v-centered static-backdrop scrollable>
    <div class="table-responsive">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @if (count($row->komplain_user_bukas) > 0)
                    @foreach ($row->komplain_user_bukas as $key => $komplain_user_buka)
                    <tr>
                        <td>{{$loop->iteration}}.</td>
                        <td>{{$komplain_user_buka->user->name}}</td>
                        <td>{{$komplain_user_buka->waktu}}</td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="3" align="center"><strong>Tidak ada yang melihat</strong></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</x-adminlte-modal>
@endsection

@section('komplain_js')
<script>
$(document).ready(function () {
    const tableTanggapan = $('#tableTanggapan').DataTable();

    $('.btnMelihat').click(function (e) { 
        e.preventDefault();
        
        $('#modalUserView').modal('show');
    });
});

function printKeluhan(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
@endsection