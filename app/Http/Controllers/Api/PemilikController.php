<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $rusunNama = $request->rusun ?? NULL;
        $search = $request->search ?? NULL;

        $rusun = \App\Models\Rusun::where('nama', $rusunNama)->firstOrFail();

        $rows = Pemilik::orderBy('nama')
            ->whereHas('rusun_pemiliks', function (Builder $query) use ($rusun) {
                $query->where('rusun_id', $rusun->id);
            })
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->get()
            ->map(function ($row) {
                $row->penyewas = $row->rusun_penghunis;

                unset(
                    $row->id, 
                    $row->created_at, 
                    $row->updated_at,
                    $row->rusun_penghunis,
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
     * @param  \App\Models\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function show(Pemilik $pemilik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pemilik $pemilik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pemilik $pemilik)
    {
        //
    }
}
