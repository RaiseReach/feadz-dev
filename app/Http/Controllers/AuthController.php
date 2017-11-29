<?php 

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\Factory as Socialite;

class AuthController extends Controller
{

    public function __construct(Socialite $socialite){
        try {
            $this->socialite = $socialite;
        } catch (Exception $e) {
            return redirect('home');
        }
    }


    public function getSocialAuth($provider=null)
    {
        try {
            if(!config("services.$provider")) abort('404'); //just to handle providers that doesn't exist

            return $this->socialite->with($provider)->redirect();
        } catch (Exception $e) {
            return redirect('home');
        }
    }


    public function getSocialAuthCallback($provider=null, Request $request)
    {
        if ($request->input('error', '') == 'access_denied') {
            return redirect('/');  
        }

        if($user = $this->socialite->with($provider)->user()){
            if (empty($user->email)) {
                return redirect('/');     
            } else {
                $user_model = User::where([ ['social_type', '=', $provider], ['social_id', '=', $user->id ] ])->first();
                if (isset($user_model->id)) {
                    Auth::loginUsingId($user_model->id, true);
                    return redirect('/');
                } else {
                    if (isset($_COOKIE['referral']) and !empty($_COOKIE['referral'])) {
                        $referral = $_COOKIE['referral'];
                        $user = User::select('id')->where(['name', $referral])->first();
                        $referral = count($user) != 0 ? $user->id : 0;
                    } else $referral = 0;

                    $User = new User;
                    $User->name = str_random(8);
                    $User->email = $user->id.$provider;
                    $User->social_type = $provider;
                    $User->social_id = $user->id;
                    $User->save();
                    Auth::loginUsingId($User->id, true);
                    return redirect('/home');
                }
            }
        } else{
            return 'something went wrong';
        }
    }

}