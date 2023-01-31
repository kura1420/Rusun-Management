<div class="row">
    <div class="col-md">
        <x-adminlte-info-box title="Total Suara Masuk" text="{{$pollingKanidatCount}}" icon="fas fa-lg fa-download" icon-theme="purple" />
    </div>
    <div class="col-md">
        <x-adminlte-info-box title="{{$totalPemilikPenghuni}}" text="Total Penghuni Diluar Kanidat" icon="fas fa-lg fa-users text-primary" theme="gradient-primary" icon-theme="white"/>
    </div>
</div>

<div class="card card-outline card-danger">
    <div class="card-header">
        <h5 class="card-title">
            Data terkahir update: <strong>{{$pollingKanidat->waktu ?? 'Tidak Tersedia'}}</strong>
        </h5>
    </div>
    <div class="card-footer">
        <div class="row">
            @forelse ($grups as $grup)
            <div class="col-md">
                <div class="description-block border-right">
                    <h3>{{$grup->total_suara_percent}} %</h3>
                    <h1>{{$grup->total_suara}}</h1>
                    <span class="description-text text-bold">
                        <a href="javascript:void(0)" class="btnDetailGrup" id="{{route('program-kanidat.show-detail', [$grup->program_id, $grup->grup_id])}}">{{$grup->grup_nama}}</a>
                    </span>
                </div>
            </div>
            @empty
            <div class="col-md">
                <div class="description-block border-right">
                    <h5 class="description-header">Data tidak tersedia</h5>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<x-adminlte-card theme="danger" title="Daftar Yang Sudah Memberikan Suara" theme-mode="outline">
    <div class="table-responsive">
        <table class="table table-striped" id="listPengambilanSuara">
            <thead>
                <tr>
                    <th style="width: 10px;">#</th>
                    <th>Nama</th>
                    <th>Telp</th>
                    <th>Email</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pollingKanidats as $key => $pollingKanidat)
                <tr>
                    <td>{{$loop->iteration}}.</td>
                    <td>{{$pollingKanidat->pemilik_penghuni_memilih_profile->nama}}</td>
                    <td>{{$pollingKanidat->pemilik_penghuni_memilih_profile->telp}}</td>
                    <td>{{$pollingKanidat->pemilik_penghuni_memilih_profile->email}}</td>
                    <td>{{$pollingKanidat->waktu}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-adminlte-card>

<x-adminlte-modal id="modalListKanidat" title="List Kanidat" theme="success" size="lg" v-centered static-backdrop scrollable>
    <div class="table-responsive">
        <table class="table table-hover text-nowrap" id="listKanidat">
            <thead>
                <tr>
                    <th>Tower</th>
                    <th>Unit</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</x-adminlte-modal>