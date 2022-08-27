<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunRequest;
use App\Http\Requests\UpdateRusunRequest;
use App\Models\Rusun;

class RusunController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRusunRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunRequest $request)
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
     * @param  \App\Http\Requests\UpdateRusunRequest  $request
     * @param  \App\Models\Rusun  $rusun
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunRequest $request, Rusun $rusun)
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
