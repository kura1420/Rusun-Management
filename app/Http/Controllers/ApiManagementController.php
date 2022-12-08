<?php

namespace App\Http\Controllers;

use App\Helpers\ApiService;
use App\Helpers\SyncData;
use App\Http\Requests\StoreApiManagementRequest;
use App\Http\Requests\UpdateApiManagementRequest;
use App\Models\ApiManagement;

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

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle'));
    }

    public function listData()
    {
        $rows = ApiManagement::latest()
            ->groupBy('reff_id')
            ->get()
            ->map(function ($row) {
                $row->childs = ApiManagement::where('reff_id', $row->reff_id)
                    ->get()
                    ->map(function ($r) {
                        $r->table = $this->getTableTextAttribute($r->table);

                        return $r;
                    });
                $row->reff_id = $row->reff_id_relation;

                return $row;
            });

        return response()->json(['data' => $rows], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        $rusuns = \App\Models\Rusun::orderBy('nama', 'asc')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusuns'));
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
        if ($request->endpoint_rusun_details) {
            ApiManagement::create([
                'reff_id' => $request->reff_id,
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_details,
                'endpoint' => $request->endpoint_rusun_details,
                'keterangan' => $request->keterangan_rusun_details,
            ]);
        }

        if ($request->endpoint_rusun_tarifs) {
            ApiManagement::create([
                'reff_id' => $request->reff_id,
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_tarifs,
                'endpoint' => $request->endpoint_rusun_tarifs,
                'keterangan' => $request->keterangan_rusun_tarifs,
            ]);
        }

        if ($request->endpoint_rusun_outstanding_penghunis) {
            ApiManagement::create([
                'reff_id' => $request->reff_id,
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_outstanding_penghunis,
                'endpoint' => $request->endpoint_rusun_outstanding_penghunis,
                'keterangan' => $request->keterangan_rusun_outstanding_penghunis,
            ]);
        }

        if ($request->endpoint_rusun_pemiliks) {
            ApiManagement::create([
                'reff_id' => $request->reff_id,
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_pemiliks,
                'endpoint' => $request->endpoint_rusun_pemiliks,
                'keterangan' => $request->keterangan_rusun_pemiliks,
            ]);
        }

        return redirect()
                ->route(self::URL . 'index')
                ->with('success', 'Tambah data berhasil...');
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
        $row->rusun_details = ApiManagement::where('reff_id', $row->reff_id)->where('table', 'rusun_details')->first();
        $row->rusun_pemiliks = ApiManagement::where('reff_id', $row->reff_id)->where('table', 'rusun_pemiliks')->first();
        $row->rusun_tarifs = ApiManagement::where('reff_id', $row->reff_id)->where('table', 'rusun_tarifs')->first();
        $row->rusun_outstanding_penghunis = ApiManagement::where('reff_id', $row->reff_id)->where('table', 'rusun_outstanding_penghunis')->first();

        $rusuns = \App\Models\Rusun::orderBy('nama', 'asc')->get();

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusuns'));
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
        if ($request->endpoint_rusun_details) {
            ApiManagement::where([
                    'reff_id' => $request->reff_id,
                    'table' => 'rusun_details',
                ])
                ->update([
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_details,
                'endpoint' => $request->endpoint_rusun_details,
                'keterangan' => $request->keterangan_rusun_details,
            ]);
        }

        if ($request->endpoint_rusun_tarifs) {
            ApiManagement::where([
                    'reff_id' => $request->reff_id,
                    'table' => 'rusun_tarifs',
                ])
                ->update([
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_tarifs,
                'endpoint' => $request->endpoint_rusun_tarifs,
                'keterangan' => $request->keterangan_rusun_tarifs,
            ]);
        }

        if ($request->endpoint_rusun_outstanding_penghunis) {
            ApiManagement::where([
                    'reff_id' => $request->reff_id,
                    'table' => 'rusun_outstanding_penghunis',
                ])
                ->update([
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_outstanding_penghunis,
                'endpoint' => $request->endpoint_rusun_outstanding_penghunis,
                'keterangan' => $request->keterangan_rusun_outstanding_penghunis,
            ]);
        }

        if ($request->endpoint_rusun_pemiliks) {
            ApiManagement::where([
                    'reff_id' => $request->reff_id,
                    'table' => 'rusun_pemiliks',
                ])
                ->update([
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_pemiliks,
                'endpoint' => $request->endpoint_rusun_pemiliks,
                'keterangan' => $request->keterangan_rusun_pemiliks,
            ]);
        }

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
    public function destroy($id)
    {
        //
        $row = ApiManagement::findOrFail($id);

        ApiManagement::where('reff_id', $row->reff_id)->delete();

        return response()->json('Success');
    }

    public function testEndpoint($id)
    {
        $row = ApiManagement::findOrFail($id);

        $res = ApiService::run($row, 'GET', NULL);

        return $res->object();
    }

    public function syncManual($id)
    {
        $row = ApiManagement::findOrFail($id);

        $res = ApiService::run($row, 'GET', NULL);

        if ($res->ok()) {
            $object = $res->object();

            return $this->syncExecute($row, $object);
        } else {
            return $res;
        }
    }

    protected function getTableTextAttribute($table)
    {
        switch ($table) {
            case 'rusun_details':
                return 'Tower';
                break;

            case 'rusun_tarifs':
                return 'Tarif';
                break;

            case 'rusun_outstanding_penghunis':
                return 'Outstanding Penghuni';
                break;

            case 'rusun_pemiliks':
                return 'Pemilik & Penghuni';
                break;

            // case 'rusun_penghunis':
            //     return 'Penghuni';
            //     break;
            
            default:
                return 'No Defined';
                break;
        }
    }

    protected function syncExecute($row, $object)
    {
        switch ($row->table) {
            case 'rusun_details':
                $result = SyncData::rusunDetail($row, $object);

                return $result ? response()->json('OK', 200) : $result;
                break;
            
            case 'rusun_outstanding_penghunis':
                $result = SyncData::rusunOutstandingPenghuni($row, $object);

                return $result ? response()->json('OK', 200) : $result;
                break;

            case 'rusun_pemiliks':
                $result = SyncData::rusunPemilik($row, $object);

                return $result ? response()->json('OK', 200) : $result;
                break;

            case 'rusun_tarifs':
                $result = SyncData::tarif($row, $object);

                return $result ? response()->json('OK', 200) : $result;
                break;
            
            default:
                return abort(404);
                break;
        }
    }
}
