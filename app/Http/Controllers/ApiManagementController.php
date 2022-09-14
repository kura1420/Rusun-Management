<?php

namespace App\Http\Controllers;

use App\Helpers\ApiService;
use App\Http\Requests\StoreApiManagementRequest;
use App\Http\Requests\UpdateApiManagementRequest;
use App\Models\ApiManagement;
use Illuminate\Http\Request;

class ApiManagementController extends Controller
{

    const TITLE = 'API Manage';
    const FOLDER_VIEW = 'api_manage.';
    const URL = 'api-manage.';

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

        $rows = ApiManagement::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->keterangan,
                $row->username,
                $row->password,
                $row->last_sync,
                '<nobr>' . 
                    '<button type="button" class="btn btn-success btn-sm btnTestEndpoint" value="'.$row->id.'" id="'.route(self::URL . 'testEndpoint', $row->id).'"><i class="fas fa-plane"></i> Test API</button> ' . 
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Keterangan',
            'Username',
            'Password',
            'Last Sync',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, ['orderable' => false]],
        ];

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return abort(404);
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
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApiManagement  $apiManagement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = ApiManagement::findOrFail($id);

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApiManagementRequest  $request
     * @param  \App\Models\ApiManagement  $apiManagement
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApiManagementRequest $request, $id)
    {
        //
        $input = $request->all();
        
        $row = ApiManagement::findOrFail($id);

        $row->update($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Perbarui data berhasil...');
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
        $apiManagement->delete();

        return response()->json('Success');
    }

    public function testEndpoint($id)
    {
        $row = ApiManagement::findOrFail($id);

        $res = ApiService::run($row, 'GET', NULL);

        return $res->object();
    }
}
