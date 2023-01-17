<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramDokumenRequest;
use App\Http\Requests\UpdateProgramDokumenRequest;
use App\Models\ProgramDokumen;
use Illuminate\Http\Request;

class ProgramDokumenController extends Controller
{

    const TITLE = 'Program Dokumen';
    const FOLDER_VIEW = 'program_dokumen.';
    const URL = 'program-dokumen.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $program_id = $request->program_id ?? NULL;

        $program = \App\Models\Program::where('id', $program_id)->firstOrFail();
        
        $title = self::TITLE;
        $subTitle = 'List Data';

        $rows = ProgramDokumen::orderBy('created_at')
            ->where('program_id', $program_id)
            ->get()
            ->map(fn($row) => [
                $row->rusun->nama,
                $row->nama,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Rusun',
            'Nama',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
        ];

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'heads', 'config', 'program'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $program_id = $request->program_id ?? NULL;

        $program = \App\Models\Program::where('id', $program_id)->firstOrFail();

        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'program'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgramDokumenRequest $request)
    {
        //
        $input = $request->all();

        unset(
            $input['rusun'],
            $input['program'],
        );

        ProgramDokumen::create($input);

        return redirect()
            ->route(self::URL . 'index', ['program_id' => $request->program_id])
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgramDokumen  $programDokumen
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramDokumen $programDokumen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramDokumen  $programDokumen
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramDokumen $programDokumen, $id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = ProgramDokumen::findOrFail($id);

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgramDokumen  $programDokumen
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramDokumenRequest $request, ProgramDokumen $programDokumen, $id)
    {
        //
        $input = $request->all();

        $programDokumen = ProgramDokumen::findOrFail($id);

        $programDokumen->update($input);

        return redirect()
            ->route(self::URL . 'index', ['program_id' => $request->program_id])
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramDokumen  $programDokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramDokumen $programDokumen, $id)
    {
        //
        $row = ProgramDokumen::findOrFail($id);

        $kanidatDokumen = $row->program_kanidat_dokumens->count();

        if (
            empty($kanidatDokumen)
        ) {
            $row->delete();

            return response()->json('Success');
        } else {
            return response()->json('Data tidak bisa di hapus, karena sudah mempunyai hubungan dibawahnya.', 403);
        }
    }
}
