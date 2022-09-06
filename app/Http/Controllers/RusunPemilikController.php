<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunPemilikRequest;
use App\Http\Requests\UpdateRusunPemilikRequest;
use App\Models\RusunPemilik;

class RusunPemilikController extends Controller
{

    const TITLE = 'Rusun Pemilik';
    const FOLDER_VIEW = 'rusun_pemilik.';
    const URL = 'rusun-pemilik.';
    
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

        $rows = RusunPemilik::with([
                'rusuns',
                'rusun_details',
                'rusun_unit_details',
            ])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn($row) => [
                $row->rusuns->nama,
                $row->rusun_details->nama_tower ?? NULL,
                $row->nama,
                $row->email,
                $row->phone,
                $row->identitas_tipe,
                $row->identitas_nomor,
                $row->updated_at,
            ]);

        $heads = [
            'Rusun',
            'Tower',
            'Nama',
            'Email',
            'Phone',
            'Identitas Tipe',
            'Identitas Nomor',
        ];
        
        $config = [
            'data' => $rows,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, null, null],
        ];

        $lastUpdate = collect($rows)
            ->sortKeysDesc(5)
            ->first();

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'heads', 'config', 'lastUpdate'));
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
     * @param  \App\Http\Requests\StoreRusunPemilikRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunPemilikRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RusunPemilik  $rusunPemilik
     * @return \Illuminate\Http\Response
     */
    public function show(RusunPemilik $rusunPemilik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RusunPemilik  $rusunPemilik
     * @return \Illuminate\Http\Response
     */
    public function edit(RusunPemilik $rusunPemilik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunPemilikRequest  $request
     * @param  \App\Models\RusunPemilik  $rusunPemilik
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunPemilikRequest $request, RusunPemilik $rusunPemilik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RusunPemilik  $rusunPemilik
     * @return \Illuminate\Http\Response
     */
    public function destroy(RusunPemilik $rusunPemilik)
    {
        //
    }
}
