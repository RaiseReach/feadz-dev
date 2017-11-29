<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|min:3|max:255|unique:users|alpha_num|regex:/^[a-zA-Z0-9]+$/u|not_in:admin,create,upload,success,report,auth,user,ref,referrals,home,logout,login,disclaimer,channel,edit,category,search',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        if (isset($_COOKIE['referral']) and !empty($_COOKIE['referral'])) {
            $referral = $_COOKIE['referral'];
            $user = User::select('id')->where('name', $referral)->first();
            $referral = count($user) != 0 ? $user->id : 0;
        } else $referral = 0;
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'referral' => $referral,
            'password' => bcrypt($data['password']),
        ]);
    }
}
