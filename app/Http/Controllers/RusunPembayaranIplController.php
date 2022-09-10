<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunPembayaranIplRequest;
use App\Http\Requests\UpdateRusunPembayaranIplRequest;
use App\Models\RusunPembayaranIpl;
use Illuminate\Http\Request;

class RusunPembayaranIplController extends Controller
{

    const TITLE = 'Rusun Pembayaran IPL';
    const FOLDER_VIEW = 'rusun_pembayaran_ipl.';
    const URL = 'rusun-pembayaran-ipl.';

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
    public function create(Request $request)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        $pemilik_id = $request->pemilik_id ?? NULL;
        
        $rusunPenghuniToIPL = $this->rusunPenghuniToIPL($pemilik_id, 'create');

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'pemilik_id', 'rusunPenghuniToIPL'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRusunPembayaranIplRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunPembayaranIplRequest $request)
    {
        //
        $input = $request->all();
        
        $row = \App\Models\RusunPenghuni::where([
            ['rusun_unit_detail_id', $input['rusun_unit_detail_id']],
            ['pemilik_id', $input['pemilik_id']]
        ])->first();

        if (!$row) {
            $row = \App\Models\RusunPemilik::where([
                ['rusun_unit_detail_id', $input['rusun_unit_detail_id']],
                ['pemilik_id', $input['pemilik_id']]
            ])->first();

            $input['pemilik_bayar'] = true;
        }

        RusunPembayaranIpl::create([
            'rusun_id' => $row->rusun_id,
            'rusun_unit_detail_id' => $row->rusun_unit_detail_id,
            'rusun_detail_id' => $row->rusun_detail_id,
            'pemilik_id' => $input['pemilik_id'],
            'pemilik_penghuni_id' => isset($input['pemilik_bayar']) ? $input['pemilik_id'] : $row->id,
            'pemilik_bayar' => isset($input['pemilik_bayar']) ? 1 : 0,
        ]);

        return redirect()
            ->route('pemilik.show', $row->pemilik_id)
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RusunPembayaranIpl  $rusunPembayaranIpl
     * @return \Illuminate\Http\Response
     */
    public function show(RusunPembayaranIpl $rusunPembayaranIpl)
    {
        //
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RusunPembayaranIpl  $rusunPembayaranIpl
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, RusunPembayaranIpl $rusunPembayaranIpl)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $rusunPembayaranIpl;
        
        $rusunPenghuniToIPL = $this->rusunPenghuniToIPL($row->pemilik_id, 'edit');

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusunPenghuniToIPL'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunPembayaranIplRequest  $request
     * @param  \App\Models\RusunPembayaranIpl  $rusunPembayaranIpl
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunPembayaranIplRequest $request, RusunPembayaranIpl $rusunPembayaranIpl)
    {
        //
        $input = $request->all();

        $row = \App\Models\RusunPenghuni::where([
            ['rusun_unit_detail_id', $input['rusun_unit_detail_id']],
            ['pemilik_id', $input['pemilik_id']]
        ])->first();

        if (!$row) {
            $row = \App\Models\RusunPemilik::where([
                ['rusun_unit_detail_id', $input['rusun_unit_detail_id']],
                ['pemilik_id', $input['pemilik_id']]
            ])->first();

            $input['pemilik_bayar'] = true;
        }

        $rusunPembayaranIpl->update([
            'pemilik_penghuni_id' => isset($input['pemilik_bayar']) ? $input['pemilik_id'] : $row->id,
            'pemilik_bayar' => isset($input['pemilik_bayar']) ? 1 : 0,
        ]);

        return redirect()
            ->route('pemilik.show', $row->pemilik_id)
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RusunPembayaranIpl  $rusunPembayaranIpl
     * @return \Illuminate\Http\Response
     */
    public function destroy(RusunPembayaranIpl $rusunPembayaranIpl)
    {
        //
        return abort(404);
    }

    protected function rusunPenghuniToIPL($pemilik_id, $state)
    {
        $pemilik = \App\Models\Pemilik::where('id', $pemilik_id)->firstOrFail();

        $rusunPembayaranIPL = [];
        foreach ($pemilik->rusun_pembayaran_ipls()->get() as $rusun_pembayaran_ipl) {
            $rusunPembayaranIPL[] = $rusun_pembayaran_ipl->rusun_unit_detail_id;
        }

        $rusunPenghunis = $pemilik->rusun_penghunis()
            ->get()
            ->map(function ($rusun_penghuni) {
                $rusun_penghuni->rusuns = $rusun_penghuni->rusuns()->first();
                $rusun_penghuni->rusun_details = $rusun_penghuni->rusun_details()->first();
                $rusun_penghuni->rusun_unit_details = $rusun_penghuni->rusun_unit_details()->first();

                return [
                    'id' => $rusun_penghuni->rusun_unit_detail_id,
                    'rusun_id' => $rusun_penghuni->rusun_id,
                    'rusun_nama' => $rusun_penghuni->rusuns->nama,
                    'tower_id' => $rusun_penghuni->rusun_detail_id,
                    'tower_nama' => $rusun_penghuni->rusun_details->nama_tower,
                    'unit_ukuran_id' => $rusun_penghuni->rusun_unit_detail_id,
                    'unit_ukuran' => $rusun_penghuni->rusun_unit_details->ukuran,
                    'pic' => $rusun_penghuni->nama
                ];
            });

        $rusunUnitDetailID = [];
        if (count($rusunPenghunis)>0) {
            foreach ($rusunPenghunis as $rusunPenghuni) {
                $rusunUnitDetailID[] = $rusunPenghuni['unit_ukuran_id'];
            }
        }

        $collects = collect($pemilik->rusun_pemiliks()
            ->get())
            ->map(function ($item, $key) use ($pemilik) {
                $item->rusuns = $item->rusuns()->first();
                $item->rusun_details = $item->rusun_details()->first();
                $item->rusun_unit_details = $item->rusun_unit_details()->first();
                $item->pemilik = $pemilik;

                return [
                    'id' => $item->rusun_unit_detail_id,
                    'rusun_id' => $item->rusun_id,
                    'rusun_nama' => $item->rusuns->nama,
                    'tower_id' => $item->rusun_detail_id,
                    'tower_nama' => $item->rusun_details->nama_tower,
                    'unit_ukuran_id' => $item->rusun_unit_detail_id,
                    'unit_ukuran' => $item->rusun_unit_details->ukuran,
                    'pic' => $item->pemilik->nama
                ];
            })
            ->whereNotIn('unit_ukuran_id', $rusunUnitDetailID)
            ->merge($rusunPenghunis)
            ->map(function ($item, $key) {
                return [
                    'id' => $item['id'],
                    'rusun' => $item['rusun_nama'],
                    'text' => $item['tower_nama'] . ', ' . $item['unit_ukuran'] . ' - ' . $item['pic']
                ];
            });

        if ($state == 'create') {
            $collects = $collects->whereNotIn('id', $rusunPembayaranIPL)
                ->groupBy('rusun')
                ->all();
        } else {
            $collects = $collects->groupBy('rusun')
                ->all();
        }                 

        $listToIPL = [];
        foreach ($collects as $key => $collect) {
            $listToIPL[] = [
                'text' => $key,
                'children' => $collect,
            ];
        };

        return $listToIPL;
    }
}
