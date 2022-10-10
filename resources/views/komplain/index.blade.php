@extends('komplain.main')

@section('komplain_content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Kotak Masuk</h3>
        <div class="card-tools">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" placeholder="Cari Data" />
                <div class="input-group-append">
                    <div class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="mailbox-controls">
            <button type="button" class="btn btn-default btn-sm">
                <i class="fas fa-sync-alt"></i>
            </button>
            <div class="float-right">
                Total: <strong>{{count($rows)}}</strong> &nbsp;
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="btn btn-default btn-sm">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
                <tbody>
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
                        <td class="mailbox-name"><a href="{{route('komplain.show', $row->id)}}">{{$row->kode}}</a></td>
                        <td class="mailbox-subject"><b>{{$row->user->name}}</b> - {{$row->judul}}</td>
                        <td class="mailbox-attachment">{{$row->rusun->nama}}</td>
                        <td class="mailbox-date">{{$row->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer p-0">
        <div class="mailbox-controls">
            <button type="button" class="btn btn-default btn-sm">
                <i class="fas fa-sync-alt"></i>
            </button>
            <div class="float-right">
                Total: <strong>{{count($rows)}}</strong> &nbsp;
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="btn btn-default btn-sm">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('komplain_create')

@endsection