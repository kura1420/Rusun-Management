<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunPemilikDokumenRequest;
use App\Http\Requests\UpdateRusunPemilikDokumenRequest;
use App\Models\RusunPemilikDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RusunPemilikDokumenController extends Controller
{

    const TITLE = 'Rusun Pemilik Dokumen';
    const FOLDER_VIEW = 'rusun_pemilik_dokumen.';
    const FOLDER_DOKUMEN = 'rusun_pemilik/dokumen';
    const URL = 'rusun-pemilik-dokumen.';

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

        $pemilik_id = $request->pemilik_id ?? NULL;
        $pemilik = \App\Models\Pemilik::with([
            'rusun_pemiliks',
        ])
        ->where('id', $pemilik_id)
        ->firstOrFail();

        $pemilik->rusun_pemiliks = $pemilik->rusun_pemiliks->map(function ($rusun_pemilik) {
            $rusun_pemilik->rusuns = $rusun_pemilik->rusuns()->first();
            $rusun_pemilik->rusun_details = $rusun_pemilik->rusun_details()->first();
            $rusun_pemilik->rusun_unit_details = $rusun_pemilik->rusun_unit_details()->first();

            return $rusun_pemilik;
        });

        $pemilik->rusun_pemilik_groups = $this->rusunPemilikList($pemilik->rusun_pemiliks);

        $dokumens = \App\Models\Dokumen::orderBy('nama')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'pemilik', 'dokumens'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRusunPemilikDokumenRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();

        $pemilik = \App\Models\Pemilik::where('id', $input['pemilik_id'])->firstOrFail();
        $rusunUnitDetail = \App\Models\RusunUnitDetail::where('id', $input['rusun_unit_detail_id'])->firstOrFail();

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
        $input['rusun_unit_detail_id'] = $rusunUnitDetail->id;
        $input['rusun_detail_id'] = $rusunUnitDetail->rusun_detail_id;
        $input['rusun_id'] = $rusunUnitDetail->rusun_id;

        RusunPemilikDokumen::create($input);

        return redirect()
            ->route('pemilik.show', $pemilik->id)
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RusunPemilikDokumen  $rusunPemilikDokumen
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $row = RusunPemilikDokumen::findOrFail($id);

        $file = storage_path('app/' . self::FOLDER_DOKUMEN . '/' . $row->file);

        return response()->file($file);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RusunPemilikDokumen  $rusunPemilikDokumen
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';
        
        $row = RusunPemilikDokumen::findOrFail($id);

        $pemilik = \App\Models\Pemilik::with([
            'rusun_pemiliks',
        ])
        ->where('id', $row->pemilik_id)
        ->firstOrFail();
        
        $pemilik->rusun_pemilik_groups = $this->rusunPemilikList($pemilik->rusun_pemiliks);

        $dokumens = \App\Models\Dokumen::orderBy('nama')->get();

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'dokumens', 'pemilik'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunPemilikDokumenRequest  $request
     * @param  \App\Models\RusunPemilikDokumen  $rusunPemilikDokumen
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunPemilikDokumenRequest $request, $id)
    {
        //
        $input = $request->all();

        $rusunPemilikDokumen = RusunPemilikDokumen::findOrFail($id);
        $rusunUnitDetail = \App\Models\RusunUnitDetail::where('id', $input['rusun_unit_detail_id'])->firstOrFail();

        $file = $rusunPemilikDokumen->file;

        if ($request->file) {
            $file = md5(uniqid()) . '.' . $request->file->extension();

            $request->file('file')
                ->storeAs(
                    self::FOLDER_DOKUMEN,
                    $file,
                    'local',
                );

            if ($rusunPemilikDokumen->file) {
                Storage::delete(self::FOLDER_DOKUMEN . '/' . $rusunPemilikDokumen->file);
            }
        }

        $input['file'] = $file;
        $input['rusun_unit_detail_id'] = $rusunUnitDetail->id;
        $input['rusun_detail_id'] = $rusunUnitDetail->rusun_detail_id;
        $input['rusun_id'] = $rusunUnitDetail->rusun_id;

        $rusunPemilikDokumen->update($input);

        return redirect()
            ->route('pemilik.show', $rusunPemilikDokumen->pemilik_id)
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RusunPemilikDokumen  $rusunPemilikDokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $row = RusunPemilikDokumen::findOrFail($id);

        if ($row->file) {
            Storage::delete(self::FOLDER_DOKUMEN . '/' . $row->file);
        }
        
        $row->delete();

        return response()->json('Success');
    }

    protected function rusunPemilikList($rusun_pemiliks)
    {
        $collects = collect($rusun_pemiliks)
            ->map(function ($item, $key) {
                return [
                    'id' => $item->rusun_unit_detail_id,
                    'rusun' => $item->rusuns->nama,
                    'text' => $item->rusun_details->nama_tower . ' - ' . $item->rusun_unit_details->ukuran,
                ];
            })
            ->groupBy('rusun');

        $rusunPemilikList = [];
        foreach ($collects as $key => $collect) {
            $rusunPemilikList[] = [
                'text' => $key,
                'children' => $collect,
            ];
        }

        return $rusunPemilikList;
    }
}
