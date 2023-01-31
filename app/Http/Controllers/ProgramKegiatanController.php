<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramKegiatanRequest;
use App\Http\Requests\UpdateProgramKegiatanRequest;
use App\Models\ProgramKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramKegiatanController extends Controller
{

    const TITLE = 'Program Kegiatan';
    const FOLDER_VIEW = 'program_kegiatan.';
    const URL = 'program-kegiatan.';

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

        $rows = ProgramKegiatan::orderBy('created_at')
            ->where('program_id', $program_id)
            ->get()
            ->map(fn($row) => [
                $row->rusun->nama,
                $row->nama,
                $row->tanggal_mulai,
                $row->tanggal_berakhir,
                $row->status_text,
                '<nobr>' . 
                    '<a href="'.route('program-laporan.index', ['program_kegiatan_id' => $row->id]).'" class="btn btn-secondary btn-sm" title="Laporan"><i class="fas fa-pencil-alt"></i> Laporan</a> ' .
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-eye"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Rusun',
            'Nama',
            'Tgl. Mulai',
            'Tgl. Berakhir',
            'Status',
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
    public function store(StoreProgramKegiatanRequest $request)
    {
        //
        $input = $request->all();

        if ($request->file) {
            $filename = md5(uniqid()) . '.' . $request->file->extension();
            $request->file('file')->storeAs(
                str_replace('.', '', self::FOLDER_VIEW),
                $filename,
                'public',
            );

            $input['file'] = $filename;
        } else {
            unset($input['file']);
        }

        unset(
            $input['rusun'],
            $input['program'],
            $input['files'],
        );

        ProgramKegiatan::create($input);

        return redirect()
            ->route(self::URL . 'index', ['program_id' => $request->program_id])
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgramKegiatan  $programKegiatan
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramKegiatan $programKegiatan)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = $programKegiatan;

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramKegiatan  $programKegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramKegiatan $programKegiatan)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $programKegiatan;

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgramKegiatan  $programKegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramKegiatanRequest $request, ProgramKegiatan $programKegiatan)
    {
        //
        $input = $request->all();

        if ($request->file) {
            $filename = md5(uniqid()) . '.' . $request->file->extension();
            $request->file('file')->storeAs(
                str_replace('.', '', self::FOLDER_VIEW),
                $filename,
                'public',
            );

            $input['file'] = $filename;

            Storage::delete(str_replace('.', '', self::FOLDER_VIEW) . '/' . $programKegiatan->file);
        } else {
            unset($input['file']);
        }

        unset(
            $input['rusun'],
            $input['program'],
            $input['files'],
        );

        $programKegiatan->update($input);

        return redirect()
            ->route(self::URL . 'index', ['program_id' => $request->program_id])
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramKegiatan  $programKegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramKegiatan $programKegiatan)
    {
        //
        $row = $programKegiatan;

        $laporan = $row->program_laporans->count();

        if (
            empty($laporan)
        ) {
            $row->delete();

            return response()->json('Success');
        } else {
            return response()->json('Data tidak bisa di hapus, karena sudah mempunyai hubungan dibawahnya.', 403);
        }
    }
}
