<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKomplainRequest;
use App\Http\Requests\UpdateKomplainRequest;
use App\Models\Komplain;
use App\Models\KomplainFile;
use App\Models\KomplainTanggapan;
use App\Notifications\KomplaintNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KomplainController extends Controller
{

    const TITLE = 'Komplain';
    const FOLDER_VIEW = 'komplain.';
    const FOLDER_UPLOAD = 'komplain';
    const URL = 'komplain.';

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
        $status = $request->status ?? NULL;
        $tingkat = $request->tingkat ?? NULL;
        $search = $request->search ?? NULL;

        if ($status !== 'noreply' && $status !== 'reply' && $status !== 'undone' && $status !== 'done') {
            return abort(404);
        }

        if (isset($tingkat)) {
            if ($tingkat !== 'high' && $tingkat !== 'medium' && $tingkat !== 'low') {
                return abort(404);
            }
        }

        return $this->generatePage($status, $tingkat, $search);
    }

    public function pages(Request $request)
    {
        $status = $request->status ?? NULL;
        $tingkat = $request->tingkat ?? NULL;
        $search = $request->search ?? NULL;

        if ($status !== 'noreply' && $status !== 'reply' && $status !== 'undone' && $status !== 'done') {
            return abort(404);
        }

        if (isset($tingkat)) {
            if ($tingkat !== 'high' && $tingkat !== 'medium' && $tingkat !== 'low') {
                return abort(404);
            }
        }
        
        return $this->generatePage($status, $tingkat, $search);
    }

    protected function generatePage($status, $tingkat, $search)
    {
        $params = new \stdClass;
        $params->status = $status;
        $params->tingkat = $tingkat;
        $params->search = $search;

        $title = self::TITLE;
        $subTitle = 'List Data';
        
        $rows = $this->getDataKomplain()
            ->whereYear('tanggal_dibuat', date('Y'))
            ->when($params, function ($query, $params) {
                $status = $params->status ?? NULL;
                $tingkat = $params->tingkat ?? NULL;
                $search = $params->search ?? NULL;
                
                if ($status == 'noreply') {
                    $query
                        ->where('sudah_dijawab', 0)
                        ->where('status', '!=', '1')
                        ->where('status', '!=', '3');
                }

                if ($status == 'reply') {
                    $query->where('sudah_dijawab', 1)
                        ->where('status', '!=', '1')
                        ->where('status', '!=', '3');
                }

                if ($status == 'undone') {
                    $query->where('status', 3);
                }

                if ($status == 'done') {
                    $query->where('status', 1);
                }

                if (isset($tingkat)) {
                    if ($tingkat == 'high') {
                        $query->where('tingkat', 3);
                    }

                    if ($tingkat == 'medium') {
                        $query->where('tingkat', 2);
                    }

                    if ($tingkat == 'low') {
                        $query->where('tingkat', 1);
                    }
                }

                if (isset($search)) {
                    $query->where('kode', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%");
                }
            })
            ->paginate(10);

        $path = '/page/q?status=' . $status;
        
        if (isset($tingkat)) {
            $path .= '&tingkat=' . $tingkat;
        }

        $rows->withPath($path);

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'rows', 'status', 'params'));
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
        $subTitle = 'List Data';

        $status = $request->status ?? NULL;
        $tingkat = $request->tingkat ?? NULL;

        if ($status !== 'noreply' && $status !== 'reply' && $status !== 'undone' && $status !== 'done') {
            return abort(404);
        }

        if (isset($tingkat)) {
            if ($tingkat !== 'high' && $tingkat !== 'medium' && $tingkat !== 'low') {
                return abort(404);
            }
        }

        if ($this->sessionUser->level !== 'Pemilik' || $this->sessionUser->level !== 'Penghuni') {
            return abort(403, "Anda tidak memiliki akses ke halaman ini.");
        }
        
        $rusuns = $this->getRusun();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusuns', 'status', 'tingkat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreKomplainRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKomplainRequest $request)
    {
        //
        $input = $request->all();
        $attachments = $input['attachments'] ?? [];

        unset($input['files'], $input['attachments']);

        $insertAttachment = [];
        if (count($attachments)) {
            for ($i=0; $i < count($attachments); $i++) { 
                $type = $attachments[$i]->extension();
                $filename = md5(uniqid()) . '.' . $type;

                $attachments[$i]->storeAs(
                    self::FOLDER_UPLOAD,
                    $filename,
                    'local',
                );

                $insertAttachment[] = [
                    'filename' => $filename,
                    'tipe' => $type,
                    'pengelola_id' => $request->pengelola_id,
                    'rusun_id' => $request->rusun_id,
                ];
            }
        }

        $rusun = \App\Models\Rusun::where('id', $request->rusun_id)->firstOrFail();

        if ($request->pengelola_id) {
            $pengelola = \App\Models\Pengelola::where('id', $request->pengelola_id)->firstOrFail();
        }

        $input['kode'] = strtoupper(Str::random(6));
        $input['status'] = 0;
        $input['tanggal_dibuat'] = Carbon::now();
        $input['komplain_user_id'] = auth()->user()->id;
        
        $input['province_id'] = $rusun->province_id;
        $input['regencie_id'] = $rusun->regencie_id;
        $input['district_id'] = $rusun->district_id;
        $input['village_id'] = $rusun->village_id;

        $result = DB::transaction(function () use ($input, $attachments, $insertAttachment) {
            $komplain = Komplain::create($input);

            if (count($attachments) > 0) {
                $komplain->komplain_files()->createMany($insertAttachment);
            }

            $komplain->attachment = $insertAttachment;

            return $komplain;
        });

        $rusun->notify(new KomplaintNotification($result));

        if (isset($pengelola)) {
            $pengelolaKontaks = \App\Models\PengelolaKontak::where('pengelola_id', $request->pengelola_id)->get();

            // $pengelola->notify(new KomplaintNotification($result));

            // Notification::send($pengelolaKontaks, new KomplaintNotification($result));
        }

        return redirect()
            ->route(self::URL . 'show', [$result->id, 'status=noreply'],)
            ->with('success', 'Komplain sudah masuk, mohon menunggu updatenya...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Komplain  $komplain
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Komplain $komplain)
    {
        //
        $status = $request->status ?? NULL;

        if (!$status) {
            return abort(404);
        }

        if ($komplain->komplain_user_id !== auth()->user()->id) {
            $komplain->komplain_user_bukas()
                ->firstOrCreate(
                    [
                        'user_id' => auth()->user()->id,
                        'pengelola_id' => $komplain->pengelola_id,
                        'rusun_id' => $komplain->rusun_id,
                    ],
                    [
                        'waktu' => Carbon::now(),
                    ]
                );
        }

        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = $komplain;

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row', 'status'));        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Komplain  $komplain
     * @return \Illuminate\Http\Response
     */
    public function edit(Komplain $komplain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKomplainRequest  $request
     * @param  \App\Models\Komplain  $komplain
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKomplainRequest $request, Komplain $komplain)
    {
        //
        $status = $request->status ?? NULL;
        if ($status == 'done' || $status == 'undone') {
            $komplain->update([
                'status' => $status == 'done' ? 1 : 3,
                'tanggal_diselesaikan' => Carbon::now(),
            ]);
    
            return response()->json('Success');
        } else {
            return abort(403, 'Statuts tutup komplain tidak ditemukan');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Komplain  $komplain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Komplain $komplain)
    {
        //
    }

    public function tanggapi(Request $request, $id)
    {
        $title = self::TITLE;
        $subTitle = 'Tanggapi';

        $row = Komplain::findOrFail($id);

        $status = $request->status ?? NULL;
        $tingkat = $request->tingkat ?? NULL;

        if ($status !== 'noreply' && $status !== 'reply' && $status !== 'undone' && $status !== 'done') {
            return abort(404);
        }

        if (isset($tingkat)) {
            if ($tingkat !== 'high' && $tingkat !== 'medium' && $tingkat !== 'low') {
                return abort(404);
            }
        }

        return view(self::FOLDER_VIEW . 'tanggapi_create', compact('title', 'subTitle', 'row', 'status', 'tingkat'));
    }

    public function tanggapiStore(Request $request, $id)
    {
        Validator::make($request->all(), [
            'penjelasan' => 'required|string',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'nullable|max:1024|mimes:jpeg,bmp,png,gif,svg,pdf'
        ])->validate();

        $input = $request->all();
        $attachments = $input['attachments'] ?? [];

        unset($input['files'], $input['attachments']);

        $komplain = Komplain::findOrFail($id);

        if ($komplain->pengelola_id) {
            $pengelolaKontaks = \App\Models\PengelolaKontak::where('pengelola_id', $komplain->pengelola_id)->get();
        }

        $input['ditanggapi_user_id'] = auth()->user()->id;
        $input['komplain_id'] = $komplain->id;
        $input['pengelola_id'] = $komplain->pengelola_id;
        $input['rusun_id'] = $komplain->rusun_id;

        $insertAttachment = [];
        if (count($attachments)) {
            for ($i=0; $i < count($attachments); $i++) { 
                $type = $attachments[$i]->extension();
                $filename = md5(uniqid()) . '.' . $type;

                $attachments[$i]->storeAs(
                    self::FOLDER_UPLOAD,
                    $filename,
                    'local',
                );

                $insertAttachment[] = [
                    'filename' => $filename,
                    'type' => $type,
                    'pengelola_id' => $komplain->pengelola_id,
                    'rusun_id' => $komplain->rusun_id,
                    'komplain_id' => $id,
                ];
            }
        }

        DB::transaction(function () use ($input, $attachments, $insertAttachment, $komplain) {
            $komplain->update([
                'tanggal_ditanggapi' => Carbon::now(),
                'sudah_dijawab' => 1,
            ]);

            $komplain_tanggapan = KomplainTanggapan::create($input);

            if (count($attachments) > 0) {
                $komplain_tanggapan->komplain_files()->createMany($insertAttachment);
            }
        });
        
        // Notification::send($komplain->rusun, new KomplaintNotification($komplain));
        // Notification::send($komplain->user, new KomplaintNotification($komplain));

        if (isset($komplain->pengelola)) {
            // Notification::send($komplain->pengelola, new KomplaintNotification($komplain));

            if ($pengelolaKontaks) {
                // Notification::send($pengelolaKontaks, new KomplaintNotification($komplain));
            }
        }

        return redirect()
            ->route(self::URL . 'show', [$id, 'status=reply'])
            ->with('success', 'Komplain sudah ditanggapi, terimakasih...');
    }

    public function tanggapiShow(Request $request, $fk, $id)
    {
        $title = self::TITLE;
        $subTitle = 'Tanggapi Detail';

        $row = KomplainTanggapan::findOrFail($id);

        if ($row->ditanggapi_user_id !== auth()->user()->id) {
            $row->komplain_user_bukas()
                ->firstOrCreate(
                    [
                        'user_id' => auth()->user()->id,
                        'komplain_id' => $fk,
                        'pengelola_id' => $row->pengelola_id,
                        'rusun_id' => $row->rusun_id,
                    ],
                    [
                        'waktu' => Carbon::now(),
                    ]
                );
        }

        if ($row->parent) {
            $parent = KomplainTanggapan::where('id', $row->parent)->first();
        } else {
            $parent = $row->komplain;
        }

        $status = $request->status ?? NULL;
        $tingkat = $request->tingkat ?? NULL;

        if ($status !== 'noreply' && $status !== 'reply' && $status !== 'undone' && $status !== 'done') {
            return abort(404);
        }

        if (isset($tingkat)) {
            if ($tingkat !== 'high' && $tingkat !== 'medium' && $tingkat !== 'low') {
                return abort(404);
            }
        }

        $user = $this->sessionUser;

        $rusuns = $this->getRusun();

        return view(self::FOLDER_VIEW . 'tanggapi_show', compact('title', 'subTitle', 'row', 'rusuns', 'status', 'tingkat', 'parent'));
    }

    public function tanggapiKembali(Request $request, $id)
    {
        $title = self::TITLE;
        $subTitle = 'Ditanggapi Kembali';

        $row = KomplainTanggapan::findOrFail($id);

        if ($row->parent) {
            $parent = KomplainTanggapan::where('id', $row->parent)->first();
        } else {
            $parent = $row->komplain;
        }

        $status = $request->status ?? NULL;
        $tingkat = $request->tingkat ?? NULL;

        if ($status !== 'noreply' && $status !== 'reply' && $status !== 'undone' && $status !== 'done') {
            return abort(404);
        }

        if (isset($tingkat)) {
            if ($tingkat !== 'high' && $tingkat !== 'medium' && $tingkat !== 'low') {
                return abort(404);
            }
        }

        return view(self::FOLDER_VIEW . 'ditanggapi_kembali', compact('title', 'subTitle', 'row', 'status', 'tingkat'));
    }

    public function tanggapiKembaliStore(Request $request, $id)
    {
        Validator::make($request->all(), [
            'penjelasan' => 'required|string',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'nullable|max:1024|mimes:jpeg,bmp,png,gif,svg,pdf'
        ])->validate();

        $input = $request->all();
        $attachments = $input['attachments'] ?? [];

        unset($input['files'], $input['attachments']);

        $komplainTanggapan = KomplainTanggapan::findOrFail($id);

        $input['ditanggapi_user_id'] = auth()->user()->id;
        $input['komplain_id'] = $komplainTanggapan->komplain_id;
        $input['pengelola_id'] = $komplainTanggapan->pengelola_id;
        $input['rusun_id'] = $komplainTanggapan->rusun_id;
        $input['parent'] = $komplainTanggapan->id;

        $insertAttachment = [];
        if (count($attachments)) {
            for ($i=0; $i < count($attachments); $i++) { 
                $type = $attachments[$i]->extension();
                $filename = md5(uniqid()) . '.' . $type;

                $attachments[$i]->storeAs(
                    self::FOLDER_UPLOAD,
                    $filename,
                    'local',
                );

                $insertAttachment[] = [
                    'filename' => $filename,
                    'type' => $type,
                    'pengelola_id' => $komplainTanggapan->pengelola_id,
                    'rusun_id' => $komplainTanggapan->rusun_id,
                    'komplain_id' => $id,
                ];
            }
        }

        DB::transaction(function () use ($input, $attachments, $insertAttachment) {
            $komplainDitanggapiKembali = KomplainTanggapan::create($input);

            if (count($attachments) > 0) {
                $komplainDitanggapiKembali->komplain_files()->createMany($insertAttachment);
            }
        });

        return redirect()
            ->route(self::URL . 'show', [$komplainTanggapan->komplain_id, 'status=reply'])
            ->with('success', 'Komplain sudah ditanggapi kembali, terimakasih...');
    }

    public function view_file(Request $request, $id, $filename)
    {
        $komplainFile = KomplainFile::where([
            ['id', $id],
            ['filename', $filename]
        ])->firstOrFail();

        $type = $request->type ?? NULL;
        switch ($type) {
            case 'preview':
                $file = Storage::path(self::FOLDER_UPLOAD . '/' . $komplainFile->filename);

                return response()->file($file);
                break;

            case 'download':
                return Storage::download(self::FOLDER_UPLOAD . '/' . $komplainFile->filename);
                break;

            default:
                return abort(404);
                break;
        }
    }

    // api
    public function apiList(Request $request)
    {
        $search = $request->search ?? NULL;

        $rows = $this->getDataKomplain()
            // ->when($search, function ($query, $search) {
            //     $query
            //         ->where('kode', 'like', "%{$search}%")
            //         ->orWhere('judul', 'like', "%{$search}%");
            // })
            ->whereYear('tanggal_dibuat', date('Y'))
            ->get();

        $collect = collect($rows);

        // status
        $noReply = $collect->where('sudah_dijawab', 0)->where('status', '!=', '1')->where('status', '!=', '3')->count();
        $reply = $collect->where('sudah_dijawab', 1)->where('status', '!=', '1')->where('status', '!=', '3')->count();
        $undone = $collect->where('status', 3)->count();
        $done = $collect->where('status', 1)->count();

        // tingkat
        $high = $collect->where('tingkat', 3)->count();
        $medium = $collect->where('tingkat', 2)->count();
        $low = $collect->where('tingkat', 1)->count();

        return [
            'noReply' => $noReply,
            'reply' => $reply,
            'undone' => $undone,
            'done' => $done,

            'high' => $high,
            'medium' => $medium,
            'low' => $low,
        ];
    }

    // query
    protected function getDataKomplain()
    {
        $user = $this->sessionUser;

        return Komplain::orderBy('created_at', 'desc')
            ->when($user, function ($query, $user) {
                if ($user->level == 'pemilik') {
                    $sessionData = session()->get('pemilik');
                    $rusunPemiliks = \App\Models\RusunPemilik::where('pemilik_id', $sessionData->id)->pluck('rusun_id');

                    $query->where('komplain_user_id', $user->id);
                    $query->whereIn('rusun_id', $rusunPemiliks);
                }

                if ($user->level == 'penghuni') {
                    $sessionData = session()->get('rusun_penghuni');

                    $query->where('komplain_user_id', $user->id);
                    $query->where('rusun_id', $sessionData->rusun_id);
                }

                if ($user->level == 'rusun') {
                    $sessionData = session()->get('rusun');

                    $query->where('id', $sessionData->id);
                }

                if ($user->level == 'pemda') {
                    $sessionData = session()->get('pemda');

                    $query
                        ->where('province_id', $sessionData->province_id)
                        ->where('regencie_id', $sessionData->regencie_id);
                }
            });
    }

    protected function getRusun()
    {
        $user = $this->sessionUser;

        return \App\Models\Rusun::orderBy('nama')
            ->when($user, function ($query, $user) {
                if ($user->level == 'pemilik') {
                    $sessionData = session()->get('pemilik');
                    $rusunPemiliks = \App\Models\RusunPemilik::where('pemilik_id', $sessionData->id)->pluck('rusun_id');

                    $query->whereIn('id', $rusunPemiliks);
                }

                if ($user->level == 'penghuni') {
                    $sessionData = session()->get('rusun_penghuni');

                    $query->where('id', $sessionData->rusun_id);
                }

                if ($user->level == 'rusun') {
                    $sessionData = session()->get('rusun');

                    $query->where('id', $sessionData->id);
                }

                if ($user->level == 'pemda') {
                    $sessionData = session()->get('pemda');

                    $query
                        ->where('province_id', $sessionData->province_id)
                        ->where('regencie_id', $sessionData->regencie_id);
                }
            })
            ->get();
    }
}
