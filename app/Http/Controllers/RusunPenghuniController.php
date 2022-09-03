<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunPenghuniRequest;
use App\Http\Requests\UpdateRusunPenghuniRequest;
use App\Models\RusunPenghuni;

class RusunPenghuniController extends Controller
{

    const TITLE = 'Rusun Penghuni';
    const FOLDER_VIEW = 'rusun_penghuni.';
    const URL = 'rusun-penghuni.';
    
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

        $rows = RusunPenghuni::with([
                'rusuns',
            ])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn($row) => [
                $row->rusuns->nama,
                $row->nama,
                $row->email,
                $row->phone,
                $row->updated_at,
            ]);

        $heads = [
            'Rusun',
            'Nama',
            'Email',
            'Phone',
        ];
        
        $config = [
            'data' => $rows,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null],
        ];

        $lastUpdate = collect($rows)
            ->sortBy(['updated_at', 'desc'])
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
     * @param  \App\Http\Requests\StoreRusunPenghuniRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunPenghuniRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Http\Response
     */
    public function show(RusunPenghuni $rusunPenghuni)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Http\Response
     */
    public function edit(RusunPenghuni $rusunPenghuni)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunPenghuniRequest  $request
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunPenghuniRequest $request, RusunPenghuni $rusunPenghuni)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RusunPenghuni  $rusunPenghuni
     * @return \Illuminate\Http\Response
     */
    public function destroy(RusunPenghuni $rusunPenghuni)
    {
        //
    }
}
