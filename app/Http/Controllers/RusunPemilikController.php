<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunPemilikRequest;
use App\Http\Requests\UpdateRusunPemilikRequest;
use App\Models\RusunPemilik;
use Illuminate\Database\Eloquent\Builder;

class RusunPemilikController extends Controller
{

    const TITLE = 'Rusun Pemilik';
    const FOLDER_VIEW = 'rusun_pemilik.';
    const URL = 'rusun-pemilik.';

    protected $sessionUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->sessionUser = auth()->user();

            return $next($request);
        });
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = self::TITLE;
        $subTitle = 'List Data';

        $user = $this->sessionUser;

        $table = RusunPemilik::orderBy('created_at')
            ->when($user, function ($query, $user) {
                if ($user->level == 'rusun') {
                    $sessionData = session()->get('rusun');

                    $query->where('rusun_id', $sessionData->id);
                }
            });

        if ($user->level == 'pemda') {
            $table->whereHas('rusuns', function (Builder $query) {
                $sessionData = session()->get('pemda');

                $query
                    ->where('province_id', $sessionData->province_id)
                    ->where('regencie_id', $sessionData->regencie_id);
            });
        }

        $rows = $table->get()
            ->map(fn($row) => [
                $row->rusuns->nama,
                $row->rusun_details->nama_tower ?? NULL,
                $row->rusun_unit_details->jenis ?? NULL,
                $row->pemiliks->nama ?? NULL,
                $row->status_text,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-warning btn-sm" title="Verifikasi"><i class="fas fa-tasks"></i> Verifikasi</a> ' .
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                '</nobr>',
            ]);

        $heads = [
            'Rusun',
            'Tower',
            'Unit',
            'Nama',
            'Status',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 10],
        ];
        
        $config = [
            'data' => $rows,
        ];

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRusunPemilikRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunPemilikRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RusunPemilik  $rusunPemilik
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = RusunPemilik::findOrFail($id);

        $row->rusun_pemilik_dokumens = $row->pemiliks
            ->rusun_pemilik_dokumens()
            ->where('rusun_unit_detail_id', $row->rusun_unit_detail_id)
            ->get()
            ->map(function ($rusun_pemilik_dokumen) {
                $rusun_pemilik_dokumen->dokumens = $rusun_pemilik_dokumen->dokumens()->first();
                $rusun_pemilik_dokumen->rusuns = $rusun_pemilik_dokumen->rusuns()->first();
                $rusun_pemilik_dokumen->rusun_details = $rusun_pemilik_dokumen->rusun_details()->first();
                $rusun_pemilik_dokumen->rusun_unit_details = $rusun_pemilik_dokumen->rusun_unit_details()->first();
    
                return $rusun_pemilik_dokumen;
            });

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row',));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RusunPemilik  $rusunPemilik
     * @return \Illuminate\Http\Response
     */
    public function edit(RusunPemilik $rusunPemilik, $id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = RusunPemilik::findOrFail($id);

        $row->rusun_pemilik_dokumens = $row->pemiliks
            ->rusun_pemilik_dokumens()
            ->where('rusun_unit_detail_id', $row->rusun_unit_detail_id)
            ->get()
            ->map(function ($rusun_pemilik_dokumen) {
                $rusun_pemilik_dokumen->dokumens = $rusun_pemilik_dokumen->dokumens()->first();
                $rusun_pemilik_dokumen->rusuns = $rusun_pemilik_dokumen->rusuns()->first();
                $rusun_pemilik_dokumen->rusun_details = $rusun_pemilik_dokumen->rusun_details()->first();
                $rusun_pemilik_dokumen->rusun_unit_details = $rusun_pemilik_dokumen->rusun_unit_details()->first();
    
                return $rusun_pemilik_dokumen;
            });

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunPemilikRequest  $request
     * @param  \App\Models\RusunPemilik  $rusunPemilik
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunPemilikRequest $request, RusunPemilik $rusunPemilik, $id)
    {
        //
        $row = RusunPemilik::findOrFail($id);

        $row->update([
            'status' => $request->status == 'verif' ? 1 : 0,
            'alasan' => $request->status == 'verif' ? '-' : $request->alasan,
        ]);

        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RusunPemilik  $rusunPemilik
     * @return \Illuminate\Http\Response
     */
    public function destroy(RusunPemilik $rusunPemilik)
    {
        //
        return abort(404);
    }
}
