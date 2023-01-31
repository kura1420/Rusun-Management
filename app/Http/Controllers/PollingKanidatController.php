<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePollingKanidatRequest;
use App\Models\PollingKanidat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollingKanidatController extends Controller
{
    
    const TITLE = 'Polling Kanidat';
    const FOLDER_VIEW = 'polling_kanidat.';
    const FOLDER_UPLOAD = 'polling_kanidat';
    const URL = 'polling_kanidat.';

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

        $user = $this->sessionUser;

        $pemilik_penghuni_memilih = NULL;
        $pemilikPenghuniIsChoose = FALSE;

        if ($user->level == 'pemilik') {
            $pemilik = session()->get('pemilik');

            $pemilik_penghuni_memilih = $pemilik->id;
        }

        if ($user->level == 'penghuni') {
            $penghuni = session()->get('penghuni');

            $pemilik_penghuni_memilih = $penghuni->id;
        }

        $program = \App\Models\Program::whereHas('program_kegiatans', function (Builder $query) {
                $query->where('template', 'polling');
            })
            ->whereHas('program_kegiatans', function (Builder $query) {
                $query->where('template', 'form_pendaftaran');
            })
            ->where('id', $program_id)
            ->first();

        if (! $program) {
            return abort(403, "Program ini tidak memiliki template Form Pendaftaran atau Polling");
        }

        $checkUserHasBeenChoose = PollingKanidat::where([
            ['program_id', $program->id],
            ['rusun_id', $program->rusun_id],
            ['pemilik_penghuni_memilih', $pemilik_penghuni_memilih],
        ])->first();

        if (! $checkUserHasBeenChoose) {
            $pemilikPenghuniIsChoose = TRUE;
        }

        $totalSuara = PollingKanidat::where([
            ['program_id', $program->id],
            ['rusun_id', $program->rusun_id],
        ])->count();

        $grups = \App\Models\ProgramKanidat::orderBy('created_at')
            ->where('program_id', $program_id)
            ->where('grup_status', 1)
            ->groupBy('grup_nama')
            ->select('id', 'grup_id', 'grup_nama', DB::raw('COUNT(grup_nama) as total'), 'grup_status', 'program_id')
            ->get()
            ->map(function ($grup) use ($totalSuara) {
                $count = $grup->polling_kanidats()->count();

                $grup->total_suara = $count;

                if ($totalSuara > 0) {
                    $grup->total_suara_percent = round(($count / $totalSuara) * 100, 2);
                } else {
                    $grup->total_suara_percent = 0;
                }

                return $grup;
            });

        $kanidats = \App\Models\ProgramKanidat::where('program_id', $program_id)
            // ->where('grup_status', 1)
            ->pluck('pemilik_penghuni_id');

        $rusunPenghuni = \App\Models\RusunPenghuni::where('rusun_id', $program->rusun_id);
            // ->whereNotIn('id', $kanidats);
        $rusunPenghuniCount = $rusunPenghuni->count();
        $rusunPenghuniPemilik = $rusunPenghuni->pluck('rusun_pemilik_id');

        $rusunPemilikCount = \App\Models\RusunPemilik::whereNotIn('id', $rusunPenghuniPemilik)
            // ->whereNotIn('pemilik_id', $kanidats)
            ->count();

        $totalPemilikPenghuni = $rusunPenghuniCount + $rusunPemilikCount;

        $getPollingKanidat = PollingKanidat::latest('waktu')->where('program_id', $program_id);

        $pollingKanidat = $getPollingKanidat->first();
        $pollingKanidatCount = $getPollingKanidat->count();
        $pollingKanidats = PollingKanidat::latest('waktu')->where('program_id', $program_id)->get();

        $pollingKanidatTerpilih = \App\Models\ProgramKanidatTerpilih::where('program_id', $program_id)->first();

        $title = self::TITLE;
        $subTitle = $program->nama;

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'program', 'grups', 'pollingKanidat', 'pollingKanidatCount', 'totalPemilikPenghuni', 'pollingKanidats', 'pemilikPenghuniIsChoose', 'pollingKanidatTerpilih'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePollingKanidatRequest $request)
    {
        //
        $input = $request->all();
        $pemilik_penghuni_memilih = NULL;
        $user = $this->sessionUser;

        if ($user->level == 'pemilik') {
            $pemilik = session()->get('pemilik');

            $pemilik_penghuni_memilih = $pemilik->id;
        } else {
            $rusunPenghuni = session()->get('rusun_penghuni');

            $pemilik_penghuni_memilih = $rusunPenghuni->id;
        }

        $programKanidat = \App\Models\ProgramKanidat::where([
            ['grup_id', $request->grup_id],
            ['grup_status', 1]
        ])->firstOrFail();

        $input['waktu'] = Carbon::now();
        $input['apakah_pemilik'] = $user->level == 'pemilik' ? 1 : 0;
        $input['pemilik_penghuni_memilih'] = $pemilik_penghuni_memilih;
        $input['program_id'] = $programKanidat->program_id;
        $input['rusun_id'] = $programKanidat->rusun_id;

        PollingKanidat::create($input);

        return response()->json('Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PollingKanidat  $pollingKanidat
     * @return \Illuminate\Http\Response
     */
    public function show(PollingKanidat $pollingKanidat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PollingKanidat  $pollingKanidat
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Penetapan Program';

        $row = PollingKanidat::where('program_id', $id)->firstOrFail();

        $totalSuara = \App\Models\PollingKanidat::where([
            ['program_id', $row->program_id],
            ['rusun_id', $row->rusun_id],
        ])->count();

        $grups = \App\Models\ProgramKanidat::orderBy('created_at')
            ->where('program_id', $row->program_id)
            ->where('grup_status', 1)
            ->groupBy('grup_nama')
            ->select('id', 'grup_id', 'grup_nama', DB::raw('COUNT(grup_nama) as total'), 'grup_status', 'program_id')
            ->get()
            ->map(function ($grup) use ($totalSuara) {
                $count = $grup->polling_kanidats()->count();

                $grup->total_suara = $count;

                if ($totalSuara > 0) {
                    $grup->total_suara_percent = round(($count / $totalSuara) * 100, 2);
                } else {
                    $grup->total_suara_percent = 0;
                }

                return $grup;
            });

        $kanidats = \App\Models\ProgramKanidat::where('program_id', $row->program_id)
            // ->where('grup_status', 1)
            ->pluck('pemilik_penghuni_id');

        $rusunPenghuni = \App\Models\RusunPenghuni::where('rusun_id', $row->rusun_id);
            // ->whereNotIn('id', $kanidats);
        $rusunPenghuniCount = $rusunPenghuni->count();
        $rusunPenghuniPemilik = $rusunPenghuni->pluck('rusun_pemilik_id');

        $rusunPemilikCount = \App\Models\RusunPemilik::whereNotIn('id', $rusunPenghuniPemilik)
            // ->whereNotIn('pemilik_id', $kanidats)
            ->count();

        $totalPemilikPenghuni = $rusunPenghuniCount + $rusunPemilikCount;

        $getPollingKanidat = \App\Models\PollingKanidat::latest('waktu')->where('program_id', $row->program_id);

        $pollingKanidat = $getPollingKanidat->first();
        $pollingKanidatCount = $getPollingKanidat->count();
        $pollingKanidats = \App\Models\PollingKanidat::latest('waktu')->where('program_id', $row->program_id)->get();

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'totalSuara', 'totalPemilikPenghuni', 'pollingKanidatCount', 'grups', 'pollingKanidats', 'pollingKanidat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PollingKanidat  $pollingKanidat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $input = $request->all();
        
        \App\Models\Program::findOrFail($id);

        $grups = \App\Models\ProgramKanidat::where('grup_id', $input['grup_id'])->get();

        foreach ($grups as $grup) {
            \App\Models\ProgramKanidatTerpilih::create([
                'apakah_pemilik' => $grup->apakah_pemilik,
                'rusun_unit_detail_id' => $grup->rusun_unit_detail_id,
                'rusun_detail_id' => $grup->rusun_detail_id,
                'pemilik_penghuni_id' => $grup->pemilik_penghuni_id,
                'program_jabatan_id' => $grup->program_jabatan_id,
                'program_id' => $grup->program_id,
                'rusun_id' => $grup->rusun_id,
                'program_kanidat_id' => $grup->id,
                'grup_id' => $grup->grup_id,
            ]);
        }

        return redirect()
            ->route('program.index')
            ->with('success', 'Update data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PollingKanidat  $pollingKanidat
     * @return \Illuminate\Http\Response
     */
    public function destroy(PollingKanidat $pollingKanidat)
    {
        //
    }
}
