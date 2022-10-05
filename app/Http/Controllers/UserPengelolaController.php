<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserPengelolaController extends Controller
{

    const TITLE = 'User Pengelola';
    const FOLDER_VIEW = 'user_pengelola.';
    const URL = 'user-pengelola.';

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

        $rows = User::orderBy('created_at')
            ->with(['user_mapping'])
            ->where('level', 'pengelola')
            ->get()
            ->map(fn($row) => [
                $row->name,
                $row->username,
                $row->email,
                \App\Models\Pengelola::where('id', $row->user_mapping->reff_id)->first()->nama,
                $row->active_text,
                $row->last_login,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-ban"></i> Non Aktifkan</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Name',
            'Username',
            'Email',
            'Pengelola',
            'Aktif',
            'Terakhir Masuk',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
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
        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        $pengelolas = \App\Models\Pengelola::orderBy('nama')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'pengelolas'));
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
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'picture' => 'nullable|image',
            'username' => 'required|string|max:100|alpha_num|unique:users',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required|string|min:6',

            'pengelola' => 'required|string',
        ])->validate();

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'username' => strtolower($request->username),
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'active' => 1,
                'level' => 'pengelola',
            ]);

            $user->user_mapping()
                ->create([
                    'table' => 'pengelolas',
                    'reff_id' => $request->pengelola,
                ]);

            // event(new UserVerifiedNotification($user));
        });

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = User::with('user_mapping')->findOrFail($id);

        $pengelolas = \App\Models\Pengelola::orderBy('nama')->get();

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'pengelolas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $row = User::with(['user_mapping'])->findOrFail($id);

        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'picture' => 'nullable|image',
            'username' => 'required|string|max:100|alpha_num|unique:users,username,' . $row->id,
            'email' => 'required|string|max:255|email|unique:users,email,' . $row->id,
            // 'password' => 'required|string|min:6',

            'pengelola' => 'required|string',
        ])->validate();

        DB::transaction(function () use ($row, $request) {
            $row->user_mapping()
                ->update([
                    'reff_id' => $request->pengelola,
                ]);

            $row->update([
                'name' => $request->name,
                'email' => strtolower($request->email),
                'username' => strtolower($request->username),
            ]);
        });

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        User::findOrFail($id)->update(['active' => 0]);

        return response()->json('Success');
    }
}
