@extends('blog.default')

@section('show')
<main role="main" class="container">
    <div class="row">
        <div class="col-md-12 blog-main">
            <h3 class="pb-4 mb-4 font-italic border-bottom">
                Program & Kegiatan
            </h3>

            <div class="blog-post">
                <h2 class="blog-post-title">{{$row->nama}}</h2>
                <p class="blog-post-meta">{{date('F m Y', strtotime($row->publish_at))}}</p>

                <p>@php echo $row->keterangan; @endphp</p>
            </div>
            <!-- /.blog-post -->

            @foreach ($row->program_kegiatans as $program_kegiatan)
            <div class="blog-post">
                <h2 class="blog-post-title">{{$program_kegiatan->nama}}</h2>
                <p class="blog-post-meta">Periode Waktu: {{$program_kegiatan->tanggal_mulai}} sd {{$program_kegiatan->tanggal_berakhir}}</p>

                <strong>Informasi</strong>
                <p>@php echo $program_kegiatan->informasi; @endphp</p>
                
                <strong>Syarat & Ketentuan</strong>
                <p>@php echo $program_kegiatan->syarat_ketentuan; @endphp</p>
            </div>
            @endforeach

            <nav class="blog-pagination">
                <a class="btn btn-outline-primary" href="{{route('blog.index')}}">Kembali</a>
            </nav>
        </div>
        <!-- /.blog-main -->
    </div>
    <!-- /.row -->
</main>
@endsection