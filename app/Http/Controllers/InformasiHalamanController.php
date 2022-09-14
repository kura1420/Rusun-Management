<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInformasiHalamanRequest;
use App\Http\Requests\UpdateInformasiHalamanRequest;
use App\Models\InformasiHalaman;

class InformasiHalamanController extends Controller
{

    const TITLE = 'Informasi Halaman';
    const FOLDER_VIEW = 'informasi_halaman.';
    const FOLDER_UPLOAD = 'informasi_halaman';
    const URL = 'informasi-halaman.';

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

        $rows = InformasiHalaman::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->halaman_nama_format,
                $row->halaman_aksi_format,
                $row->judul,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'copy', $row->id).'" class="btn btn-warning btn-sm" title="Copy"><i class="fas fa-copy"></i> Copy</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Halaman',
            'Halaman Aksi',
            'Judul',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
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

        $halamans = $this->listHalaman();
        $aksis = $this->listHalamanAksi();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'halamans', 'aksis'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInformasiHalamanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInformasiHalamanRequest $request)
    {
        //
        $input = $request->all();

        $file = NULL;
        if ($input['copy']) {
            $row = InformasiHalaman::where('id', $input['copy'])->firstOrFail();

            $file = $row->file;

            $input['file'] = $file;
        }
        
        if ($request->file) {
            $file = md5(uniqid()) . '.' . $request->file->extension();

            $request->file('file')
                ->storeAs(self::FOLDER_UPLOAD, $file, 'local');

            $input['file'] = $file;
        }

        unset($input['files'], $input['copy']);

        InformasiHalaman::create($input);
        
        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InformasiHalaman  $informasiHalaman
     * @return \Illuminate\Http\Response
     */
    public function show(InformasiHalaman $informasiHalaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InformasiHalaman  $informasiHalaman
     * @return \Illuminate\Http\Response
     */
    public function edit(InformasiHalaman $informasiHalaman)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $informasiHalaman;
        $halamans = $this->listHalaman();
        $aksis = $this->listHalamanAksi();

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'halamans', 'aksis'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInformasiHalamanRequest  $request
     * @param  \App\Models\InformasiHalaman  $informasiHalaman
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInformasiHalamanRequest $request, InformasiHalaman $informasiHalaman)
    {
        //
        $input = $request->all();

        $file = $informasiHalaman->file;
        if ($request->file) {
            $file = md5(uniqid()) . '.' . $request->file->extension();

            $request->file('file')
                ->storeAs(self::FOLDER_UPLOAD, $file, 'local');

            $input['file'] = $file;
        }

        unset($input['files']);

        $informasiHalaman->update($input);
        
        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Perbarui data berhasil...');        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InformasiHalaman  $informasiHalaman
     * @return \Illuminate\Http\Response
     */
    public function destroy(InformasiHalaman $informasiHalaman)
    {
        //
        $informasiHalaman->delete();

        return response()->json('Success');
    }

    public function view_file($id, $file)
    {
        $row = InformasiHalaman::where('id', $id)
            ->where('file', $file)
            ->first();

        $file = storage_path('app/' . self::FOLDER_UPLOAD . '/' . $row->file);

        return response()->file($file);
    }

    public function copy($id)
    {
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = InformasiHalaman::findOrFail($id);
        $halamans = $this->listHalaman();
        $aksis = $this->listHalamanAksi();

        return view(self::FOLDER_VIEW . 'copy', compact('title', 'subTitle', 'row', 'halamans', 'aksis'));
    }

    protected function listHalaman()
    {
        return [
            'pengembang',
            'pengelola',
            'pemilik',
            'penghuni',
        ];
    }

    public function listHalamanAksi()
    {
        return [
            'index',
            'create',
            'edit',
            'show',
            'full',
        ];
    }
}
