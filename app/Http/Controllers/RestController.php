<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Pengelola;
use App\Models\Pengembang;
use App\Models\Provinsi;
use App\Models\RusunDetail;
use Illuminate\Http\Request;

class RestController extends Controller
{
    //
    public function provinsis(Request $request)
    {
        $search = $request->search ?? NULL;

        $rows = [];
        if (!$search) {
            $rows = Provinsi::orderBy('name', 'asc')->get();
        } else {
            $rows = Provinsi::orderBy('name', 'asc')
                ->where('name', 'like', "%$search%")
                ->get();
        }

        return response()->json($rows);
    }

    public function kotas(Request $request)
    {
        $province_id = $request->province_id;
        $search = $request->search ?? NULL;

        $rows = [];
        if ($province_id) {
            $rows = Kota::orderBy('name', 'asc')
                ->where('province_id', $province_id)
                ->get();

            if ($search) {
                $rows = Kota::orderBy('name', 'asc')
                    ->where('province_id', $province_id)
                    ->where('name', 'like', "%$search%")
                    ->get();                
            }
        }

        return response()->json($rows);
    }

    public function kecamatans(Request $request)
    {
        $regencie_id = $request->regencie_id;
        $search = $request->search ?? NULL;

        $rows = [];
        if ($regencie_id) {
            $rows = Kecamatan::orderBy('name', 'asc')
                ->where('regency_id', $regencie_id)
                ->get();

            if ($search) {
                $rows = Kecamatan::orderBy('name', 'asc')
                    ->where('regency_id', $regencie_id)
                    ->where('name', 'like', "%$search%")
                    ->get();
            }
        }

        return response()->json($rows);
    }

    public function desas(Request $request)
    {
        $district_id = $request->district_id;
        $search = $request->search ?? NULL;

        $rows = [];
        if ($district_id) {
            $rows = Desa::orderBy('name', 'asc')
                ->where('district_id', $district_id)
                ->get();

            if ($search) {
                $rows = Desa::orderBy('name', 'asc')
                    ->where('district_id', $district_id)
                    ->where('name', 'like', "%$search%")
                    ->get();
            }
        }

        return response()->json($rows);
    }

    public function rusun_details(Request $request)
    {
        $rusun_id = $request->rusun_id;
        $search = $request->search ?? NULL;

        $rows = [];
        if ($rusun_id) {
            $rows = RusunDetail::orderBy('nama_tower', 'asc')
                ->where('rusun_id', $rusun_id)
                ->get();

            if ($search) {
                $rows = RusunDetail::orderBy('nama_tower', 'asc')
                    ->where('rusun_id', $rusun_id)
                    ->where('nama_tower', 'like', "%$search%")
                    ->get();
            }
        }

        return response()->json($rows);
    }

    public function pengembangs(Request $request)
    {
        $search = $request->search ?? NULL;

        $rows = [];
        if (!$search) {
            $rows = Pengembang::orderBy('nama', 'asc')->get();
        } else {
            $rows = Pengembang::orderBy('nama', 'asc')
                ->where('nama', 'like', "%$search%")
                ->get();
        }

        return response()->json($rows);
    }

    public function pengelolas(Request $request)
    {
        $search = $request->search ?? NULL;

        $rows = [];
        if (!$search) {
            $rows = Pengelola::orderBy('nama', 'asc')->get();
        } else {
            $rows = Pengelola::orderBy('nama', 'asc')
                ->where('nama', 'like', "%$search%")
                ->get();
        }

        return response()->json($rows);
    }
}
