@extends('komplain.main')

@section('komplain_content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Kode: <strong>{{$row->kode}}</strong></h3>
        <div class="float-right">
            <button type="button" onclick="window.history.back()" class="btn btn-sm btn-secondary"><i class="fas fa-angle-left"></i> Kembali</button>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="mailbox-read-info">
            <h5>{{$row->judul}}</h5>
            <h6>
                From: <a href="#" class="__cf_email__">{{$row->user->name}}</a> <span class="mailbox-read-time float-right">{{$row->created_at}}</span>
            </h6>
        </div>

        <div class="mailbox-controls with-border text-center">
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm" data-container="body" title="Reply">
                    <i class="fas fa-reply"></i>
                </button>
                <button type="button" class="btn btn-default btn-sm btnMelihat" data-container="body" title="Show">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <button type="button" class="btn btn-default btn-sm" title="Print">
                <i class="fas fa-print"></i>
            </button>
        </div>

        <div class="mailbox-read-message">
            @php echo $row->penjelasan; @endphp
        </div>
    </div>

    <!-- <div class="card-footer bg-white">
        <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
            <li>
                <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>
                <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> Sep2014-report.pdf</a>
                    <span class="mailbox-attachment-size clearfix mt-1">
                        <span>1,245 KB</span>
                        <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                    </span>
                </div>
            </li>
            <li>
                <span class="mailbox-attachment-icon has-img"><img src="https://adminlte.io/themes/v3/dist/img/photo1.png" alt="Attachment" /></span>
                <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-camera"></i> photo1.png</a>
                    <span class="mailbox-attachment-size clearfix mt-1">
                        <span>2.67 MB</span>
                        <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                    </span>
                </div>
            </li>
        </ul>
    </div> -->

    <div class="card-footer">
        <div class="float-right">
            <button type="button" class="btn btn-default"><i class="fas fa-reply"></i> Menjawab</button>
            <button type="button" class="btn btn-default btnMelihat"><i class="fas fa-eye"></i> Yang Melihat</button>
        </div>
        <button type="button" class="btn btn-default"><i class="fas fa-print"></i> Print</button>
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
                @foreach ($row->komplain_user_bukas()->get() as $key => $komplain_user_buka)
                <tr>
                    <td>{{$loop->iteration}}.</td>
                    <td>{{$komplain_user_buka->user->name}}</td>
                    <td>{{$komplain_user_buka->waktu_format}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-adminlte-modal>
@endsection

@section('komplain_js')
<script>
$(document).ready(function () {
    $('.btnMelihat').click(function (e) { 
        e.preventDefault();
        
        $('#modalUserView').modal('show');
    });
});
</script>
@endsection