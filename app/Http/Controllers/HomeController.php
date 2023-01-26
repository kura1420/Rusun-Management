<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jorenvh\Share\ShareFacade as Share;

class HomeController extends Controller
{

    const TITLE = 'Beranda';
    const FOLDER_VIEW = 'beranda.';
    const URL = '/';

    protected $sessionUser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->sessionUser = auth()->user();

            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = self::TITLE;
        $subTitle = NULL;
        $today = Carbon::today()->format('Y-m-d');

        $userSession = $this->sessionUser;
        if ($userSession->level == 'pemilik' || $userSession->level == 'penghuni') {
            return redirect()->route('beranda.penghuni');
        }

        $programs = \App\Models\Program::where([
                ['status', 2],
                ['publish', 1],
                [DB::raw('YEAR(publish_at)'), date('Y')],
            ])
            ->latest('publish_at')
            ->get()
            ->map(function ($program) use ($today) {
                $formPendaftaran = $program->program_kegiatans()
                    ->where('template', 'form_pendaftaran')
                    // ->where('tanggal_mulai', '>=', $today)
                    // ->where('tanggal_berakhir', '<=', $today)
                    ->first();

                $polling = $program->program_kegiatans()
                    ->where('template', 'polling')
                    // ->where('tanggal_mulai', '>=', $today)
                    // ->where('tanggal_berakhir', '<=', $today)
                    ->first();

                if ($formPendaftaran) {
                    $program->grups = $program->program_kanidats()->groupBy('grup_id')->get();
                } else {
                    $program->grups = [];
                }

                if ($polling) {
                    $program->polling_result = $polling;
                } else {
                    $program->polling_result = NULL;
                }
                
                $program->form_pendaftaran = $formPendaftaran ? TRUE : FALSE;
                $program->polling = $polling ? TRUE : FALSE;
                
                $program->keterangan = Str::limit($program->keterangan, 500);
                $program->publish_at = Carbon::parse($program->publish_at)->diffForHumans();

                $sharedLinks = Share::page(route('blog.program-show', $program->slug), $program->nama)
                    ->facebook()
                    ->twitter()
                    ->whatsapp()
                    ->telegram()
                    ->getRawLinks();
                $program->shareds = $sharedLinks;

                return $program;
            });

        $tickets = \App\Models\Komplain::latest('updated_at', 'desc')
            ->where([
                ['sudah_dijawab', 0],
                ['status', '!=', 1],
                ['status', '!=', 3],
                [DB::raw('YEAR(created_at)'), date('Y')],
            ])
            ->orWhere([
                ['sudah_dijawab', 0],
                ['status', '!=', 1],
                ['status', '!=', 3],
                [DB::raw('YEAR(updated_at)'), date('Y')],
            ])
            ->limit(7)
            ->get();

        return view('home', compact('title', 'subTitle', 'programs', 'tickets'));
    }

    public function penghuni()
    {
        $title = self::TITLE;
        $subTitle = NULL;
        $pemilik_penghuni_id = NULL;
        $today = Carbon::today()->format('Y-m-d');

        $user = $this->sessionUser;

        if ($user->level == 'pemilik') {
            $pemilik = session()->get('pemilik');
            $pemilik_penghuni_id = $pemilik->id;
        }

        if ($user->level == 'penghuni') {
            $rusunPenghuni = session()->get('rusun_penghuni');
            $pemilik_penghuni_id = $rusunPenghuni->id;
        }

        $programs = \App\Models\Program::where([
                ['status', 2],
                ['publish', 1],
                [DB::raw('YEAR(publish_at)'), date('Y')],
            ])
            ->when($user, function ($query, $user) {
                if ($user->level == 'pemilik') {
                    $pemilik = session()->get('pemilik');
                    
                    $rusuns = \App\Models\RusunPemilik::where('pemilik_id', $pemilik->id)->pluck('rusun_id');

                    $query->whereIn('rusun_id', $rusuns);
                }

                if ($user->level == 'penghuni') {
                    $rusunPenghuni = session()->get('rusun_penghuni');
                    
                    $query->where('rusun_id', $rusunPenghuni->rusun_id);
                }
            })
            ->latest('publish_at')
            ->get()
            ->map(function ($program) use ($pemilik_penghuni_id, $today) {
                $formPendaftaran = $program->program_kegiatans()
                    ->where('template', 'form_pendaftaran')
                    // ->where('tanggal_mulai', '>=', $today)
                    // ->where('tanggal_berakhir', '<=', $today)
                    ->first();

                $polling = $program->program_kegiatans()
                    ->where('template', 'polling')
                    // ->where('tanggal_mulai', '>=', $today)
                    // ->where('tanggal_berakhir', '<=', $today)
                    ->first();

                if ($formPendaftaran) {
                    $programKanidat = $program->program_kanidats()
                        ->where('pemilik_penghuni_id', $pemilik_penghuni_id)
                        ->where('status', 4)
                        ->orWhere('pemilik_penghuni_id', $pemilik_penghuni_id)
                        ->where('status', 5)
                        ->first();

                    $programKanidatCheck = $program->program_kanidats()
                        ->where('pemilik_penghuni_id', $pemilik_penghuni_id)
                        ->where('status', '!=', 4)
                        ->orWhere('pemilik_penghuni_id', $pemilik_penghuni_id)
                        ->where('status', '!=', 5)
                        ->first();

                    $program->undangan = $programKanidat ? route('program-kanidat-dokumen.index', ['program_kanidat_id' => $programKanidat->id]) : NULL;
                    $program->register = ! $programKanidatCheck ? TRUE : FALSE;
                }

                if ($polling) {
                    $program->polling_result = $polling;
                } else {
                    $program->polling_result = NULL;
                }

                $program->form_pendaftaran = $formPendaftaran ? TRUE : FALSE;
                $program->polling = $polling ? TRUE : FALSE;

                $program->keterangan = Str::limit($program->keterangan, 500);
                $program->publish_at = Carbon::parse($program->publish_at)->diffForHumans();

                $sharedLinks = Share::page(route('blog.program-show', $program->slug), $program->nama)
                    ->facebook()
                    ->twitter()
                    ->whatsapp()
                    ->telegram()
                    ->getRawLinks();

                $program->shareds = $sharedLinks;

                return $program;
            });

        $programTeams = \App\Models\Program::whereHas('program_kegiatans', function (Builder $query) {
                $query->where('template', 'form_pendaftaran');
            })
            ->where([
                ['status', 2],
                ['publish', 1],
                [DB::raw('YEAR(publish_at)'), date('Y')],
            ])
            ->latest('publish_at')
            ->get()
            ->map(function ($program) use ($pemilik_penghuni_id) {
                $programKanidat = $program->program_kanidats()
                    ->where('pemilik_penghuni_id', $pemilik_penghuni_id)
                    ->where('status', '!=', 6)
                    ->first();

                $teams = [];
                if ($programKanidat) {
                    $teams = $program->program_kanidats()
                        ->where('grup_id', $programKanidat->grup_id)
                        ->get()
                        ->map(function ($team) {
                            $team->profile = $team->pemilik_penghuni_profile;

                            return $team;
                        });
                }

                $program->teams = $teams;
                $program->team_count = count($teams);

                return $program;
            });

        return view(self::FOLDER_VIEW . 'penghuni', compact('title', 'subTitle', 'programs', 'programTeams'));
    }
}
