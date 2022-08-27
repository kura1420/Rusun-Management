<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengembangRequest;
use App\Http\Requests\UpdatePengembangRequest;
use App\Models\Pengembang;

class PengembangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StorePengembangRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengembangRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Http\Response
     */
    public function show(Pengembang $pengembang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengembang $pengembang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePengembangRequest  $request
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengembangRequest $request, Pengembang $pengembang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengembang  $pengembang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengembang $pengembang)
    {
        //
    }
}
