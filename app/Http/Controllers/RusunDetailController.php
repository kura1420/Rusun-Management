<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunDetailRequest;
use App\Http\Requests\UpdateRusunDetailRequest;
use App\Models\RusunDetail;
use Illuminate\Support\Facades\Storage;

class RusunDetailController extends Controller
{

    const TITLE = 'Rusun Detail';
    const FOLDER_VIEW = 'rusun_detail.';
    const FOLDER_FOTO = 'rusun_detail/foto';
    const URL = 'rusun-detail.';

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

        $rows = RusunDetail::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->rusuns->nama,
                $row->nama_tower,
                $row->jumlah_unit,
                $row->jumlah_jenis_unit,
                $row->jumlah_lantai,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    // '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Rusun',
            'Nama Tower',
            'Jml. Unit',
            'Jml. Jenis',
            'Jml. Lantai',
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

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusuns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRusunDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunDetailRequest $request)
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
                    'public',
                );
        }
        
        RusunDetail::create($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RusunDetail  $rusunDetail
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = RusunDetail::findOrFail($id);

        $row->foto = $row->foto ? asset('storage/' . self::FOLDER_FOTO . '/' . $row->foto) : NULL;

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row',));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RusunDetail  $rusunDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $rusuns = \App\Models\Rusun::orderBy('nama', 'asc')->get();

        $row = RusunDetail::findOrFail($id);

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusuns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunDetailRequest  $request
     * @param  \App\Models\RusunDetail  $rusunDetail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunDetailRequest $request, $id)
    {
        //
        $rusunDetail = RusunDetail::findOrFail($id);

        $input = $request->all();

        $foto = $rusunDetail->foto;

        if ($request->foto) {
            $foto = md5(uniqid()) . '.' . $request->foto->extension();

            $request->file('foto')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto,
                    'public',
                );

            if ($rusunDetail->foto) {
                Storage::delete(self::FOLDER_FOTO . '/' . $rusunDetail->foto);
            }
        }

        $input['foto'] = $foto;

        $rusunDetail->update($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Update data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RusunDetail  $rusunDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        RusunDetail::findOrFail($id)->delete();

        return response()->json('Success');
    }
}
