<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengembangDokumenRequest;
use App\Http\Requests\UpdatePengembangDokumenRequest;
use App\Models\PengembangDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengembangDokumenController extends Controller
{

    const TITLE = 'Pengembang Dokumen';
    const FOLDER_VIEW = 'pengembang_dokumen.';
    const FOLDER_FILE = 'pengembang/dokumen';
    const URL = 'pengembang-dokumen.';

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

        $rows = PengembangDokumen::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->pengembangs->nama,
                $row->rusuns->nama,
                $row->dokumens->nama,
                $row->tersedia ? 'Ya' : 'Tidak',
                '<nobr>' .
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' . 
                    '<a href="'.route(self::URL .'edit', $row->id).'?pengembang_id='.$row->pengembang_id.'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' . 
                    // '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' .
                '</nobr>',
            ]);

        $heads = [
            'Pengembang',
            'Rusun',
            'Dokumen',
            'Tersedia',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, ['orderable' => false]],
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
        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        $pengembang_id = $request->pengembang_id ?? NULL;

        if (!$pengembang_id) {
            return abort(404);
        }

        $rusunPengelolas = \App\Models\RusunPengembang::where('pengembang_id', $pengembang_id)->get();
        $dokumens = \App\Models\Dokumen::where('kepada', 'pengembang')->orderBy('nama', 'asc')->get();
        $pengembangs = \App\Models\Pengembang::orderBy('nama', 'asc')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusunPengelolas', 'dokumens', 'pengembangs', 'pengembang_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePengembangDokumenRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengembangDokumenRequest $request)
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

        PengembangDokumen::create($input);

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
     * @param  \App\Models\PengembangDokumen  $pengembangDokumen
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';
        
        $row = PengembangDokumen::findOrFail($id);

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PengembangDokumen  $pengembangDokumen
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $pengembang_id = $request->pengembang_id ?? NULL;

        if (!$pengembang_id) {
            return abort(404);
        }

        $rusunPengelolas = \App\Models\RusunPengembang::where('pengembang_id', $pengembang_id)->get();
        $dokumens = \App\Models\Dokumen::where('kepada', 'pengembang')->orderBy('nama', 'asc')->get();
        $pengembangs = \App\Models\Pengembang::orderBy('nama', 'asc')->get();
        
        $row = PengembangDokumen::findOrFail($id);

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusunPengelolas', 'dokumens', 'pengembangs', 'pengembang_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePengembangDokumenRequest  $request
     * @param  \App\Models\PengembangDokumen  $pengembangDokumen
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengembangDokumenRequest $request, $id)
    {
        //
        $pengembangDokumen = PengembangDokumen::findOrFail($id);

        $input = $request->all();

        $file = $pengembangDokumen->file;
        if ($request->file) {
            $file = md5(uniqid()) . '.' . $request->file->extension();

            $request->file('file')
                ->storeAs(
                    self::FOLDER_FILE,
                    $file,
                    'local',
                );

            if ($pengembangDokumen->file) {
                Storage::delete(self::FOLDER_FILE . '/' . $pengembangDokumen->file);
            }
        }

        $input['file'] = $file;
        $input['tersedia'] = !empty($file) ? 1 : 0;
        
        unset($input['redirect_to']);

        $pengembangDokumen->update($input);

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
     * @param  \App\Models\PengembangDokumen  $pengembangDokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $row = PengembangDokumen::findOrFail($id);

        if ($row->file) {
            Storage::delete(self::FOLDER_FILE . '/' . $row->file);
        }

        $row->delete();

        return response()->json('Success');
    }

    public function view_file($id, $filename)
    {
        $row = PengembangDokumen::where('id', $id)
            ->where('file', $filename)
            ->first();

        $file = storage_path('app/' . self::FOLDER_FILE . '/' . $row->file);

        return response()->file($file);
    }
}
