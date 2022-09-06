<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreP3srsJabatanRequest;
use App\Http\Requests\UpdateP3srsJabatanRequest;
use App\Models\P3srsJabatan;

class P3srsJabatanController extends Controller
{

    const TITLE = 'P3SRS - Jabatan';
    const FOLDER_VIEW = 'p3srs_jawaban.';
    const URL = 'p3srs-jabatan.';

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

        $rows = P3srsJabatan::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->nama,
                $row->keterangan,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Nama',
            'Keterangan',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
            'order' => [[1, 'asc']],
            'columns' => [null, null, ['orderable' => false]],
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
        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle',));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreP3srsJabatanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreP3srsJabatanRequest $request)
    {
        //
        $input = $request->all();

        unset($input['_token']);

        P3srsJabatan::create($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\P3srsJabatan  $p3srsJabatan
     * @return \Illuminate\Http\Response
     */
    public function show(P3srsJabatan $p3srsJabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\P3srsJabatan  $p3srsJabatan
     * @return \Illuminate\Http\Response
     */
    public function edit(P3srsJabatan $p3srsJabatan)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $p3srsJabatan;

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateP3srsJabatanRequest  $request
     * @param  \App\Models\P3srsJabatan  $p3srsJabatan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateP3srsJabatanRequest $request, P3srsJabatan $p3srsJabatan)
    {
        //
        $input = $request->all();

        $p3srsJabatan->update($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\P3srsJabatan  $p3srsJabatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(P3srsJabatan $p3srsJabatan)
    {
        //
        $p3srsJabatan->delete();

        return response()->json('Success');
    }
}
