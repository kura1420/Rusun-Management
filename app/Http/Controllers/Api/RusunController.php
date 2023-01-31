<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rusun;
use Illuminate\Http\Request;

class RusunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $search = $request->search ?? NULL;

        $rows = Rusun::orderBy('nama')
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->select([
                'nama', 
                'alamat', 
                'kode_pos', 
                'email', 
                'telp', 
                'province_id',
                'regencie_id',
                'district_id',
                'village_id',
            ])
            ->get()
            ->map(function ($row) {
                $row->provinces;
                $row->kotas;
                $row->kecamatans;
                $row->desas;

                unset(
                    $row->province_id,
                    $row->regencie_id,
                    $row->district_id,
                    $row->village_id,
                );

                return $row;
            });

        return response()->json($rows, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rusun  $rusun
     * @return \Illuminate\Http\Response
     */
    public function show(Rusun $rusun)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rusun  $rusun
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rusun $rusun)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rusun  $rusun
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rusun $rusun)
    {
        //
    }
}
