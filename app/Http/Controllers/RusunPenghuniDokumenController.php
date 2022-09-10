<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunPenghuniDokumenRequest;
use App\Http\Requests\UpdateRusunPenghuniDokumenRequest;
use App\Models\RusunPenghuniDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RusunPenghuniDokumenController extends Controller
{

    const TITLE = 'Rusun Penghuni Dokumen';
    const FOLDER_VIEW = 'rusun_penghuni_dokumen.';
    const FOLDER_DOKUMEN = 'rusun_penghuni/dokumen';
    const URL = 'rusun-penghuni-dokumen.';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return abort(404);
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

        $rusun_penghuni_id = $request->rusun_penghuni_id ?? NULL;
        $rusunPenghuni = \App\Models\RusunPenghuni::with([
            'rusuns',
            'rusun_details',
            'rusun_unit_details',
        ])
        ->where('id', $rusun_penghuni_id)
        ->firstOrFail();

        $dokumens = \App\Models\Dokumen::orderBy('nama')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusunPenghuni', 'dokumens'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRusunPenghuniDokumenRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunPenghuniDokumenRequest $request)
    {
        //
        $input = $request->all();

        $rusunPenghuni = \App\Models\RusunPenghuni::where('id', $input['rusun_penghuni_id'])->firstOrFail();

        $file = NULL;
        if ($request->file) {
            $file = md5(uniqid()) . '.' . $request->file->extension();

            $request->file('file')
                ->storeAs(
                    self::FOLDER_DOKUMEN,
                    $file,
                    'local',
                );
        }

        $input['file'] = $file;
        $input['rusun_unit_detail_id'] = $rusunPenghuni->rusun_unit_detail_id;
        $input['rusun_detail_id'] = $rusunPenghuni->rusun_detail_id;
        $input['rusun_id'] = $rusunPenghuni->rusun_id;

        RusunPenghuniDokumen::create($input);

        return redirect()
            ->route('rusun-penghuni.show', $rusunPenghuni->id)
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RusunPenghuniDokumen  $rusunPenghuniDokumen
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $row = RusunPenghuniDokumen::findOrFail($id);

        $file = storage_path('app/' . self::FOLDER_DOKUMEN . '/' . $row->file);

        return response()->file($file);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RusunPenghuniDokumen  $rusunPenghuniDokumen
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';
        
        $row = RusunPenghuniDokumen::findOrFail($id);

        $dokumens = \App\Models\Dokumen::orderBy('nama')->get();

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'dokumens',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunPenghuniDokumenRequest  $request
     * @param  \App\Models\RusunPenghuniDokumen  $rusunPenghuniDokumen
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunPenghuniDokumenRequest $request, $id)
    {
        //
        $input = $request->all();

        $rusunPenghuni = RusunPenghuniDokumen::findOrFail($id);

        $file = $rusunPenghuni->file;

        if ($request->file) {
            $file = md5(uniqid()) . '.' . $request->file->extension();

            $request->file('file')
                ->storeAs(
                    self::FOLDER_DOKUMEN,
                    $file,
                    'local',
                );

            if ($rusunPenghuni->file) {
                Storage::delete(self::FOLDER_DOKUMEN . '/' . $rusunPenghuni->file);
            }
        }

        $input['file'] = $file;

        $rusunPenghuni->update($input);

        return redirect()
            ->route('rusun-penghuni.show', $rusunPenghuni->rusun_penghuni_id)
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RusunPenghuniDokumen  $rusunPenghuniDokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $row = RusunPenghuniDokumen::findOrFail($id);

        if ($row->file) {
            Storage::delete(self::FOLDER_DOKUMEN . '/' . $row->file);
        }
        
        $row->delete();

        return response()->json('Success');
    }
}
