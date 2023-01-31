<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengelolaDokumenRequest;
use App\Http\Requests\UpdatePengelolaDokumenRequest;
use App\Models\PengelolaDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PengelolaDokumenController extends Controller
{

    const TITLE = 'Pengelola Dokumen';
    const FOLDER_VIEW = 'pengelola_dokumen.';
    const FOLDER_FILE = 'pengelola/dokumen';
    const URL = 'pengelola-dokumen.';

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

        $rows = PengelolaDokumen::orderBy('created_at')
            ->when($user, function ($query, $user) {
                if ($user->level == 'pengelola') {
                    $sessionData = session()->get('pengelola');

                    $query->where('pengelola_id', $sessionData->id);
                }
            })
            ->get()
            ->map(fn($row) => [
                $row->pengelolas->nama,
                $row->rusuns->nama,
                $row->dokumens->nama,
                $row->tersedia ? 'Ya' : 'Tidak',
                '<nobr>' .
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' . 
                    '<a href="'.route(self::URL .'edit', $row->id).'?pengelola_id='.$row->pengelola_id.'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' . 
                    // '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' .
                '</nobr>',
            ]);

        $heads = [
            'Pengelola',
            'Rusun',
            'Dokumen',
            'Tersedia',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
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
    public function create(Request $request)
    {
        //
        if (! $this->sessionUser->can('create', PengelolaDokumen::class)) {
            return abort(403, "User does not have the right roles");
        }

        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        $pengelola_id = $request->pengelola_id ?? NULL;

        if (!$pengelola_id) {
            return abort(404);
        }

        $rusunPengelolas = \App\Models\RusunPengelola::where('pengelola_id', $pengelola_id)->get();
        $dokumens = \App\Models\Dokumen::where('kepada', 'pengelola')->orderBy('nama', 'asc')->get();
        $pengelolas = \App\Models\Pengelola::orderBy('nama', 'asc')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusunPengelolas', 'dokumens', 'pengelolas', 'pengelola_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePengelolaDokumenRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengelolaDokumenRequest $request)
    {
        //
        $input = $request->all();

        $file = NULL;
        if ($request->file) {
            $file = md5(uniqid()) . '.' . $request->file->extension();

            $request->file('file')
                ->storeAs(
                    self::FOLDER_FILE,
                    $file,
                    'local',
                );
        }

        $input['file'] = $file;
        $input['tersedia'] = !empty($file) ? 1 : 0;
        
        unset($input['redirect_to']);

        PengelolaDokumen::create($input);

        if ($request->redirect_to) {
            return redirect()
                ->route('pengelola.show', $request->pengelola_id)
                ->with('success', 'Tambah data berhasil...');
        } else {
            return redirect()
                ->route(self::URL . 'index')
                ->with('success', 'Tambah data berhasil...');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PengelolaDokumen  $pengelolaDokumen
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';
        
        $row = PengelolaDokumen::findOrFail($id);

        if (! $this->sessionUser->can('view', $row)) {
            return abort(403, "User does not have the right roles");
        }

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PengelolaDokumen  $pengelolaDokumen
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $pengelola_id = $request->pengelola_id ?? NULL;

        if (!$pengelola_id) {
            return abort(404);
        }

        $rusunPengelolas = \App\Models\RusunPengelola::where('pengelola_id', $pengelola_id)->get();
        $dokumens = \App\Models\Dokumen::where('kepada', 'pengelola')->orderBy('nama', 'asc')->get();
        $pengelolas = \App\Models\Pengelola::orderBy('nama', 'asc')->get();

        $row = PengelolaDokumen::findOrFail($id);

        if (! $this->sessionUser->can('update', $row)) {
            return abort(403, "User does not have the right roles");
        }

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusunPengelolas', 'dokumens', 'pengelolas', 'pengelola_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePengelolaDokumenRequest  $request
     * @param  \App\Models\PengelolaDokumen  $pengelolaDokumen
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengelolaDokumenRequest $request, $id)
    {
        //
        $pengelolaDokumen = PengelolaDokumen::findOrFail($id);

        $input = $request->all();

        $file = $pengelolaDokumen->file;
        if ($request->file) {
            $file = md5(uniqid()) . '.' . $request->file->extension();

            $request->file('file')
                ->storeAs(
                    self::FOLDER_FILE,
                    $file,
                    'local',
                );

            if ($pengelolaDokumen->file) {
                Storage::delete(self::FOLDER_FILE . '/' . $pengelolaDokumen->file);
            }
        }        

        $input['file'] = $file;
        $input['tersedia'] = !empty($file) ? 1 : 0;
        
        unset($input['redirect_to']);

        $pengelolaDokumen->update($input);

        if ($request->redirect_to) {
            return redirect()
                ->route('pengelola.show', $request->pengelola_id)
                ->with('success', 'Perbarui data berhasil...');
        } else {
            return redirect()
                ->route(self::URL . 'index')
                ->with('success', 'Perbarui data berhasil...');
        }    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PengelolaDokumen  $pengelolaDokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $row = PengelolaDokumen::findOrFail($id);

        if (! $this->sessionUser->can('delete', $row)) {
            return response()->json("User does not have the right roles", 403);
        }
        
        if ($row->status == 0) {
            if ($row->file) {
                Storage::delete(self::FOLDER_FILE . '/' . $row->file);
            }
    
            $row->delete();
    
            return response()->json('Success');
        } else {
            return response()->json('Data sudah dicek atau sedang dalam proses cek', 403);
        }
    }

    public function view_file($id, $filename)
    {
        $row = PengelolaDokumen::where('id', $id)
            ->where('file', $filename)
            ->first();

        $file = storage_path('app/' . self::FOLDER_FILE . '/' . $row->file);

        return response()->file($file);
    }

    public function verifUpdate(Request $request, $id)
    {
        Validator::make($request->all(), [
            'keterangan_ditolak' => 'nullable|string',
        ])->validate();

        $pengelolaDokumen = PengelolaDokumen::findOrFail($id);

        $input = $request->all();

        $pengelolaDokumen->update($input);

        return redirect()
            ->route(self::URL . 'show', $id)
            ->with('success', 'Data berhasil disimpan');
    }
}
