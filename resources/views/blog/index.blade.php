@extends('blog.default')

@section('list')
<div class="row mb-2">
    @foreach ($rows as $row)
    <div class="col-md-12">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col p-4 d-flex flex-column position-static">
                <strong class="d-inline-block mb-2 text-primary">Program & Kegiatan</strong>
                <h3 class="mb-0">{{$row->nama}}</h3>
                <div class="mb-1 text-muted">{{$row->publish_at}}</div>
                <p class="card-text mb-auto">@php echo $row->keterangan; @endphp.</p>
                <a href="{{route('blog.program-show', $row->slug)}}" class="stretched-link">Selengkapnya</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{ $rows->links() }}
@endsection