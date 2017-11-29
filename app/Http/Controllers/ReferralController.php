<?php namespace App\Http\Controllers;

class ReferralController extends Controller {

    /*
    *--------------------------------------------------------------------------
    * Referral Controller
    *--------------------------------------------------------------------------
    *
	* This controller is intended to store the referral in the user's cookies
    *
    */

    /**
     * Store a nickname of referral in the user cookie
     * @param  Request $name
     * @return Response
     */
	public function referral($name) {
		SetCookie('referral', $name, (int) time() + 3600000, '/');
		return redirect('home');
	}
}
