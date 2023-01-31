@extends('komplain.main')

@section('komplain_content')
<div class="card card-widget" id="printArea">
    <div class="card-header">
        <div class="user-block">
            <img class="img-circle" src="{{asset('images/blank.png')}}" alt="User Image" />
            <span class="username"><a href="#">{{$row->judul}}</a></span>
            <span class="description">{{$row->user->name}} - {{$row->created_at}}</span>
        </div>

        <div class="card-tools">
            Kode: <strong>{{$row->kode}}</strong> | 
            Level: <strong>{{$row->tingkat_text}}</strong>
        </div>
    </div>

    <div class="card-body">
        @php echo $row->penjelasan; @endphp

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

        <button type="button" class="btn btn-success btn-sm btnMelihat"><i class="fas fa-eye"></i> Melihat</button>
        <button type="button" class="btn btn-info btn-sm" onclick="printKeluhan('printArea')"><i class="fas fa-print"></i> Print</button>

        @if (
            $row->status !== 1 && $row->status !== 3 && $row->komplain_user_id == auth()->user()->id ||
            $row->status !== 1 && $row->status !== 3 && auth()->user()->level == 'root'
        )
        <button type="button" class="btn btn-danger btn-sm" id="btnTutupKomplain"><i class="fas fa-question-circle"></i> Tutup Komplain</button>
        @endif

        <span class="float-right text-muted">{{$row->komplain_tanggapans->count()}} Dijawab</span>
    </div>

    <div class="card-footer card-comments">
        @foreach ($row->komplain_tanggapans as $key => $komplain_tanggapan)
        <div class="card-comment">
            <img class="img-circle img-sm" src="{{asset('images/blank.png')}}" alt="User Image" />
            <div class="comment-text">
                <span class="username">
                    {{$komplain_tanggapan->user->name}}
                    <span class="text-muted float-right">{{$komplain_tanggapan->created_at}}</span>
                </span>
                {{substr(strip_tags($komplain_tanggapan->penjelasan), 0, 255)}}
                
                @if (strlen(strip_tags($komplain_tanggapan->penjelasan)) > 255)
                <a href="{{route('komplain.tanggapiShow', [ $komplain_tanggapan->parent ?? $row->id, $komplain_tanggapan->id, 'status=reply'])}}" class="btn btn-xs btn-dark">
                    Lihat Lebih Lanjut
                </a>
                @endif
            </div>
        </div>
        @endforeach

        @if ($row->status !== 1 && $row->status !== 3)
            <div class="card-comment" style="margin-top: 10px;">
                <a href="{{route('komplain.tanggapi', [$row->id, 'status=' . $status])}}" class="btn btn-warning btn-block" title="Reply">
                    <i class="fas fa-reply"></i>
                    Tanggapi
                </a>
            </div>
        @endif
    </div>
</div>

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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    $('.btnMelihat').click(function (e) { 
        e.preventDefault();
        
        $('#modalUserView').modal('show');
    });

    $('#btnTutupKomplain').on('click', function () {
        Swal
            .fire({
                title: 'Informasi',
                text: 'Apakah anda ingin menutup komplain ini?',
                icon: 'info',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Puas!',
                confirmButtonAriaLabel: 'Puas dengan pelayanannya',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> Tidak Puas',
                cancelButtonAriaLabel: 'Tidak puas'
            })
            .then((res) => {
                var data = {};

                if (res.value) {
                    data.status = 'done';
                } 
                
                if (res.dismiss == 'cancel') {
                    data.status = 'undone';
                }

                if (data.status) {
                    $.ajax({
                        type: "PUT",
                        url: "{{route('komplain.update', $row->id)}}",
                        data: data,
                        dataType: "json",
                        success: function (response) {
                            Swal.fire(
                                'Informasi!',
                                'Terimakasih senang melayani anda.',
                                'success'
                            );

                            window.location.reload();
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
                }
            });
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