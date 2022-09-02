<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengembangKontakRequest;
use App\Http\Requests\UpdatePengembangKontakRequest;
use App\Models\PengembangKontak;
use Illuminate\Http\Request;

class PengembangKontakController extends Controller
{

    const TITLE = 'Pengembang Kontak';
    const FOLDER_VIEW = 'pengembang_kontak.';
    const URL = 'pengembang-kontak.';
    
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

        $rows = PengembangKontak::with(['pengembangs'])
            ->orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->pengembangs->nama,
                $row->nama,
                $row->handphone,
                $row->email,
                $row->posisi,
                '<nobr>' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' . 
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' .
                '</nobr>',
            ]);

        $heads = [
            'Pengembang',
            'Nama',
            'Handphone',
            'Email',
            'Posisi',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
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
    public function create(Request $request)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        $posisis = PengembangKontak::select('posisi')->distinct()->get();
        $pengembangs = \App\Models\Pengembang::orderBy('nama', 'asc')->get();
        $pengembang_id = $request->pengembang_id ?? NULL;

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'posisis', 'pengembangs', 'pengembang_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePengembangKontakRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengembangKontakRequest $request)
    {
        //
        $input = $request->all();
        
        unset($input['redirect_to']);

        PengembangKontak::create($input);

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
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function show(PengembangKontak $pengembangKontak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, PengembangKontak $pengembangKontak)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $posisis = PengembangKontak::select('posisi')->distinct()->get();
        $pengembangs = \App\Models\Pengembang::orderBy('nama', 'asc')->get();
        $pengembang_id = $request->pengembang_id ?? NULL;

        $row = $pengembangKontak;

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'posisis', 'pengembangs', 'pengembang_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePengembangKontakRequest  $request
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengembangKontakRequest $request, $id)
    {
        //
        $input = $request->all();

        unset($input['_token'], $input['_method'], $input['redirect_to']);

        PengembangKontak::findOrFail($id)->update($input);

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
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        PengembangKontak::where('id', $request->id)->delete();

        return response()->json('Success');
    }
}
