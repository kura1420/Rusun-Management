@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
<input type="text" name="filter" id="filter" class="form-control" placeholder="Search" style="width:100%;">

<br>

@foreach ($rows as $key => $row)
<x-adminlte-card theme="primary" theme-mode="outline" title="{{$row->judul}}">
    @php echo $row->penjelasan; @endphp
</x-adminlte-card>
@endforeach

<div class="modal fade" id="modal-xl" data-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{asset('plugins/EasyAutocomplete/easy-autocomplete.min.css')}}">
@stop

@section('js')
<script src="{{asset('plugins/EasyAutocomplete/jquery.easy-autocomplete.min.js')}}"></script>
<script>
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    let faqs = {{ Js::from($rows) }};
    
    const options = {
        data: faqs,
        getValue: 'judul',
        list: {
            maxNumberOfElements: 10,
            match: {
                enabled: true,
            },
            sort: {
                enabled: true,
            },
            onHideListEvent: function () {
                const valueFilter = $('#filter').val();
                
                if (valueFilter) {
                    const grepFilter = $(faqs).filter(function (index, item) {
                        return item.judul == valueFilter
                    });
                    
                    if (grepFilter[0]) {
                        const {judul, penjelasan} = grepFilter[0];

                        $('.modal-title').text(judul);
                        $('.modal-body').html(penjelasan);
                        $('#modal-xl').modal('show');
                    }
                }
            }
        },
    }

    $('#filter').easyAutocomplete(options);
});
</script>
@stop