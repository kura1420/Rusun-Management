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

        $rows = Pengelola::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->nama,
                $row->telp,
                $row->email,
                $row->sebagai,
                '<nobr><a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a></nobr>',
            ]);

        $heads = [
            'Nama',
            'Telp',
            'Email',
            'Sebagai',
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
     * @param  \App\Http\Requests\StorePengelolaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengelolaRequest $request)
    {
        //
        Pengelola::create($request->all());

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pengelola  $pengelola
     * @return \Illuminate\Http\Response
     */
    public function show(Pengelola $pengelola)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengelola  $pengelola
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengelola $pengelola)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $pengelola;

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePengelolaRequest  $request
     * @param  \App\Models\Pengelola  $pengelola
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengelolaRequest $request, Pengelola $pengelola)
    {
        //
        $pengelola->update($request->all());

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Update data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengelola  $pengelola
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengelola $pengelola)
    {
        //
        $pengelola->delete();

        return response()->json('OK');
    }
}
