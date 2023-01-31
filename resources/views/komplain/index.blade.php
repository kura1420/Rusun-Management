@extends('komplain.main')

@section('komplain_content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Kotak Masuk</h3>
        <div class="card-tools">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" id="text_search" placeholder="Cari Data" />
                <div class="input-group-append" id="btnSearch">
                    <div class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="mailbox-controls">
            <button type="button" class="btn btn-default btn-sm btnReload">
                <i class="fas fa-sync-alt"></i>
            </button>
            <div class="float-right">
                PerPage: <strong>{{$rows->perPage()}}</strong> &nbsp;
                <div class="btn-group">
                    @if ($rows->previousPageUrl())
                    <a href="{{route('komplain.index')}}{{$rows->previousPageUrl()}}" class="btn btn-default btn-sm">
                        <i class="fas fa-angle-left"></i>
                    </a>
                    @endif

                    @if ($rows->nextPageUrl())
                    <a href="{{route('komplain.index')}}{{$rows->nextPageUrl()}}" class="btn btn-default btn-sm">
                        <i class="fas fa-angle-right"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
                <tbody>
                    @if ($rows->count() > 0)
                        @foreach ($rows as $key => $row)
                        <tr>
                            <td class="mailbox-star">
                                @switch($row->tingkat)
                                    @case(1)
                                        <i class="fa fa-exclamation-triangle text-info"></i>
                                        @break
                                
                                    @case(2)
                                        <i class="fa fa-exclamation-triangle text-warning"></i>
                                        @break

                                    @case(3)
                                        <i class="fa fa-exclamation-triangle text-danger"></i>
                                        @break
                                
                                    @default
                                        Default case...
                                @endswitch
                                
                            </td>
                            <td class="mailbox-name"><a href="{{route('komplain.show', $row->id)}}?status={{$status}}">{{$row->kode}}</a></td>
                            <td class="mailbox-subject"><b>{{$row->user->name}}</b> - {{$row->judul}}</td>
                            <td class="mailbox-attachment">{{$row->rusun->nama}}</td>
                            <td class="mailbox-date">{{$row->created_at}}</td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="5" style="text-align: center;"><strong>Data tidak tersedia</strong></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer p-0">
        <div class="mailbox-controls">
            <button type="button" class="btn btn-default btn-sm btnReload">
                <i class="fas fa-sync-alt"></i>
            </button>
            <div class="float-right">
                PerPage: <strong>{{$rows->perPage()}}</strong> &nbsp;
                <div class="btn-group">
                    @if ($rows->previousPageUrl())
                    <a href="{{route('komplain.index')}}{{$rows->previousPageUrl()}}" class="btn btn-default btn-sm">
                        <i class="fas fa-angle-left"></i>
                    </a>
                    @endif

                    @if ($rows->nextPageUrl())
                    <a href="{{route('komplain.index')}}{{$rows->nextPageUrl()}}" class="btn btn-default btn-sm">
                        <i class="fas fa-angle-right"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
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

    const params = {{Js::from($params)}};

    $('.btnReload').click(function (e) { 
        e.preventDefault();
        
        window.location.reload();
    });

    $('#text_search').keyup(function (e) { 
        e.preventDefault();

        if (e.keyCode === 13) {
            let value = $(this).val();

            ajaxSearch(value);
        }
    });

    $('#btnSearch').click(function (e) { 
        e.preventDefault();
        
        let value = $('#text_search').val();

        ajaxSearch(value);
    });

    const ajaxSearch = value => {
        if (value !== '' && value !== null) {
            params.search = value;
        } else {
            params.search = null;
        }

        $.each(params, function (index, value) { 
            if (! value) {
                delete params[index];
            }
        });
        
        const u = new URLSearchParams(params).toString();

        window.location.href = '{{route("komplain.index")}}?' + u;
    }
});
</script>
@endsection