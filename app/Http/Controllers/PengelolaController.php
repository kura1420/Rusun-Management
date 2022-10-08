<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengelolaRequest;
use App\Http\Requests\UpdatePengelolaRequest;
use App\Models\Pengelola;

class PengelolaController extends Controller
{

    const TITLE = 'Pengelola';
    const FOLDER_VIEW = 'pengelola.';
    const URL = 'pengelola.';

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

        $rows = Pengelola::orderBy('created_at')
            ->when($user, function ($query, $user) {
                if ($user->level == 'pengelola') {
                    $sessionData = session()->get('pengelola');

                    $query->where('id', $sessionData->id);
                }
            })
            ->get()
            ->map(fn($row) => [
                $row->nama,
                $row->telp,
                $row->email,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    // '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Nama',
            'Telp',
            'Email',
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
        if (! $this->sessionUser->can('create', Pengelola::class)) {
            return abort(403, "User does not have the right roles");
        }

        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle',));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePengelolaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengelolaRequest $request)
    {
        //
        $row = Pengelola::create($request->all());
        
        return response()->json($row);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pengelola  $pengelola
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = Pengelola::findOrFail($id);

        $row->pengelola_dokumens = $row->pengelola_dokumens->map(function ($pengelola_dokumen) {
            $pengelola_dokumen->dokumen = $pengelola_dokumen->dokumens()->first();
            $pengelola_dokumen->rusun = $pengelola_dokumen->rusuns()->first();

            return $pengelola_dokumen;
        });

        if (! $this->sessionUser->can('view', $row)) {
            return abort(403, "User does not have the right roles");
        }

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengelola  $pengelola
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = Pengelola::findOrFail($id);

        if (! $this->sessionUser->can('update', $row)) {
            return abort(403, "User does not have the right roles");
        }

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePengelolaRequest  $request
     * @param  \App\Models\Pengelola  $pengelola
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengelolaRequest $request, $id)
    {
        //
        Pengelola::findOrFail($id)->update($request->all());
        
        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengelola  $pengelola
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $row = Pengelola::findOrFail($id);

        if (! $this->sessionUser->can('delete', $row)) {
            return abort(403, "User does not have the right roles");
        }

        $row->delete();

        return response()->json('Success');
    }
}
