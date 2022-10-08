<?php

namespace App\Http\Controllers;

use App\Helpers\ApiService;
use App\Http\Requests\StorePemilikRequest;
use App\Http\Requests\UpdatePemilikRequest;
use App\Models\Pemilik;
use Illuminate\Support\Facades\Storage;

class PemilikController extends Controller
{

    const TITLE = 'Pemilik';
    const FOLDER_VIEW = 'pemilik.';
    const FOLDER_DOKUMEN = 'pemilik/dokumen';
    const URL = 'pemilik.';

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

        $rows = Pemilik::orderBy('updated_at', 'desc')
            ->when($user, function ($query, $user) {
                if ($user->level == 'pemilik') {
                    $sessionData = session()->get('pemilik');

                    $query->where('id', $sessionData->id);
                }
            })
            ->get()
            ->map(fn($row) => [
                $row->nama,
                $row->email,
                $row->phone,
                $row->identitas_tipe,
                $row->identitas_nomor,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                '</nobr>',
                $row->updated_at,
            ]);

        $heads = [
            'Nama',
            'Email',
            'Phone',
            'ID. Tipe',
            'ID. Nomor',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 10],
        ];
        
        $config = [
            'data' => $rows,
        ];

        $lastUpdate = collect($rows)
            ->sortKeysDesc(6)
            ->first();

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'heads', 'config', 'lastUpdate'));
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
     * @param  \App\Http\Requests\StorePemilikRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePemilikRequest $request)
    {
        //
        $res = ApiService::run('/pemilik', 'GET', NULL);

        return $res->object();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = Pemilik::findOrFail($id);

        if (! $this->sessionUser->can('view', $row)) {
            return abort(403, "User does not have the right roles");
        }

        $row->rusun_pemiliks = $row->rusun_pemiliks->map(function ($rusun_pemilik) {
            $rusun_pemilik->rusuns = $rusun_pemilik->rusuns()->first();
            $rusun_pemilik->rusun_details = $rusun_pemilik->rusun_details()->first();
            $rusun_pemilik->rusun_unit_details = $rusun_pemilik->rusun_unit_details()->first();

            return $rusun_pemilik;
        });

        $row->rusun_pemilik_dokumens = $row->rusun_pemilik_dokumens->map(function ($rusun_pemilik_dokumen) {
            $rusun_pemilik_dokumen->dokumens = $rusun_pemilik_dokumen->dokumens()->first();
            $rusun_pemilik_dokumen->rusuns = $rusun_pemilik_dokumen->rusuns()->first();
            $rusun_pemilik_dokumen->rusun_details = $rusun_pemilik_dokumen->rusun_details()->first();
            $rusun_pemilik_dokumen->rusun_unit_details = $rusun_pemilik_dokumen->rusun_unit_details()->first();

            return $rusun_pemilik_dokumen;
        });

        $row->rusun_penghunis = $row->rusun_penghunis->map(function ($rusun_penghuni) {
            $rusun_penghuni->rusuns = $rusun_penghuni->rusuns()->first();
            $rusun_penghuni->rusun_details = $rusun_penghuni->rusun_details()->first();
            $rusun_penghuni->rusun_unit_details = $rusun_penghuni->rusun_unit_details()->first();

            return $rusun_penghuni;
        });

        $row->rusun_pembayaran_ipls = $row->rusun_pembayaran_ipls->map(function ($rusun_pembayaran_ipl) {
            $rusun_pembayaran_ipl->kepada = $rusun_pembayaran_ipl->pemilik_penghuni_nama;
            
            $rusun_pembayaran_ipl->rusuns = $rusun_pembayaran_ipl->rusuns()->first();
            $rusun_pembayaran_ipl->rusun_details = $rusun_pembayaran_ipl->rusun_details()->first();
            $rusun_pembayaran_ipl->rusun_unit_details = $rusun_pembayaran_ipl->rusun_unit_details()->first();

            return $rusun_pembayaran_ipl;
        });

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row',));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function edit(Pemilik $pemilik)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $pemilik;

        if (! $this->sessionUser->can('update', $row)) {
            return abort(403, "User does not have the right roles");
        }

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePemilikRequest  $request
     * @param  \App\Models\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePemilikRequest $request, Pemilik $pemilik)
    {
        //
        $input = $request->all();
        
        $identitas_file = $pemilik->identitas_file;
        if ($request->identitas_file) {
            $identitas_file = md5(uniqid()) . '.' . $request->identitas_file->extension();

            $request->file('identitas_file')
                ->storeAs(
                    self::FOLDER_DOKUMEN,
                    $identitas_file,
                    'local',
                );

            if ($pemilik->identitas_file) {
                Storage::delete(self::FOLDER_DOKUMEN . '/' . $pemilik->identitas_file);
            }
        }

        $input['identitas_file'] = $identitas_file;

        $pemilik->update($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pemilik $pemilik)
    {
        //
        if (! $this->sessionUser->can('delete', $pemilik)) {
            return abort(403, "User does not have the right roles");
        }

        return abort(404);
    }

    public function view_file($id, $file)
    {
        $row = Pemilik::where('id', $id)
            ->where('identitas_file', $file)
            ->first();

        $file = storage_path('app/' . self::FOLDER_DOKUMEN . '/' . $row->identitas_file);

        return response()->file($file);
    }
}
