@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Folders</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a href="{{route('komplain.index')}}?status=noreply" class="nav-link"> 
                                    <i class="fa fa-mail-bulk text-info"></i> Belum Dijawab 
                                    <span class="badge bg-info float-right" id="noReplyTotal">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('komplain.index')}}?status=reply" class="nav-link"> 
                                    <i class="fa fa-spinner"></i> Dijawab 
                                    <span class="badge bg-warning float-right" id="replyTotal">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('komplain.index')}}?status=undone" class="nav-link">
                                    <i class="fas fa-thumbs-down text-danger"></i> Tidak Puas
                                    <span class="badge bg-danger float-right" id="undoneTotal">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('komplain.index')}}?status=done" class="nav-link"> 
                                    <i class="far fa-thumbs-up text-success"></i> Puas 
                                    <span class="badge bg-success float-right" id="doneTotal">0</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Level</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a href="{{route('komplain.index')}}?status={{$status}}&tingkat=high" class="nav-link">
                                    <i class="far fa-circle text-danger"></i> High

                                    <span class="float-right text-bold" id="highTotal">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('komplain.index')}}?status={{$status}}&tingkat=medium" class="nav-link">
                                    <i class="far fa-circle text-warning"></i> Medium 

                                    <span class="float-right text-bold" id="mediumTotal">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('komplain.index')}}?status={{$status}}&tingkat=low" class="nav-link">
                                    <i class="far fa-circle text-primary"></i> Low

                                    <span class="float-right text-bold" id="lowTotal">0</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                @yield('komplain_content')
            </div>
        </div>
    </div>
</section>
@stop

@section('css')

@stop

@section('js')
<script>
$(document).ready(function () {
    $.get(
        "{{route('komplain.apiList')}}",
        function (data, textStatus, jqXHR) {
            const {noReply, reply, undone, done, high, medium, low} = data;

            $('#noReplyTotal').text(noReply);
            $('#replyTotal').text(reply);
            $('#undoneTotal').text(undone);
            $('#doneTotal').text(done);

            $('#highTotal').text(high);
            $('#mediumTotal').text(medium);
            $('#lowTotal').text(low);
        },
        "json"
    );
});
</script>

@yield('komplain_js')
@stop