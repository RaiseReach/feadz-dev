<?php 
namespace App\Http\Controllers\Tools;
use App\Http\Controllers\Controller;


use Auth;
use App\Post;
use App\Comment;
use Response;
use Validator;
use Illuminate\Http\Request;

class CommentsController extends Controller {
	public function add(Request $request) {
		$validator = Validator::make($request->input(), [
			'post_id' => 'required|integer',
			'parent_id' => 'required|integer',
			'message' => 'required|min:3|max:500',
		]);
		if(!$validator->fails()) {
			$post = Post::where(['id' => $request->input('post_id')])->count();
			if($post == 0) {
				return Response::json(['success' => false, 'errorText' => 'The Post not found!']);
			}
			if($request->input('parent_id') != 0) {
				$comment = Comment::where(['id' => $request->input('parent_id')])->count();
				if($comment == 0) {
					return Response::json(['success' => false, 'errorText' => 'The Comment not found!']);
				}
			}
			$comment = new Comment;
			$comment->user_id   = Auth::user()->id;
			$comment->post_id   = $request->input('post_id');
			$comment->parent_id = $request->input('parent_id');
			$comment->message   = $request->input('message');
			$comment->save();
			return Response::json(['success' => true]);
		}
		return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
	}
}