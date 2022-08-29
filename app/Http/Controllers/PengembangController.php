<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengembangRequest;
use App\Http\Requests\UpdatePengembangRequest;
use App\Models\Pengembang;
use Illuminate\Http\Request;

class PengembangController extends Controller
{

    const TITLE = 'Pengembang';
    const FOLDER_VIEW = 'pengembang.';
    const URL = 'pengembang.';

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

        $rows = Pengembang::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->nama,
                $row->telp,
                $row->email,
                '<nobr><a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' . 
                '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'"><i class="fas fa-trash"></i> Hapus</button></nobr>',
            ]);

        $heads = [
            'Nama',
            'Telp',
            'Email',
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

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle',));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePengembangRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengembangRequest $request)
    {
        //
        Pengembang::create($request->all());
        
        return response()->json('Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Http\Response
     */
    public function show(Pengembang $pengembang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = Pengembang::findOrFail($id);

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePengembangRequest  $request
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengembangRequest $request, $id)
    {
        //
        Pengembang::findOrFail($id)->update($request->all());
        
        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        Pengembang::where('id', $request->id)->delete();

        return response()->json('OK');
    }
}
