<?php 
namespace App\Http\Controllers\Tools;
use App\Http\Controllers\Controller;

use Auth;
use App\Post;
use App\Like;
use App\Comment;
use App\CommentLikes;
use Response;
use Validator;
use Illuminate\Http\Request;

class LikesController extends Controller {
	public function post(Request $request) {
		$validator = Validator::make($request->input(), [
			'post_id' => 'required|integer',
		]);
		if(!$validator->fails()) {
			$count = Post::where(['id' => $request->input('post_id')])->count();
			if($count != 0) {
				$count_like = Like::where(['user_id' => Auth::user()->id, 'post_id' => $request->input('post_id')])->count();
				$method = $count_like == 0 ? 'like' : 'dislike';
				switch ($method) {
					case 'like':
						$like = new Like;
						$like->user_id = Auth::user()->id;
						$like->post_id = $request->input('post_id');
						$like->save();
 						break;
					
					case 'dislike':
						Like::where(['user_id' => Auth::user()->id, 'post_id' => $request->input('post_id')])->delete();
						break;
				}
				return Response::json(['success' => true, 'method' => $method]);
			}
			return Response::json(['success' => false]);
		}
		return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
	}

	public function comment(Request $request) {
		$validator = Validator::make($request->input(), [
			'comment_id' => 'required|integer',
		]);
		if(!$validator->fails()) {
			$comment = Comment::where(['id' => $request->input('comment_id')])->count();
			if($comment != 0) {
				$count_like = CommentLikes::where(['user_id' => Auth::user()->id, 'comment_id' => $request->input('comment_id')])->count();
				$method = $count_like == 0 ? 'like' : 'dislike';
				switch ($method) {
					case 'like':
						$like = new CommentLikes;
						$like->user_id = Auth::user()->id;
						$like->comment_id = $request->input('comment_id');
						$like->save();
 						break;
					
					case 'dislike':
						CommentLikes::where(['user_id' => Auth::user()->id, 'comment_id' => $request->input('comment_id')])->delete();
						break;
				}
				return Response::json(['success' => true, 'method' => $method]);
			}
			return Response::json(['success' => false]);
		}
		return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
	}
}