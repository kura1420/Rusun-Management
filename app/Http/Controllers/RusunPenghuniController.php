<?php

namespace App\Http\Controllers;

use App\Helpers\ApiService;
use App\Http\Requests\StoreRusunPenghuniRequest;
use App\Http\Requests\UpdateRusunPenghuniRequest;
use App\Models\RusunPenghuni;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class RusunPenghuniController extends Controller
{

    const TITLE = 'Rusun Penghuni';
    const FOLDER_VIEW = 'rusun_penghuni.';
    const FOLDER_DOKUMEN = 'rusun_penghuni/dokumen';
    const URL = 'rusun-penghuni.';

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

        $table = RusunPenghuni::orderBy('updated_at', 'desc')
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
                $row->pemiliks->nama,
                $row->nama,
                // $row->email,
                // $row->phone,
                $row->status_label,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                    // '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                '</nobr>',
                $row->updated_at,
            ]);

        $heads = [
            'Rusun',
            'Tower',
            'Unit',
            'Pemilik',
            'Nama',
            // 'Email',
            // 'Phone',
            'Status',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 10],
        ];
        
        $config = [
            'data' => $rows,
        ];

        $lastUpdate = collect($rows)
            ->sortKeysDesc(9)
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
     * @param  \App\Http\Requests\StoreRusunPenghuniRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunPenghuniRequest $request)
    {
        //
        $res = ApiService::run('/penghuni', 'GET', NULL);

        return $res->object();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = RusunPenghuni::findOrFail($id);

        $row->rusun_penghuni_dokumens = $row->rusun_penghuni_dokumens->map(function ($rusun_penghuni_dokumen) {
            $rusun_penghuni_dokumen->dokumens = $rusun_penghuni_dokumen->dokumens()->first();

            return $rusun_penghuni_dokumen;
        });

        $row->rusun_pembayaran_ipls = \App\Models\RusunPembayaranIpl::where([
            ['pemilik_id', $row->pemilik_id],
            ['rusun_unit_detail_id', $row->rusun_unit_detail_id],
        ])->first();

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row',));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Http\Response
     */
    public function edit(RusunPenghuni $rusunPenghuni)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $rusunPenghuni;

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunPenghuniRequest  $request
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunPenghuniRequest $request, RusunPenghuni $rusunPenghuni)
    {
        //
        $input = $request->all();

        $identitas_file = $rusunPenghuni->identitas_file;
        if ($request->identitas_file) {
            $identitas_file = md5(uniqid()) . '.' . $request->identitas_file->extension();

            $request->file('identitas_file')
                ->storeAs(
                    self::FOLDER_DOKUMEN,
                    $identitas_file,
                    'local',
                );

            if ($rusunPenghuni->identitas_file) {
                Storage::delete(self::FOLDER_DOKUMEN . '/' . $rusunPenghuni->identitas_file);
            }
        }

        $input['identitas_file'] = $identitas_file;

        $rusunPenghuni->update($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Http\Response
     */
    public function destroy(RusunPenghuni $rusunPenghuni)
    {
        //
        return abort(404);
    }

    public function view_file($id, $file)
    {
        $row = RusunPenghuni::where('id', $id)
            ->where('identitas_file', $file)
            ->first();

        $file = storage_path('app/' . self::FOLDER_DOKUMEN . '/' . $row->identitas_file);

        return response()->file($file);
    }
}
