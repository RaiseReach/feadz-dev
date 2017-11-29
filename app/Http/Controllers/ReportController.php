<?php 
namespace App\Http\Controllers;

use Auth;
use Response;
use Validator;
use App\Post;
use App\Report;
use Illuminate\Http\Request;

class ReportController extends Controller {


    /*
    *--------------------------------------------------------------------------
    * Report Controller
    *--------------------------------------------------------------------------
    *
	* This controller is designed to save reports about users posts.
    *
    */

    /**
     * Store a new report.
     * @param  Request  $request
     * @return Response
    */
	public function store(Request $request) {
		$validator = Validator::make($request->input(), [
			'post_id' => 'required|integer',
			'reason'  => 'required|min:3|max:255'
		]);
		if(!$validator->fails()) {
			$post = Post::where(['id' => $request->post_id])->count();
			if($post != 0) {
				$report = new Report;
				$report->user_id = Auth::user()->id;
				$report->post_id = $request->post_id;
				$report->reason  = $request->reason;
				$report->save();
				return Response::json(['success' => true]);
			}
		}
		return Response::json(['success' => false]);
	}
}