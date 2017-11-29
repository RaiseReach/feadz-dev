<?php namespace App\Http\Controllers;


use Auth;
use App\Post;
use App\Feadr;
use Response;
use Validator;
use Illuminate\Http\Request;

class FeadrController extends Controller {

    /*
    *--------------------------------------------------------------------------
    * Feadr Controller
    *--------------------------------------------------------------------------
    *
    * This controller is designed to save users 'votes' 
    * for a particular post, which further affects the
    * order of the output of posts on the main page
    *
    */

    /**
     * Store a new feadr(vote).
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|integer'
        ]);
        if (!$validator->fails()) {
            $post = Post::find($request->post_id)->count();
            if($post != 0) {
                $user = Auth::user();
                $voted = Feadr::where(['user_id' => $user->id, 'post_id' => $request->post_id])->count();
                if($voted == 0) {
                    $feadr = new Feadr;
                    $feadr->user_id = $user->id;
                    $feadr->post_id = $request->post_id;
                    $feadr->save();
                    return Response::json(['success' => true]);
                }
            }
        }
        return Response::json(['success' => false]);
    }
}
