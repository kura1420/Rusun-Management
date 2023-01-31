<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    //
    const TITLE = 'User Profile';
    const FOLDER_VIEW = 'user_profile.';
    const URL = 'profile.';

    public function edit($username)
    {
        $userAuth = auth()->user();

        if ($userAuth->username !== $username) {
            return abort(403, "Data login anda tidak sama dengan parametter");
        }

        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $userAuth;

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    public function update(UpdateUserProfileRequest $request, $username)
    {
        $input = $request->all();

        $user = User::where('username', $username)->firstOrFail();

        // $input['username'] = strtolower($request->username);
        $input['email'] = strtolower($request->email);

        if ($request->password) {
            $input['password'] = Hash::make($request->password);
        } else {
            unset($input['password']);
        }

        $user->update($input);

        return redirect()
            ->route(self::URL . 'edit', $username)
            ->with('success', 'Update data berhasil...');
    }
}
