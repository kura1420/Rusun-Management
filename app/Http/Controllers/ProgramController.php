<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgramController extends Controller
{

    const TITLE = 'Program';
    const FOLDER_VIEW = 'program.';
    const URL = 'program.';

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

        $rows = Program::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->rusun->nama,
                $row->nama,
                $row->tahun . ' sd ' . ($row->tahun + $row->periode),
                $row->statusText($row->status),
                $row->publish ? 'Ya' : 'Tidak',
                '<nobr>' . 
                    '<div class="btn-group mr-2">
                        <button type="button" class="btn btn-success btn-sm">Detail</button>
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="'.route('program-kegiatan.index', ['program_id' => $row->id]).'">Kegiatan</a>
                            <a class="dropdown-item" href="'.route('program-dokumen.index', ['program_id' => $row->id]).'">Dokumen</a>
                            <a class="dropdown-item" href="'.route('program-kanidat.index', ['program_id' => $row->id]).'">Kanidat</a>
                        </div>
                    </div>' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Rusun',
            'Nama',
            'Periode Jabatan',
            'Status',
            'Publish',
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
        $tahuns = $this->tahuns();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusuns', 'tahuns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgramRequest $request)
    {
        //
        $input = $request->all();

        if ($request->file) {
            $filename = md5(uniqid()) . '.' . $request->file->extension();

            $request->file('file')->storeAs(
                str_replace('.', '', self::FOLDER_VIEW),
                $filename,
                'public'
            );

            $input['file'] = $filename;
        } else {
            unset($input['file']);
        }

        unset($input['files']);

        $input['publish'] = $request->publish == 'true' ? 1 : 0;

        if ($request->publish == 'true') {
            $input['publish_at'] = Carbon::now();
            $input['status'] = 2;
        }

        $input['slug'] = Str::slug($request->nama . ' ' . uniqid());

        Program::create($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        //
        return $program;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $program;
        $rusuns = \App\Models\Rusun::orderBy('nama')->get();
        $tahuns = $this->tahuns();

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusuns', 'tahuns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramRequest $request, Program $program)
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

            if ($program->file) {
                Storage::delete(str_replace('.', '', self::FOLDER_VIEW) . '/' . $program->file);
            }
        } else {
            unset($input['file']);
        }

        unset($input['files']);

        $input['publish'] = $request->publish == 'true' || $request->publish == 1 ? 1 : 0;

        if ($request->publish == 'true' || $request->publish == 1) {
            $input['publish_at'] = Carbon::now();
            $input['status'] = 2;
        }

        if (! $program->slug) {
            $input['slug'] = Str::slug($request->nama . ' ' . uniqid());
        }

        $program->update($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        //
        $row = $program;

        $kegiatan = $row->program_kegiatans->count();
        $laporan = $row->program_laporans->count();
        $kanidat = $row->program_kanidats->count();

        if (
            empty($kegiatan) &&
            empty($laporan) &&
            empty($kanidat)
        ) {
            $row->delete();

            return response()->json('Success');
        } else {
            return response()->json('Data tidak bisa di hapus, karena sudah mempunyai hubungan dibawahnya.', 403);
        }
    }

    protected function tahuns()
    {
        $data = range((date('Y') - 30), (date('Y') + 30));
        rsort($data);
        
        return $data;
    }
}
