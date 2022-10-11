<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKomplainRequest;
use App\Http\Requests\UpdateKomplainRequest;
use App\Models\Komplain;
use App\Models\KomplainTanggapan;
use App\Notifications\KomplaintNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
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

        if ($status !== 'noreply' && $status !== 'reply' && $status !== 'undone' && $status !== 'done') {
            return abort(404);
        }

        if (isset($tingkat)) {
            if ($tingkat !== 'high' && $tingkat !== 'medium' && $tingkat !== 'low') {
                return abort(404);
            }
        }

        return $this->generatePage($status, $tingkat);
    }

    public function pages(Request $request)
    {
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
        
        return $this->generatePage($status, $tingkat);
    }

    protected function generatePage($status, $tingkat)
    {
        $params = new \stdClass;
        $params->status = $status;
        $params->tingkat = $tingkat;

        $title = self::TITLE;
        $subTitle = 'List Data';
        
        $rows = Komplain::orderBy('created_at', 'desc')
            ->whereYear('tanggal_dibuat', date('Y'))
            ->when($params, function ($query, $params) {
                $status = $params->status ?? NULL;
                $tingkat = $params->tingkat ?? NULL;
                
                if ($status == 'noreply') {
                    $query->where('sudah_dijawab', 0);
                }

                if ($status == 'reply') {
                    $query->where('sudah_dijawab', 1);
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
            })
            ->paginate(10);

        $path = '/page/q?status=' . $status;
        
        if (isset($tingkat)) {
            $path .= '&tingkat=' . $tingkat;
        }

        $rows->withPath($path);

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'rows', 'status'));
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
        $subTitle = 'List Data';

        $user = $this->sessionUser;
        
        $rusuns = \App\Models\Rusun::orderBy('nama')
            ->when($user, function ($query, $user) {
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

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusuns'));
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
                    'type' => $type,
                    'pengelola_id' => $request->pengelola_id,
                    'rusun_id' => $request->rusun_id,
                ];
            }
        }

        $rusun = \App\Models\Rusun::where('id', $request->rusun_id)->firstOrFail();
        $pengelola = \App\Models\Pengelola::where('id', $request->pengelola_id)->firstOrFail();

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

        // $rusun->notify(new KomplaintNotification($result));

        $pengelola->notify(new KomplaintNotification($result));
        $pengelolaKontaks = \App\Models\PengelolaKontak::where('pengelola_id', $request->pengelola_id)->get();

        Notification::send($pengelolaKontaks, new KomplaintNotification($result));

        return redirect()
            ->route(self::URL . 'show', $result->id)
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
        // $komplain->update([
        //     'status' => 1,
        //     'tanggal_diselesaikan' => Carbon::now(),
        // ]);

        // return response()->json('Success');
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

    public function tanggapi($id)
    {
        $title = self::TITLE;
        $subTitle = 'Tanggapi';

        $row = Komplain::findOrFail($id);

        return view(self::FOLDER_VIEW . 'tanggapi', compact('title', 'subTitle', 'row'));        
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
        $pengelolaKontaks = \App\Models\PengelolaKontak::where('pengelola_id', $komplain->pengelola_id)->get();

        $input['ditanggapi_user_id'] = auth()->user()->id;
        $input['komplain_id'] = $id;
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
            ]);

            $komplain_tanggapan = KomplainTanggapan::create($input);

            if (count($attachments) > 0) {
                $komplain_tanggapan->komplain_files()->createMany($insertAttachment);
            }
        });
        
        // Notification::send($komplain->rusun, new KomplaintNotification($komplain));

        Notification::send($komplain->pengelola, new KomplaintNotification($komplain));
        Notification::send($pengelolaKontaks, new KomplaintNotification($komplain));

        return redirect()
            ->route(self::URL . 'show', $id)
            ->with('success', 'Komplain sudah ditanggapi, terimakasih...');
    }

    public function apiList(Request $request)
    {
        $search = $request->search ?? NULL;

        $rows = Komplain::orderBy('created_at')
            ->when($search, function ($query, $search) {
                $query
                    ->where('kode', 'like', "%{$search}%")
                    ->orWhere('judul', 'like', "%{$search}%");
            })
            ->whereYear('tanggal_dibuat', date('Y'))
            ->get();

        $collect = collect($rows);

        // status
        $noReply = $collect->where('sudah_dijawab', 0)->count();
        $reply = $collect->where('sudah_dijawab', 1)->count();
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
}
