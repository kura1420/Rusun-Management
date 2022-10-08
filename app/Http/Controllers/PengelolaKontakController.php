<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengelolaKontakRequest;
use App\Http\Requests\UpdatePengelolaKontakRequest;
use App\Models\PengelolaKontak;
use Illuminate\Http\Request;

class PengelolaKontakController extends Controller
{

    const TITLE = 'Pengelola Kontak';
    const FOLDER_VIEW = 'pengelola_kontak.';
    const URL = 'pengelola-kontak.';

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

        $rows = PengelolaKontak::orderBy('created_at')
            ->when($user, function ($query, $user) {
                if ($user->level == 'pengelola') {
                    $sessionData = session()->get('pengelola');

                    $query->where('pengelola_id', $sessionData->id);
                }
            })
            ->get()
            ->map(fn($row) => [
                $row->pengelolas->nama,
                $row->nama,
                $row->handphone,
                $row->email,
                $row->posisi,
                '<nobr>' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' . 
                    // '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' .
                '</nobr>',
            ]);

        $heads = [
            'Pengelola',
            'Nama',
            'Handphone',
            'Email',
            'Posisi',
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
        if (! $this->sessionUser->can('create', PengelolaKontak::class)) {
            return abort(403, "User does not have the right roles");
        }

        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        $pengelola_id = $request->pengelola_id ?? NULL;

        if (!$pengelola_id) {
            return abort(404);
        }

        $posisis = PengelolaKontak::select('posisi')->distinct()->get();
        $pengelolas = \App\Models\Pengelola::orderBy('nama', 'asc')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'posisis', 'pengelolas', 'pengelola_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePengelolaKontakRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengelolaKontakRequest $request)
    {
        //
        $input = $request->all();
        
        unset($input['redirect_to']);

        PengelolaKontak::create($input);

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
     * @param  \App\Models\PengelolaKontak  $pengelolaKontak
     * @return \Illuminate\Http\Response
     */
    public function show(PengelolaKontak $pengelolaKontak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PengelolaKontak  $pengelolaKontak
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, PengelolaKontak $pengelolaKontak)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $pengelola_id = $request->pengelola_id ?? NULL;
        $posisis = PengelolaKontak::select('posisi')->distinct()->get();
        $pengelolas = \App\Models\Pengelola::orderBy('nama', 'asc')->get();

        $row = $pengelolaKontak;

        if (! $this->sessionUser->can('update', $row)) {
            return abort(403, "User does not have the right roles");
        }

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'posisis', 'pengelolas', 'pengelola_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePengelolaKontakRequest  $request
     * @param  \App\Models\PengelolaKontak  $pengelolaKontak
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengelolaKontakRequest $request, PengelolaKontak $pengelolaKontak)
    {
        //
        $input = $request->all();

        unset($input['_token'], $input['_method'], $input['redirect_to']);

        $pengelolaKontak->update($input);

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
     * @param  \App\Models\PengelolaKontak  $pengelolaKontak
     * @return \Illuminate\Http\Response
     */
    public function destroy(PengelolaKontak $pengelolaKontak)
    {
        //
        if (! $this->sessionUser->can('delete', $pengelolaKontak)) {
            return abort(403, "User does not have the right roles");
        }

        $pengelolaKontak->delete();

        return response()->json('Success');
    }
}
