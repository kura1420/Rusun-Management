<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramJabatanRequest;
use App\Http\Requests\UpdateProgramJabatanRequest;
use App\Models\ProgramJabatan;
use Illuminate\Http\Request;

class ProgramJabatanController extends Controller
{

    const TITLE = 'Program Jabatan';
    const FOLDER_VIEW = 'program_jabatan.';
    const URL = 'program-jabatan.';
    
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

        $rows = ProgramJabatan::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->rusun->nama,
                $row->nama,
                $row->keterangan,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Rusun',
            'Nama',
            'Keterangan',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
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

        $rusuns = \App\Models\Rusun::orderBy('nama')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusuns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgramJabatanRequest $request)
    {
        //
        $input = $request->all();

        ProgramJabatan::create($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgramJabatan  $programJabatan
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramJabatan $programJabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramJabatan  $programJabatan
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramJabatan $programJabatan)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $programJabatan;
        $rusuns = \App\Models\Rusun::orderBy('nama')->get();

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusuns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgramJabatan  $programJabatan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramJabatanRequest $request, ProgramJabatan $programJabatan)
    {
        //
        $input = $request->all();

        $programJabatan->update($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramJabatan  $programJabatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramJabatan $programJabatan)
    {
        //
        $row = $programJabatan;

        $kanidats = $row->program_kanidats->count();

        if (empty($kanidats)) {
            $row->delete();

            return response()->json('Success');
        } else {
            return response()->json('Data tidak bisa di hapus, karena sudah mempunyai hubungan dibawahnya.', 403);
        }
    }
}
