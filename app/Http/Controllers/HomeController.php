<?php namespace App\Http\Controllers;

use Auth;
use App\Post;
use Illuminate\Http\Request;

class HomeController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index(Request $request) {
        $current_user = Auth::check() ? Auth::user()->id : null;
        $posts = Post::where(['state' => 'publish'])
                    ->select('id', 'author_name', 'url', 'description_image', 'description_title')
                    ->withCount('comments', 'likes', 'feadr')
                    ->with([
                      'like' => function ($like) use ($current_user) {
                          $like->whereHas('user', function ($user) use ($current_user) {
                              $user->where('id', $current_user);
                          });
                      },
                      'comment' => function ($comment) use ($current_user) {
                          $comment->whereHas('author', function ($user) use ($current_user) {
                              $user->where('id', $current_user);
                          });
                      }
                    ])
                    ->orderBy('feadr_count', 'DESC')
                    ->paginate(6);
        if ($request->ajax())
            return $posts->toJson();
        return view('home', ['body_class' => 'home', 'posts' => $posts]);
    }
}
