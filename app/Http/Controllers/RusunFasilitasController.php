<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunFasilitasRequest;
use App\Http\Requests\UpdateRusunFasilitasRequest;
use App\Models\RusunFasilitas;
use Illuminate\Support\Facades\Storage;

class RusunFasilitasController extends Controller
{

    const TITLE = 'Rusun Fasilitas';
    const FOLDER_VIEW = 'rusun_fasilitas.';
    const FOLDER_FOTO = 'rusun_fasilitas/foto';
    const URL = 'rusun-fasilitas.';

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

        $rows = RusunFasilitas::with([
                'rusuns',
                'rusun_details',
            ])
            ->orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->rusuns->nama,
                $row->rusun_details->nama_tower ?? NULL,
                $row->nama,
                $row->jumlah,
                $row->keterangan,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Rusun',
            'Tower',
            'Nama',
            'Jumlah',
            'Keterangan',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 10],
        ];
        
        $config = [
            'data' => $rows,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, ['orderable' => false]],
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

        $rusuns = \App\Models\Rusun::orderBy('nama', 'asc')->get();
        $namas = RusunFasilitas::select('nama')->distinct()->orderBy('nama', 'asc')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusuns', 'namas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRusunFasilitasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunFasilitasRequest $request)
    {
        //
        $input = $request->all();

        $foto = NULL;
        
        if ($request->foto) {
            $foto = md5(uniqid()) . '.' . $request->foto->extension();

            $input['foto'] = $foto;

            $request->file('foto')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto,
                    'local',
                );
        }
        
        RusunFasilitas::create($input);

        return response()->json('Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RusunFasilitas  $rusunFasilitas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = RusunFasilitas::with([
                'rusuns',
                'rusun_details',
            ])
            ->findOrFail($id);

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row',));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RusunFasilitas  $rusunFasilitas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $rusuns = \App\Models\Rusun::orderBy('nama', 'asc')->get();
        $namas = RusunFasilitas::select('nama')->distinct()->orderBy('nama', 'asc')->get();

        $row = RusunFasilitas::with([
                'rusuns',
                'rusun_details',
            ])
            ->findOrFail($id);

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusuns', 'namas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunFasilitasRequest  $request
     * @param  \App\Models\RusunFasilitas  $rusunFasilitas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunFasilitasRequest $request, RusunFasilitas $rusunFasilitas)
    {
        //
    }

    public function updateAsStore(UpdateRusunFasilitasRequest $request, $id)
    {
        // 
        $rusunFasilitas = RusunFasilitas::findOrFail($id);

        $input = $request->all();

        $foto = $rusunFasilitas->foto;

        if ($request->foto) {
            $foto = md5(uniqid()) . '.' . $request->foto->extension();

            $request->file('foto')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto,
                    'local',
                );

            if ($rusunFasilitas->foto) {
                Storage::delete(self::FOLDER_FOTO . '/' . $rusunFasilitas->foto);
            }
        }

        $input['foto'] = $foto;

        $rusunFasilitas->update($input);

        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RusunFasilitas  $rusunFasilitas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        RusunFasilitas::findOrFail($id)->delete();

        return response()->json('Success');
    }

    public function view_file($id, $foto)
    {
        $row = RusunFasilitas::where('id', $id)
            ->where('foto', $foto)
            ->firstOrFail();

        $file = storage_path('app/' . self::FOLDER_FOTO . '/' . $row->foto);

        return response()->file($file);
    }
}
