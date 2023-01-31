@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@if($layoutHelper->isLayoutTopnavEnabled())
    @php 
        $def_container_class = 'container'; 
        $pg = \App\Helpers\PageAction::getInformationPage();
    @endphp
@else
    @php
        $def_container_class = 'container-fluid';
        $pg = \App\Helpers\PageAction::getInformationPage();
    @endphp
@endif

{{-- Default Content Wrapper --}}
<div class="content-wrapper {{ config('adminlte.classes_content_wrapper', '') }}">

    {{-- Content Header --}}
    @hasSection('content_header')
        <div class="content-header">
            <div class="{{ config('adminlte.classes_content_header') ?: $def_container_class }}">
                {{-- Informasi Halaman --}}
                @if ($pg)
                <x-adminlte-alert title="{{$pg->judul}}" theme="info">
                    @php echo $pg->penjelasan; @endphp

                    @if ($pg->file)
                    <a href="{{route('informasi-halaman.view_file', [$pg->id, $pg->file])}}" class="btn btn-sm btn-warning text-dark" target="_blank"><strong><i class="fa fa-file"></i> View File</strong></a>
                    @endif
                </x-adminlte-alert>
                @endif

                @yield('content_header')
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="content">
        <div class="{{ config('adminlte.classes_content') ?: $def_container_class }}">
            @yield('content')
        </div>
    </div>

</div>
