<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApiManagementRequest;
use App\Http\Requests\UpdateApiManagementRequest;
use App\Models\ApiManagement;

class ApiManagementController extends Controller
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
     * @param  \App\Http\Requests\StoreApiManagementRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApiManagementRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApiManagement  $apiManagement
     * @return \Illuminate\Http\Response
     */
    public function show(ApiManagement $apiManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApiManagement  $apiManagement
     * @return \Illuminate\Http\Response
     */
    public function edit(ApiManagement $apiManagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApiManagementRequest  $request
     * @param  \App\Models\ApiManagement  $apiManagement
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApiManagementRequest $request, ApiManagement $apiManagement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApiManagement  $apiManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApiManagement $apiManagement)
    {
        //
    }
}
