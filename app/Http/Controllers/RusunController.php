<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunRequest;
use App\Http\Requests\UpdateRusunRequest;
use App\Models\Rusun;
use Illuminate\Support\Facades\Storage;

class RusunController extends Controller
{

    const TITLE = 'Rusun';
    const FOLDER_VIEW = 'rusun.';
    const FOLDER_FOTO = 'rusun/foto';
    const URL = 'rusun.';

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

        $rows = Rusun::with([
            'provinces',
            'kotas',
            'kecamatans',
            'desas',
        ])
            ->orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->nama,
                $row->total_tower,
                $row->total_unit,
                $row->kotas->name ?? NULL,
                $row->kecamatans->name ?? NULL,
                $row->desas->name ?? NULL,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Nama',
            'Total Tower',
            'Total Unit',
            'Kota',
            'Kecamatan',
            'Kelurahan',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 10],
        ];
        
        $config = [
            'data' => $rows,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, null, ['orderable' => false]],
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
     * @param  \App\Http\Requests\StoreRusunRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunRequest $request)
    {
        //
        $input = $request->all();

        $foto_1 = NULL;
        $foto_2 = NULL;
        $foto_3 = NULL;

        if ($request->foto_1) {
            $foto_1 = md5(uniqid()) . '.' . $request->foto_1->extension();

            $input['foto_1'] = $foto_1;

            $request->file('foto_1')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_1,
                    'public',
                );
        }

        if ($request->foto_2) {
            $foto_2 = md5(uniqid()) . '.' . $request->foto_2->extension();

            $input['foto_2'] = $foto_2;

            $request->file('foto_2')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_2,
                    'public',
                );
        }

        if ($request->foto_3) {
            $foto_3 = md5(uniqid()) . '.' . $request->foto_3->extension();

            $input['foto_3'] = $foto_3;

            $request->file('foto_3')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_3,
                    'public',
                );
        }

        $row = Rusun::create($input);

        return response()->json($row);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rusun  $rusun
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = Rusun::with([
            'provinces',
            'kotas',
            'kecamatans',
            'desas',
            'rusun_details',
            'rusun_unit_details',
            'rusun_fasilitas',
        ])
        ->findOrFail($id);

        $row->foto_1 = $row->foto_1 ? asset('storage/' . self::FOLDER_FOTO . '/' . $row->foto_1) : NULL;
        $row->foto_2 = $row->foto_2 ? asset('storage/' . self::FOLDER_FOTO . '/' . $row->foto_2) : NULL;
        $row->foto_3 = $row->foto_3 ? asset('storage/' . self::FOLDER_FOTO . '/' . $row->foto_3) : NULL;

        $fotos = collect([$row->foto_1, $row->foto_2, $row->foto_3])
            ->filter(function ($value, $key) {
                return $value !== NULL;
            })
            ->all();

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row', 'fotos'));
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

        $row = Rusun::with([
            'provinces',
            'kotas',
            'kecamatans',
            'desas',
        ])->findOrFail($id);

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunRequest  $request
     * @param  \App\Models\Rusun  $rusun
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunRequest $request, Rusun $rusun)
    {
        //
    }

    public function updateAsStore(UpdateRusunRequest $request, $id)
    {
        $rusun = Rusun::findOrFail($id);

        $input = $request->all();

        $foto_1 = $rusun->foto_1;
        $foto_2 = $rusun->foto_2;
        $foto_3 = $rusun->foto_3;

        if ($request->foto_1) {
            $foto_1 = md5(uniqid()) . '.' . $request->foto_1->extension();

            $request->file('foto_1')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_1,
                    'public',
                );

            if ($rusun->foto_1) {
                Storage::delete(self::FOLDER_FOTO . '/' . $rusun->foto_1);
            }
        }

        $input['foto_1'] = $foto_1;

        if ($request->foto_2) {
            $foto_2 = md5(uniqid()) . '.' . $request->foto_2->extension();

            $request->file('foto_2')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_2,
                    'public',
                );

            if ($rusun->foto_2) {
                Storage::delete(self::FOLDER_FOTO . '/' . $rusun->foto_2);
            }
        }

        $input['foto_2'] = $foto_2;

        if ($request->foto_3) {
            $foto_3 = md5(uniqid()) . '.' . $request->foto_3->extension();

            $request->file('foto_3')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_3,
                    'public',
                );

            if ($rusun->foto_3) {
                Storage::delete(self::FOLDER_FOTO . '/' . $rusun->foto_3);
            }
        }

        $input['foto_3'] = $foto_3;

        $rusun->update($input);

        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rusun  $rusun
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Rusun::findOrFail($id)->delete();
        
        return response()->json('Success');
    }

    public function view_file($id, $filename)
    {
        $row = Rusun::where('id', $id)
            ->where('file', $filename)
            ->first();

        $file = storage_path('app/' . self::FOLDER_FOTO . '/' . $row->file);

        return response()->file($file);
    }
}
