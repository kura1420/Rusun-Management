<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    const TITLE = 'User';
    const FOLDER_VIEW = 'user.';
    const URL = 'user.';

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
            ->where('id', '!=', auth()->user()->id)
            ->get()
            ->map(fn($row) => [
                $row->name,
                $row->email,
                $row->username,
                $row->active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>',
                '<nobr><a href="'.route(self::URL . 'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a></nobr>',
            ]);

        $heads = [
            'Nama',
            'Email',
            'Username',
            'Status',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false], ['orderable' => false]],
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

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle',));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        //
        $input = $request->all();

        $input['username'] = strtolower($request->username);
        $input['email'] = strtolower($request->email);
        $input['password'] = Hash::make($request->password);
        $input['active'] = $request->active == 'true' ? 1 : 0;

        User::create($input);

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
    public function edit(User $user)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $user;

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
        $input = $request->all();

        // $input['username'] = strtolower($request->username);
        $input['email'] = strtolower($request->email);

        if ($request->active) {
            $input['active'] = $request->active == 'true' ? 1 : 0;
        }

        if ($request->password) {
            $input['password'] = Hash::make($request->password);
        } else {
            unset($input['password']);
        }

        $user->update($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Update data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
