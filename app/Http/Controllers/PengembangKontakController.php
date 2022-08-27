<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengembangKontakRequest;
use App\Http\Requests\UpdatePengembangKontakRequest;
use App\Models\PengembangKontak;

class PengembangKontakController extends Controller
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
     * @param  \App\Http\Requests\StorePengembangKontakRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengembangKontakRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function show(PengembangKontak $pengembangKontak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function edit(PengembangKontak $pengembangKontak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePengembangKontakRequest  $request
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengembangKontakRequest $request, PengembangKontak $pengembangKontak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PengembangKontak  $pengembangKontak
     * @return \Illuminate\Http\Response
     */
    public function destroy(PengembangKontak $pengembangKontak)
    {
        //
    }
}
