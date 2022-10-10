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
                            <li class="nav-item active">
                                <a href="{{route('komplain.index')}}" class="nav-link">
                                    <i class="fas fa-inbox text-primary"></i> Kotak Masuk
                                    <span class="badge bg-primary float-right">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link"> 
                                    <i class="fa fa-mail-bulk"></i> Belum Dijawab 
                                    <span class="badge bg-info float-right">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link"> 
                                    <i class="fa fa-spinner"></i> Proses 
                                    <span class="badge bg-warning float-right">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-thumbs-down text-danger"></i> Tidak Selesai
                                    <span class="badge bg-danger float-right">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link"> 
                                    <i class="far fa-thumbs-up text-success"></i> Selesai 
                                    <span class="badge bg-success float-right">0</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Level Komplain</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle text-danger"></i>
                                    High
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link"> <i class="far fa-circle text-warning"></i> Medium </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle text-primary"></i>
                                    Low
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
    @yield('komplain_create')
@stop