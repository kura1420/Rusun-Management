<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {   
        $input = $request->all();
  
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
  
        $usernameOrEmail = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if(Auth::attempt([
            $usernameOrEmail => $input['username'], 
            'password' => $input['password'], 
            'active' => 1,
        ])) {
            $row = \App\Models\User::where('email', $request->username)
                ->orWhere('username', $request->username)
                ->first();

            $row->update([
                'last_login' => Carbon::now(),
            ]);

            if ($row->level !== 'root') {
                $userMapping = $row->user_mapping()->first();

                $profileTable = DB::table($userMapping->table)->where('id', $userMapping->reff_id)->first();

                $sessionKey = Str::singular($userMapping->table);

                session([
                    $sessionKey => $profileTable,
                ]);
            }

            return redirect()
                ->route('home');
        } else {
            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'Salah username dan kata sandi',
                ]);
        }
          
    }
}
