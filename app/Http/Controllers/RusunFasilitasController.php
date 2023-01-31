<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunFasilitasRequest;
use App\Http\Requests\UpdateRusunFasilitasRequest;
use App\Models\RusunFasilitas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class RusunFasilitasController extends Controller
{

    const TITLE = 'Rusun Fasilitas';
    const FOLDER_VIEW = 'rusun_fasilitas.';
    const FOLDER_FOTO = 'rusun_fasilitas/foto';
    const URL = 'rusun-fasilitas.';

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

        $table = RusunFasilitas::orderBy('created_at')
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
                $row->nama,
                $row->jumlah,
                $row->keterangan,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    // '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Rusun',
            'Tower',
            'Nama',
            'Jumlah',
            'Keterangan',
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
        if (! $this->sessionUser->can('create', RusunFasilitas::class)) {
            return abort(403, "User does not have the right roles");
        }

        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        $user = $this->sessionUser;

        $rusuns = \App\Models\Rusun::orderBy('nama', 'asc')
            ->when($user, function ($query, $user) {
                if ($user->level == 'rusun') {
                    $sessionData = session()->get('rusun');

                    $query->where('id', $sessionData->id);
                }
            })
            ->get();

        $namas = RusunFasilitas::select('nama')->distinct()->orderBy('nama', 'asc')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusuns', 'namas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRusunFasilitasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunFasilitasRequest $request)
    {
        //
        $input = $request->all();

        $foto = NULL;
        
        if ($request->foto) {
            $foto = md5(uniqid()) . '.' . $request->foto->extension();

            $input['foto'] = $foto;

            $request->file('foto')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto,
                    'local',
                );
        }

        if ($request->rusun_detail_id) {
            $rusun_details = explode(',', $request->rusun_detail_id);
            for ($i=0; $i < count($rusun_details); $i++) { 
                $input['rusun_detail_id'] = $rusun_details[$i];

                RusunFasilitas::create($input);
            }
        } else {
            RusunFasilitas::create($input);
        }

        return response()->json('Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RusunFasilitas  $rusunFasilitas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = RusunFasilitas::findOrFail($id);

        if (! $this->sessionUser->can('view', $row)) {
            return abort(403, "User does not have the right roles");
        }

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row',));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RusunFasilitas  $rusunFasilitas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $user = $this->sessionUser;

        $rusuns = \App\Models\Rusun::orderBy('nama', 'asc')
            ->when($user, function ($query, $user) {
                if ($user->level == 'rusun') {
                    $sessionData = session()->get('rusun');

                    $query->where('id', $sessionData->id);
                }
            })
            ->get();
            
        $namas = RusunFasilitas::select('nama')->distinct()->orderBy('nama', 'asc')->get();

        $row = RusunFasilitas::findOrFail($id);

        if (! $this->sessionUser->can('update', $row)) {
            return abort(403, "User does not have the right roles");
        }

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusuns', 'namas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunFasilitasRequest  $request
     * @param  \App\Models\RusunFasilitas  $rusunFasilitas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunFasilitasRequest $request, RusunFasilitas $rusunFasilitas)
    {
        //
    }

    public function updateAsStore(UpdateRusunFasilitasRequest $request, $id)
    {
        // 
        $rusunFasilitas = RusunFasilitas::findOrFail($id);

        $input = $request->all();

        $foto = $rusunFasilitas->foto;

        if ($request->foto) {
            $foto = md5(uniqid()) . '.' . $request->foto->extension();

            $request->file('foto')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto,
                    'local',
                );

            if ($rusunFasilitas->foto) {
                Storage::delete(self::FOLDER_FOTO . '/' . $rusunFasilitas->foto);
            }
        }

        $input['foto'] = $foto;

        $rusunFasilitas->update($input);

        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RusunFasilitas  $rusunFasilitas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $row = RusunFasilitas::findOrFail($id);

if (! $this->sessionUser->can('delete', $row)) {
            return response()->json("User does not have the right roles", 403);
        }
        
        $row->delete();

        return response()->json('Success');
    }

    public function view_file($id, $foto)
    {
        $row = RusunFasilitas::where('id', $id)
            ->where('foto', $foto)
            ->firstOrFail();

        $file = storage_path('app/' . self::FOLDER_FOTO . '/' . $row->foto);

        return response()->file($file);
    }
}
