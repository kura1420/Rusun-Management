<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramKanidatRequest;
use App\Http\Requests\UpdateProgramKanidatRequest;
use App\Models\ProgramKanidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProgramKanidatController extends Controller
{

    const TITLE = 'Program Kanidat';
    const FOLDER_VIEW = 'program_kanidat.';
    const URL = 'program-kanidat.';

    protected $sessionUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->sessionUser = auth()->user();

            return $next($request);
        });
    }

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

        $rows = ProgramKanidat::orderBy('created_at')
            ->where('program_id', $program_id)
            ->groupBy('grup_nama')
            ->select('id', 'grup_id', 'grup_nama', DB::raw('COUNT(grup_nama) as total'), 'grup_status')
            ->get()
            ->map(fn($row) => [
                $row->grup_nama,
                $row->grup_status_text,
                $row->total,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->grup_id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-eye"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->grup_id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->grup_id.'" id="'.route(self::URL . 'destroy', $row->grup_id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Grup',
            'Status',
            'Peserta',
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
        $grups = ProgramKanidat::select('grup_id', 'grup_nama')->distinct()->get();
        $jabatans = \App\Models\ProgramJabatan::where('rusun_id', $program->rusun_id)->orderBy('nama')->get();
        $towers = \App\Models\RusunDetail::where('rusun_id', $program->rusun_id)->orderBy('nama_tower')->get();

        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'program', 'grups', 'jabatans', 'towers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgramKanidatRequest $request)
    {
        //
        $input = $request->all();

        $grupId = md5($request->program_id . $request->grup_nama);
        $input['grup_id'] = $grupId;
        $input['grup_status'] = 0;

        $user = $this->sessionUser;
        switch ($user->level) {
            case 'root':
            case 'rusun':
            case 'pemda':
                $input['status'] = 4;
                break;

            case 'pemilik':
            case 'penghuni':
                if (isset($input['__state'])) {
                    if ($input['__state'] == 'mendaftarkan') {
                        $input['status'] = 5;

                        unset($input['__state']);
                    } else {
                        $input['status'] = 3;
                    }
                } else {
                    $input['status'] = 3;
                }
                break;
            
            default:
                $input['status'] = 99;
                break;
        }

        ProgramKanidat::create($input);

        return response()->json($grupId, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgramKanidat  $programKanidat
     * @return \Illuminate\Http\Response
     */
    public function show($grupId)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $user = $this->sessionUser;
        if ($user->level == 'pemilik') {
            $pemilik = session()->get('pemilik');

            $check = ProgramKanidat::where('grup_id', $grupId)->where('pemilik_penghuni_id', $pemilik->id)->firstOrFail();

            if ($check->status == 6) {
                return abort(403, "Anda tidak memiliki akses ke halaman ini");
            }
        }

        if ($user->level == 'penghuni') {
            $rusunPenghuni = session()->get('rusun_penghuni');

            $check = ProgramKanidat::where('grup_id', $grupId)->where('pemilik_penghuni_id', $rusunPenghuni->id)->firstOrFail();

            if ($check->status == 6) {
                return abort(403, "Anda tidak memiliki akses ke halaman ini");
            }
        }

        $row = ProgramKanidat::where('grup_id', $grupId)->firstOrFail();
        $programDokumens = \App\Models\ProgramDokumen::where('program_id', $row->program_id)->orderBy('nama')->get();
        $kanidats = ProgramKanidat::where('grup_id', $grupId)
            ->get()
            ->map(function ($kanidat) use ($programDokumens) {
                $kanidatDokumen = $kanidat->program_kanidat_dokumens()->count();


                $kanidat->dokumen = $kanidatDokumen == count($programDokumens) ? 'Sudah Dipenuhi' : 'Belum Dipenuhi';

                return $kanidat;
            });
        $programKegiatan = \App\Models\ProgramKegiatan::where([
            ['rusun_id', $row->rusun_id],
            ['program_id', $row->program_id],
            ['template', 'form_pendaftaran']
        ])->first();

        $pemilik_penghuni_id = NULL;

        $userSession = auth()->user();
        if ($userSession->level == 'pemilik') {
            $pemilik = session()->get('pemilik');
            $pemilik_penghuni_id = $pemilik->id;
        }

        if ($userSession->level == 'penghuni') {
            $penghuni = session()->get('penghuni');
            $pemilik_penghuni_id = $penghuni->id;
        }

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row', 'kanidats', 'programKegiatan', 'programDokumens', 'pemilik_penghuni_id'));
    }

    public function showDetail($programId, $grupId)
    {
        $programKanidats = ProgramKanidat::where([
            ['program_id', $programId],
            ['grup_id', $grupId],
        ])
        ->get()
        ->map(function ($programKanidat) {
            $programKanidat->rusun;
            $programKanidat->program;
            $programKanidat->program_jabatan;
            $programKanidat->pemilik_penghuni_profile = $programKanidat->pemilik_penghuni_profile;
            $programKanidat->rusun_detail;
            $programKanidat->rusun_unit_detail;
            $programKanidat->status = $programKanidat->status_text;

            return $programKanidat;
        });

        return response()->json(['data' => $programKanidats]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramKanidat  $programKanidat
     * @return \Illuminate\Http\Response
     */
    public function edit($grupId)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $user = $this->sessionUser;
        $pemilikPenghuniLogin = NULL;

        if ($user->level == 'pemilik') {
            $pemilik = session()->get('pemilik');

            $pemilikPenghuniLogin = $pemilik->id;
        }

        if ($user->level == 'penghuni') {
            $rusunPenghuni = session()->get('rusun_penghuni');

            $pemilikPenghuniLogin = $rusunPenghuni->id;
        }

        $row = ProgramKanidat::where('grup_id', $grupId)->firstOrFail();

        if ($row->grup_status == 1) {
            return abort(403, "Grup Kanidat Sudah Diverifikasi");
        }

        $jabatans = \App\Models\ProgramJabatan::where('rusun_id', $row->rusun_id)->orderBy('nama')->get();
        $towers = \App\Models\RusunDetail::orderBy('nama_tower')
            ->where('rusun_id', $row->rusun_id)
            ->when($user, function ($query, $user) use ($row) {
                if ($user->level == 'pemilik') {
                    $pemilik = session()->get('pemilik');

                    $pemilikPenghuniLogin = $pemilik->id;
            
                    $rusunDetails = \App\Models\RusunPemilik::where([
                        ['rusun_id', $row->rusun_id],
                        ['pemilik_id', $pemilik->id]
                    ])->pluck('rusun_detail_id');

                    $query->whereIn('id', $rusunDetails);
                }

                if ($user->level == 'penghuni') {
                    $rusunPenghuni = session()->get('rusun_penghuni');

                    $pemilikPenghuniLogin = $rusunPenghuni->id;

                    $query->where('id', $rusunPenghuni->rusun_detail_id);
                }
            })
            ->get();

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'jabatans', 'towers', 'pemilikPenghuniLogin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgramKanidat  $programKanidat
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramKanidatRequest $request, ProgramKanidat $programKanidat)
    {
        //
        $input = $request->all();

        $programKanidat->update($input);

        return response()->json('OK', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramKanidat  $programKanidat
     * @return \Illuminate\Http\Response
     */
    public function destroy($paramId)
    {
        //
        $row = ProgramKanidat::where('grup_id', $paramId)->first();

        if (! $row) {
            $row = ProgramKanidat::where('id', $paramId)->first();

            $dokumens = $row->program_kanidat_dokumens()->count();

            if (
                empty($dokumens)
            ) {
                $row->delete();

                return response()->json('OK', 200);
            } else {
                return response()->json('Data tidak bisa di hapus, karena sudah mempunyai hubungan dibawahnya.', 403);
            }
        } else {
            $dokumens = $row->program_kanidat_dokumens()->count();

            if (
                empty($dokumens)
            ) {
                ProgramKanidat::where('grup_id', $paramId)->delete();

                return response()->json('OK', 200);
            } else {
                return response()->json('Data tidak bisa di hapus, karena sudah mempunyai hubungan dibawahnya.', 403);
            }
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'status' => 'required|string',
            'penjelasan' => 'nullable|string',
            '__state' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        } else {
            switch ($request->__state) {
                case 'kanidat':
                    ProgramKanidat::findOrFail($id)
                        ->update([
                            'status' => $request->status == 'verif' ? 1 : 2,
                            'penjelasan' => $request->penjelasan ?? NULL,
                        ]);

                    return response()->json('OK', 200);
                    break;

                case 'grup':
                    ProgramKanidat::where('grup_id', $id)
                        ->update([
                            'grup_status' => $request->status == 'verif' ? 1 : 2,
                        ]);

                    if ($request->status == 'verif') {
                        ProgramKanidat::where('grup_id', $id)->where('status', 6)->delete();
                    }

                    return response()->json('OK', 200);
                    break;

                case 'pemilik_penghuni':
                    ProgramKanidat::findOrFail($id)
                        ->update([
                            'status' => $request->status,
                        ]);

                    return response()->json('OK', 200);
                    break;
                
                default:
                    return abort(404);
                    break;
            }
        }
    }

    public function register($id)
    {
        $program = \App\Models\Program::findOrFail($id);
        $jabatans = \App\Models\ProgramJabatan::where('rusun_id', $program->rusun_id)->orderBy('nama')->get();

        $user = $this->sessionUser;

        $towers = [];
        $pemilikPenghuni = [];
        if ($user->level == 'pemilik') {
            $pemilik = session()->get('pemilik');
            
            $rusunDetail = \App\Models\RusunPemilik::where([
                ['rusun_id', $program->rusun_id],
                ['pemilik_id', $pemilik->id]
            ])->first();

            $rusunUnitDetail = \App\Models\RusunUnitDetail::where([
                ['rusun_id', $program->rusun_id],
                ['rusun_detail_id', $rusunDetail->rusun_detail_id],
            ])->first();

            $towers = \App\Models\RusunDetail::where('id', $rusunDetail->rusun_detail_id)->orderBy('nama_tower')->get();

            $pemilikPenghuni['id'] = $pemilik->id;
            $pemilikPenghuni['nama'] = $pemilik->nama;
            $apakah_pemilik = 1;

            $rusun_unit_detail_id = $rusunUnitDetail->id;
        }

        if ($user->level == 'penghuni') {
            $rusunPenghuni = session()->get('rusun_penghuni');
            $pemilik_penghuni_id = $rusunPenghuni->id;

            $towers = \App\Models\RusunDetail::where('id', $rusunPenghuni->rusun_detail_id)->orderBy('nama_tower')->get();

            $pemilikPenghuni['id'] = $rusunPenghuni->id;
            $pemilikPenghuni['nama'] = $rusunPenghuni->nama;
            $apakah_pemilik = 0;
            $rusun_unit_detail_id = $rusunPenghuni->rusun_unit_detail_id;
        }

        $title = self::TITLE;
        $subTitle = 'Daftar ' . $program->nama;

        return view(self::FOLDER_VIEW . 'register', compact('title', 'subTitle', 'program', 'jabatans', 'towers', 'pemilikPenghuni', 'apakah_pemilik', 'rusun_unit_detail_id'));
    }
}
