<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengembangKontakRequest;
use App\Http\Requests\UpdatePengembangKontakRequest;
use App\Models\PengembangKontak;
use Illuminate\Http\Request;

class PengembangKontakController extends Controller
{

    const TITLE = 'Pengembang Kontak';
    const FOLDER_VIEW = 'pengembang_kontak.';
    const URL = 'pengembang-kontak.';

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

        $rows = PengembangKontak::orderBy('created_at')
            ->when($user, function ($query, $user) {
                if ($user->level == 'pengembang') {
                    $sessionData = session()->get('pengembang');

                    $query->where('pengembang_id', $sessionData->id);
                }
            })
            ->get()
            ->map(fn($row) => [
                $row->pengembangs->nama,
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
            'Pengembang',
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
        if (! $this->sessionUser->can('create', PengembangKontak::class)) {
            return abort(403, "User does not have the right roles");
        }

        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        $pengembang_id = $request->pengembang_id ?? NULL;

        if (!$pengembang_id) {
            return abort(404);
        }

        $posisis = PengembangKontak::select('posisi')->distinct()->get();
        $pengembangs = \App\Models\Pengembang::orderBy('nama', 'asc')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'posisis', 'pengembangs', 'pengembang_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePengembangKontakRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengembangKontakRequest $request)
    {
        //
        $input = $request->all();
        
        unset($input['redirect_to']);

        PengembangKontak::create($input);

        if ($request->redirect_to) {
            return redirect()
                ->route('pengembang.show', $request->pengembang_id)
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
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function show(PengembangKontak $pengembangKontak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, PengembangKontak $pengembangKontak)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $pengembang_id = $request->pengembang_id ?? NULL;
        $posisis = PengembangKontak::select('posisi')->distinct()->get();
        $pengembangs = \App\Models\Pengembang::orderBy('nama', 'asc')->get();

        $row = $pengembangKontak;

        if (! $this->sessionUser->can('update', $row)) {
            return abort(403, "User does not have the right roles");
        }

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'posisis', 'pengembangs', 'pengembang_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePengembangKontakRequest  $request
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengembangKontakRequest $request, $id)
    {
        //
        $input = $request->all();

        unset($input['_token'], $input['_method'], $input['redirect_to']);

        PengembangKontak::findOrFail($id)->update($input);

        if ($request->redirect_to) {
            return redirect()
                ->route('pengembang.show', $request->pengembang_id)
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
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $row = PengembangKontak::where('id', $request->id)->firstOrFail();

if (! $this->sessionUser->can('delete', $row)) {
            return response()->json("User does not have the right roles", 403);
        }

        $row->delete();

        return response()->json('Success');
    }
}
