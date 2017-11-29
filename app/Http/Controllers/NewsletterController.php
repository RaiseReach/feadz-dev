<?php namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Email;
use Response;
use Illuminate\Http\Request;

class NewsletterController extends Controller {

    /*
    *--------------------------------------------------------------------------
    * Newsletter Controller
    *--------------------------------------------------------------------------
    *
    * This controller is designed to store e-mail 
    * addresses of guests and users, in order to use 
    * them for mass mailing in the future.
    *
    */

    /**
     * Store a new email.
     * @param  Request  $request
     * @return Response
    */
    public function store(Request $request) {
        $validator = Validator::make($request->input(), [
            'email' => 'required|email'
        ]);

        if(!$validator->fails()) {
            if(Auth::check()) {
                $user = Auth::user();
                $user->email_for_news = $request->email;
                $user->save();
            } else {
                $email = new Email;
                $email->email = $request->email;
                $email->save();
            }
            return Response::json(['success' => true]);
        }
        return Response::json(['success' => false]);
    }
}
